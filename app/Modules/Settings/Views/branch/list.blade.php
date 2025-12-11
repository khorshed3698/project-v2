<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V')) {
    die('You have no access right! Please contact with system admin for more information.');
}
?>
@extends('layouts.admin')

@section('page_heading',trans('messages.bank_list'))
@section('style')
    <style>
        body, html {
            overflow-x: unset;
        }
    </style>
@endsection

@section('content')
    <div class="col-md-12 col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
    </div>

    <div class="col-lg-12 col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><i class="fa fa-list"></i> <strong>{!! trans('messages.branch_form') !!}</strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <a class="" href="{{ url('/settings/create-branch') }}">
                            {!! Form::button('<i class="fa fa-plus"></i><b>'.trans('messages.new_branch').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Bank Name</th>
                            <th>Branch Name</th>
                            <th>Branch Code</th>
                            <th>Address</th>
                            <th>Manager Info</th>
                            <th>Status</th>
                            <th width="8%">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')

    @include('partials.datatable-scripts')
    <script>
        $(function () {
            $('#list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                iDisplayLength: 25,
                pageLength: 25,
                ajax: {
                    url: '{{url("settings/get-branch/list")}}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'bank_name', name: 'bank_name'},
                    {data: 'branch_name', name: 'branch_name'},
                    {data: 'branch_code', name: 'branch_code'},
                    {data: 'address', name: 'address'},
                    {data: 'manager_info', name: 'manager_info'},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });

        function ConfirmDelete(id) {
            var sure_del = confirm("Are you sure you want to delete this item?");
            if (sure_del) {
                var url = '<?php echo url();?>';
                window.location=(url+"/settings/delete/Branch/"+id);
            }else {
                return false;
            }
        }
    </script>
@endsection
