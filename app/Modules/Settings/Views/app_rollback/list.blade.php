@extends('layouts.admin')

@section('page_heading',trans('messages.rollback'))

@section('content')

    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'ARB')) {
        die('You have no access right! For more information please contact system admin.');
    }
    ?>

    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5>Application Rollback List</h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','ARB'))
                        <a href="{{ url('/settings/app-rollback-search') }}">
                            {!! Form::button('<i class="fa fa-plus"></i><b> ' ."New application rollback".'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
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
                            <th>Sl No.</th>
                            <th>Tracking No.</th>
                            <th>Application Tracking No.</th>
                            <th>Status</th>
                            <th>Last Modified</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div>

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
                    url: '{{url("settings/app-rollback/list")}}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [

                    { data: 'rownum', name: 'rownum' , searchable: false},
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'app_tracking_no', name: 'app_tracking_no'},
                    {data: 'status_id', name: 'status_id'},
                    {data: 'last_modified', name: 'last_modified', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });

    </script>
@endsection