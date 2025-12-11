<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ url("assets/plugins/select2.min.css") }}">
<script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
<style>
    .wizard > .content,
    .wizard,
    .tabcontrol {
        overflow: visible;
    }

    .form-group {
        margin-bottom: 2px;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
    }

    .wizard > .steps .number {
        font-size: 1.2em;
    }

    .intl-tel-input .country-list {
        z-index: 5;
    }

    textarea {
        height: 60px !important;
    }

    .col-md-7 {
        margin-bottom: 10px;
    }

    label {
        float: left !important;
    }

    .col-md-5 {
        position: relative;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 8px;
    }

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .adhoc {
        margin-left: 15px;
    }

    .adhoc button {
        margin-top: 15px;
    }

    table thead {
        background-color: #ddd;
    }

    #loader {
        background: none repeat scroll 0 0 black;
        position: fixed;
        display: block;
        opacity: 0.5;
        z-index: 1000001;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        background: url(https://media.tenor.com/images/817596d6626736251eea50f61b9492a4/tenor.gif) center no-repeat #fff;
        background-size: 100px 100px;
    }

    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

    }
</style>

<div class="col-md-12">
    @include('message.message')
</div>
<div class="col-md-12">
    @if($appInfo->status_id == 5)
        <div class="pull-right">
            <a class="btn btn-sm btn-danger" target="_blank"
               href="/licence-applications/vat-registration/show-shortfall-message/{{\App\Libraries\Encryption::encodeId($appInfo->id)}}"
               role="button" aria-expanded="false" aria-controls="collapseExample">
                View Shortfall Messages
            </a>
        </div>
    @endif
    <div class="panel panel-primary" id="inputForm">
        <div class="panel-heading">
            <h5><strong>Application For VAT Registration</strong></h5>
        </div>

        {!! Form::open(array('url' => 'vat-registration/store','method' => 'post', 'class' => 'form-horizontal', 'id' =>
        'VATRegForm',
        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
        <input type="hidden" name="selected_file" id="selected_file"/>
        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
        <input type="hidden" name="isRequired" id="isRequired"/>
        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"
               id="app_id"/>

        <h3 class="text-center stepHeader">Details Information</h3>
        <fieldset>

            <div id="loader" style="display:none;">
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>A. REGISTRATION BASICS</strong></div>
                <div class="panel-body">

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('reg_category','A1. Registration Category', ['class'=>'col-md-4
                                required-star']) !!}
                                <div class="col-md-8 {{$errors->has('reg_category') ? 'has-error': ''}}"
                                     id="reg_cagegory_div">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"
                         style="{{(isset($appData->reg_category) && $appData->reg_category == 're-registration') ?  '' : 'display:none;'}}"
                         id="reRegHiddenDiv">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('old_bin','Old 11-digit BIN', ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('old_bin') ? 'has-error': ''}}">
                                    {!! Form::text('old_bin', (isset($appData->old_bin) ? $appData->old_bin :
                                    ''),['class' => 'form-control onlyNumber
                                    input-sm
                                    required','id'=>'old_bin']) !!}
                                    {!! $errors->first('old_bin','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('company_name','Name of Company', ['class'=>'col-md-5 required-star'])
                                !!}
                                <div class="col-md-7 {{$errors->has('company_name') ? 'has-error': ''}}">
                                    {!! Form::text('company_name', (isset($appData->company_name) ?
                                    $appData->company_name : '' ),['class' =>
                                    'form-control
                                    input-sm required','id'=>'company_name']) !!}
                                    {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group" style="">
                        <div class="col-md-12">
                            {!! Form::label('reg_category','If you registered a 9-digit BIN before 01-Jul-2019, you must provide that BIN here. By providing the existing BIN, VAT Officer will review your Mushak-2.1 form and decide if you are entitled to link with that existing 9-digit BIN or not', ['class'=>'
                           required-star']) !!}
                        </div>
                        <div class="col-md-12">
                            <label class="radio-inline">
                                <input type="radio" id="registeredBin_no" name="registeredBin"
                                       onclick="reg_bin_show(1)"
                                       <?php if (isset($appData->registeredBin) && $appData->registeredBin == 1){?> checked
                                       <?php } ?>
                                       value="1">
                                No, I haven't registered for any 9-digit BIN
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label class="radio-inline">
                                <input type="radio" id="registeredBin_no" name="registeredBin" onclick="reg_bin_show(2)"
                                       <?php if (isset($appData->registeredBin) && $appData->registeredBin == 2){?> checked
                                       <?php } ?>
                                       value="2">
                                Yes, I have registered a 9-digit BIN
                            </label>
                        </div>
                        <div id="reg_bin_num" class="col-md-8 row">
                            {!! Form::label('reg_9_bin','Registered 9-digit BIN:', ['class'=>'col-md-6
                           required-star']) !!}
                            {!! Form::text('bin_number', (isset($appData->bin_number)) ?
                                        $appData->bin_number : '',['class' => 'col-md-6
                                        input-md required' ,'id'=>'bin_number']) !!}

                        </div>

                    </div>

                    <div class="clearfix"></div>
                </div>
                <!--/panel-body-->
            </div>
            <!--/panel-->

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>B. BUSINESS INFORMATION</strong></div>
                <div class="panel-body">

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-12">
                                <?php (isset($appData->ownership_type)) ? $appData->ownership_type : $appData->ownership_type = []; ?>
                                {!! Form::label('ownership_type','B1. Type of Ownership', ['class'=>'col-md-3
                                required-star']) !!}
                                <div class="col-md-9 {{$errors->has('reg_category') ? 'has-error': ''}}"
                                     id="ownership_type_div">
                                </div>
                                <div class="col-md-9" id="business_please_specify"
                                     style="{{in_array('10@Others',$appData->ownership_type) ? '' : 'display:none;'}}">
                                    {!! Form::label('please_specify','Please Specify', ['class'=>'col-md-5
                                    required-star']) !!}
                                    <div
                                            class="col-md-7 {{$errors->has('please_specify') ? 'has-error': ''}}">
                                        {!! Form::text('please_specify', (isset($appData->please_specify)) ?
                                        $appData->please_specify : '',['class' => 'form-control
                                        input-sm required please_specify']) !!}
                                        {!! $errors->first('please_specify','<span
                                            class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">

                            </div>
                        </div>
                    </div>


                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('withholding_entity','B2. Are you a Withholding Entity',
                                ['class'=>'col-md-5 required-star']) !!}

                                <div class="col-md-7 {{$errors->has('withholding_entity') ? 'has-error': ''}}"
                                     id="withholding_entity_div">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <!--/panel-body-->
            </div>
            <!--/panel-->

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>C. GENERAL INFORMATION</strong></div>
                <div class="panel-body">

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('tl_number','C1. Trade License Number', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('tl_number') ? 'has-error': ''}}">
                                    {!! Form::text('tl_number', $appData->tl_number,['class' => 'form-control input-sm
                                    required']) !!}
                                    {!! $errors->first('tl_number','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('tl_issue_date','Issue Date',['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('tl_issue_date') ? 'has-error': ''}}">
                                    <div class="datepickerlicence input-group date">
                                        {!! Form::text('tl_issue_date', $appData->tl_issue_date,['class' =>
                                        'form-control input-sm required']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('tl_issue_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('rjsc_inc_number','C2. RJSC Incorporation Number', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('rjsc_inc_number') ? 'has-error': ''}}">
                                    {!! Form::text('rjsc_inc_number', $appData->rjsc_inc_number,['class' =>
                                    'form-control input-sm required']) !!}
                                    {!! $errors->first('rjsc_inc_number','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('rjsc_inc_issue_date','Issue Date',['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('tl_issue_date') ? 'has-error': ''}}">
                                    <div class="datepickerlicence2 input-group date">
                                        {!! Form::text('rjsc_inc_issue_date', $appData->rjsc_inc_issue_date,['class' =>
                                        'form-control input-sm required']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('rjsc_inc_issue_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('etin','C3. e-TIN', ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('etin') ? 'has-error': ''}}">
                                    {!! Form::text('etin', $appData->etin,['class' => 'form-control input-sm required
                                    onlyNumber']) !!}
                                    {!! $errors->first('etin','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('etin_entity_name','C4. Name of the Entity (as in e-TIN)',
                                ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('etin_entity_name') ? 'has-error': ''}}">
                                    {!! Form::text('etin_entity_name', $appData->etin_entity_name,['class' =>
                                    'form-control input-sm required']) !!}
                                    {!! $errors->first('etin_entity_name','<span class="help-block">:message</span>')
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('entity_name','C5. Name of the Entity', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('entity_name') ? 'has-error': ''}}">
                                    {!! Form::text('entity_name',$appData->entity_name,['class' => 'form-control
                                    input-sm required']) !!}
                                    {!! $errors->first('entity_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('trading_brand_name','C6. Trading Brand Name', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('trading_brand_name') ? 'has-error': ''}}">
                                    {!! Form::text('trading_brand_name',$appData->trading_brand_name,['class' =>
                                    'form-control input-sm required', 'id'=>'trading_brand_name']) !!}
                                    {!! $errors->first('trading_brand_name','<span class="help-block">:message</span>')
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('registration_type','C7. Registration Type', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('reg_category') ? 'has-error': ''}}"
                                     id="registration_type_div">

                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('equity_info','C8. Equity Information', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('reg_category') ? 'has-error': ''}}"
                                     id="equity_info_div">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"
                         style="{{(isset($appData->equity_info) && $appData->equity_info == 3) ? '' : 'display:none;'}}"
                         id="local_share_div">
                        <div class="row">
                            <div class="col-md-offset-6 col-md-6">
                                {!! Form::label('local_share','Local Share (%)', ['class'=>'col-md-5 required-star'])
                                !!}
                                <div class="col-md-7 {{$errors->has('local_share') ? 'has-error': ''}}">
                                    {!! Form::text('local_share', (isset($appData->local_share)) ?
                                    $appData->local_share:'',['class' => 'form-control input-sm required
                                    onlyNumber','id'=>'local_share']) !!}
                                    {!! $errors->first('local_share','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('bida_reg_number','C9. BIDA Registration Number', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('bida_reg_number') ? 'has-error': ''}}">
                                    {!! Form::text('bida_reg_number', !empty($appData->bida_reg_number)?$appData->bida_reg_number:"",['class' =>
                                    'form-control input-sm required', 'id'=>'bida_reg_number']) !!}
                                    {!! $errors->first('bida_reg_number','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('bida_reg_issue_date','Issue Date',['class'=>'col-md-5 required-star'])
                                !!}
                                <div class="col-md-7 {{$errors->has('bida_reg_issue_date') ? 'has-error': ''}}">
                                    <div class="datepickerbida input-group date">
                                        {!! Form::text('bida_reg_issue_date', !empty($appData->bida_reg_issue_date)? $appData->bida_reg_issue_date:"",['class' =>
                                        'form-control input-sm required']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('bida_reg_issue_date','<span class="help-block">:message</span>')
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <!--/panel-body-->
            </div>
            <!--/panel-->

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>D. CONTACT INFORMATION</strong></div>
                <div class="panel-body">

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('factory_address','D1. Factory/ Business Operations Address',
                                ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('factory_address') ? 'has-error': ''}}">
                                    {!! Form::textarea('factory_address', isset($appData->factory_address) ? $appData->factory_address: '', ['data-rule-maxlength'=>'240', 'class'
                                    => 'form-control bigInputField input-md maxTextCountDown',
                                    'size'=>'5x2','data-charcount-maxlength'=>'200','id'=>'factory_address']) !!}
                                    {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6 {{$errors->has('district') ? 'has-error': ''}}">
                                {!! Form::label('district','D2. District',['class'=>'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    <select name="district" class="form-control input-md search-box" id="district">
                                    </select>
                                    {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('police_station') ? 'has-error': ''}}">
                                {!! Form::label('police_station','D3. Police Station',['class'=>'col-md-5 text-left'])
                                !!}
                                <div class="col-md-7">
                                    <select name="police_station" class="form-control input-md search-box" id="police_station">

                                    </select>
                                    {!! $errors->first('police_station','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6 {{$errors->has('post_code') ? 'has-error': ''}}">
                                {!! Form::label('post_code','D4. Postal Code',['class'=>'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    <select name="post_code" class="form-control input-md search-box" id="postCode">
                                    </select>
                                    {!! $errors->first('post_code','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('land_telephone','D5. Land Telephone Number ', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('land_telephone') ? 'has-error': ''}}">
                                    {!! Form::text('land_telephone', isset($appData->land_telephone) ? $appData->land_telephone: '',['class' => 'form-control input-sm required
                                    onlyNumber',"maxlength"=>"15"]) !!}
                                    {!! $errors->first('land_telephone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('mobile_telephone','D6. Mobile Telephone Number', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('mobile_telephone') ? 'has-error': ''}}">
                                    {!! Form::text('mobile_telephone', isset($appData->mobile_telephone) ? $appData->mobile_telephone: '',['class' => 'form-control input-sm required
                                    onlyNumber mobile',"maxlength"=>"11"]) !!}
                                    {!! $errors->first('mobile_telephone','<span class="help-block">:message</span>')
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('email','D7. e-Mail', ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('email') ? 'has-error': ''}}">
                                    {!! Form::text('email',  isset($appData->email) ? $appData->email: '',['class' => 'form-control input-sm required
                                    email','placeholder'=>'example@gmail.com']) !!}
                                    {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('fax','D8. Fax Number', ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('fax') ? 'has-error': ''}}">
                                    {!! Form::text('fax', isset($appData->fax) ? $appData->fax: '',['class' => 'form-control input-sm required onlyNumber',"maxlength"=>"15","id"=>"fax"])
                                    !!}
                                    {!! $errors->first('fax','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('web_address','D9. Web Address', ['class'=>'col-md-5 required-star'])
                                !!}
                                <div class="col-md-7 {{$errors->has('web_address') ? 'has-error': ''}}">
                                    {!! Form::text('web_address', isset($appData->web_address) ? $appData->web_address: '',['class' => 'form-control input-sm required
                                    web_address','id'=>'web_address','placeholder'=>'www.example.com']) !!}
                                    {!! $errors->first('web_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('headquarter_address','D10. Headquarter Address', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('headquarter_address') ? 'has-error': ''}}">
                                    {!! Form::textarea('headquarter_address', isset($appData->headquarter_address) ? $appData->headquarter_address: '', ['data-rule-maxlength'=>'240',
                                    'class' => 'form-control bigInputField input-md maxTextCountDown',
                                    'size'=>'5x2','data-charcount-maxlength'=>'200','id'=>'headquarter_address']) !!}
                                    {!! $errors->first('headquarter_address','<span class="help-block">:message</span>')
                                    !!}
                                    {!! Form::checkbox('same_as_factory',1, (isset($appData->same_as_factory) && ($appData->same_as_factory == 1) ? true : false) , array('id'=>'same_as_factory',
                                    'onclick'=>'samaAsFactoryFunction()','class'=>'helpTextCheckbox col-md-1')) !!}
                                    {!! Form::label('same_as_factory','Same
                                    as
                                    (Factory/Business Operations Address)', ['class'=>'col-md-11']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('headquarter_address_outside','D11. Headquarter Address outside of
                                Bangladesh', ['class'=>'col-md-5']) !!}
                                <div class="col-md-7 {{$errors->has('headquarter_address_outside') ? 'has-error': ''}}">
                                    {!! Form::textarea('headquarter_address_outside', isset($appData->headquarter_address_outside) ? $appData->headquarter_address_outside: '',
                                    ['data-rule-maxlength'=>'240', 'class' => 'form-control bigInputField input-md
                                    maxTextCountDown',
                                    'size'=>'5x2','data-charcount-maxlength'=>'200']) !!}
                                    {!! $errors->first('headquarter_address_outside','<span
                                        class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="clearfix"></div>
                </div>
                <!--/panel-body-->
            </div>
            <!--/panel-->

            <div class="panel panel-primary">
                @include('VATReg::branch_information_edit')
            </div>
            <!--/panel-->

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>F. MAJOR AREA OF ECONOMIC ACTIVITY</strong></div>
                <div class="panel-body">
                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-12" id="economic_activity_div">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="imports_div" style="display:none;">
                                    <div class="col-md-6">
                                        {!! Form::label('support_document_imports','ImportsSupporting Document Number',
                                        ['class'=>'col-md-5 required-star']) !!}
                                        <div
                                                class="col-md-7 {{$errors->has('support_document_imports') ? 'has-error': ''}}">
                                            {!! Form::text('support_document_imports',isset($appData->support_document_imports)?$appData->support_document_imports:null,['class' => 'form-control
                                            input-sm required']) !!}
                                            {!! $errors->first('support_document_imports','<span
                                                class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('issue_date_imports','Imports Issue Date', ['class'=>'col-md-5
                                        required-star']) !!}
                                        <div class="col-md-7 {{$errors->has('issue_date_imports') ? 'has-error': ''}}">
                                            <div class="datepickerimports input-group date">
                                                {!! Form::text('issue_date_imports', isset($appData->issue_date_imports)?$appData->issue_date_imports:null,['class' => 'form-control input-sm
                                            required']) !!}
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                            </div>
                                            {!! $errors->first('issue_date_imports','<span
                                                class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="exports_div" style="display:none;">
                                    <div class="col-md-6">
                                        {!! Form::label('support_document_exports','Exports Supporting Document Number',
                                        ['class'=>'col-md-5 required-star']) !!}
                                        <div class="col-md-7 {{$errors->has('support_document_exports') ? 'has-error': ''}}">
                                            {!! Form::text('support_document_exports', isset($appData->support_document_exports)?$appData->support_document_exports:null,['class' => 'form-control
                                            input-sm required']) !!}
                                            {!! $errors->first('support_document_exports','<span
                                                class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('issue_date_exports','Exports Issue Date', ['class'=>'col-md-5
                                        required-star']) !!}
                                        <div class="col-md-7 {{$errors->has('issue_date_exports') ? 'has-error': ''}}">
                                            <div class="datepickerexports  input-group date">
                                                {!! Form::text('issue_date_exports',  isset($appData->issue_date_exports)?$appData->issue_date_exports:null,['class' => 'form-control input-sm
                                                required']) !!}
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                            </div>
                                            {!! $errors->first('issue_date_exports','<span
                                                class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-8" id="please_specify_data" style="display:none;">
                                    {!! Form::label('please_specify_f','Please Specify', ['class'=>'col-md-5
                                    required-star']) !!}
                                    <div class="col-md-7 {{$errors->has('please_specify_f') ? 'has-error': ''}}">
                                        {!! Form::text('please_specify_f', isset($appData->please_specify_f)?$appData->please_specify_f:null,['class' => 'form-control input-sm
                                        required']) !!}
                                        {!! $errors->first('please_specify_f','<span
                                            class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!--/panel-body-->
            </div>
            <!--/panel-->

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>G. AREAS OF MANUFACTURING</strong></div>
                <div class="panel-body">
                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-12" id="toptext">
                                <p style="margin-left: 25px;">This section will be available only when "F1.
                                    Manufacturing" is selected.</p>
                            </div>
                            <div id="manufacturing_area" style="display:none;">
                                <div class="col-md-12" id="manufacturing_area_checkboxes">
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-8" id="please_specify_G"
                                         style="{{!empty($appData->economic_area) && in_array('22@Others',$appData->economic_area)?'':'display:none'}}">
                                        {!! Form::label('please_specify_g','Please Specify', ['class'=>'col-md-5
                                        required-star']) !!}
                                        <div class="col-md-7 {{$errors->has('please_specify_g') ? 'has-error': ''}}">
                                            {!! Form::text('please_specify_g', isset($appData->please_specify_g)?$appData->please_specify_g:null,['class' => 'form-control input-sm
                                            required']) !!}
                                            {!! $errors->first('please_specify_g','<span
                                                class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!--/panel-body-->
            </div>
            <!--/panel-->

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>H. AREA OF SERVICE </strong></div>
                <div class="panel-body">
                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-12" id="toptext_H">
                                <p style="margin-left: 25px;">This section will be available only when "F2. Services" is
                                    selected.</p>
                            </div>
                            <div id="services_div" style="display:none;">
                                <div class="col-md-12" id="services_div_checkboxes">
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-8" id="please_specify_H"
                                         style="{{!empty($appData->area_service) && in_array('23@Others',$appData->area_service)?'':'display:none'}}">
                                        {!! Form::label('please_specify_h','Please Specify', ['class'=>'col-md-5
                                        required-star']) !!}
                                        <div class="col-md-7 {{$errors->has('please_specify_h') ? 'has-error': ''}}">
                                            {!! Form::text('please_specify_h', isset($appData->please_specify_h)?$appData->please_specify_h:null,['class' => 'form-control input-sm
                                            required']) !!}
                                            {!! $errors->first('please_specify_h','<span
                                                class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!--/panel-body-->
            </div>
            <!--/panel-->


            <div class="panel panel-primary">
                @include('VATReg::business_classification_code_edit')
            </div>

            <div class="panel panel-primary">
                @include('VATReg::bank_account_edit')
            </div>
            <!--/panel-->

            <div class="panel panel-primary">
                @include('VATReg::owner_of_entity_edit')
            </div>
            <!--/panel-->

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>L. BUSINESS OPERATIONS</strong></div>
                <div class="panel-body">

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('taxable_turnover','L1. Taxable Turnover in past 12 Months Period',
                                ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('taxable_turnover') ? 'has-error': ''}}">
                                    {!! Form::text('taxable_turnover', isset($appData->taxable_turnover) ? $appData->taxable_turnover :'',['class' => 'form-control onlyNumber input-sm required'])
                                    !!}
                                    {!! $errors->first('taxable_turnover','<span class="help-block">:message</span>')
                                    !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('projected_turnover','L2. Projected Turnover in next 12 Months Period',
                                ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('projected_turnover') ? 'has-error': ''}}">
                                    {!! Form::text('projected_turnover', isset($appData->projected_turnover) ? $appData->projected_turnover :'',['class' => 'form-control input-sm
                                    required onlyNumber']) !!}
                                    {!! $errors->first('projected_turnover','<span class="help-block">:message</span>')
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('employee_number','L3. Number of Employees', ['class'=>'col-md-5
                                required-star']) !!}
                                <div class="col-md-7 {{$errors->has('employee_number') ? 'has-error': ''}}">
                                    {!! Form::text('employee_number', isset($appData->employee_number) ? $appData->employee_number :'',['class' => 'form-control input-sm required
                                    onlyNumber']) !!}
                                    {!! $errors->first('employee_number','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('zero_rated_supply','L4. Are you making any Zero Rated Supply?',
                                ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('zero_rated_supply') ? 'has-error': ''}}">
                                    <label class="radio-inline">{!! Form::radio('zero_rated_supply','1', (isset($appData->zero_rated_supply) && ($appData->zero_rated_supply) == 1) ? true :false,
                                        ['class'=>'helpTextCheckbox zero_rated_supply required', 'id' => 'zero_rated_supply_yes']) !!}
                                        Yes</label>
                                    <label class="radio-inline">{!! Form::radio('zero_rated_supply', '2', (isset($appData->zero_rated_supply) && ($appData->zero_rated_supply) == 2) ? true :false,
                                        ['class'=>'helpTextCheckbox zero_rated_supply', 'id' => 'zero_rated_supply_no']) !!}
                                        No</label>
                                    {!! $errors->first('zero_rated_supply','<span class="help-block">:message</span>')
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">

                            <div class="col-md-6">
                                {!! Form::label('vat_extended_supply','L5. Are you making any VAT Exempted Supply?',
                                ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('vat_extended_supply') ? 'has-error': ''}}">
                                    <label class="radio-inline">{!! Form::radio('vat_extended_supply','1', (isset($appData->vat_extended_supply) && ($appData->vat_extended_supply) == 1) ? true :false,
                                        ['class'=>'helpTextCheckbox vat_extended_supply required', 'id' => 'vat_extended_supply_yes']) !!}
                                        Yes</label>
                                    <label class="radio-inline">{!! Form::radio('vat_extended_supply', '2', (isset($appData->vat_extended_supply) && ($appData->vat_extended_supply) == 2) ? true :false,
                                        ['class'=>'helpTextCheckbox vat_extended_supply', 'id' => 'vat_extended_supply_no']) !!}
                                        No</label>
                                    {!! $errors->first('vat_extended_supply','<span class="help-block">:message</span>')
                                    !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="row">
                            <div id="capital_machinery">
                                @include('VATReg::major_capital_machinery_edit')
                            </div>
                            <div id="input_output_data">
                                @include('VATReg::input_output_data_edit')
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!--/panel-->
            <div class="panel panel-primary">
                @include('VATReg::authorized_persons_online_edit')
            </div>

        </fieldset>


        <h3 class="text-center stepHeader">Attachments</h3>
        <fieldset>
            <div class="row">
                <div class="col-md-12">
                    <div id="docListDiv">
                        {{--                        @include('VATReg::documents')--}}
                    </div>

                </div>
            </div>
        </fieldset>

        <h3 class="text-center stepHeader">Declaration</h3>
        <fieldset>
            <div class="panel panel-info">
                <div class="panel-heading" style="padding-bottom: 4px;">
                    <strong>N. DECLARATION</strong>
                </div>
                <div class="panel-body">

                    <table class="table table-bordered table-striped">
                        <thead class="alert alert-info">
                        <tr>
                            <th colspan="2" style="font-size: 15px">Authorized person of the organization</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {!! Form::label('auth_name','Full name:', ['class'=>'required-star']) !!}
                                {!! Form::text('auth_name',\App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required']) !!}
                                {!! $errors->first('auth_name','<span class="help-block">:message</span>') !!}
                            </td>
                            <td>
                                {!! Form::label('auth_designation','Designation:', ['class'=>'required-star']) !!}
                                {!! Form::select('auth_designation',[], null, ['class' =>'form-control required input-sm']) !!}
                                {!! $errors->first('auth_designation','<span class="help-block">:message</span>')
                                !!}
                            </td>

                        </tr>
                        <tr>
                            <td colspan="3"><strong>Date : </strong><?php echo date('F d,Y')?></td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms',
                                'class'=>'required')) !!}
                                All the details and information provided in this form are true and complete. I am aware
                                that any untrue/incomplete statement may result in delay in BIN issuance and I may be
                                subjected to full penal action under the Value Added Tax and Supplementary Duty Act,
                                2012 or any other applicable Act Prevailing at present.
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>


        <h3 class="text-center stepHeader">Payment & Submit</h3>
        <fieldset>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <strong>Service fee payment</strong>
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
                    </div>
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
                            </div>
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
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
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
                                    Vat/ tax and service charge is an approximate amount, it may vary based on the
                                    Sonali Bank system.
                                </div>
                                <div class="alert alert-info" role="alert">
                                    Please check your mailbox for an email from ""VAT Online Services"" within 5 minutes
                                    for further instructions
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        @if ($viewMode != 'on')
            <div class="row">
                <div class="col-md-6 col-xs-12" style="">
                    @if($appInfo->status_id !== 5)
                        <div class="pull-left">
                            <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                    value="draft" name="actionBtn">Save as Draft
                            </button>
                        </div>
                    @endif

                    <div class="pull-left" style="padding-left: 1em;margin-bottom: 20px;">
                        @if($appInfo->status_id == 5)
                            <button type="submit" id="submitForm" style="cursor: pointer;"
                                    class="btn btn-success btn-md" value="submit" name="actionBtn">Re Submit
                            </button>
                        @else
                            <button type="submit" id="submitForm" style="cursor: pointer;"
                                    class="btn btn-success btn-md" value="submit" name="actionBtn">Payment
                                &amp; Submit
                            </button>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 button_last">
                    <div class="clearfix"></div>
                </div>
            </div> {{--row--}}
    </div>

    @endif

<!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background-color:#61A656;">
                    <h4 class="modal-title" style="text-align:center;">Goods/Service Code</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="current_row">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-offset-4 col-md-4 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::select('hs_code_type',['good'=>'Good', 'service'=>'Service'],'',['class' => 'form-control input-md hs_code_type_vat','id'=>'hs_code_type_vat']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover" id="hslist" style="width: 100%;">
                                    <thead>
                                    <tr style="background: #f5f5f7">
                                        <th>Goods/Service Code</th>
                                        <th>Goserve Code</th>
                                        <th>Goods/Service Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="myModalClose">Close</button>
                </div>
            </div>

        </div>
    </div>
    {!! Form::close() !!}
</div>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $(document).on('blur', '.mobile', function () {
            var mobile_telephone = $(this).val()
            if (mobile_telephone.length > 11) {
                $(this).addClass('error');
                return false;
            } else {
                $(this).removeClass('error');
                return true;
            }

        });

        $(document).on('keydown', '.mobile', function () {
            var mobile_telephone = $(this).val();
            var reg = /^01/;
            if (mobile_telephone.length == 2) {
                if (reg.test(mobile_telephone)) {
                    $(this).removeClass('error');
                    return true;
                } else {
                    $(this).addClass('error')
                    $(this).val('')
                    return false;
                }
            }

        });

        $(document).on('focusout', '.nid', function (e) {
            var nid = $(this).val().length
            if (nid == 10 || nid == 13 || nid == 17) {
                $(this).removeClass('error');
                return true;
            } else {
                $(this).addClass('error');
                return false;
            }
        })

        $(document).on('focusout', '.bin', function (e) {
            var e_bin = $(this).val().length
            // alert(e_bin)
            if (e_bin == 9 || e_bin == 11) {
                $(this).removeClass('error');
                return true;
            } else {
                $(this).addClass('error');
                return false;
            }
        })

        var form = $("#VATRegForm").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if (newIndex == 1) {

                }

                if (newIndex == 2) {
                }

                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex != -1) {
                    form.find('#save_as_draft').css('display', 'block');
                    form.find('.actions').css('top', '-42px');
                } else {
                    form.find('#save_as_draft').css('display', 'none');
                    form.find('.actions').css('top', '-15px');
                }

                if (currentIndex == 3) {
                    form.find('#submitForm').css('display', 'block');

                    $('#submitForm').on('click', function (e) {
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                    });
                } else {
                    form.find('#submitForm').css('display', 'none');
                }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                }
            }
        });

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            form.validate().settings.ignore = ":disabled,:hidden";
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=VATRegForm@3'); ?>');
            } else {
                return false;
            }
        });
        $("#VATRegForm").validate({
            rules: {
                field: {
                    required: true,
                    email: true,
                    url: true
                }
            }
        });


        // Datepicker Plugin initialize
        var today = new Date();
        var yyyy = today.getFullYear();
        $('.lastday').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: new Date(new Date().setDate(new Date().getDate() - 1)),

        });
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100)
        });
        $('.datepickerlicence').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: 'now'
        });
        $('.datepickerbida').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: 'now'
        });
        $('.datepickerlicence2').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: 'now'
        });
        $('.datepickerimports').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: 'now'
        });
        $('.datepickerexports').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: 'now'
        });

        $('.datepickerDob').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 150),
            maxDate: 'now'
        });

        $('.datepickerFuture').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            maxDate: '01/01/' + (yyyy + 6)
        });
        //     $(".percentage").change(function(){
        //      var type = $('.percentage:checked').val();
        //         if(type == 3){
        //             $('#local_share').show();
        //         }else{
        //             $('#local_share').hide();
        //         }
        //     });
        // $('.percentage').trigger('change');


    });

    function samaAsFactoryFunction() {
        var checkBox = document.getElementById("same_as_factory");
        var text = $.trim($("#factory_address").val());
        var district = $("#district").val();
        var districtData = district.split("@")[1];
        var policeStation = $("#police_station").val();
        var policeStationData = policeStation.split("@")[1];
        var post = $("#postCode").val();
        var postCode = post.split("@")[1];
        // var postCodeData = policeStationData +"-"+postCode+","+"Bangladesh";
        var checkboxesChecked = [];
        if (checkBox.checked == true) {
            checkboxesChecked.push(text, districtData, "PS:" + policeStationData + "-" + postCode + "," + "Bangladesh");
            $('#headquarter_address').attr('readonly', true);
        } else {
            $('#headquarter_address').attr('readonly', false);
        }
        document.getElementById("headquarter_address").value = checkboxesChecked;
    }

    function BusinessOtherFunction() {
        var checkBox = document.getElementById("business_others");
        var text = document.getElementById("business_please_specify");
        var classname = Array('please_specify');
        if (checkBox.checked == true) {
            text.style.display = "block";
            disablebycommonfunction(classname, false, 1);
        } else {
            text.style.display = "none";
            disablebycommonfunction(classname, true, 1);
        }
    }

    function otherGFunction(dom) {
        var text = document.getElementById("please_specify_G");
        var classname = Array('please_specify_g');
        if (dom.checked == true) {
            text.style.display = "block";
            disablebycommonfunction(classname, false, 1);
        } else {
            text.style.display = "none";
            disablebycommonfunction(classname, true, 1);
        }
    }


    function otherHFunction(dom) {
        var text = document.getElementById("please_specify_H");
        var classname = Array('pls_spec_h');
        if (dom.checked == true) {
            text.style.display = "block";
            disablebycommonfunction(classname, false, 1);
        } else {
            text.style.display = "none";
            disablebycommonfunction(classname, true, 1);
        }
    }

    function otherFunction(dom) {
        var text = document.getElementById("please_specify_data");
        var classname = Array('please_specify_f');
        if (dom.checked == true) {
            text.style.display = "block";
            disablebycommonfunction(classname, false, 1);
        } else {
            text.style.display = "none";
            disablebycommonfunction(classname, true, 1);
        }
    }

    function exportsFunction(dom) {
        var text = document.getElementById("exports_div");
        var classname = Array('economic_exports');
        if (dom.checked == true) {
            text.style.display = "block";
            disablebycommonfunction(classname, false, 1);
            document.getElementById("issue_date_exports").disabled = false;
            document.getElementById("support_document_exports").disabled = false;
        } else {
            text.style.display = "none";
            disablebycommonfunction(classname, true, 1);
            document.getElementById("issue_date_exports").disabled = true;
            document.getElementById("support_document_exports").disabled = true;
        }
    }

    function importsFunction(dom) {
        var text = document.getElementById("imports_div");
        var classname = Array('economic_imports');
        if (dom.checked == true) {
            document.getElementById("issue_date_imports").disabled = false;
            document.getElementById("support_document_imports").disabled = false;
            text.style.display = "block";
            disablebycommonfunction(classname, false, 1);
        } else {
            document.getElementById("issue_date_imports").disabled = true;
            document.getElementById("support_document_imports").disabled = true;
            text.style.display = "none";
            disablebycommonfunction(classname, true, 1);
        }
    }

    function manufacturingFunction(dom) {

        var text = document.getElementById("manufacturing_area");
        var classname = Array('economic_area');
        if (dom.checked == true) {
            $("#manufacturing_area_checkboxes").keydown();
            text.style.display = "block";
            document.getElementById("toptext").style.display = "none";
            document.getElementById("capital_machinery").style.display = "block";
            document.getElementById("input_output_data").style.display = "block";
            document.getElementById("MajorCapital").click()
            document.getElementById("InputOutput").click()
            disablebycommonfunction(classname, false, 1);
        } else {
            text.style.display = "none";
            document.getElementById("toptext").style.display = "block";
            if ($("#economic_activate_02").prop('checked') === true) {
                document.getElementById("capital_machinery").style.display = "block";
            } else {
                document.getElementById("capital_machinery").style.display = "none";
            }
            document.getElementById("input_output_data").style.display = "none";
            disablebycommonfunction(classname, true, 1)
        }
    }

    function ServicesFunction(dom) {
        var text = document.getElementById("services_div");
        var classname = Array('area_service');

        if (dom.checked == true) {
            $("#services_div_checkboxes").keydown();
            text.style.display = "block";
            document.getElementById("toptext_H").style.display = "none";
            document.getElementById("capital_machinery").style.display = "block";
            disablebycommonfunction(classname, false, 1);
        } else {
            text.style.display = "none";
            document.getElementById("toptext_H").style.display = "block";
            if ($("#economic_activate_01").prop('checked') === true) {
                document.getElementById("capital_machinery").style.display = "block";
                document.getElementById("input_output_data").style.display = "block";
            } else {
                document.getElementById("capital_machinery").style.display = "none";
                document.getElementById("input_output_data").style.display = "none";
            }

            disablebycommonfunction(classname, true, 1);
        }
    }

    function checkRegCategory(regCategory) {
        regCategory = regCategory.split('@')[0];
        if (regCategory == '2') {
            $('#reRegHiddenDiv').show();
            $("#old_bin").prop("disabled", false);
            $("#company_name").prop("disabled", false);
        } else {
            $('#reRegHiddenDiv').hide();
            $("#old_bin").prop("disabled", true);
            $("#company_name").prop("disabled", true);

        }
    }

    function checkSharePercentage(Percentage) {
        // console.log(Percentage.attr('checked'));
        if (Percentage.checked == true) {
            var ids = Array('local_share');
            Percentage = Percentage.value.split('@')[0];
            if (Percentage == 3) {
                $('#local_share_div').show();
                disablebycommonfunction(ids, false);
                disablebycommonfunction(Array("bida_reg_issue_date", "bida_reg_number", "headquarter_address_outside"), false);
            } else if (Percentage == 1) {
                $('#local_share_div').hide();
                disablebycommonfunction(Array("bida_reg_issue_date", "bida_reg_number", "headquarter_address_outside"), true);
                disablebycommonfunction(ids, true);
            } else if (Percentage == 2) {
                $('#local_share_div').hide();
                disablebycommonfunction(Array("bida_reg_issue_date", "bida_reg_number", "headquarter_address_outside"), false);
                disablebycommonfunction(ids, true);
            }
        }
    }

    function disablebycommonfunction(ids, type, isclass = null) {

        $.each(ids, function (index, value) {
            if (isclass == 1) {
                $("." + value + "").prop("disabled", type);
            } else {
                $("#" + value + "").prop("disabled", type);
            }

        });
    }

    reg_bin_show({{(isset($appData->registeredBin)) ? explode('@',$appData->registeredBin)[0] : ''}});


    function reg_bin_show(id) {
        if (id == 2) {
            $('#reg_bin_num').show()
            $('#bin_number').removeAttr("disabled")
        } else {
            $('#reg_bin_num').hide()
            $('#bin_number').attr("disabled", "disabled")
        }
    }

    function BusinessFunction(element) {
        var elementvalue = element.value;
        elementvalue = elementvalue.split("@")[0];
        if (elementvalue == "03" || elementvalue == "04") {
            if (element.checked == true) {
                $('#government').addClass("limited");
                $('#ngo').addClass("limited");
                $('#business_others').addClass("limited");
                $('#government').removeClass("business");
                $('#ngo').removeClass("business");
                $('#business_others').removeClass("business");
            } else {
                $('#government').removeClass("limited");
                $('#ngo').removeClass("limited");
                $('#business_others').removeClass("limited");
                $('#government').addClass("business");
                $('#ngo').addClass("business");
                $('#business_others').addClass("business");
            }
        }
    }


    $(document).ready(function () {

        $(document).on('change', '.business', function () {
            if (this.checked) {
                $('.business').not(this).prop('checked', false);
            }
        });


        $(document).on('change', '.limited', function () {
            if (this.checked) {
                $('.limited').not(this).prop('checked', false);
            }
        });

        $('.bank_name').select2();


        // $(document).on('change', '.singleBusiness', function() {
        //     if (this.checked) {
        //         $('.business').not(this).prop('checked', false);
        //     }
        // });

        $('.onlyNumber').on('keydown', function (e) {
            //period decimal
            if ((e.which >= 48 && e.which <= 57)
                //numpad decimal
                || (e.which >= 96 && e.which <= 105)
                // Allow: backspace, delete, tab, escape, enter and .
                || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                // Allow: Ctrl+A
                || (e.keyCode == 65 && e.ctrlKey === true)
                // Allow: Ctrl+C
                || (e.keyCode == 67 && e.ctrlKey === true)
                // Allow: Ctrl+V
                || (e.keyCode == 86 && e.ctrlKey === true)
                // Allow: Ctrl+X
                || (e.keyCode == 88 && e.ctrlKey === true)
                // Allow: home, end, left, right
                || (e.keyCode >= 35 && e.keyCode <= 39)) {

                var $this = $(this);
                setTimeout(function () {
                    $this.val($this.val().replace(/[^0-9.]/g, ''));
                }, 4);

                var thisVal = $(this).val();
                if (thisVal.indexOf(".") != -1 && e.key == '.') {
                    return false;
                }
                $(this).removeClass('error');
                return true;
            } else {
                $(this).addClass('error');
                return false;
            }
        }).on('paste', function (e) {
            var $this = $(this);
            setTimeout(function () {
                $this.val($this.val().replace(/[^0-9]/g, ''));
            }, 5);
        });
    });

    $(document).ready(function () {
        //Custom Validation

        $('#old_bin').on('focusout', function (e) {
            var old_bin = $('#old_bin').val().length
            if (old_bin != 11) {
                $(this).addClass('error');
                return false;
            } else {
                $(this).removeClass('error');
                return true;
            }
        })

        $('#bin_number').on('focusout', function (e) {
            var old_bin = $('#bin_number').val().length
            if (old_bin != 9) {
                $(this).addClass('error');
                return false;
            } else {
                $(this).removeClass('error');
                return true;
            }
        })

        $('#land_telephone').on('blur', function () {
            var land_telephone = $('#land_telephone').val()
            if (land_telephone.length > 15) {
                $(this).addClass('error');
                return false;
            } else {
                $(this).removeClass('error');
                return true;
            }

        })


        $('#land_telephone').on('keyup', function () {
            var land_telephone = $('#land_telephone').val();
            if (land_telephone.indexOf('0') !== 0) {
                $(this).addClass('error')
                $('#land_telephone').val('')
                return false;
            } else {
                $(this).removeClass('error');
                return true;
            }
        });

        $('#fax').on('keyup', function () {
            var fax = $('#fax').val();
            if (fax.indexOf('0') !== 0) {
                $(this).addClass('error')
                $('#fax').val('')
                return false;
            } else {
                $(this).removeClass('error');
                return true;
            }
        });

        $('#fax').on('blur', function () {
            var fax = $('#fax').val()
            if (fax.length > 15) {
                $(this).addClass('error');
                return false;
            } else {
                $(this).removeClass('error');
                return true;
            }

        });


        $(".hsSelect").select2({});
        $(".hs_code_major").select2({});
        $(".hs_code_inputs").select2({});
        $(".hs_code_output").select2({});
        token = "{{$token}}";
        var tokenUrl = '/vat-registration/get-refresh-token';

        $(function () {
            $('#district').keydown()
            $('.bank_name').keydown();
            $('.branch_category').keydown();
            $('.identity_category').keydown();
            $('.identity_category_authorized').keydown();
            $('.purpose').keydown();
            $('.authorized_designation').keydown();
            $('.physical_condition').keydown();
            $('.owner_designation').keydown();
            $('.owner_nationality').keydown();
            $('.authorized_nationality').keydown();
            $('#auth_designation').keydown();
        })

        $(document).on('click', '.serviceData', function (el) {
            $('#loader').css('display', 'block');
            var alldata = "";
            var seperator = "";

            $('.manufacture:checkbox:checked').each(function () {
                if (alldata != "") {
                    seperator = ","
                }
                alldata = alldata + seperator + $(this).attr('data-value');
            });

            $('.service:checkbox:checked').each(function () {
                if (alldata != "") {
                    seperator = ","
                }
                alldata = alldata + seperator + $(this).attr('data-value');
            });
            // console.log(alldata);
            var e = $(this);
            var api_url = "{{$vat_service_url}}/business-classification-code/" + alldata;
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var calling_type = $(this).attr('data-type'); // for callback
            // alert(calling_id)
            var element_id = "ITEM_ID"; //dynamic id for callback
            var element_name = "GOSERV_CODE"; //dynamic name for callback
            var data = "NAME";
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data, calling_type]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, CheckBoXCallbackResponse, arrays);

        });

        //DISTRICT Onload --- SECTION D
        $('#district').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$vat_service_url}}/district";
            var selected_value = '{{isset($appData->district) ? $appData->district: ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "CITY_CODE"; //dynamic id for callback
            var element_name = "CITY_NAME"; //dynamic name for callback
            var data = '';
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);

        });

        $("#district").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#police_station").html('<option value="">Please Wait...</option>');
            var district = $('#district').val();
            var districtId = district.split("@")[0];
            if (districtId) {
                var e = $(this);
                var api_url = "{{$vat_service_url}}/police-station" + "/" + districtId;
                var selected_value = '{{isset($appData->police_station) ? $appData->police_station: ''}}';// for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "police_station"; // for callback
                var element_id = "PSTATION_CODE"; //dynamic id for callback
                var element_name = "PSTATION_DESCR"; //dynamic name for callback
                var district_id = "DISTR_CODE";
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, district_id];

                var apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                ];
                apiCallGet(e, options, apiHeaders, districtPoliceStationCallbackResponseDependentSelect, arrays);

            } else {
                $("#police_station").html('<option value="">Select District First</option>');
                $(self).next().hide();
            }

        })
        $('#auth_designation').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var e = $(this);
            var api_url = "{{$vat_service_url}}/designation";
            var selected_value = '{{isset($appData->auth_designation) ? $appData->auth_designation: ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, authDesCallbackResponse, arrays);

        });

        $("#district").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#postCode").html('<option value="">Please Wait...</option>');
            var district = $('#district').val();
            var districtId = district.split("@")[0];
            if (districtId) {
                var e = $(this);
                var api_url = "{{$vat_service_url}}/postal-code" + "/" + districtId;
                var selected_value = '{{isset($appData->post_code) ? $appData->post_code: ''}}'; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "postCode"; // for callback
                var element_id = "POST_CODE"; //dynamic id for callback
                var element_name = "POST_CODE"; //dynamic name for callback
                var district_id = "CITY_CODE";
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, district_id];

                var apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                ];
                apiCallGet(e, options, apiHeaders, districtPostalCodeCallbackResponseDependentSelect, arrays);

            } else {
                $("#police_station").html('<option value="">Select District First</option>');
                $(self).next().hide();
            }

        })

        //Bank Name Onload --- SECTION J
        $('.bank_name').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$vat_service_url}}/bank-account-details";
            var calling_id = $(this).attr('id');
            var selected_value = $(this).attr(calling_id);
            var element_id = "BANCD"; //dynamic id for callback
            var element_name = "BANKN"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);

        });

        $('.branch_category').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$vat_service_url}}/branch-category";
            var calling_id = $(this).attr('id'); // for callback
            var selected_value = $(this).attr(calling_id); // for callback
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            //console.log(calling_id)
            var data = '';
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);

        });

        $('.identity_category_authorized').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$vat_service_url}}/identity-category";
            var calling_id = $(this).attr('id'); // for callback
            var selected_value = $(this).attr(calling_id); // for callback
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var data = '';
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);

        });

        $('.purpose').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$vat_service_url}}/purpose";
            var calling_id = $(this).attr('id'); // for callback
            var selected_value = $(this).attr(calling_id); // for callback
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var data = '';
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);

        });

        $('.identity_category').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$vat_service_url}}/identity-category";
            var calling_id = $(this).attr('id'); // for callback
            var selected_value = $(this).attr(calling_id); // for callback
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];
            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);
        });

        $(document).on('keydown', '.owner_nationality', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var e = $(this);
            var api_url = "{{$vat_service_url}}/nationality";
            var calling_id = $(this).attr('id'); // for callback
            var selected_value = $(this).attr(calling_id);
            var element_id = "NATION_KEY"; //dynamic id for callback
            var element_name = "NATION_TEXT"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback
            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];
            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);
        });

        $(document).on('keydown', '.authorized_nationality', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            var e = $(this);
            var api_url = "{{$vat_service_url}}/nationality";
            var calling_id = $(this).attr('id'); // for callback
            var selected_value = $(this).attr(calling_id); // for callback
            var element_id = "NATION_KEY"; //dynamic id for callback
            var element_name = "NATION_TEXT"; //dynamic name for callback
            var data = '';
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);

        });

        $('.authorized_designation').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var e = $(this);
            var api_url = "{{$vat_service_url}}/designation";
            var calling_id = $(this).attr('id'); // for callback
            var selected_value = $(this).attr(calling_id) // for callback
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);

        });

        $('.physical_condition').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var e = $(this);
            var api_url = "{{$vat_service_url}}/physical-condition";
            var calling_id = $(this).attr('id'); // for callback
            var selected_value = $(this).attr(calling_id); // for callback
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);

        });

        $('.owner_designation').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var e = $(this);
            var api_url = "{{$vat_service_url}}/designation";
            var calling_id = $(this).attr('id'); // for callback
            var selected_value = $(this).attr(calling_id); // for callback
            //  alert(selected_value)
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, selectCallbackResponse, arrays);

        });

        $(document).on('change', ".bank_name", function () {
            //test
            var a = this.id;
            var d_id = a.split("_").pop();
            $('#branch_name' + d_id).select2('destory');
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $('#branch_name' + d_id).html('<option value="">Please Wait...</option>');
            var bank = $("#bank_name_" + d_id).val();
            var bank_id = bank.split("@")[0];
            if (bank_id) {
                var e = $(this);
                var api_url = "{{$vat_service_url}}/bank-branch-info" + '/' + bank_id;
                var calling_id = $(this).attr('id');
                var selected_value = $("#branch_name_" + d_id).attr("branch_name_" + d_id);
                // console.log(selected_value)
                var dependent_section_id = "branch_name_" + d_id; // for callback
                var element_id = "BANKL"; //dynamic id for callback
                var element_name = "BRANNM"; //dynamic name for callback
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                var apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                ];
                apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

            } else {
                $(".branch_name").html('<option value="">Select Description of branch name</option>');
                $(self).next().hide();
            }

        });

        function validateURL(textval) {
            var urlregex = new RegExp("^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
            return urlregex.test(textval);
        }

        // assign whatever is in the inputbox to a variable
        $("#web_address").on('blur', function () {
            var textval = $("#web_address").val();
            var urlregex = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
            // console.log(urlregex.test(textval));
            if (urlregex.test(textval) === false) {
                $(this).addClass('error')
                return false;
            } else {
                $(this).removeClass('error')
                return true;
            }

        })

        //this is if they paste the url from somewhere
        // $("#web_address").live('input paste', function () {
        //     var url = $("#web_address").val();
        //     if (validateURL(url)) {
        //         $("#web_address").removeClass("error");
        //         $("#web_address").addClass("required");
        //         $("#web_address").removeClass("valid");
        //         return true;
        //     } else {
        //         $("#web_address").removeClass("error");
        //         $("#web_address").removeClass("required");
        //         $("#web_address").addClass("valid");
        //         return true;
        //     }
        // });
    })
    ;

    function CheckBoXCallbackResponse(response, [calling_id, selected_value, element_id, element_name, data, calling_type]) {
        if (response.responseCode === 200) {
            loadHsCodeDatatable(response.data, calling_id, calling_type)
        }

    }


    function loadHsCodeDatatable(data = '', calling_id = '', calling_type = '') {
        var hsdata = []
        $.each(data, function (key, value) {
                var hs = {
                    "ITEM_ID": value.ITEM_ID,
                    "GOSERV_CODE": value.GOSERV_CODE,
                    "VALID_FRM": value.VALID_FRM,
                    "VALID_TO": value.VALID_TO,
                    "NAME": value.NAME
                };
                hsdata.push(hs);
            }
        )

        $('#hslist').DataTable({
            processing: true,
            destroy: true,
            data: hsdata,
            columns:
                [
                    {"data": "ITEM_ID"},
                    {"data": "GOSERV_CODE"},
                    {"data": "NAME"},
                    {"data": "VALID_FRM"},
                    {"data": "VALID_TO"},
                    {
                        "data": "GOSERV_CODE", render: function (data, type, full) {
                            var id = full.ITEM_ID + '@' + full.GOSERV_CODE + '@' + full.NAME;
                            return '<button class="btn btn-success" data-dismiss="modal" onclick="showCode(\'' + id + '\',' + calling_id + ',\'' + calling_type + '\')">Select</button>';
                        }
                    },
                ],
            drawCallback: function (settings) {
                $(".paginate_button").css({"display": "inline"});
            },
            "aaSorting":
                []
        })
        $('#loader').css('display', 'none');
        $('#myModal').modal('show');
    }

    function showCode(id, calling_id, calling_type) {
        if (calling_type == 'businessClass') {
            $('#hs_code_' + calling_id).val(id.split('@')[1]);
            $('#hs_description_' + calling_id).val(id.split('@')[2]);
            $('#hs_code_hidden_' + calling_id).val(id);
        } else if (calling_type == 'majorCapital') {
            $('#hs_code_major_' + calling_id).val(id.split('@')[1]);
            $('#hs_code_major_hidden_' + calling_id).val(id);
        } else if (calling_type == 'dataOutput') {
            $('#hs_code_output_' + calling_id).val(id.split('@')[1]);
            $('#hs_code_output_hidden_' + calling_id).val(id);
        } else if (calling_type == 'dataInput') {
            $('#hs_code_input_' + calling_id).val(id.split('@')[1]);
            $('#hs_code_input_hidden_' + calling_id).val(id);
        }

    }

    //DISTRICT Onload --- SECTION D
    function selectCallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).parent().find('.loading_data').hide();
        $("#" + calling_id).trigger('change');
        $(".search-box").select2();

    }

    function authDesCallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                // alert(id.split('@')[0])
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();

    }


    function selectClassCallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];

                if (selected_value == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }
        $("." + calling_id).html(option);
        $("." + calling_id).next().hide();
    }

    function districtPoliceStationCallbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id, district_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name] + '@' + row[district_id];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        $(".search-box").select2();
        $("#" + calling_id).parent().find('.loading_data').hide();
    }

    function districtPostalCodeCallbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id, district_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name] + '@' + row[district_id];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        $(".search-box").select2();
        $("#" + calling_id).parent().find('.loading_data').hide();
    }

    function callbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        // console.log(response.data);
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        $("#" + calling_id).next().hide();
        $('.branch_name').select2();
    }

    $('body').on('click', '.reCallApi', function () {
        var id = $(this).attr('data-id');
        $("#" + id).trigger('keydown');
        $(this).remove();
    });

    $(document).ready(function () {
        var _token = $('input[name="_token"]').val();
        var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}';
        $.ajax({
            type: "POST",
            url: '/vat-registration/getDocList',
            dataType: "json",
            data: {
                _token: _token,
                appId: appId
            },
            success: function (result) {
                //  console.log(result.responseCode);
                $("#docListDiv").html(result.data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#docListDiv").html('');
            },
        });
        $('.owner_nationality').select2();
        $('.authorized_nationality').select2();
    });

    function uploadDocument(targets, id, vField, isRequired) {
        var inputFile = $("#" + id).val();
        if (inputFile == '') {
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if ($('#label_' + id).length)
                $('#label_' + id).remove();
            return false;
        }

        try {

            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{URL::to('/vat-registration/upload-document')}}";
            // alert(action);
            $("#" + targets).html('Uploading....');
            var file_data = $("#" + id).prop('files')[0];
            var form_data = new FormData();
            form_data.append('selected_file', id);
            form_data.append('isRequired', isRequired);
            form_data.append('validateFieldName', vField);
            form_data.append('_token', "{{ csrf_token() }}");
            form_data.append(id, file_data);
            $.ajax({
                target: '#' + targets,
                url: action,
                dataType: 'text', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = id;
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" class="filedelete" docid="' + id + '" ><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    $('#' + id).removeClass('required');
                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field == '') {
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = '';
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
        } else {
            return false;
        }
    });


</script>

@include('VATReg::checkbox-radiobutton-scripts-edit')