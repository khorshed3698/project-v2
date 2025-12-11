@extends('layouts.admin')

@section('page_heading',trans('messages.payment_list'))

@section('content')
    <?php // $accessMode=ACL::getAccsessRight('spg');
    // if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
    ?>
    <div class="row">
        <div class="col-sm-12">
            @include('partials.messages')
        </div>
    </div>

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>{!!trans('messages.indiv_payment_hist')!!}</strong></strong></h5>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="nav-tabs-custom" style="margin-top: 15px;padding: 0px 5px;">
{{--                    <ul  class="nav nav-tabs">--}}
{{--                        <li id="tab1" class="active">--}}
{{--                            <a data-toggle="tab" href="#list_payment" class="mydesk" aria-expanded="true">--}}
{{--                                <b>List</b>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li id="tab3" class="">--}}
{{--                            <a data-toggle="tab" href="#list_search" aria-expanded="false">--}}
{{--                                <b>Search</b>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
                    <!-- /.panel-heading -->
                    <div class="tab-content">
                        <div id="list_payment" class="tab-pane active" style="margin-top: 20px">
                            <div class="table-responsive">
                                <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Tracking No.</th>
                                        <th>Contact Email</th>
                                        <th>Transaction Id</th>
                                        <th>Request Id</th>
                                        <th>Ref Tran No</th>
                                        <th>Tran Date Time</th>
                                        <th>Amount</th>
                                        <th>Status</th>
{{--                                        <th class="text-center">Action</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div><!-- /.table-responsive -->
                        </div><!-- /.tab-pane -->

{{--                        <div id="list_search" class="tab-pane" style="margin-top: 20px">--}}
{{--                            @include('SonaliPayment::search')--}}
{{--                        </div>--}}

                    </div><!-- /.tab-content -->
                </div><!-- /.nav-tabs-custom -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')
    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" id="spPaymentId" value="{{$paymentId}}">
    <script>
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("spg/history-data")}}',
                    method: 'POST',
                    data: function (d) {
                        d.search_time = $('.search_time').val();
                        d.search_text = $('.search_text').val();
                        d.search_date = $('.search_date').val();
                        d.sp_payment_id = $('#spPaymentId').val();
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
//                    {data: 'sl_no', name: 'sl_no'},
                    {data: 'app_tracking_no', name: 'app_tracking_no'},
                    {data: 'contact_email', name: 'contact_email'},
                    {data: 'transaction_id', name: 'transaction_id'},
                    {data: 'request_id', name: 'request_id'},
                    {data: 'ref_tran_no', name: 'ref_tran_no'},
                    {data: 'ref_tran_date_time', name: 'ref_tran_date_time'},
                    {data: 'pay_amount', name: 'pay_amount'},
                    {data: 'payment_status', name: 'payment_status'},
                    // {data: 'action', name: 'action', orderable: false, searchable: true}
                ],
                "aaSorting": []
            });

        });
    </script>
    @yield('footer-script2')
@endsection