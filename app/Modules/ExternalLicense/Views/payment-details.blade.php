<?php
$countPayment = count($amountDetails);
?>
<div class="panel panel-info">
    <div class="panel-heading">
        <strong>Service Fee Payment</strong>
    </div>
    <div class="panel-body">

        <div class="form-group">
            <div class="row">
                <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                    {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left
                    required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(),
                        ['class' => 'form-control input-md required']) !!}
                        {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div>
                <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                    {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left
                    required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' =>
                        'form-control input-md email required']) !!}
                        {!! $errors->first('sfp_contact_email','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div>
            </div>
        </div><!--./form-group-->
        <div class="form-group">
            <div class="row">
                <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                    {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left
                    required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' =>
                        'form-control input-md required']) !!}
                        {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div><!--./col-md-6-->
                <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                    {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left
                    required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('sfp_contact_address', Auth::user()->road_no .
                        (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' =>
                        'form-control input-md required']) !!}
                        {!! $errors->first('sfp_contact_address','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div><!--./col-md-6-->
            </div><!--./row-->
        </div><!--./form-group-->
        @if($countPayment>0)
                <?php
                $sl = 1;
                ?>
            @foreach($amountDetails as $key => $amount)
                @if($sl % 2 == 1)
                    <div class="form-group">
                        <div class="row">
                            @endif
                            <div class="col-md-6 {{$errors->has('sfp_pay_amount') ? 'has-error': ''}}">
                                {!! Form::label("$key",str_replace('_', ' ', $key),['class'=>'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    {!! Form::text("$key", $amount, ['class' => 'form-control input-md','disabled']) !!}
                                    {!! $errors->first("$key",'<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            @if($sl % 2 == 0)
                        </div><!--./row-->
                    </div><!--./form-group-->
                @endif
                    <?php
                    $sl++;
                    ?>
            @endforeach
            @if(($sl-1) % 2 == 1)
    </div>
</div>
@endif
@endif
<div class="form-group " {{$countPayment>0?"style=display:none":''}}>
    <div class="row">
        <div class="col-md-6 {{$errors->has('sfp_pay_amount') ? 'has-error': ''}}">
            {!! Form::label('sfp_pay_amount','Pay amount',['class'=>'col-md-5 text-left']) !!}
            <div class="col-md-7">
                {!! Form::text('sfp_pay_amount', $payment_config->amount, ['class' => 'form-control input-md', 'readonly']) !!}
                {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="col-md-6 {{$errors->has('sfp_vat_on_pay_amount') ? 'has-error': ''}}">
            {!! Form::label('sfp_vat_on_pay_amount','VAT on pay amount',['class'=>'col-md-5 text-left']) !!}
            <div class="col-md-7">
                {!! Form::text('sfp_vat_on_pay_amount', number_format($payment_config->vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                {!! $errors->first('sfp_vat_on_pay_amount','<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group ">
    <div class="row">
        <div class="col-md-6">
            {!! Form::label('sfp_total_amount','Total amount',['class'=>'col-md-5 text-left']) !!}
            <div class="col-md-7">
                {!! Form::text('sfp_total_amount', number_format($payment_config->amount + $payment_config->vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
            </div>
        </div>
        <div class="col-md-6">
            {!! Form::label('sfp_status','Payment status',['class'=>'col-md-5 text-left']) !!}
            <div class="col-md-7">
                <span class="label label-warning">Not Paid</span>
            </div>
        </div>
    </div>
</div>


{{--Vat/ tax and service charge is an approximate amount--}}
<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                Vat/ tax and service charge is an approximate amount, it may vary
                based
                on the
                Sonali Bank system.
            </div>
        </div>
    </div>
</div><!--./form-group-->