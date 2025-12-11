<?php
$accessMode = ACL::getAccsessRight('RajukLUCGeneral');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>
    .wizard > .content,
    .wizard,
    .tabcontrol {
        overflow: visible;
    }

    .wizard > .steps > ul > li {
        width: 20% !important;
    }

    .wizard > .steps .number {
        font-size: 1.2em;
    }

    .full-page {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        background-color: rgb(255, 255, 255);
        z-index: 9000;
    }

    .intl-tel-input .country-list {
        z-index: 5;
    }

    .form-group {
        margin-bottom: 5px;
    }

    label {
        float: left !important;
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

    .note_description {
        width: 95%;
        background-color: #FEF2CE;
        /* padding: 20px 10px 20px 10px; */
        margin: 10px 28px 10px 20px;
        border-left: 5px solid #B1BEC5;
        font-family: Arial, sans-serif;
        font-size: 16px;
        font-weight: normal;
    }

    table thead {
        background-color: #ddd;
    }

    .none-pointer {
        pointer-events: none;
    }

    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

    }
</style>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box" id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}
                {!! Session::has('errorResponse') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("errorResponse") .'</div>
                ' : '' !!}
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h5><strong>{{$serviceConfiguration->servicename}}
                                of {{$serviceConfiguration->agencyname}}</strong></h5>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'licence-applications/external-service/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NOCproposed',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                        <input type="hidden" name="selected_file" id="selected_file"/>
                        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                        <input type="hidden" name="isRequired" id="isRequired"/>
                        <input type="hidden" name="app_id"
                               value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"
                               id="app_id"/>
                        <input type="hidden" name="process_type_id"
                               value="{{\App\Libraries\Encryption::encodeId($process_type_id)}}" id="process_type_id"/>



                        <h3 class="text-center stepHeader"> Application Data</h3>
                        <fieldset>
                            <div class="hidden">
                                @if(!empty($serviceConfiguration->default_data))
                                    @foreach($serviceConfiguration->default_data as $defaultKey => $default)
                                    @if(is_string($default))
                                        <input type="hidden" value="{{$appData->$defaultKey}}" name="{{$defaultKey}}" id="{{$defaultKey}}"/>
                                    @else
                                        @foreach($default as $key => $value)
                                            <input type="hidden" value="{{$value}}"
                                            name="{{$defaultKey."[{$key}]"}}" id="{{$defaultKey}}"/>
                                        @endforeach
                                    @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-md-12">
                                @if(!empty($serviceConfiguration->introduction))
                                    <a target="_blank" class="text-center btn btn-outline btn-primary" title=""
                                       href="{{'/licence-applications/external-service/preview-introduction/'.\App\Libraries\Encryption::encodeId($process_type_id)}}" style="padding: 5px 10px !important;max-width: 200px;margin-bottom: 10px;">
                                        <span style="font-weight: bold">Introduction &nbsp;&nbsp;<i
                                                    class="fas fa-external-link-square-alt"
                                                    aria-hidden="true" title="Access Log"></i></span>
                                    </a>
                                @endif
                                @if(!empty($serviceConfiguration->guideline))
                                    <a target="_blank"  class="text-center btn btn-outline btn-primary" title=""
                                       href="{{'/licence-applications/external-service/preview-guideline/'.\App\Libraries\Encryption::encodeId($process_type_id)}}" style="padding: 5px 10px !important;max-width: 200px; margin-bottom: 10px;">
                                        <span style="font-weight: bold"> Guideline &nbsp;&nbsp;<i
                                                    class="fas fa-external-link-square-alt"
                                                    aria-hidden="true" title="Access Log"></i></span>
                                    </a>
                                @endif
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <?php
                                        $sl = 1;
                                        $dependent = '';
                                        $listAndSublist = [];
                                        ?>
                                        @foreach($serviceConfiguration->data as $key=>$value)
                                            @if($sl % 2 == 1)
                                                <div class="form-group">
                                                    <div class="row">
                                                        @endif
                                                        <div class="col-md-6 col-xs-12">
                                                                <?php
                                                                    $input_specification_key = isset($serviceConfiguration->input_specification->$key)?$serviceConfiguration->input_specification->$key:'';
                                                                    if(!empty($input_specification_key->label)){
                                                                        $label = ucfirst($serviceConfiguration->input_specification->$key->label);
                                                                    }else{
                                                                        $label = ucfirst(str_replace('_', ' ', $key));
                                                                    }
                                                                ?>
                                                            {!! Form::label("$key","$label:",['class'=>'text-left col-md-5']) !!}

                                                                <?php
                                                                $list = substr($value, 0, 6);
                                                                $listMultiple = substr($value, 0, 14);
                                                                $sublist = substr($value, 0, 9);
                                                                $sublistMultiple = substr($value, 0, 17);
                                                                ?>
                                                            @if($list != '<list>' &&  $sublist != '<sublist>' && $listMultiple != '<listMultiple>' && $sublistMultiple != '<sublistMultiple>')

                                                                <div class="col-md-7">
                                                                    @if(!empty($input_specification_key->type))
                                                                        <?php
                                                                            $keyType = $input_specification_key->type;
                                                                            $keyTypeClasses = !empty($input_specification_key->additionalClasses)?$input_specification_key->additionalClasses:'';
                                                                        ?>
                                                                            {!! Form::$keyType("$key",(!empty($appData->$key)?$appData->$key:null),["class" => "form-control input-md ".$keyTypeClasses,'id'=>"$key",!empty($value)?'readonly':'', 'rows' => 2]) !!}
                                                                    @else
                                                                        {!! Form::text("$key",(!empty($appData->$key)?$appData->$key:null),['class' => 'form-control input-md ','id'=>"$key",!empty($value)?'readonly':'']) !!}
                                                                    @endif
                                                                </div>
                                                            @else
                                                                    <?php
                                                                    $selectedListValue = substr($value, 0, 7);
                                                                    $selectedListMultiple = substr($value, 0, 15);
                                                                    $selectedSublistValue = substr($value, 0, 10);
                                                                    $selectedSubMultiple = substr($value, 0, 18);
                                                                    $selectedInfoKey = '';
                                                                    if ($selectedSublistValue == '<sublist>@') {
                                                                        $value = str_replace($selectedSublistValue, '', $value);
                                                                        $apiInfoArray = explode('#', $value);
                                                                        $selectedInfoKey = $apiInfoArray[0];
                                                                        $value = $apiInfoArray[1];
                                                                    } elseif ($selectedListValue == '<list>@') {
                                                                        $value = str_replace($selectedListValue, '', $value);
                                                                        $apiInfoArray = explode('#', $value);
                                                                        $selectedInfoKey = $apiInfoArray[0];
                                                                        $value = $apiInfoArray[1];
                                                                    } elseif ($selectedListMultiple == '<listMultiple>@') {
                                                                        $value = str_replace($selectedListMultiple, '', $value);
                                                                        $apiInfoArray = explode('#', $value);
                                                                        $selectedInfoKey = $apiInfoArray[0];
                                                                        $value = $apiInfoArray[1];
                                                                    } elseif ($selectedSubMultiple == '<sublistMultiple>@') {
                                                                        $value = str_replace($selectedSubMultiple, '', $value);
                                                                        $apiInfoArray = explode('#', $value);
                                                                        $selectedInfoKey = $apiInfoArray[0];
                                                                        $value = $apiInfoArray[1];
                                                                    }
                                                                    $apiDetails = explode(':', $value);
                                                                    $apiUrl = $apiDetails[1];
                                                                    ?>
                                                                    <?php
                                                                    $selectedValue = !empty($selectedInfoKey) ? $info->$selectedInfoKey : '##';
                                                                    ?>

                                                                @if($sublist =='<sublist>')
                                                                        <?php
                                                                        $listAndSublist[] = $key;
                                                                        ?>
                                                                    <div class="col-md-7">
                                                                        {!! Form::select($key,[], '',['class' => 'form-control input-md','id'=>"$key", 'api-url'=>$apiUrl,'selectedvalue'=>(!empty($appData->$key)?$appData->$key:null),'onkeydown'=>"setDependent(this,'".$dependent."')"]) !!}
                                                                    </div>
                                                                    <?php
                                                                        $dependent = '';
                                                                    ?>
                                                                @endif

                                                                @if($list =='<list>')
                                                                        <?php
                                                                        $dependent = $key;
                                                                        $listAndSublist[] = $key;
                                                                        ?>
                                                                    <div class="col-md-7">
                                                                        {!! Form::select($key,[], '',['class' => 'form-control input-md','id'=>"$key",'onkeydown'=>"callListApi(this,'".$apiUrl."','".(!empty($appData->$key)?$appData->$key:null)."')",
                                                                            'onchange'=>"callSublistApi(this,'".$key."')"]) !!}
                                                                    </div>
                                                                @endif

                                                                @if($sublistMultiple =='<sublistMultiple>')
                                                                        <?php
                                                                        $listAndSublist[] = $key;
                                                                        $allVAule = '';
                                                                        $allVAule = '';
                                                                        if (!empty($appData->$key)) {
                                                                            foreach ($appData->$key as $selectedValue) {
                                                                                $allVAule .= explode('@', $selectedValue)[0] . ',';
                                                                            }
                                                                        }
                                                                        $allVAule = rtrim($allVAule, ',');
                                                                        ?>
                                                                    <div class="col-md-7">
                                                                        {!! Form::select($key."[]",[], '',['class' => 'form-control input-md select2','id'=>"$key",'multiple', 'api-url'=>$apiUrl,'selectedvalue'=>$allVAule,'onkeydown'=>"setDependent(this,'".$dependent."')"]) !!}
                                                                    </div>
                                                                @endif

                                                                @if($listMultiple =='<listMultiple>')
                                                                        <?php
                                                                        $dependent = $key;
                                                                        $listAndSublist[] = $key;
                                                                        ?>
                                                                    <div class="col-md-7">
                                                                        {!! Form::select($key."[]",[], '',['class' => 'form-control input-md select2','id'=>"$key",'multiple','onkeydown'=>"callListApi(this,'".$apiUrl."','".$appData->$key."')",
                                                                            'onchange'=>"callSublistApi(this,'".$key."')"]) !!}
                                                                    </div>
                                                                @endif

                                                            @endif
                                                        </div>
                                                        @if($sl % 2 == 0)
                                                    </div>
                                                </div>
                                            @endif
                                                <?php
                                                $sl++;
                                                ?>

                                        @endforeach

                                    </div>

                                </div>


                                @if(!empty($serviceConfiguration->declaration))
                                    <div class="panel panel-info">
                                        <div class="panel-heading" style="padding-bottom: 4px;">
                                            <strong>DECLARATION</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms',
                                                            'class'=>'required')) !!}
                                                            {{$serviceConfiguration->declaration}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </fieldset>
                        @if(!empty($serviceConfiguration->files))
                            <h3 class="text-center stepHeader">Attachments</h3>
                            <fieldset>
                                <div id="docListDiv">
                                    @include('ExternalLicense::documents')
                                </div>
                            </fieldset>
                        @elseif(!empty($serviceConfiguration->server_configuration->document_api_url))
                            <h3 class="text-center stepHeader">Attachments</h3>
                            <fieldset>
                                <div id="docListDiv">
                                </div>
                            </fieldset>
                        @endif
                        <?php
                        $countPayment = count($amountDetails);
                        ?>
                        <h3>{{$countPayment>0?'Payment':'OSSP Payment'}}</h3>
                        <fieldset>
                            <div id="paymentDetails">
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
                            </div>


                        </fieldset><!--./Payment/OSSP Payment-->
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="pull-left">
                                    <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn">Save as Draft
                                    </button>
                                </div>

                                <div class="pull-left" style="padding-left: 1em;">
                                    <button type="submit" id="submitForm" style="cursor: pointer;"
                                            class="btn btn-success btn-md" value="Submit" name="actionBtn">Payment &amp;
                                        Submit
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 button_last">
                                <div class="clearfix"></div>
                            </div>
                        </div> {{--row--}}
                        {!! Form::close() !!}
                    </div>


                </div>


            </div>
        </div>
    </div>
</section>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script src="{{asset('vendor/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
<script>
    var apiHeaders = [
        {
            key: "Content-Type",
            value: 'application/json'
        }
    ];





    $(document).ready(function () {
        $(".select2").select2()
        $(function () {
            token = "{{$token}}";
            tokenUrl = '/licence-applications/external-service/get-refresh-token';
            @foreach($listAndSublist as $key=>$value)
            $("#{{$value}}").keydown();
            @endforeach

        });
        $("#NewApplication").validate();

        var popupWindow = null;

        $(document).ready(function () {

            var form = $("#NOCproposed").show();
            form.find('#submitForm').css('display', 'none');
            form.steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex) {
                    // return true;
                    // bank challan fee
                    // Always allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }
                    // return true;
                    // Needed in some cases if the user went back (clean up)
                    if (currentIndex < newIndex) {
                        // To remove error styles
                        form.find(".body:eq(" + newIndex + ") label.error").remove();
                        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                    }
                    form.validate().settings.ignore = ":disabled,:hidden";
                    @if(!empty($serviceConfiguration->vendor_payment) && !empty($serviceConfiguration->payment_parameter))
                    if (!form.valid()) {
                        return false;
                    }
                    if (currentIndex == 0 && hasAsyncOperation(currentIndex)) {
                        handleAsyncOperation(currentIndex);
                        //return false;// This return should check further investigation, It off for Trade License Renewal
                    }
                    return true;
                    @else
                        return form.valid();
                    @endif
                },
                onStepChanged: function (event, currentIndex, priorIndex) {
                    @if(!empty($serviceConfiguration->files) || !empty($serviceConfiguration->server_configuration->document_api_url))
                    let moOffStep = 2;
                    @else
                    let moOffStep = 1;
                    @endif

                    if (currentIndex == moOffStep) {
                        form.find('#submitForm').css('display', 'block');
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

            $(document).ready(function () {
                tinymce.init({
                    selector: '.texteditor',
                    // height: 200,
                    theme: 'modern',
                    toolbar1: 'styleselect | alignleft aligncenter alignright alignjustify  | print preview media | forecolor backcolor emoticons |link image',
                    image_advtab: true,
                    menubar: false,
                    content_css: [
                        '//www.tinymce.com/css/codepen.min.css'
                    ]
                });
            });

            var popupWindow = null;
            $('.finish').on('click', function (e) {
                form.validate().settings.ignore = ":disabled,:hidden";
                if (form.valid()) {
                    $('body').css({"display": "none"});
                    popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=NOCproposed@3'); ?>');
                } else {
                    return false;
                }
            });

        });


        $('#nid_number').on('blur', function (e) {
            var nid = $('#nid_number').val().length
            if (nid == 10 || nid == 13 || nid == 17) {
                $('#nid_number').removeClass('error')
            } else {
                $('#nid_number').addClass('error')
                $('#nid_number').val('')
            }
        })

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
        $('#nid_number').on('blur', function (e) {
            var nid = $('#nid_number').val().length
            if (nid == 10 || nid == 13 || nid == 17) {
                $('#nid_number').removeClass('error')
            } else {
                $('#nid_number').addClass('error')
                $('#nid_number').val('')
            }
        })

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

    /*document upload start*/
    function uploadDocument(targets, id, vField, isRequired) {
        var check = document.getElementById(id).getAttribute("flag")
        if (check == "img") {
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop();
            if ((fileSize >= 149999) || (extension !== "jpg" && extension !== "jpeg" && extension !== "png")) {
                alert('File size cannot be over 150 KB and file extension should be only jpg, jpeg and png');
                document.getElementById(id).value = "";
                return false;
            }
        } else {
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop();
            if ((fileSize >= 1000000) || (extension !== "pdf")) {
                alert('File size cannot be over 1 MB and file extension should be only pdf');
                document.getElementById(id).value = "";
                return false;
            }

        }
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
            var action = "{{URL::to('/dcci-cos/upload-document')}}";
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
                    if (response.includes("Error") == false) {
                        $('#' + id).removeClass('required');
                        $('#span_' + id).hide();
                    }

                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field == '') {
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            console.log(err)
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

    function callSublistApi(el, replace) {
        let elementID = el.value.split("@")[0];

        let dependent_field = $("#" + el.id).attr('ondepend');
        if(dependent_field == undefined){
            return false;
        }

        let subListApiUrl = $("#" + dependent_field).attr('api-url').replace('$.' + el.id, elementID);
         let selectedvalue =  $("#" + dependent_field).attr('selectedvalue');
         if(selectedvalue != undefined){
            selectedvalue = $("#" + dependent_field).attr('selectedvalue').replace('$.' + el.name, elementID);
        }


        callListApi(el, subListApiUrl, selectedvalue, dependent_field, 'ThanaID', 'ThanaName');
    }

    function callListApi(el, api_end_point, selectedvalue, sublistid = '', param1 = 'DistrictID', param2 = 'DistrictName') {
        let key = el.which;
        if (typeof key !== "undefined") {
            return false
        }
        let calling_id = el.id;// for callback
        if (sublistid != '' && sublistid != undefined) {
            if ($("#" + sublistid).attr('multiple')) {
                selectedvalue = selectedvalue.split(',');
            }
        }

        $("#" + calling_id).after('<span class="loading_data">Loading...</span>');
        let e = $("#" + calling_id);
        let api_url = "{{$getListBaseUrl}}/" + api_end_point;
        // let api_url = "https://stage-insightdb.ba-systems.com/api/api-bank/data-hub/" + api_end_point;
        let selected_value = selectedvalue;

        let dependant_select_id = sublistid;//dynamic id for callback
        let element_id = param1;//dynamic id for callback
        let element_name = param2;//dynamic name for callback
        let element_calling_id = "";//dynamic name for callback
        let data = '';//Third option to make id

        let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
        let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

        apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
    }


    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]) {
        var option = '<option value="">Select One</option>';
        let isMultiple = $.isArray(selected_value);
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let selectedValueEdit = '';
                if(selected_value !=undefined){
                    selectedValueEdit = selected_value.split('@')[0];
                }

                var id = row[Object.keys(row)[0]] + '@' + row[Object.keys(row)[1]];
                let value = row[Object.keys(row)[1]]
                if (isMultiple == true) {
                    if ($.inArray(id.split('@')[0], selected_value) != -1) {
                        option += '<option selected="true" value="' + id + '">' + value + '</option>'
                    } else {
                        option += '<option value="' + id + '">' + value + '</option>'
                    }
                } else {
                    if (selectedValueEdit== id.split('@')[0]) {
                        option += '<option selected="true" value="' + id + '">' + value + '</option>'
                    } else {
                        option += '<option value="' + id + '">' + value + '</option>'
                    }
                }


            })
        } else {
            console.log(response.responseCode)
        }
        $("#" + calling_id).next().hide()
        if (dependant_select_id != '') {
            $("#" + dependant_select_id).next().remove()
            $("#" + dependant_select_id).html(option);
            $("#" + dependant_select_id).select2();
        } else {
            $("#" + calling_id).html(option);
            $("#" + calling_id).select2();
            if (selected_value != '') {
                $("#" + calling_id).trigger('change');
            }
        }

    }

    function setDependent(el, dependentValue) {
        $("#" + dependentValue).attr('ondepend', el.id);
    }

    function imagePreview(input) {
        if (input.files && input.files[0]) {
            var calling_id = input.id;
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#photo_viewer_" + calling_id).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }


    //Doc and image upload section
    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = ''
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
            $('#span_' + abc).show()
            $('#photo_viewer_' + abc).attr('src', '{{(url('assets/images/no-image.png'))}}')
        } else {
            return false;
        }
    });

    async function handleAsyncOperation(currentIndex) {
        if (currentIndex === 0) {
            try {
                $isValid = await getPayment();
                return $isValid;
            } catch (error) {
                console.error(error);
                return false;
            }
        }
        return true;
    }

    function hasAsyncOperation(currentIndex) {
        return currentIndex === 0;
    }

    function getPayment() {
        let paymentParam = "{{!empty($serviceConfiguration->payment_parameter)?$serviceConfiguration->payment_parameter:''}}";
        let paymentParamArray = paymentParam.split(',');
        var paramvalues = {};
        $.each(paymentParamArray, function (index, value) {
            paramvalues[value] = $("#" + value).val();
        });
        paramvalues['process_type_id'] = "{{$process_type_id}}";
        let url = '/licence-applications/external-service/get-payment';
        $.ajax({
            url: url,
            type: "POST",
            data: paramvalues,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function (response) {
                if (response.responseCode == 1) {
                    $("#paymentDetails").html(response.html);
                }else{
                    alert(response.message);
                    return false
                }
            },
            error: function () {
                alert("something went wrong");
                return false; //this will prevent to go to next step
            }
        });
    }
    @if(!empty($serviceConfiguration->dependent_document_filed))
    $(document).ready(function () {
        $(function () {
            let dependentFieldId = "{{$serviceConfiguration->dependent_document_filed}}";
            var  fieldValue= $("#"+dependentFieldId).val();
            if (fieldValue) {
                var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}';
                $.ajax({
                    type: "POST",
                    url: '/licence-applications/external-service/get-dynamic-doc',
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        process_type_id: "{{$process_type_id}}",
                        doc_dependent_field: fieldValue,
                        appId: appId
                    },
                    success: function (result) {
                        $("#docListDiv").html(result.data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#docListDiv").html('');
                    },
                });
            }
        });
    });
    @endif


</script>