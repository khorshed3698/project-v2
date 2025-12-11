<div class="form-group clearfix">
    <div class="row">
        <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
            {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left required-star']) !!}
            <div class="col-md-7">
                {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required']) !!}
                {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
            {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
            <div class="col-md-7">
                {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' => 'form-control input-md email required']) !!}
                {!! $errors->first('sfp_contact_email','<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group clearfix">
    <div class="row">
        <div class="col-md-6">
            {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left required-star']) !!}
            <div class="col-md-7">
                {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md phone_or_mobile required']) !!}

            </div>
        </div>
        <div class="col-md-6">
            {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
            <div class="col-md-7">
                {!! Form::text('sfp_contact_address', Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' => 'form-control input-md required']) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group clearfix">
    <div class="row">
        <div class="col-md-6">
            {!! Form::label('brep_fee','CDA Processing Fee',['class'=>'col-md-5 text-left']) !!}
            <div class="col-md-7">
                <input name="brep_fee" class="form-control input-md" value="{{$applyPaymentfeeWithVat}}" readonly>
            </div>
        </div>
        <div class="col-md-6">
            {!! Form::label('service_fee','Service Fee',['class'=>'col-md-5 text-left']) !!}
            <div class="col-md-6">
                <input name="service_fee" class="form-control input-md" value="{{$ServicepaymentData->amount}}"
                       readonly>
            </div>
        </div>
    </div>
</div>
<div class="form-group clearfix">
    <div class="row">
        <?php
        $totalamount = number_format($applyPaymentfeeWithVat + $ServicepaymentData->amount, 2);
        ?>
        <div class="col-md-6">
            {!! Form::label('sfp_total_amount','Total amount',['class'=>'col-md-5 text-left']) !!}
            <div class="col-md-7">
                <input name="sfp_total_amount" class="form-control input-md" value="{{$totalamount}}" readonly>
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