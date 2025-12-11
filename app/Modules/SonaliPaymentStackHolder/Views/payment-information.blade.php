<div id="paymentInfo" class="collapse">
    <div class="panel panel-success">
        <div class="panel-heading">
            <strong>Payment Information</strong>
        </div>
        @foreach($spPaymentinformation as $spPayment)
        <div class="panel-body">
            <div style="margin:0;" class="panel panel-info">
                <div class="panel-heading">
                    Service Fee Payment
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Contact name</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $spPayment->sfp_contact_name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Contact email</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $spPayment->sfp_contact_email }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Contact phone</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $spPayment->sfp_contact_phone }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Contact address</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $spPayment->sfp_contact_address }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Pay amount</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $spPayment->sfp_pay_amount }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">VAT on pay amount</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $spPayment->sfp_vat_on_pay_amount }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label"> Transaction charge</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $spPayment->sfp_transaction_charge_amount }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label"> VAT on transaction charge</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $spPayment->sfp_vat_on_transaction_charge }}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Total amount</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $spPayment->sfp_total_amount }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Payment status</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    @if($spPayment->sfp_payment_status == 0)
                                        <span class="label label-warning">Pending</span>
                                    @elseif($spPayment->sfp_payment_status == -1)
                                        <span class="label label-info">In-progress</span>
                                    @elseif($spPayment->sfp_payment_status == 1)
                                        <span class="label label-success">Paid</span>
                                    @elseif($spPayment->sfp_payment_status == 2)
                                        <span class="label label-danger">Exception</span>
                                    @elseif($spPayment->sfp_payment_status == 3)
                                        <span class="label label-warning">Waiting for payment confirmation</span>
                                    @else
                                        <span class="label label-warning">Invalid status</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Payment mode</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $spPayment->pay_mode }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-footer">


                    <div class="pull-left">
                        <a href="/spg/stack-holder/{{ $spPayment->pay_mode_code == 'A01' ? 'counter-' : '' }}payment-voucher/{{ Encryption::encodeId($spPayment->sp_payment_id)}}"
                           target="_blank" class="btn btn-info btn-sm">
                            <strong> Download voucher</strong>
                        </a>
                    </div>

                    @if(isset($spg_challan_base_url))
                        <div class="pull-left" style="margin-left: 10px">
                            <a href="{{$spg_challan_base_url.$spPayment->transaction_id}}"
                               target="_blank" class="btn btn-info btn-sm">
                                <strong> Download Challan</strong>
                            </a>
                        </div>
                    @endif

                    @if($spPayment->sfp_payment_status == 3 && in_array(Auth::user()->user_type, ['5x505']))
                        <div class="pull-right">
                            <a href="/spg/stack-holder/counter-payment-check/{{ Encryption::encodeId($spPayment->sp_payment_id)}}/{{Encryption::encodeId(0)}}"
                               class="btn btn-danger btn-sm">
                                <strong> Cancel payment request</strong>
                            </a>
                            <a href="/spg/stack-holder/counter-payment-check/{{ Encryption::encodeId($spPayment->sp_payment_id)}}/{{Encryption::encodeId(1)}}"
                               class="btn btn-primary btn-sm">
                                <strong> Confirm payment request</strong>
                            </a>
                        </div>
                    @endif
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>