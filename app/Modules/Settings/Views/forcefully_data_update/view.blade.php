@extends('layouts.admin')

@section('page_heading',trans('messages.forcible_data_update_form'))

@section('style')
    <style>
        body, html {
            overflow-x: unset !important;
        }
        .padding {
            padding: 5px 10px;
        }
    </style>
@endsection

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'V')) {
        die('You have no access right! For more information please contact system admin.');
    }
    ?>
    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong> {!!trans('messages.forcible_data_update_view')!!} </strong></h5>
            </div>

            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12 padding">
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Tracking No.</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ (!empty($forcefully_data_update->tracking_no)) ? $forcefully_data_update->tracking_no : '' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 padding">
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Table name</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ (!empty($forcefully_data_update->table_name)) ? $forcefully_data_update->table_name : '' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 padding">
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Updated type</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ (!empty($forcefully_data_update->update_type)) ? $forcefully_data_update->update_type : '' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 padding">
                        <div class="row">
                            @if($forcefully_data_update->update_type == 'user')
                                <div class="col-md-3 col-xs-6">
                                    <span class="v_label">User</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-9 col-xs-6">
                                    {{ (!empty($forcefully_data_update->user_info)) ? $forcefully_data_update->user_info : '' }}
                                </div>
                            @elseif($forcefully_data_update->update_type == 'company')
                                <div class="col-md-3 col-xs-6">
                                    <span class="v_label">Company</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-9 col-xs-6">
                                    {{ (!empty($forcefully_data_update->company_name)) ? $forcefully_data_update->company_name : '' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 col-xs-6">
                        <span class="v_label">Affected rows</span>
                        <span class="pull-right">&#58;</span>
                    </div>
                    <div class="col-md-9 col-xs-6">
                        <span class="label label-primary">{{ $affected_rows_count }}</span>
                        [{{ (!empty($forcefully_data_update->affected_row_ids)) ? $forcefully_data_update->affected_row_ids : '' }}]
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table aria-label="Detailed Report Data Table" class="table table-striped table-bordered dt-responsive" width="100%">
                        <thead>
                        <tr>
                            <th>Label name</th>
                            <th>Column name</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <td>{{ $data->label_name }}</td>
                                <td>{{ $data->column_name }}</td>
                                <td>{{ $data->old_value }}</td>
                                <td>{{ $data->new_value }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <div class="col-md-3">
                    <a href="{{ url('/settings/forcefully-data-update') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="col-md-6">
                    {!! App\Libraries\CommonFunction::showAuditLog($forcefully_data_update->updated_at, $forcefully_data_update->updated_by) !!}
                </div>
                <div class="col-md-3">
                    @if ($forcefully_data_update->status_id == 1 && Auth::user()->id != $forcefully_data_update->created_by)
                        <div class="pull-right">
                            <button href="javascript:void(0)" class="btn btn-success" onclick='confirmApprovedForcefullyData("{{ Encryption::encodeId($forcefully_data_update->id) }}", "{{ Encryption::encodeId(25) }}")'>Approved</button>
                            <button href="javascript:void(0)" class="btn btn-danger" onclick='confirmApprovedForcefullyData("{{ Encryption::encodeId($forcefully_data_update->id) }}", "{{ Encryption::encodeId(6) }}")'>Rejected</button>
                        </div>
                    @endif
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        function confirmApprovedForcefullyData(row_id, status) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "/settings/approve-forcefully-data-update",
                        type: "POST",
                        dataType: "text",
                        async: false,
                        data: {
                            _token: $('input[name="_token"]').val(),
                            id: row_id,
                            status: status,
                        },
                        success: function (response) {
                            var res_obj = jQuery.parseJSON(response);
                            if (res_obj.success == true) {
                                swal(
                                    'Done!',
                                    res_obj.status,
                                    'success'
                                ).then((result) => {
                                    if (result.value) {
                                        location.reload();
                                    }
                                })
                            } else {
                                swal(
                                    'Sorry!',
                                    res_obj.status,
                                    'error'
                                )
                            }
                        }
                    });
                } else {
                    return false;
                }
            })
        }
    </script>
@endsection
