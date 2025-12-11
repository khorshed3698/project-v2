<?php
$user_type = CommonFunction::getUserType();
$accessMode = ACL::getAccsessRight('CompanyAssociation');
if (!ACL::isAllowed($accessMode, '-V-'))
    die('no access right!');
?>

@extends('layouts.admin')
@section('content')
    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-6">
                        <h5>
                            <i class="fa fa-list"></i>
                            Company association request list
                            <span class="list_name"></span>
                            @if(isset($process_info->name))
                                for ({{$process_info->name}})
                            @endif
                        </h5>
                    </div>
                    <div class="col-lg-6">
                        @if(in_array($user_type,['5x505']) && ACL::isAllowed($accessMode, '-A-'))
                            <a href="{{ url('company-association/create') }}"
                               class="btn btn-default pull-right"><i class="fa fa-plus"></i> <strong>Company
                                    Request</strong></a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                    <table id="app_list"
                           class="table table-striped table-bordered dt-responsive " cellspacing="0"
                           width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Requested company</th>
                            <th>Requested type</th>
                            <th>Business Category</th>
                            <th>User remarks</th>
                            <th>Desk remarks</th>
                            <th>Application date</th>
                            <th>App Status</th>
                            <th>Active status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div><!-- /.panel-body -->
        </div>
    </div>

@endsection
@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
    @include('partials.datatable-scripts')

    <script language="javascript">

        $(function () {
            /**
             * table desk script
             * @type {jQuery}
             */
            $('#app_list').DataTable({
                iDisplayLength: 25,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: '{{url("company-association/get-list")}}',
                    method: 'post'
                },
                columns: [
                    {data: 'sl', name: 'sl', searchable: false},
                    {data: 'user_email', name: 'user_email', searchable: true},
                    {data: 'requested_company_id', name: 'requested_company_id', searchable: true},
                    {data: 'request_type', name: 'request_type', searchable: true},
                    {data: 'business_category', name: 'business_category', searchable: true},
                    {data: 'user_remarks', name: 'user_remarks', searchable: true},
                    {data: 'desk_remarks', name: 'desk_remarks', searchable: true},
                    {data: 'application_date', name: 'application_date', searchable: true},
                    {data: 'status_id', name: 'status_id', searchable: false},
                    {data: 'status', name: 'status', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });

    </script>
@endsection