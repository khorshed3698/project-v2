@extends('layouts.admin')
@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>
<div class="col-lg-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="pull-left">
                <h5><strong>Payment configuration</strong></h5>
            </div>
            <div class="pull-right">
                @if(ACL::getAccsessRight('settings','A'))
                <a class="" href="{{ url('/settings/create-payment-configuration') }}">
                    {!! Form::button('<i class="fa fa-plus"></i>  <b>Create payment configuration</b> ', array('type' => 'button', 'class' => 'btn btn-default')) !!}
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
                            {{--<th>#</th>--}}
                            <th>Process type</th>
                            <th>Payment category</th>
                            <th>Amount(Tk.)</th>
                            {{--<th>VAT on transaction charge(%)</th>--}}
                            {{--<th>Charge(%)</th>--}}
                            {{--<th>Charge min(Tk.)</th>--}}
                            {{--<th>Charge max(Tk.)</th>--}}
                            <th>Status</th>
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

@section('footer-script')
    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("settings/get-payment-configuration-details-data")}}',
                    method:'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
//                    {data: 'sl_no', name: 'sl_no'},
                    {data: 'process_type_name', name: 'process_type_name'},
                    {data: 'payment_cat_name', name: 'payment_cat_name'},
                    {data: 'amount', name: 'amount'},
                    // {data: 'vat_on_transaction_charge_percent', name: 'vat_on_transaction_charge_percent'},
                    // {data: 'trans_charge_percent', name: 'trans_charge_percent'},
                    // {data: 'trans_charge_min_amount', name: 'trans_charge_min_amount'},
                    // {data: 'trans_charge_max_amount', name: 'trans_charge_max_amount'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });

        });
    </script>
@endsection
