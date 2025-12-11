    <div id="viewadditionalpayment" class="collapse">
    <div class="panel panel-success">
        <div class="panel-heading">
            <strong>Payment Information</strong>
        </div>
        <div class="panel-body">
            <div style="margin:0;" class="panel panel-info">
                <div class="panel-heading">
                    Government Fee Payment
                </div>
                <div class="panel-body">
                    @foreach($additionalPayment as $addPayment)
                    <fieldset  class="border p-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Contact name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $addPayment->sfp_contact_name }}
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
                                        {{ $addPayment->sfp_contact_email }}
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
                                        {{ $addPayment->sfp_contact_phone }}
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
                                        {{ $addPayment->sfp_contact_address }}
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
                                        {{ $addPayment->sfp_pay_amount }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">VAT/ TAX</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $addPayment->sfp_vat_tax }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label"> Bank charge</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $addPayment->sfp_bank_charge }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Total amount</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ number_format($addPayment->sfp_pay_amount + $appInfo->sfp_vat_tax + $appInfo->sfp_bank_charge, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Payment status</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        @if($addPayment->sfp_payment_status == 0)
                                            <span class="label label-warning">Pending</span>
                                        @elseif($addPayment->sfp_payment_status == -1)
                                            <span class="label label-info">In-progress</span>
                                        @elseif($addPayment->sfp_payment_status == 1)
                                            <span class="label label-success">Paid</span>
                                        @elseif($addPayment->sfp_payment_status == 2)
                                            <span class="label label-danger">Exception</span>
                                        @elseif($addPayment->sfp_payment_status == 3)
                                            <span class="label label-warning">Waiting for payment confirmation</span>
                                        @else
                                            <span class="label label-warning">Invalid status</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Payment mode</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $addPayment->pay_mode }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Ref. Date Time</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $addPayment->ref_tran_date_time }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                        <br>
                        @endforeach
                </div>

{{--                <div class="panel-footer">--}}
{{--                    <div class="pull-left">--}}
{{--                        <a href="/spg/{{ $appInfo->pay_mode_code == 'A01' ? 'counter-' : '' }}payment-voucher/{{ Encryption::encodeId($appInfo->sf_payment_id)}}"--}}
{{--                           target="_blank" class="btn btn-info btn-sm">--}}
{{--                            <strong> Download voucher</strong>--}}
{{--                        </a>--}}
{{--                    </div>--}}

{{--                    --}}{{-- Counter payment, 3 = Waiting for Payment Confirmation--}}
{{--                    @if($appInfo->sfp_payment_status == 3 && in_array(Auth::user()->user_type, ['5x505']))--}}
{{--                        <div class="pull-right">--}}
{{--                            <a href="/spg/counter-payment-check/{{ Encryption::encodeId($appInfo->sf_payment_id)}}/{{Encryption::encodeId(0)}}"--}}
{{--                               class="btn btn-danger btn-sm">--}}
{{--                                <strong> Cancel payment request</strong>--}}
{{--                            </a>--}}
{{--                            <a href="/spg/counter-payment-check/{{ Encryption::encodeId($appInfo->sf_payment_id)}}/{{Encryption::encodeId(1)}}"--}}
{{--                               class="btn btn-primary btn-sm">--}}
{{--                                <strong> Confirm payment request</strong>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    <div class="clearfix"></div>--}}
{{--                </div>--}}
            </div>
        </div>

        @if($appInfo->gf_payment_id != 0 && !empty($appInfo->gf_payment_id))
        <div class="panel-body">
            <div style="margin:0;" class="panel panel-info">
                <div class="panel-heading">
                    Government Fee Payment
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
                                    {{ $appInfo->gfp_contact_name }}
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
                                    {{ $appInfo->gfp_contact_email }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label"> Contact phone</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $appInfo->gfp_contact_phone }}
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
                                    {{ $appInfo->gfp_contact_address }}
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
                                    {{ $appInfo->gfp_pay_amount }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">VAT/ TAX</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $appInfo->gfp_vat_tax }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Bank charge</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $appInfo->gfp_bank_charge }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Total amount</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ number_format($appInfo->gfp_pay_amount + $appInfo->gfp_vat_tax + $appInfo->gfp_bank_charge, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Payment status</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    @if($appInfo->gfp_payment_status == 0)
                                        <span class="label label-warning">Pending</span>
                                    @elseif($appInfo->gfp_payment_status == -1)
                                        <span class="label label-info">In-progress</span>
                                    @elseif($appInfo->gfp_payment_status == 1)
                                        <span class="label label-success">Paid</span>
                                    @elseif($appInfo->gfp_payment_status == 2)
                                        <span class="label label-danger">Exception</span>
                                    @elseif($appInfo->gfp_payment_status == 3)
                                        <span class="label label-warning">Waiting for payment confirmation</span>
                                    @else
                                        <span class="label label-warning">Invalid status</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Payment mode</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $appInfo->gfp_pay_mode }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-footer">
                    <div class="pull-left">
                        <a href="/spg/{{ $appInfo->gf_pay_mode_code == 'A01' ? 'counter-' : '' }}payment-voucher/{{ Encryption::encodeId($appInfo->gf_payment_id)}}"
                           target="_blank" class="btn btn-info btn-sm">
                            <strong> Download voucher</strong>
                        </a>
                    </div>

                    {{-- Counter payment, 3 = Waiting for Payment Confirmation--}}
                    @if($appInfo->gfp_payment_status == 3 && in_array(Auth::user()->user_type, ['5x505']))
                        <div class="pull-right">
                            <a href="/spg/counter-payment-check/{{ Encryption::encodeId($appInfo->gf_payment_id)}}/{{Encryption::encodeId(0)}}"
                               class="btn btn-danger btn-sm">
                                <strong> Cancel payment request</strong>
                            </a>
                            <a href="/spg/counter-payment-check/{{ Encryption::encodeId($appInfo->gf_payment_id)}}/{{Encryption::encodeId(1)}}"
                               class="btn btn-primary btn-sm">
                                <strong> Confirm payment request</strong>
                            </a>
                        </div>
                    @endif
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>