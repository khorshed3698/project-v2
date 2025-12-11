<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="bn" xml:lang="bn"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>jQeury.steps Demos</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">


    <link rel="stylesheet" href="{{asset('asset/')}}/css/normalize.css">
    <link rel="stylesheet" href="{{asset('asset/')}}/css/main.css">

    <link rel="stylesheet" href="{{asset('asset/')}}/css/jquery.steps.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>


    <script src="{{asset('asset/')}}/lib/modernizr-2.6.2.min.js"></script>
    <script src="{{asset('asset/')}}/lib/jquery-1.9.1.min.js"></script>
    <script src="{{asset('asset/')}}/lib/jquery.cookie-1.3.1.js"></script>
    <script src="{{asset('asset/')}}/build/jquery.steps.js"></script>
    <script src="{{ asset("3.3.7/js/bootstrap.min.js") }}"></script>


    <script src="{{ asset("assets/scripts/bootstrap-datepicker.js") }}"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"
          type="text/css"/>


    <style>
        .wizard > .content {
            background: #eee;
            display: block;
            margin: 0.5em;
            min-height: 750px;
            overflow-y: scroll;
            position: relative;
            width: auto;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }

        .wizard > .content > .body {
            float: left;
            position: absolute;
            width: 100%;
            height: auto;
            padding: 2.5%;
        }


    </style>


</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to
    improve your experience.</p>
<![endif]-->


