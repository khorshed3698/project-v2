<?php
    if (!ACL::getAccsessRight('Training', '-V-')) {
        die('You have no access right! Please contact system administration for more information');
    }
?>

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
                    <strong><i class="fa fa-list"></i> Training Category List</strong>
                </div>
                <div class="pull-right">
                    <a class="" href="{{ url('training/create-category') }}">
                        <button type="button" class="btn btn-default"><i class="fa fa-plus"></i> <b> Create Category
                            </b></button>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                </div>

                <div class="table-responsive">
                    <table aria-label="Detailed Training Category List" id="list" class="table table-striped table-bordered dt-responsive " cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th>Category Name (English)</th>
                                <th>Category Name (Bangla)</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
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
                    url: "{{ url('training/get-category-data') }}",
                    method: 'post',
                },
                columns: [{
                        data: 'category_name',
                        name: 'category_name',
                    },
                    {
                        data: 'category_name_bn',
                        name: 'category_name_bn'
                    },
                    {
                        data: 'status',
                        name: 'is_active',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        sortable: false
                    }
                ],
                "aaSorting": []
            });


        });
    </script>

@endsection <!--- footer-script--->
