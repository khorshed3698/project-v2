{!! Form::open(['url' => '#process/search','method' => 'get','id' => ''])!!}
<div class="row">
    <div class="col-md-12">
{{--        old --}}
{{--        <div class="col-md-4">--}}
{{--            <div class="col-md-6 ">--}}
{{--                <label for="">Process Type: </label>--}}
{{--                {!! Form::select('ProcessType', ['' => 'Select One'] + $ProcessType,'', ['class' => 'form-control search_type']) !!}--}}
{{--            </div>--}}
{{--            <div class="col-md-6 ">--}}
{{--                <label for="">Company: </label>--}}
{{--                {!! Form::select('CompanyInfo', ['' => 'Select One'] + $CompanyInfo,'', ['class' => 'form-control search_company']) !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-3">--}}
{{--            <label for="">Payment Type: </label>--}}
{{--            {!! Form::select('PaymentType', ['' => 'Select One'] + $PaymentType,'', ['class' => 'form-control search_payment_type']) !!}--}}
{{--        </div>--}}
{{--        <div class="col-md-5">--}}
{{--            <div class="col-md-1">--}}
{{--                <label for="">&nbsp;</label>--}}
{{--                <input type="button" id="search_process" class="btn btn-primary" value="Search">--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="col-md-5 col-md-offset-3">
            <label for="">Search text: </label>
            {!! Form::text('search_text', '', ['class' => 'form-control search_text', 'placeholder' => 'Tracking Number']) !!}
        </div>
        <div class="col-md-1">
            <label for="">&nbsp;</label><br>
            <input type="button" id="search_process" class="btn btn-primary" value="Search">
        </div>
    </div>
</div>
{!! Form::close()!!}
<div id="list_search" class="" style="margin-top: 20px">
    <div class="table-responsive">
        <table aria-label="Search text" id="table_search" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Pay Mode</th>
                    <th>Tracking No.</th>
                    <th>Transaction Id</th>
                    <th>Request Id</th>
                    <th>Ref Tran No</th>
                    <th>Tran Date Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div><!-- /.table-responsive -->
</div><!-- /.tab-pane -->



@section('footer-script2')
    @include('partials.datatable-scripts')
    <script language="javascript">
        $(function () {
            $('#table_search').hide();
            $('#search_process').click(function () {

                $('#table_search').show();

                // old
                // var searchType ='';
                // var searchCompany='';
                // var searchPaymentType='';
                // searchType=$('.search_type').val();
                // searchCompany=$('.search_company').val();
                // searchPaymentType=$('.search_payment_type').val();

                $('#table_search').DataTable({
                    destroy: true,
                    iDisplayLength: 25,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: '{{url("spg/search")}}',
                        method: 'GET',
                        data: {
                            'search_text': $('.search_text').val()
                        }

                        // old
                        // data: function (d) {
                        //     d.process_type = searchType;
                        //     d.company = searchCompany;
                        //     d.payment_type = searchPaymentType;
                        // }
                    },
                    columns: [
                        {data: 'pay_mode', name: 'pay_mode'},
                        {data: 'app_tracking_no', name: 'app_tracking_no'},
                        {data: 'transaction_id', name: 'transaction_id'},
                        {data: 'request_id', name: 'transaction_id'},
                        {data: 'ref_tran_no', name: 'ref_tran_no'},
                        {data: 'ref_tran_date_time', name: 'ref_tran_date_time'},
                        {data: 'pay_amount', name: 'pay_amount'},
                        {data: 'payment_status', name: 'payment_status'},
                        {data: 'action', name: 'action', orderable: false, searchable: true}
                    ],
                    "aaSorting": []
                });
            });
            $("form").keydown(function (e) {
                if(e.keyCode == 13) {
                    e.preventDefault();
                    $('#search_process').click();
                }
            });
        });
    </script>
@endsection

