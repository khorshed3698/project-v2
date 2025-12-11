@extends('layouts.admin')

@section('page_heading', trans('messages.rollback'))

@section('content')
    <style>
        .label-upcoming {
            background-color: #f0ad4e;
            color: #fff;
            cursor: pointer;
        }

        .label-ongoing {
            background-color: #5cb85c;
            color: #fff;
            cursor: pointer;
        }

        .label-completed {
            background-color: #5bc0de;
            color: #fff;
            cursor: pointer;
        }

        .label-upcoming:hover {
            background-color: #eda33b;
            color: #fff;
        }

        .label-ongoing:hover {
            background-color: #31b531;
            color: #fff;
        }

        .label-completed:hover {
            background-color: #2fb4dc;
            color: #fff;
        }
    </style>
    {{-- start application form with wizard --}}
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="padding-top: 5px; padding-bottom: 5px">
                <div class="pull-left" style="line-height: 35px;">
                    <strong><i class="fa fa-list"></i> Training Schedule List</strong>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('Training-Desk','A'))
                    <a class="" href="{{ url('training/create-schedule') }}">
                        <button type="button" class="btn btn-default"><i class="fa fa-plus"></i> <b> Create Schedule
                            </b></button>
                    </a>
                      @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                </div>
            
                @if(\Illuminate\Support\Facades\Auth::user()->desk_training_id == 1)
                <div class="table-responsive">
                    <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered dt-responsive " cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th> Course Title</th>
                                <th> Batch</th>
                                <th> Is Publish</th>
                                <th> Status</th>
                                <th>Start Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
                @elseif(Auth::user()->desk_training_id == 2)
                <div class="col-md-12"><br/>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#home"><b><i class="fa fa-warning"></i>
                            Waiting Training List</b></a></li>
                        <li><a data-toggle="tab" href="#menu1"><b><i class="fa fa-check"></i> Approved Training List</b></a></li>
                    </ul>

                    <div class="tab-content" style="margin-top: 20px">

                        <div id="home" class="tab-pane fade in active">
                            <div class="table-responsive">
                                <table aria-label="Detailed Approved Training List Report" id="waitinglist" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                       width="100%">
                                    <thead>
                                        <tr>
                                            <th> Course Title</th>
                                            <th> Batch</th>
                                            <th> Status</th>
                                            <th>Start Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div><!-- /.table-responsive -->
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <div class="table-responsive">
                                <table aria-label="Detailed Report Data Table" id="approvelist" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                       width="100%">
                                    <thead>
                                        <tr>
                                            <th> Course Title</th>
                                            <th> Batch</th>
                                            <th> Status</th>
                                            <th>Start Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div><!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>

    </div><!-- /.col-lg-12 -->

@endsection <!--content section-->
@section('footer-script')
    @include('partials.datatable-scripts')
    <script>
        $(function() {

            $('#list').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('training/get-schedule-data') }}",
                    method: 'post',
                    data: { status:'' },
                },
                columns: [{
                        data: 'course',
                        name: 'course',
                    },
                    {
                        data: 'batch',
                        name: 'batch',
                        searchable: true
                    },
                    {
                        data: 'is_publish',
                        name: 'is_publish',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'start_time',
                        name: 'start_time',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false
                    }
                ],
                "aaSorting": []
            });
            $('#waitinglist').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('training/get-schedule-data') }}",
                    method: 'post',
                    data: { status:'0' },
                },
                columns: [{
                        data: 'course',
                        name: 'course',
                    },
                    {
                        data: 'batch',
                        name: 'batch',
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'start_time',
                        name: 'start_time',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false
                    }
                ],
                "aaSorting": []
            });
            $('#approvelist').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('training/get-schedule-data') }}",
                    method: 'post',
                    data: { status:'1' },
                },
                columns: [{
                        data: 'course',
                        name: 'course',
                    },
                    {
                        data: 'batch',
                        name: 'batch',
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'start_time',
                        name: 'start_time',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false
                    }
                ],
                "aaSorting": []
            });


        });
    </script>

@endsection <!--- footer-script--->
