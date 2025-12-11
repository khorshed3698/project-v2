@extends('layouts.admin')

@section('page_heading', trans('messages.rollback'))

@section('content')
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="padding-top: 5px; padding-bottom: 5px">
                <div class="pull-left" style="line-height: 35px;">
                    <strong><i class="fa fa-list"></i> Contact List</strong>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                    <table id="list" class="table table-striped table-bordered dt-responsive " cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Action</th>
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
<!--content section-->

@section('footer-script')
    @include('partials.datatable-scripts')
    <script>
        $(function() {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('contact.list') }}",
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    error: function(xhr, error, thrown) {
                        let errorMessage =
                            'An error occurred while fetching data. Please try again later.';
                        if (thrown) {
                            errorMessage = thrown;
                        }
                        $('#list').html('<div class="alert alert-warning" role="alert">' +
                            errorMessage + '</div>');
                        $(".dataTables_processing").hide()
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                "aaSorting": []
            });
        });
    </script>
@endsection
