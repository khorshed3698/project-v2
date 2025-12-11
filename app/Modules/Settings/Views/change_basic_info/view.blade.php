@extends('layouts.admin')

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

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong> Change Basic Information </strong></h5>
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
                                {{ (!empty($basic_info_update->tracking_no)) ? $basic_info_update->tracking_no : '' }}
                            </div>
                        </div>
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
                        @foreach($data_view as $data_view1)
                            <tr>
                                <td>{{ $data_view1->label_name }}</td>
                                <td>{{ $data_view1->column_name }}</td>
                                <td>{{ $data_view1->old_value }}</td>
                                <td>{{ $data_view1->new_value }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <div class="col-md-3">
                    <a href="{{ url('/settings/get-change-basic-info-list') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="col-md-6">
                    {!! App\Libraries\CommonFunction::showAuditLog($basic_info_update->updated_at, $basic_info_update->updated_by) !!}
                </div>
                <div class="col-md-3">
                    @if ($basic_info_update->status_id == 1  && \Illuminate\Support\Facades\Auth::user()->id != $basic_info_update->created_by)
                        <div class="pull-right">
                            <button href="javascript:void(0)" class="btn btn-success" onclick='confirmApprovedBasicInfoData("{{ Encryption::encodeId($basic_info_update->id) }}", "{{ Encryption::encodeId(25) }}")'>Approved</button>
                            <button href="javascript:void(0)" class="btn btn-danger" onclick='confirmApprovedBasicInfoData("{{ Encryption::encodeId($basic_info_update->id) }}", "{{ Encryption::encodeId(6) }}")'>Rejected</button>
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
        function confirmApprovedBasicInfoData(row_id, status) {
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
                        url: "/settings/change-basic-info-data-update",
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
