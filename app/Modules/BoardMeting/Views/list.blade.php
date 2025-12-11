@extends('layouts.admin')
@section('content')
    <?php $accessMode = ACL::getAccsessRight('BoardMeting');if (!ACL::isAllowed($accessMode, 'V')) {
        die('no access right!');
    }?>

    <style>
        .panel {
            margin: 0px;
        }

        /*.hover-item{*/
        /*hover-*/
        /*}*/
        .hover-item:hover {
            background: #ddd;
        }
    </style>
    <div class="col-lg-12">
        @include('message.message')
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left" style="line-height: 35px;">
                    <strong><i class="fa fa-list"></i> {{ trans('messages.board_meting_list') }}</strong>
                </div>
                <div class="pull-right">
                    {{--@if(ACL::getAccsessRight('BoardMeting','A'))--}}
                        {{--<a class="" href="{{ url('/board-meting/new-board-meting') }}">--}}
                            {{--{!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_boardmeeting').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}--}}
                        {{--</a>--}}
                    {{--@endif--}}

                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <span class="col-md-3 col-md-offset-5"
                          style="color: gray; font-weight: bold">Today's Date: {{date("d-M-Y")}}</span>
                </div>

                <div class="table-responsive">
                    {{--<table id="list" class="table table-striped table-bordered dt-responsive " cellspacing="0"--}}
                           {{--width="100%" aria-label="Detailed Report Data Table">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                            {{--<th style="font-size: 13px">{!! trans('messages.basic_list_of_meeting') !!}</th>--}}
                            {{--<th style="font-size: 13px;width: 30%;">{!! trans('messages.agenda') !!}</th>--}}
                            {{--<th style="font-size: 13px">{!! trans('messages.status') !!}</th>--}}
                            {{--<th style="font-size: 13px">{!! trans('messages.created_at') !!}</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}

                        {{--</tbody>--}}
                    {{--</table>--}}

                    <table id="list" class="table table-striped table-bordered dt-responsive " cellspacing="0"
                           width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th style="font-size: 13px">Name of meeting</th>
                            <th style="font-size: 13px;width: 30%;"> Meeting Number</th>
                            <th style="font-size: 13px"> Meeting Date</th>
                            <th style="font-size: 13px"> Status</th>
                            <th style="font-size: 13px">Action</th>
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
    @include('Users::partials.datatable')
    <script>
        $(document).ready(function () {
            $('.processList').on('click', function (e) {
                if ($('#listOfProcess').is(":visible")) {

                    $('.processList').find('i').removeClass("fa-arrow-up fa");
                    $('.processList').find('i').addClass("fa fa-arrow-down");
                    $(".processList").css("background-color", "");
                    $(".processList").css("color", "");
                } else {
                    $(this).find('i').removeClass("fa fa-arrow-down");
                    $(this).find('i').addClass("fa fa-arrow-up");
                    $(".processList").css("background-color", "#1abc9c");
                    $(".processList").css("color", "white");
                }
                $('#listOfProcess').slideToggle();
            });



        });
        $(function () {

            $('#list').DataTable({
                serverSide: true,
                ajax: {
                    url: '{{url("board-meting/get-row-details-data")}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'meting_type', name: 'meting_type',  searchable:true},
                    {data: 'meting_number', name: 'meting_number',  searchable:true },
                    {data: 'meting_date', name: 'meting_date', searchable:true},
                    {data: 'status', name: 'status',searchable: false},
                    {data: 'action', name: 'action', searchable: false}
                ],
                "aaSorting": []
            });


        });
    </script>

    <style>
        div.dataTables_wrapper div.dataTables_length select {
            width: 60px;
        }

        #pointer_shape {
            width: 30px;
            height: 14px;
            position: relative;
            background: #000;
            margin: 10px 0;
        }

        #pointer_shape:before {
            content: "";
            position: absolute;
            right: -20px;
            bottom: 0px;
            width: 0;
            height: 0;
            border-top: 6px solid transparent;
            border-left: 22px solid #000;
            border-bottom: 8px solid transparent;
        }
    </style>
@endsection <!--- footer-script--->

