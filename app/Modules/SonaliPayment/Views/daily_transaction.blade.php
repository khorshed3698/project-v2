@extends('layouts.admin')

@section('page_heading',trans('messages.payment_list'))

@section('content')
    <?php // $accessMode=ACL::getAccsessRight('spg');
    // if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
    ?>

    <div class="col-lg-12">

        @include('partials.messages')

        <div class="panel panel-primary">
            <div class="panel-heading">

                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>{!!trans('messages.payment_list')!!}</strong></strong></h5>
                </div>
                <div class="pull-right">
                    <a class="" href="{{ url('/spg') }}">
                        {!! Form::button('<i class="fa fa-list"></i><b> ' .trans('messages.daily_transaction').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php var_dump($dailyTransactions); die;?>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Transaction Date</th>
                            <th>Applicant Name</th>
                            <th>Mobile No</th>
                            <th>Tran Amount</th>
                            <th>Pay Amount</th>
                            <th>Commission Amount</th>
                            <th>Vat Amount</th>
                            <th>Status Code</th>
                        </tr>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyTransactions as $dailyTransaction)
                                <td>{{$dailyTransaction->TransactionId}}</td>
                                <td>{{$dailyTransaction->TransactionDate}}</td>
                                <td>{{$dailyTransaction->ApplicantName}}</td>
                                <td>{{$dailyTransaction->MobileNo}}</td>
                                <td>{{$dailyTransaction->TranAmount}}</td>
                                <td>{{$dailyTransaction->PayAmount}}</td>
                                <td>{{$dailyTransaction->CommissionAmount}}</td>
                                <td>{{$dailyTransaction->VatAmount}}</td>
                                <td>{{$dailyTransaction->StatusCode}}</td>
                            @endforeach
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

    </script>
@endsection