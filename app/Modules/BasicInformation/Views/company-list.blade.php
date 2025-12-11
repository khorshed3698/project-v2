@extends('layouts.admin')
<?php
$accessMode = ACL::getAccsessRight('BasicInformation');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
@section('content')
    <section class="content">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body" id="inputForm">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h5><strong>Show all company</strong></h5>
                            </div>
                            <div class="pull-right">

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-responsive" id="list" aria-label="Detailed Company Info Data Table">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>User Name</th>
                                            <th>Email Address</th>
                                            <th>User Type</th>
                                            <th>Designation</th>
                                            <th>Location</th>
                                            <th>Member Since</th>
                                            <th>Action</th>
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
        </div>
    </section>
@endsection

@section('footer-script')
    @include('partials.datatable-scripts')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    <script>
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                ajax: {
                    url: '{{url("basic-information/get-all-company-list")}}',
                    method: 'POST',
                    data:{company_id : '{{ $company_id }}'},
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'sl', name: 'sl'},
                    {data: 'user_full_name', name: 'user_full_name'},
                    {data: 'user_email', name: 'user_email'},
                    {data: 'working_user_type', name: 'working_user_type'},
                    {data: 'designation', name: 'designation'},
                    {data: 'users_district', name: 'users_district'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });
    </script>
@endsection


