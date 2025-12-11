@extends('layouts.admin')
@section('content')

<section class="content">
    <div class="box">
        <div class="box-body">
            <div class="col-lg-12">

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h5><strong>Service Fee Payment</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'visa-recommendation/store','method' => 'post','id' =>
                    'VisaRecommendationForm','enctype'=>'multipart/form-data',
                    'method' => 'post', 'files' => true, 'role'=>'form')) !!}
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                    {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left
                                    required-star']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('sfp_contact_name',
                                        \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control
                                        input-md required']) !!}
                                        {!! $errors->first('sfp_contact_name','<span
                                            class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                    {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left
                                    required-star']) !!}
                                    <div class="col-md-7">
                                        {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' =>
                                        'form-control input-md required email']) !!}
                                        {!! $errors->first('sfp_contact_email','<span
                                            class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                                    {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left
                                    required-star']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' =>
                                        'form-control input-md required phone_or_mobile']) !!}
                                        {!! $errors->first('sfp_contact_phone','<span
                                            class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                    {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5
                                    text-left required-star']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('sfp_contact_address', Auth::user()->road_no .
                                        (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class'
                                        => 'form-control input-md required']) !!}
                                        {!! $errors->first('sfp_contact_address','<span
                                            class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 {{$errors->has('sfp_pay_amount') ? 'has-error': ''}}">
                                    {!! Form::label('sfp_pay_amount','Pay amount',['class'=>'col-md-5 text-left']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('sfp_pay_amount', $payment_config->amount, ['class' =>
                                        'form-control input-md', 'readonly']) !!}
                                        {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>')
                                        !!}
                                    </div>
                                </div>
                                <div class="col-md-6 {{$errors->has('sfp_vat_tax') ? 'has-error': ''}}">
                                    {!! Form::label('sfp_vat_tax','VAT/TAX',['class'=>'col-md-5 text-left']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('sfp_vat_tax', ($payment_config->vat_tax_percent / 100) *
                                        $payment_config->amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                        {!! $errors->first('sfp_vat_tax','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <?php
                                    $charge_amount = ($payment_config->trans_charge_percent / 100) * $payment_config->amount;
                                    if($charge_amount < 30){
                                        $charge_amount = 30;
                                    }
                                    if($charge_amount > 500){
                                        $charge_amount = 500;
                                    }
                                    ?>
                                <div class="col-md-6 {{$errors->has('sfp_bank_charge') ? 'has-error': ''}}">
                                    {!! Form::label('sfp_bank_charge','Bank Charge',['class'=>'col-md-5 text-left']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('sfp_bank_charge', $charge_amount, ['class' => 'form-control
                                        input-md', 'readonly']) !!}
                                        {!! $errors->first('sfp_bank_charge','<span class="help-block">:message</span>')
                                        !!}
                                    </div>
                                </div>
                                <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
                                    {!! Form::label('sfp_status','Payment Status',['class'=>'col-md-5 text-left']) !!}
                                    <div class="col-md-7">
                                        <span class="label label-warning">Not Paid</span>
                                    </div>
                                </div>
                            </div>
                        </div>



                        {{--<table class="table table-bordered table-striped">--}}
                        {{--<tbody>--}}
                        {{--<tr>--}}
                        {{--<td>Contact Name</td>--}}
                        {{--<td>{{ \App\Libraries\CommonFunction::getUserFullName() }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Contact Email</td>--}}
                        {{--<td>{{ Auth::user()->user_email }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Contact Phone</td>--}}
                        {{--<td>{{ Auth::user()->user_phone }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Contact Address</td>--}}
                        {{--<td>{{ Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : '') }}
                        </td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Payment Name</td>--}}
                        {{--<td>{{ $payment_config->name }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Amount</td>--}}
                        {{--<td>{{ $payment_config->amount }}</td>--}}
                        {{--</tr>--}}

                        {{--<tr>--}}
                        <?php
                            {{--$charge_amount = ($payment_config->trans_charge_percent / 100) * $payment_config->amount;--}}
                            {{--if($charge_amount < 30){--}}
                            {{--$charge_amount = 30;--}}
                            {{--}--}}
                            {{--if($charge_amount > 500){--}}
                            {{--$charge_amount = 500;--}}
                            {{--}--}}
                            {{--?>--}}
                        {{--<td>Transaction Charge amount</td>--}}
                        {{--<td> {{ $charge_amount }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<?php--}}
                        {{--$vat = ($payment_config->vat_tax_percent / 100) * $payment_config->amount;--}}
                        ?>
                        {{--<td>Vat</td>--}}
                        {{--<td>{{ $vat }}</td>--}}
                        {{--</tr>--}}
                        {{--</tbody>--}}
                        {{--</table>--}}
                    </div>

                    <div class="panel-footer">
                        <div class="pull-left">
                            <a href="{{ url('settings/payment-configuration') }}">
                                {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class'
                                => 'btn btn-default')) !!}
                            </a>
                        </div>

                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-chevron-circle-right"></i> Payment Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    {!! Form::close() !!}
                    <!-- /.form end -->
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
@section('footer-script')
@endsection