<div class="content">


    <script>
        $(function () {
            $("#wizard").steps({
                headerTag: "h2",
                bodyTag: "section",
                transitionEffect: "slideLeft"
            });
        });
    </script>


    <div id="wizard">
        <h2>First Step</h2>


        <section class="content" id="LoanLocator">

            {!! Form::open(array('url' => '','method' => 'post','id' => '','role'=>'form','enctype'=>'multipart/form-data')) !!}

            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="panel panel-red" id="inputForm">
                            <div class="panel-heading" style="text-align: center;">
                                <h3> বাংলাদেশ হতে সফটওয়্যার আইটিইস (Information Technology Enabled Services) ও </h3>
                                <h4><u> হার্ডওয়্যার রপ্তানির বিপরীতে ভর্তুকির জন্য আবেদন </u></h4>
                            </div>

                            <div class="panel-body" style="margin:6px;">


                                <div class="form-group">
                                    <div class="row">
                                        <h5> ( ক ) </h5>
                                        <div class="col-md-6  {{$errors->has('applicant_company_name') ? 'has-error': ''}}">
                                            {!! Form::label('applicant_company_name',' আবেদনকারী প্রতিষ্ঠানের নাম:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('applicant_company_name', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_company_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6  {{$errors->has('company_address') ? 'has-error': ''}}">
                                            {!! Form::label('company_address','  প্রতিষ্ঠানের  ঠিকানা   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('company_address', '', ['maxlength'=>'150',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('company_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md-6  {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                            {!! Form::label('applicant_name','আবেদনকারী   নাম  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('applicant_name', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6  {{$errors->has('erc_no') ? 'has-error': ''}}">
                                            {!! Form::label('erc_no','রপ্তানি নিবন্ধন সনদপত্র (ইআরসি) নম্বর   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('erc_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('erc_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <h5>( খ )</h5>
                                        <div class="col-md-4  {{$errors->has('export_agree_no') ? 'has-error': ''}}">
                                            {!! Form::label('export_agree_no','রপ্তানি চুক্তিপত্র নম্বর :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_agree_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_agree_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    <!-- <div class="col-md-4  {{$errors->has('export_date') ? 'has-error': ''}}">
                                            {!! Form::label('company_name','তারিখ   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::date('export_date', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                    {!! $errors->first('export_date','<span class="help-block">:message</span>') !!}
                                            </div>
                                     </div>  -->
                                        <div class="col-md-4">
                                            {!! Form::label('company_name','তারিখ   :',['class'=>'col-md-5 required-star']) !!}

                                            <div class="datepicker input-group date">


                                                {!! Form::text('export_date', '', ['maxlength'=>'80',
                                       'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_date','<span class="help-block">:message</span>') !!}

                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-4  {{$errors->has('export_price') ? 'has-error': ''}}">
                                            {!! Form::label('export_price','মূল্য    :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <h5>(গ) রপ্তানিকৃত সেবা/পণ্য উৎপাদনে ব্যবহৃত সেবা/পণ্যের স্থানীয় সংগ্রহসূত্র,
                                            পরিমান ও মূল্য : </h5>
                                        <br>
                                        <br>

                                        <div class="col-md-4  {{$errors->has('provider_name_add') ? 'has-error': ''}}">
                                            {!! Form::label('provider_name_add','সরবরাহকারীর নাম ও ঠিকানা :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('provider_name_add', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('provider_name_add','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4  {{$errors->has('provider_quantity') ? 'has-error': ''}}">
                                            {!! Form::label('provider_quantity','পরিমাণ    :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('provider_quantity', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('provider_quantity','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4  {{$errors->has('provider_price') ? 'has-error': ''}}">
                                            {!! Form::label('provider_price','মূল্য    :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('provider_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('provider_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        ( রপ্তানিকৃত সেবা/পণ্য বর্ণনা মূলূল্য
                                        সংগ্রহসূত্র, বিষয়ে সেবা/পণ্য সংশ্লিষ্ট এসোসিয়েশন এর প্রত্যয়ন পত্র দাখিল করতে
                                        হবে)
                                        <br>
                                        <br>
                                        <h5>(ঘ) রপ্তানিকৃত সেবা/পণ্য উৎপাদনে ব্যবহৃত আমাদানিক্রিত সেবা/উপকরানাদি</h5>
                                        <br>
                                        <br>

                                        <div class="col-md-3  {{$errors->has('provider_name') ? 'has-error': ''}}">
                                            {!! Form::label('provider_name','সরবরাহকারীর নাম:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('provider_name', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('provider_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3  {{$errors->has('provider_address') ? 'has-error': ''}}">
                                            {!! Form::label('provider_address',' ঠিকানা :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('provider_address', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('provider_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3  {{$errors->has('service_product_name') ? 'has-error': ''}}">
                                            {!! Form::label('service_product_name','সেবা পণ্যের নাম:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('service_product_name', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('service_product_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                        <div class="col-md-3  {{$errors->has('service_product_quantity') ? 'has-error': ''}}">
                                            {!! Form::label('service_product_quantity','পরিমাণ    :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('service_product_quantity', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('service_product_quantity','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md-12  {{$errors->has('credit_instrument_documentari_file') ? 'has-error': ''}}">
                                            {!! Form::label('credit_instrument_documentari_file','ঋণপত্র / ব্যাক টু ব্যাক ঋণপত্র / ডুকুমেন্টারি কালেকশন /ঋণপত্রের পাঠযোগ্য সত্যায়িত কপি  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::file('credit_instrument_documentari_file', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('credit_instrument_documentari_file','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="row">

                                        <div class="col-md-4  {{$errors->has('tt_remitence_no') ? 'has-error': ''}}">
                                            {!! Form::label('tt_remitence_no','টিটি রেমিটেন্স নম্বর :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('tt_remitence_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('tt_remitence_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4  {{$errors->has('tt_remitence_date') ? 'has-error': ''}}">
                                            <div class=" input-group date"
                                                 data-date-format="yyyy-mm-dd">
                                                {!! Form::label('tt_remitence_date','তারিখ    :',['class'=>'col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::date('tt_remitence_date', '', ['maxlength'=>'80',
                                                    'class' => 'form-control input-sm  required']) !!}
                                                    {!! $errors->first('tt_remitence_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4  {{$errors->has('tt_remitence_price') ? 'has-error': ''}}">
                                            {!! Form::label('tt_remitence_price','মূল্য    :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('tt_remitence_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('tt_remitence_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>

                                    <br>
                                    <br>
                                    <p>( কলামের ঋণপত্রের পাঠযোগ্য সত্যায়িত কপি দাখিল করতে হবে । সেবা আমদানির ক্ষেত্রে
                                        যথাযথ পদ্ধতি অনুসরণ করা হয়েছে মর্মে অনুমোদিত ডিলার শাখাকে নিশ্চিত হতে হবে
                                        ।উৎপাদন প্রক্রিয়ার ব্যবহৃত উপকরণাদির জন্য শুল্ক বন্ড সুবিধা ভোগ করা হয়নি / ডিউটি
                                        ড্র-ব্যাক সুবিধা গ্রহণ করা হয়নি ও ভবিষ্যতে আবেদন ও করা হবে না মর্মে
                                        রপ্তানিকারকের ঘোষণা পত্র দাখিল করতে হবে ।) </p>

                                </div>

                                <div class="form-group">
                                    <h5>( ঙ ) রপ্তানি চালানের বিবরণ </h5>
                                    <br>
                                    <div class="row">

                                        <div class="col-md-4  {{$errors->has('export_product_description') ? 'has-error': ''}}">
                                            {!! Form::label('export_product_description','পণের বর্ণনা :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('export_product_description', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_product_description','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4  {{$errors->has('export_product_quantity') ? 'has-error': ''}}">
                                            {!! Form::label('export_product_quantity','পরিমাণ    :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_product_quantity', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_product_quantity','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4  {{$errors->has('name_of_export_country') ? 'has-error': ''}}">
                                            {!! Form::label('name_of_export_country','আমদানিকারকের দেশের নাম     :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('name_of_export_country', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('name_of_export_country','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>

                                </div>
                                <div class="form-group">

                                    <div class="row">

                                        <div class="col-md-4  {{$errors->has('export_invoice_price') ? 'has-error': ''}}">
                                            {!! Form::label('export_invoice_price','ইনভয়েস মূল্য (বৈদেশিক মুদ্রায় ) :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_invoice_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors-> first('export_invoice_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4  {{$errors->has('export_destination_port') ? 'has-error': ''}}">
                                            {!! Form::label('export_destination_port','গন্তব্য বন্দর:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('export_destination_port', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_destination_port','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4  {{$errors->has('shipping_export_date') ? 'has-error': ''}}">
                                            {!! Form::label('shipping_export_date','জাহাজীকরণ /রপ্তানির তারিখ:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::date('shipping_export_date', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('shipping_export_date','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <br>
                                        <h5>
                                            * দৃশ্যমান আকারে পণ্য রপ্তানির ক্ষেত্রে প্রযোজ্য </h5>
                                        <br><br>


                                    </div>

                                    <p>( কমার্শিয়াল ইনভয়েস, প্যাকিং লিস্ট এবং জাহাজীকরণ এর প্রমাণস্বরূপ পরিবহন কর্তৃপক্ষ
                                        কর্তৃক ইস্যুকৃত এবং প্রত্যয়নকৃত বিল অব লোডিং/ এয়ারওয়ে বিল , বিল অব এক্সপোর্ট (
                                        শুল্ক কর্তৃপক্ষ কর্তৃক ইস্যুকৃত ও পরীক্ষিত এবং হওয়ার স্বপক্ষে পরিবহন কর্তৃপক্ষ
                                        কর্তৃক প্রত্যয়নকৃত ) এর পূরনাংগ সেট ইত্যাদির সত্যায়িত পাঠযোগ্য কপি এবং
                                        রপ্তানিমূল্য
                                        প্রত্তাবাসন সনদপত্র দাখিল করতে হবে । তবে অদৃশ্যাকারে সেবা রপ্তানির ক্ষেত্রে
                                        জাহাজীকরণের দলিল ও বিল অব এক্সপোর্ট দাখিলের অব্যশকতা থাকবেনা। )</p>

                                </div>
                                <div class="form-group">
                                    <h5>( চ ) ভর্তুকির আবেদনকৃত অংক </h5>
                                    <br>
                                    <div class="row">

                                        <div class="col-md-6  {{$errors->has('expedited_export_price') ? 'has-error': ''}}">
                                            {!! Form::label('expedited_export_price','প্রত্যাবাসিত রপ্তানি মূল্য  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('expedited_export_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('expedited_export_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6  {{$errors->has('amount_ship_rent_applicable') ? 'has-error': ''}}">
                                            {!! Form::label('amount_ship_rent_applicable','প্রযোজ্য ক্ষেত্রে জাহাজ ভাড়ার পরিমাণ     :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('amount_ship_rent_applicable', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('amount_ship_rent_applicable','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>

                                </div>
                                <div class="form-group">

                                    <div class="row">

                                        <div class="col-md-12  {{$errors->has('foreig_exchange_commission,') ? 'has-error': ''}}">
                                            {!! Form::label('foreig_exchange_commission','বৈদেশিক মুদ্রায় পরিশোধের কমিশন, ইন্সুরেন্স ইত্যাদি ( যদি থাকে ):',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('foreig_exchange_commission', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('foreig_exchange_commission','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="form-group">

                                    <div class="row">

                                        <div class="col-md-12  {{$errors->has('net_fob_export_price') ? 'has-error': ''}}">
                                            {!! Form::label('net_fob_export_price','নীট এফওবি রপ্তানি মূল্য :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('net_fob_export_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('net_fob_export_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>

                                    <h5>( প্রযোজ্য ক্ষেত্রে জাহাজ ভাড়ার উল্লেখ সম্বলিত ফ্রেইট সার্টিফিকেটের সত্যায়িত কপি
                                        দাখিল করতে হবে )</h5>

                                </div>
                                <div class="form-group">

                                    <div class="row">

                                        <div class="col-md-6">

                                            <table aria-label="Detailed Report Data Table" class="table table-bordered table-hover">
                                                <thead>
                                                <tr class="d-none">
                                                    <th aria-hidden="true"  scope="col"></th>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">রপ্তানি সেবা/পণ্য উৎপাদনে ব্যবহৃত সেবা/ পণ্যের
                                                        মূল্য
                                                    </td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>দেশের পণ্য/সেবা</td>
                                                    <td>আমদানিকৃত পণ্য/সেবা</td>
                                                </tr>
                                                <tr>
                                                    <td>

                                                        <div class="col-md-12  {{$errors->has('product_service_incountry') ? 'has-error': ''}}">
                                                            {!! Form::text('product_service_incountry', '', ['maxlength'=>'80',
                                                            'class' => 'form-control input-sm  required']) !!}
                                                            {!! $errors->first('product_service_incountry','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>

                                                        <div class="col-md-7">
                                                            {!! Form::text('imported_product_service', '', ['maxlength'=>'80',
                                                            'class' => 'form-control input-sm  required']) !!}
                                                            {!! $errors->first('imported_product_service','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-3 {{$errors->has('local_value_addition_rate') ? 'has-error': ''}}">

                                            {!! Form::label('local_value_addition_rate','স্থানীয় মূল্য সংযোজন হার :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('local_value_addition_rate', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('local_value_addition_rate','<span class="help-block">:message</span>') !!}

                                            </div>

                                        </div>
                                        <div class="col-md-3 {{$errors->has('reserved_subsidies') ? 'has-error': ''}}">
                                            {!! Form::label('reserved_subsidies','প্রাপ্য ভর্তুকি:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('reserved_subsidies', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('reserved_subsidies','<span class="help-block">:message</span>') !!}

                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5> ( * ৭ নম্বর কলামের হার আলোচ্য সার্কুলারের ৪ নম্বর অন অনুচ্ছদের সাথে
                                                সামঞ্জস্যতার ক্ষেত্রে ভর্তুকি প্রাপ্য হবে ।) এ মর্মে অঙ্গীকার যাচ্ছে যে
                                                ,আমাদের নিজস্ব কারখানায় / প্রতিষ্ঠানের তৈরি / উৎপাদিত সফটওয়্যার / আইটিএস
                                                / হার্ডওয়্যার রপ্তানির বিপরীতে ভর্তুকির জন্য আবেদন করা হলো। এ আবেদন
                                                পত্রে প্রদত্ত সকল তথ্য / ঘোষণা সম্পূর্ণ সঠিক । যদি পরবর্তীতে কোন ভুল /
                                                অসত্য তথ্য / প্রতারণা / জালিয়াতি / উদ্ঘাটিত হয় তবে গৃহীত ভর্তুকির সমুদয়
                                                অর্থ বা এঁর অংশ বিশেষ আমার/আমাদের নিকট হতে এবং / অথবা আমার / আমাদের
                                                ব্যাংক হিসাব থেকে আদায় /ফেরত নেয়া যাবে । </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('applicant_date') ? 'has-error': ''}}">
                                            {!! Form::label('applicant_date','আবেদনের তারিখ :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::date('applicant_date', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_date','<span class="help-block">:message</span>') !!}

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            আবেদনকারী প্রতিষ্ঠানের স্বত্বাধিকারী / ক্ষমতা প্রাপ্ত কর্মকর্তার স্বাক্ষর ও
                                            পদবী

                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <h5>( ছ ) ভর্তুকি প্রদানকারী ব্যাংক শাখা কর্তৃক পূরণীয় : ( বৈদেশিক মুদ্রায় ) </h5>
                                    <br>
                                    <div class="row">

                                        <div class="col-md-6  {{$errors->has('provide_expected_export_price') ? 'has-error': ''}}">
                                            {!! Form::label('provide_expected_export_price','প্রত্যাবাসিত রপ্তানি মূল্য  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('provide_expected_export_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('provide_expected_export_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6  {{$errors->has('provide_amount_ship_rent_applicable') ? 'has-error': ''}}">
                                            {!! Form::label('provide_amount_ship_rent_applicable','প্রযোজ্য ক্ষেত্রে জাহাজ ভাড়ার পরিমাণ  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('provide_amount_ship_rent_applicable', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('provide_amount_ship_rent_applicable','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="form-group">

                                    <div class="row">

                                        <div class="col-md-6  {{$errors->has('provide_foreig_exchange_commission') ? 'has-error': ''}}">
                                            {!! Form::label('provide_foreig_exchange_commission','বৈদেশিক মুদ্রায় পরিশোধ্য কমিশন   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('provide_foreig_exchange_commission', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('provide_foreig_exchange_commission','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6  {{$errors->has('provide_net_fob_export_price') ? 'has-error': ''}}">
                                            {!! Form::label('provide_net_fob_export_price','নীট এফওবি রপ্তানি মূল্য :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('provide_net_fob_export_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('provide_net_fob_export_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </section>
        <h2>Second Step</h2>
        <section>

            <div class="form-group">

                <div class="row">
                    <h3> ( ঞ ) </h3>

                    <div class="col-md-6  {{$errors->has('name_foreign_buyer') ? 'has-error': ''}}">
                        {!! Form::label('name_foreign_buyer','বিদেশী ক্রেতার নাম   :',['class'=>'col-md-5 required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::text('name_foreign_buyer', '', ['maxlength'=>'80',
                            'class' => 'form-control input-sm  required']) !!}
                            {!! $errors->first('name_foreign_buyer','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="col-md-6  {{$errors->has('address_foreign_buyer') ? 'has-error': ''}}">
                        {!! Form::label('address_foreign_buyer','বিদেশী ক্রেতার ঠিকানা:',['class'=>'col-md-5 required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::text('address_foreign_buyer', '', ['maxlength'=>'80',
                            'class' => 'form-control input-sm  required']) !!}
                            {!! $errors->first('address_foreign_buyer','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                </div>

            </div>
            <div class="form-group">

                <div class="row">
                    <div class="col-md-6  {{$errors->has('bank_name_foreign_buyer') ? 'has-error': ''}}">
                        {!! Form::label('bank_name_foreign_buyer','বিদেশী ক্রেতার ব্যাংকের নাম   :',['class'=>'col-md-5 required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::text('bank_name_foreign_buyer', '', ['maxlength'=>'80',
                            'class' => 'form-control input-sm  required']) !!}
                            {!! $errors->first('bank_name_foreign_buyer','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="col-md-6  {{$errors->has('bank_address_foreign_buyer') ? 'has-error': ''}}">
                        {!! Form::label('bank_address_foreign_buyer','বিদেশী ক্রেতার ব্যাংকের  ঠিকানা :',['class'=>'col-md-5 required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::text('bank_address_foreign_buyer', '', ['maxlength'=>'80',
                            'class' => 'form-control input-sm  required']) !!}
                            {!! $errors->first('bank_address_foreign_buyer','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                </div>

            </div>

            <div class="form-group">

                <div class="row">
                    <div class="col-md-6  {{$errors->has('invoice_no_foreign_buyer') ? 'has-error': ''}}">
                        {!! Form::label('invoice_no_foreign_buyer',' ইনভয়েস নম্বর  :',['class'=>'col-md-5 required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::number('invoice_no_foreign_buyer', '', ['maxlength'=>'80',
                            'class' => 'form-control input-sm  required']) !!}
                            {!! $errors->first('invoice_no_foreign_buyer','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="col-md-6  {{$errors->has('date_foreign_buyer') ? 'has-error': ''}}">
                        {!! Form::label('date_foreign_buyer','তারিখ  :',['class'=>'col-md-5 required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::date('date_foreign_buyer', '', ['maxlength'=>'80',
                            'class' => 'form-control input-sm  required']) !!}
                            {!! $errors->first('date_foreign_buyer','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                </div>

            </div>

            <div class="form-group">

                <div class="row">
                    <div class="col-md-6  {{$errors->has('invoice_product_service_foreign_buyer') ? 'has-error': ''}}">
                        {!! Form::label('invoice_product_service_foreign_buyer','ইনভয়েস উল্লেখিত সেবা/পণ্যের পরিমাণ :',['class'=>'col-md-5 required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::number('invoice_product_service_foreign_buyer', '', ['maxlength'=>'80',
                            'class' => 'form-control input-sm  required']) !!}
                            {!! $errors->first('invoice_product_service_foreign_buyer','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="col-md-6  {{$errors->has('price_foreign_buyer') ? 'has-error': ''}}">
                        {!! Form::label('price_foreign_buyer','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                        <div class="col-md-7">
                            {!! Form::number('price_foreign_buyer', '', ['maxlength'=>'80',
                            'class' => 'form-control input-sm  required']) !!}
                            {!! $errors->first('price_foreign_buyer','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                </div>

            </div>


        </section>


        <h2>Third Step</h2>


        <section>
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="panel panel-red" id="inputForm">
                            <div class="panel-heading" style="text-align: center;">
                                <h3> বাংলাদেশ হতে সফটওয়্যার আইটিইস (Information Technology Enabled Services) ও </h3>
                                <h4><u> হার্ডওয়্যার রপ্তানির বিপরীতে ভর্তুকি প্রাপ্তির প্রত্যায়ন সনদপত্র </u></h4>
                            </div>

                            <div class="panel-body" style="margin:6px;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12  {{$errors->has('applicant_name_erc_no_add') ? 'has-error': ''}}">
                                            {!! Form::label('applicant_name_erc_no_add','১  :  আবেদনকারীর নাম , ইআরসি , নম্বর ও ঠিকানা :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('applicant_name_erc_no_add', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name_erc_no_add','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4  {{$errors->has('export_agree_signeture_no') ? 'has-error': ''}}">
                                            {!! Form::label('export_agree_signeture_no','২  :  রপ্তানি ঋণপত্র / চুক্তিপত্র নম্বর  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_agree_signeture_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_agree_signeture_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4  {{$errors->has('export_agree_date') ? 'has-error': ''}}">
                                            {!! Form::label('export_agree_date','তারিখ   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::date('export_agree_date', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_agree_date','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4  {{$errors->has('export_agree_price') ? 'has-error': ''}}">
                                            {!! Form::label('export_agree_price','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_agree_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_agree_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12  {{$errors->has('buyer_name_address') ? 'has-error': ''}}">
                                            {!! Form::label('buyer_name_address','৩  : বিদেশী ক্রেতার নাম ও ঠিকানা :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('buyer_name_address', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('buyer_name_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12  {{$errors->has('buyer_bank_name_address') ? 'has-error': ''}}">
                                            {!! Form::label('buyer_bank_name_address','৪  : বিদেশী ক্রেতার ব্যাংকের নাম ও ঠিকানা :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('buyer_bank_name_address', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('buyer_bank_name_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('invoice_no') ? 'has-error': ''}}">
                                            {!! Form::label('invoice_no','৫ ( ক )  : ইনভয়েস নম্বর  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('invoice_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('invoice_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('invoice_date') ? 'has-error': ''}}">
                                            {!! Form::label('invoice_date','তারিখ  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('invoice_date', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('invoice_date','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('invoice_product_service_quantity') ? 'has-error': ''}}">
                                            {!! Form::label('invoice_product_service_quantity','( খ )  : ইনভয়েস উল্লেখিত সেবা / পণ্যের  পরিমাণ  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('invoice_product_service_quantity', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('invoice_product_service_quantity','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('invoice_price') ? 'has-error': ''}}">
                                            {!! Form::label('invoice_price','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('invoice_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('invoice_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12  {{$errors->has('export_soft_hard_provider_name_address') ? 'has-error': ''}}">
                                            {!! Form::label('export_soft_hard_provider_name_address','৬  : রপ্তানি কৃত সফটওয়্যার / আইটিইএস হার্ডওয়্যার তৈরিতে ব্যবহৃত স্থানীয় সেবা / উপকনাদির সংগ্রহ সূত্র ( সরবরাহকারীর নাম ও ঠিকানা  )  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('export_soft_hard_provider_name_address', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_soft_hard_provider_name_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('export_soft_provider_quantity') ? 'has-error': ''}}">
                                            {!! Form::label('export_soft_provider_quantity','পরিমাণ  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_soft_provider_quantity', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_soft_provider_quantity','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('export_soft_provider_price') ? 'has-error': ''}}">
                                            {!! Form::label('export_soft_provider_price','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_soft_provider_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_soft_provider_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12  {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                            {!! Form::label('company_name','৭  :  রপ্তানি কৃত সফটওয়্যার / আইটিইএস হার্ডওয়্যার তৈরিতে ব্যবহৃত আমদানিকৃত    আনুসাংগিক   সেবা /উপকরণাদির সংগ্রহ সূত্র  ( সরবরাহকারীর নাম ও ঠিকানা  )  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('name_of_applicant', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                            {!! Form::label('company_name','পরিমাণ  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('name_of_applicant', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                            {!! Form::label('company_name','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('name_of_applicant', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4  {{$errors->has('export_service_product_description') ? 'has-error': ''}}">
                                            {!! Form::label('export_service_product_description',' ৮  : রপ্তানি সেবা /পণ্যের  বিবরণ   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('export_service_product_description', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_service_product_description','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4  {{$errors->has('export_service_product_quantity') ? 'has-error': ''}}">
                                            {!! Form::label('export_service_product_quantity','পরিমাণ   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_service_product_quantity', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_service_product_quantity','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4  {{$errors->has('export_service_product_price') ? 'has-error': ''}}">
                                            {!! Form::label('export_service_product_price','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_service_product_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_service_product_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('shipping_date') ? 'has-error': ''}}">
                                            {!! Form::label('shipping_date',' ৯ : জাহাজীকরণ এর তারিখ :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::date('shipping_date', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('shipping_date','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('posrt_of_destination') ? 'has-error': ''}}">
                                            {!! Form::label('posrt_of_destination','গন্তব্য বন্দর:   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('posrt_of_destination', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('posrt_of_destination','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12  {{$errors->has('exp_no') ? 'has-error': ''}}">
                                            {!! Form::label('exp_no','১০  :  ইএক্সপি নম্বর ( দৃশ্যমান  পণ্যে রপ্তানির ক্ষেত্রে )',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('exp_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('exp_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('exp_price') ? 'has-error': ''}}">
                                            {!! Form::label('exp_price','মূল্য  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('exp_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('exp_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('exp_date') ? 'has-error': ''}}">
                                            {!! Form::label('exp_date',' তারিখ:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('exp_date', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('exp_date','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('total_expected_price') ? 'has-error': ''}}">
                                            {!! Form::label('total_expected_price','১১  : মোট প্রত্যাবাসিত রপ্তানি মূল্য (বৈদেশিক মুদ্রায়):',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('total_expected_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('total_expected_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('net_fob_price') ? 'has-error': ''}}">
                                            {!! Form::label('net_fob_price','নীট এফওবি রপ্তানি মূল্য:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('net_fob_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('net_fob_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('export_value_certificate_no') ? 'has-error': ''}}">
                                            {!! Form::label('export_value_certificate_no','১২ : প্রত্যাবাসিত রপ্তানি মূল্যের সনদপত্রের নম্বর :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('export_value_certificate_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_value_certificate_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('export_value_certificate_date') ? 'has-error': ''}}">
                                            {!! Form::label('export_value_certificate_date','তারিখ :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::date('export_value_certificate_date', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_value_certificate_date','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>এতদ্বারা প্রত্যয়ন করা যাচ্ছে যে , আমাদের নিজস্ব কারখানায় / প্রতিষ্ঠানে
                                                তৈরিকৃত / উৎপাদিত সফটওয়্যার আইটিইএস / হার্ডওয়্যার উপরোক্ত ৬ ও ৭ নং
                                                ক্রমিকে বর্ণিত সূত্র হওত সেবা / উপকরণাদি সংগ্রহের মাধ্যমে রপ্তানির
                                                বিপরীতে ভর্তুকির জন্য উপরোক্ত অনুচ্ছেদ গুলোতে উল্লিখিত সঠিক বা নির্ভুল ।
                                                বিদেশী ক্রেতা আমদানিকারকের ক্রয়াদেশের যথার্থতা / বিশ্বাসযোগ্যতা
                                                সম্পর্কেও নিশ্চিত করা হলো ।</h5>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>রপ্তানিকারকের উপরোক্ত ঘোষণার যথার্থতা যাচাইয়ান্তে সঠিক পাওয়া গিয়েছে । ৮
                                                নং ক্রমিকে উল্লেখিত ঘোষিত রপ্তানি মূল্য যৌক্তিক ও বিদ্যমান আন্তর্জাতিক
                                                বাজার মূল্যর সংগতিপূর্ণ পাওয়া গিয়েছে এবং বিদেশী ক্রেতার যথার্থতা /
                                                বিশ্বাসযোগ্যতা সম্পর্কেও নিশ্চিত হওয়া গিয়েছে । প্রতাবাসিত রপ্তানি মূল্যর
                                                ( নীট এফওবি মূল্য ) ওপর রপ্তানি ভর্তুকি পরিশোদের সুপারিশ করা হলো । </h5>
                                        </div>


                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <h2>Forth Step</h2>
        <section>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="panel panel-red" id="inputForm">
                            <div class="panel-heading" style="text-align: center;">
                                <h3> বাংলাদেশ হতে সফটওয়্যার আইটিইস (Information Technology Enabled Services) ও </h3>
                                <h4><u> হার্ডওয়্যার রপ্তানির বিপরীতে ভর্তুকি প্রাপ্তির প্রত্যায়ন সনদপত্র </u></h4>
                            </div>

                            <div class="panel-body" style="margin:6px;">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                            {!! Form::label('applicant_name_erc_no_add','১ : আবেদনকারীর নাম:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('applicant_name_erc_no_add', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name_erc_no_add','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('applicant_erc_no') ? 'has-error': ''}}">
                                            {!! Form::label('applicant_name_erc_no_add','ইআরসি নং :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('applicant_name_erc_no_add', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name_erc_no_add','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('applicant_add') ? 'has-error': ''}}">
                                            {!! Form::label('applicant_name_erc_no_add','ঠিকানা :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('applicant_name_erc_no_add', '', ['cols'=>'7','rows' => '3',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name_erc_no_add','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('export_agree_signeture_no') ? 'has-error': ''}}">
                                            {!! Form::label('export_agree_signeture_no','২ :  রপ্তানি ঋণপত্র / চুক্তিপত্র নম্বর  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_agree_signeture_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_agree_signeture_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            {!! Form::label('company_name','তারিখ   :',['class'=>'col-md-5 required-star']) !!}

                                            <div class="datepicker input-group date">


                                                {!! Form::text('export_agree_date', '', ['maxlength'=>'80',
                                       'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_date','<span class="help-block">:message</span>') !!}

                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('export_agree_price') ? 'has-error': ''}}">
                                            {!! Form::label('export_agree_price','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_agree_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_agree_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('buyer_name_address') ? 'has-error': ''}}">
                                            {!! Form::label('buyer_name','৩ : বিদেশী ক্রেতার নাম:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('buyer_name_address', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('buyer_name_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('buyer_bank_name_address') ? 'has-error': ''}}">
                                            {!! Form::label('buyer_bank_name_address','৪ : বিদেশী ক্রেতার ব্যাংকের নাম:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('buyer_bank_name_address', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('buyer_bank_name_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('buyer_name_address') ? 'has-error': ''}}">
                                            {!! Form::label('buyer_address','বিদেশী ক্রেতার ঠিকানা:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('buyer_name_address', '', ['cols'=>'7','rows' => '3',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('buyer_name_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6  {{$errors->has('buyer_bank_name_address') ? 'has-error': ''}}">
                                            {!! Form::label('buyer_bank_name_address','বিদেশী ক্রেতার ব্যাংকের ঠিকানা :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('buyer_bank_name_address', '', ['cols'=>'7','rows' => '3',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('buyer_bank_name_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('invoice_no') ? 'has-error': ''}}">
                                            {!! Form::label('invoice_no','৫ (ক): ইনভয়েস নম্বর  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('invoice_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('invoice_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('invoice_date','তারিখ   :',['class'=>'col-md-5 required-star']) !!}

                                            <div class="datepicker input-group date">

                                                {!! Form::text('invoice_date', '', ['maxlength'=>'80',
                                                 'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('invoice_date','<span class="help-block">:message</span>') !!}

                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('invoice_product_service_quantity') ? 'has-error': ''}}">
                                            {!! Form::label('invoice_product_service_quantity','(খ): ইনভয়েস উল্লেখিত সেবা / পণ্যের  পরিমাণ :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('invoice_product_service_quantity', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('invoice_product_service_quantity','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('invoice_price') ? 'has-error': ''}}">
                                            {!! Form::label('invoice_price','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('invoice_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('invoice_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12  {{$errors->has('export_soft_hard_provider_name_address') ? 'has-error': ''}}">
                                            {!! Form::label('export_soft_hard_provider_name_address','৬  : রপ্তানি কৃত সফটওয়্যার / আইটিইএস হার্ডওয়্যার তৈরিতে ব্যবহৃত স্থানীয় সেবা / উপকনাদির সংগ্রহ সূত্র ( সরবরাহকারীর নাম ও ঠিকানা  )  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('export_soft_hard_provider_name_address', '', ['cols'=>'7','rows' => '4',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_soft_hard_provider_name_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('export_soft_provider_quantity') ? 'has-error': ''}}">
                                            {!! Form::label('export_soft_provider_quantity','পরিমাণ  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_soft_provider_quantity', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_soft_provider_quantity','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('export_soft_provider_price') ? 'has-error': ''}}">
                                            {!! Form::label('export_soft_provider_price','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_soft_provider_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_soft_provider_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12  {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                            {!! Form::label('company_name','৭  :  রপ্তানি কৃত সফটওয়্যার / আইটিইএস হার্ডওয়্যার তৈরিতে ব্যবহৃত আমদানিকৃত    আনুসাংগিক   সেবা /উপকরণাদির সংগ্রহ সূত্র  ( সরবরাহকারীর নাম ও ঠিকানা  )  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('name_of_applicant', '', ['cols'=>'7','rows' => '4',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                            {!! Form::label('company_name','পরিমাণ  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('name_of_applicant', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                            {!! Form::label('company_name','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('name_of_applicant', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('export_service_product_description') ? 'has-error': ''}}">
                                            {!! Form::label('export_service_product_description',' ৮  : রপ্তানি সেবা /পণ্যের  বিবরণ   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('export_service_product_description', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_service_product_description','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('export_service_product_quantity') ? 'has-error': ''}}">
                                            {!! Form::label('export_service_product_quantity','পরিমাণ   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_service_product_quantity', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_service_product_quantity','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('export_service_product_price') ? 'has-error': ''}}">
                                            {!! Form::label('export_service_product_price','মূল্য   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_service_product_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_service_product_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">


                                        <div class="col-md-6">
                                            {!! Form::label('shipping_date','৯.জাহাজীকরণের তারিখ   :',['class'=>'col-md-5 required-star']) !!}

                                            <div class="datepicker input-group date">


                                                {!! Form::text('shipping_date', '', ['maxlength'=>'80',
                                       'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('shipping_date','<span class="help-block">:message</span>') !!}

                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>


                                        <div class="col-md-6  {{$errors->has('posrt_of_destination') ? 'has-error': ''}}">
                                            {!! Form::label('posrt_of_destination','গন্তব্য বন্দর:   :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('posrt_of_destination', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('posrt_of_destination','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12  {{$errors->has('exp_no') ? 'has-error': ''}}">
                                            {!! Form::label('exp_no','১০  :  ইএক্সপি নম্বর ( দৃশ্যমান  পণ্যে রপ্তানির ক্ষেত্রে )',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('exp_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('exp_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('exp_price') ? 'has-error': ''}}">
                                            {!! Form::label('exp_price','মূল্য  :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('exp_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('exp_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('exp_date','তারিখ   :',['class'=>'col-md-5 required-star']) !!}

                                            <div class="datepicker input-group date">


                                                {!! Form::text('exp_date', '', ['maxlength'=>'80',
                                       'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('exp_date','<span class="help-block">:message</span>') !!}

                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <style>
                                            table.table-hover > tbody > tr{
                                                border:1px solid #547CC6;
                                            }
                                            table.table-hover > thead > tr{
                                                border:1px solid #547CC6;
                                            }
                                            table.table-hover > tbody > tr > td{
                                                border-bottom:1px solid #547CC6;
                                                text-align: center;
                                            }


                                        </style>
                                        <div class="col-md-4">
                                            <a href="#" data-target="#myModal" data-toggle="modal">Cash Incentive Slabs for Payment</a>
                                            <div class="modal fade" id="myModal" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Cash Incentive Slabs for Payment</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table aria-label="Cash Incentive Slabs for Payment" class="table  table-hover">
                                                                <thead style="background: #547CC6; color:#ffffff">
                                                                <tr class="d-none">
                                                                    <th aria-hidden="true"  scope="col"></th>
                                                                </tr>
                                                                <tr>
                                                                    <td>SL</td>
                                                                    <td> <b style="color: red">Transection</b> size</td>
                                                                    <td>Export Amount (US$)</td>
                                                                    <td>Service Fees For BIDA Memeber (Taka)</td>
                                                                    <td>Service Fees For Non-Memeber (Taka)</td>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr >
                                                                    <td>1.</td>
                                                                    <td>Small</td>
                                                                    <td>Up to 3,000</td>
                                                                    <td>800</td>
                                                                    <td>2,00</td>
                                                                </tr>
                                                                <tr >
                                                                    <td>2.</td>
                                                                    <td>Medium</td>
                                                                    <td>3,001-10,000</td>
                                                                    <td>1500</td>
                                                                    <td>4,000</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3.</td>
                                                                    <td>Large</td>
                                                                    <td>10,000+</td>
                                                                    <td>3000</td>
                                                                    <td>7,500</td>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('total_expected_price') ? 'has-error': ''}}">
                                            {!! Form::label('total_expected_price','১১  : মোট প্রত্যাবাসিত রপ্তানি মূল্য (বৈদেশিক মুদ্রায়):',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('total_expected_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('total_expected_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  {{$errors->has('net_fob_price') ? 'has-error': ''}}">
                                            {!! Form::label('net_fob_price','নীট এফওবি রপ্তানি মূল্য:',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('net_fob_price', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('net_fob_price','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6  {{$errors->has('export_value_certificate_no') ? 'has-error': ''}}">
                                            {!! Form::label('export_value_certificate_no','১২ : প্রত্যাবাসিত রপ্তানি মূল্যের সনদপত্রের নম্বর :',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('export_value_certificate_no', '', ['maxlength'=>'80',
                                                'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_value_certificate_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('export_value_certificate_date','তারিখ   :',['class'=>'col-md-5 required-star']) !!}

                                            <div class="datepicker input-group date">


                                                {!! Form::text('export_value_certificate_date', '', ['maxlength'=>'80',
                                       'class' => 'form-control input-sm  required']) !!}
                                                {!! $errors->first('export_value_certificate_date','<span class="help-block">:message</span>') !!}

                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5><b> এতদ্বারা প্রত্যয়ন করা যাচ্ছে যে , আমাদের নিজস্ব কারখানায় /
                                                    প্রতিষ্ঠানে তৈরিকৃত / উৎপাদিত সফটওয়্যার আইটিইএস / হার্ডওয়্যার
                                                    উপরোক্ত ৬ ও ৭ নং ক্রমিকে বর্ণিত সূত্র হওত সেবা / উপকরণাদি সংগ্রহের
                                                    মাধ্যমে রপ্তানির বিপরীতে ভর্তুকির জন্য উপরোক্ত অনুচ্ছেদ গুলোতে
                                                    উল্লিখিত সঠিক বা নির্ভুল । বিদেশী ক্রেতা আমদানিকারকের ক্রয়াদেশের
                                                    যথার্থতা / বিশ্বাসযোগ্যতা সম্পর্কেও নিশ্চিত করা হলো । </b></h5>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5><b> রপ্তানিকারকের উপরোক্ত ঘোষণার যথার্থতা যাচাইয়ান্তে সঠিক পাওয়া গিয়েছে
                                                    । ৮ নং ক্রমিকে উল্লেখিত ঘোষিত রপ্তানি মূল্য যৌক্তিক ও বিদ্যমান
                                                    আন্তর্জাতিক বাজার মূল্যর সংগতিপূর্ণ পাওয়া গিয়েছে এবং বিদেশী ক্রেতার
                                                    যথার্থতা / বিশ্বাসযোগ্যতা সম্পর্কেও নিশ্চিত হওয়া গিয়েছে । প্রতাবাসিত
                                                    রপ্তানি মূল্যর ( নীট এফওবি মূল্য ) ওপর রপ্তানি ভর্তুকি পরিশোদের
                                                    সুপারিশ করা হলো । </b></h5>
                                        </div>


                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </section>
    </div>
</div>


<script>

    $(function () {
        $(".datepicker").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            startDate: '-3d'
        }).datepicker('update', new Date());
    });


</script>

</body>
</html>