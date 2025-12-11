<?php
$accessMode = ACL::getAccsessRight('LabourInspection');
if (!ACL::isAllowed($accessMode, '-A-')) {
    die('You have no access right! Please contact with system admin if you have any query.[ML-1101]');
}
?>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-switcher/css/switcher.css") }}"/>
<style>
    .form-group {
        margin-bottom: 5px;
    }

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .photo_size {
        max-height: 150px;
        max-width: 200px;
        float: right;
    }

    .padding-l-r {
        padding: 0px 20px;
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
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h5>প্রতিষ্ঠানের লাইসেন্স প্রাপ্তির আবেদন</h5>
                    </div>
                    <div class="panel-body">
                    {!! Form::open(array('url' => 'mutation-land/store','method' => 'post', 'class' =>'form-horizontal', 'id' => 'NewApplication',
                        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}

                    <!-- Licence Type -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Company Information</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('owner_type') ? 'has-error': ''}}">
                                            {!! Form::label('owner_type','লাইসেন্সের ধরণ',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('owner_type', [], '', ['class' =>'form-control input-md required', 'id'=> 'owner_type']) !!}
                                                {!! $errors->first('owner_type','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('owner_type') ? 'has-error': ''}}">
                                            {!! Form::label('owner_type','শ্রেণীবিন্যাস/ইন্ডাস্ট্রিয়াল সেক্টর',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('owner_type', [], '', ['class' =>'form-control input-md required', 'id'=> 'owner_type']) !!}
                                                {!! $errors->first('owner_type','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Licence Type -->

                        <!-- start -:- Industry Information -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>কারখানা/প্রতিষ্ঠানের পরিচিতি</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('full_name') ? 'has-error': ''}}">
                                            {!! Form::label('full_name', 'নাম (ইংরেজিতে)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('full_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('full_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('designation') ? 'has-error': ''}}">
                                            {!! Form::label('designation', 'নাম (বাংলায়)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('designation', Auth::user()->designation, ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('mobile_no') ? 'has-error': ''}}">
                                            {!! Form::label('mobile_no','মোবাইল নম্বর',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('mobile_no', Auth::user()->user_phone, ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('mobile_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('email') ? 'has-error': ''}}">
                                            {!! Form::label('email','ইমেইল',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('email', Auth::user()->user_email, ['class' =>'form-control input-md email required']) !!}
                                                {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('ceo_dob') ? 'has-error': ''}}">
                                            {!! Form::label('ceo_dob','প্রতিষ্ঠার বছর',['class'=>'col-md-5']) !!}
                                            <div class=" col-md-7">
                                                <div class="datepicker input-group date"
                                                     data-date-format="dd-mm-yyyy">
                                                    {!! Form::text('ceo_dob', '', ['class'=>'form-control input-md date', 'id' => 'ceo_dob', 'placeholder'=>'Pick from datepicker']) !!}
                                                    <span class="input-group-addon"><span
                                                                class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('ceo_dob','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- start -:- Industry Information -->


                        <!-- start -:- Industry Place -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>কারখানা/প্রতিষ্ঠানের অবস্থান</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('division_id') ? 'has-error': ''}}">
                                            {!! Form::label('division_id','বিভাগ',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('division_id', [], '', ['class' =>'form-control input-md required', 'id'=> 'division_id']) !!}
                                                {!! $errors->first('division_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('district_id') ? 'has-error': ''}}">
                                            {!! Form::label('district_id','জেলা',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('district_id', [], '', ['class' =>'form-control input-md required', 'id'=> 'district_id']) !!}
                                                {!! $errors->first('district_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('upazilla_id') ? 'has-error': ''}}">
                                            {!! Form::label('upazilla_id','উপজেলা/থানা',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('upazilla_id', [], '', ['class' =>'form-control input-md required', 'id'=> 'upazilla_id']) !!}
                                                {!! $errors->first('upazilla_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('post_office') ? 'has-error': ''}}">
                                            {!! Form::label('post_office','ডাকঘর',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('post_office', [], '', ['class' =>'form-control input-md required', 'id'=> 'post_office']) !!}
                                                {!! $errors->first('post_office','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('road_no_en') ? 'has-error': ''}}">
                                            {!! Form::label('road_no_en', 'রোড নং (ইংরেজিতে)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('road_no_en', '', ['class' => 'form-control input-md required','placeholder'=>'কারখানা/প্রতিষ্ঠানের রোড নং']) !!}
                                                {!! $errors->first('road_no_en','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('road_no_bn') ? 'has-error': ''}}">
                                            {!! Form::label('road_no_bn', 'রোড নং (বাংলায়)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('road_no_bn', '', ['class' => 'form-control input-md required','placeholder'=>'কারখানা/প্রতিষ্ঠানের রোড নং (বাংলায়)']) !!}
                                                {!! $errors->first('road_no_bn','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('holding_name_en') ? 'has-error': ''}}">
                                            {!! Form::label('holding_name_en', 'বাড়ি/হোল্ডিং/গ্রাম/মহল্লা (ইংরেজিতে)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('holding_name_en', '', ['class' => 'form-control input-md required','placeholder'=>'বাড়ি/হোল্ডিং/গ্রাম/মহল্লার নাম']) !!}
                                                {!! $errors->first('holding_name_en','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('holding_name_bn') ? 'has-error': ''}}">
                                            {!! Form::label('holding_name_bn', 'বাড়ি/হোল্ডিং/গ্রাম/মহল্লা (বাংলায়)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('holding_name_bn', '', ['class' => 'form-control input-md required','placeholder'=>'বাড়ি/হোল্ডিং/গ্রাম/মহল্লার নাম (বাংলায়)']) !!}
                                                {!! $errors->first('holding_name_bn','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- start -:- Industry Place -->


                        <!-- start -:- Building Information -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>ভবনের তথ্যাদি</strong>
                            </div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('is_building_owned') ? 'has-error': ''}}">
                                            {!! Form::label('','ভবনটি কি প্রতিষ্ঠান মালিকের নিজস্ব ?',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="radio-inline">  {!! Form::radio('is_building_owned', 'Yes',false, ['id' => 'yesCheck']) !!}
                                                            Yes </label>
                                                        <label class="radio-inline">   {!! Form::radio('is_building_owned', 'No',true,['id' => 'noCheck']) !!}
                                                            No </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('factory_building_owner') ? 'has-error': ''}}">
                                            {!! Form::label('factory_building_owner', 'ভবনের সত্তাধিকারীর নাম',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('factory_building_owner', '', ['class' => 'form-control input-md required','placeholder'=>'ভবনের সত্তাধিকারীর নাম']) !!}
                                                {!! $errors->first('factory_building_owner','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('factory_building_owner_address') ? 'has-error': ''}}">
                                            {!! Form::label('factory_building_owner_address', 'ভবনের সত্তাধিকারীর ঠিকানা',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('factory_building_owner_address','',['class'=>'form-control input-md required','id'=>'factory_building_owner_address', 'placeholder'=>'ভবনের সত্তাধিকারীর ঠিকানা','maxlength' => 254, 'rows' => 2, 'cols' => 50]) !!}
                                                {!! $errors->first('factory_building_owner_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered" cellspacing="0"
                                                   width="100%" id="factoryBuildingTable">
                                                <thead>
                                                <tr>
                                                    <th>ভবনের/স্থাপনার ধরণ</th>
                                                    <th>ভবনে অবস্থিত কারখানার সংখ্যা</th>
                                                    <th>ভবনের ভূমি এলাকা</th>
                                                    <th>ভবনের প্লিন্থ/ফ্লোর এলাকা</th>
                                                    <th>
                                                        <a class="btn btn-sm btn-primary addTableRows"
                                                           onclick="addTableRow('factoryBuildingTable', 'factoryBuildingTableRow0');"><i
                                                                    class="fa fa-plus"></i></a>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr id="factoryBuildingTableRow0" data-number="1">
                                                    <td>
                                                        {!!Form::select('factory_building_structure_type[]', ['' => 'বাছাই করুন', '1' => 'কনক্রিট কাঠামো', '2' => 'কাঠের কাঠামো'], null, ['class' => 'form-control'])!!}
                                                        {!! $errors->first('factory_building_structure_type','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('factories_in_building[]', '', ['class' => 'form-control input-md', 'placeholder'=>'ভবনে অবস্থিত কারখানার সংখ্যা']) !!}
                                                        {!! $errors->first('factories_in_building','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('factory_building_area[]', '', ['class' => 'form-control input-md', 'placeholder'=>'ভবনের ভূমি এলাকা']) !!}
                                                        {!! $errors->first('factory_building_area','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('factory_floor_area[]', '', ['class' => 'form-control input-md', 'placeholder'=>'ভবনের প্লিন্থ/ফ্লোর এলাকা']) !!}
                                                        {!! $errors->first('factory_floor_area','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0);"
                                                           class="btn btn-sm btn-danger removeRow"
                                                           onclick="removeTableRow('financeTableId','factoryBuildingTableRow0');">
                                                            <i class="fa fa-times" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>প্রতিষ্ঠান হিসেবে ব্যবহৃত ভবনের/ভাড়াকৃত ভবনের স্থানীয় কর্তৃপক্ষ কর্তৃক
                                            অনুমোদিত নকশার বিবরণ, এবং উক্ত ভবনে তার অবস্থান ও পরিমাণ</h4>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered" cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>রেফারেন্স নাম্বার</th>
                                                    <th>অনুমোদনের তারিখ</th>
                                                    <th>ভবনে অবস্থানের বিবরণ</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        {!! Form::text('building_plan_approval_reference', '', ['class' => 'form-control input-md', 'placeholder'=>'ভবনের রেফারেন্স নাম্বার']) !!}
                                                        {!! $errors->first('building_plan_approval_reference','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        <div class="datepicker input-group date"
                                                             data-date-format="dd-mm-yyyy">
                                                            {!! Form::text('building_plan_approval_date', '', ['class'=>'form-control input-md date', 'id' => 'building_plan_approval_date', 'placeholder'=>'ভবনের পরিকল্পনা অনুমোদন তারিখ']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('building_plan_approval_date','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::textarea('building_occupancy_details','',['class'=>'form-control input-md required','id'=>'building_occupancy_details', 'placeholder'=>'ভবনে অবস্থানের বিবরণ','maxlength' => 254, 'rows' => 2, 'cols' => 50]) !!}
                                                        {!! $errors->first('building_occupancy_details','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Building Information -->

                        <!-- Main Product -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>প্রধান পণ্যসমূহ</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('product_other') ? 'has-error': ''}}">
                                            {!! Form::label('product_other', 'অন্যান্য (উল্লেখিত পণ্যাদি অপ্রযোজ্য হলে)',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'']) !!}
                                                {!! $errors->first('product_other','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Main Product -->

                        <!-- start -:- Business Services/Productions Type -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>ব্যবসায়/সেবা/উৎপাদন প্রক্রিয়াসমূহের ধরণ</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('factory_building_owner_address') ? 'has-error': ''}}">
                                            {!! Form::label('factory_building_owner_address', 'পূর্ববর্তী বারো মাসে যা করা হয়েছে',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('factory_building_owner_address','',['class'=>'form-control input-md required','id'=>'factory_building_owner_address', 'placeholder'=>'পূর্ববর্তী বারো মাসে যা করা হয়েছে','maxlength' => 254, 'rows' => 3, 'cols' => 50]) !!}
                                                {!! $errors->first('factory_building_owner_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('factory_building_owner_address') ? 'has-error': ''}}">
                                            {!! Form::label('factory_building_owner_address', 'আগামী বারো মাসে যা করা হবে',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('factory_building_owner_address','',['class'=>'form-control input-md required','id'=>'factory_building_owner_address', 'placeholder'=>'আগামী বারো মাসে যা করা হবে','maxlength' => 254, 'rows' => 3, 'cols' => 50]) !!}
                                                {!! $errors->first('factory_building_owner_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Business Services/Productions Type -->

                        <!-- start -:- Owner Details -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>ব্যবস্থাপনা কর্তৃপক্ষের তথ্যাদি</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>বাংলাদেশ শ্রম আইন, ২০০৬-এর উদ্দেশ্য পূরণকল্পে প্রধান নির্বাহী/ব্যবস্থাপকের
                                            নাম: (প্রশাসনিক, তদারকি কর্মকর্তা বা ব্যবস্থাপনামূলক কাজে নিয়োজিত কোনো
                                            ব্যক্তি)&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-primary"><i
                                                        class="fa fa-plus-circle "></i> নতুন যোগ করুন
                                            </button>
                                        </h5>
                                    </div>
                                </div>
                                <div class="form-group">

                                </div>
                            </div>
                        </div>
                        <!-- start -:- Owner Details -->

                        <!-- start -:- Electricity Details -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>বিদ্যুৎশক্তির বিবরণ</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>সংস্থাপিত বা সংস্থাপনের জন্য প্রস্তাবিত বিদ্যুৎ শক্তির ধরণ ও মোট পরিমাণ
                                            (বিদ্যুৎ উৎপাদন স্টেশন ছাড়া অন্য সকল ক্ষেত্রে)</h5>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>বিদ্যুৎশক্তির ধরণ</th>
                                                    <th>বিদ্যুৎশক্তির পরিমাণ</th>
                                                    <th>বৈদ্যুতিক সাবস্টেশনের বিবরণ</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        {!!Form::select('electricity_type', ['' => 'বাছাই করুন', '1' => 'কনক্রিট কাঠামো', '2' => 'কাঠের কাঠামো'], null, ['class' => 'form-control', 'id'=>'electricity_type'])!!}
                                                        {!! $errors->first('electricity_type','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            {!! Form::text('electrical_power', '', ['class' => 'form-control input-md', 'placeholder'=>'বিদ্যুৎশক্তির পরিমাণ']) !!}
                                                            <span class="input-group-addon">কিলোওয়াট ঘন্টা (kWh)</span>
                                                        </div>
                                                        {!! $errors->first('electrical_power','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::textarea('factory_electrical_substation','',['class'=>'form-control input-md required','id'=>'factory_electrical_substation', 'placeholder'=>'বৈদ্যুতিক সাবস্টেশনের বিবরণ','maxlength' => 254, 'rows' => 2, 'cols' => 50]) !!}
                                                        {!! $errors->first('factory_electrical_substation','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Electricity Details -->

                        <!-- start -:- Employee/Worker Details -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>শ্রমিক/কর্মচারীর বিবরণ</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('product_other') ? 'has-error': ''}}">
                                            {!! Form::label('product_other', 'প্রাপ্তবয়স্ক (১৮ বছরোর্ধ)',['class'=>'col-md-3 text-left required-star']) !!}
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">পুরুষ</span>
                                                    {!! Form::number('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'অংকে লিখুন']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">নারী</span>
                                                    {!! Form::number('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'অংকে লিখুন']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">তৃতীয় লিঙ্গ</span>
                                                    {!! Form::number('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'অংকে লিখুন']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('product_other') ? 'has-error': ''}}">
                                            {!! Form::label('product_other', 'কিশোর (১৪-১৮ বছর)',['class'=>'col-md-3 text-left required-star']) !!}
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">পুরুষ</span>
                                                    {!! Form::number('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'অংকে লিখুন']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">নারী</span>
                                                    {!! Form::number('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'অংকে লিখুন']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">তৃতীয় লিঙ্গ</span>
                                                    {!! Form::number('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'অংকে লিখুন']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('product_other') ? 'has-error': ''}}">
                                            {!! Form::label('product_other', 'শিশু (১৪ বছরের নিচে)',['class'=>'col-md-3 text-left required-star']) !!}
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">পুরুষ</span>
                                                    {!! Form::number('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'অংকে লিখুন']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">নারী</span>
                                                    {!! Form::number('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'অংকে লিখুন']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">তৃতীয় লিঙ্গ</span>
                                                    {!! Form::number('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'অংকে লিখুন']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table" cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>পূর্ববর্তী বছরে নিয়োজিত ছিল এমন সর্বোচ্চ শ্রমিক/কর্মচারীর
                                                        সংখ্যা
                                                    </th>
                                                    <th>ট্রেড ইউনিয়ন আছে কি ?</th>
                                                    <th>পার্টিসিপেশন কমিটি আছে কি ?</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"></span>
                                                            {!! Form::number('product_other', '', ['class' => 'form-control input-md required','placeholder'=>'পূর্ববর্তী বছরে নিয়োজিত ছিল এমন সর্বোচ্চ শ্রমিক/কর্মচারীর সংখ্য']) !!}
                                                            <span class="input-group-addon">জন</span>
                                                        </div>
                                                        (ঠিকাদার কর্তৃক সরবরাহকৃত শ্রমিকসহ)
                                                    </td>
                                                    <td>
                                                        {!! Form::checkbox('checkbox_testing',1,null, array('id'=>'checkbox_testing',
                                                    'class'=>'required')) !!}
{{--                                                        <label class="radio-inline">  {!! Form::radio('is_building_owned', 'Yes',false, ['id' => 'yesCheck']) !!}--}}
{{--                                                            Yes </label>--}}
{{--                                                        <label class="radio-inline">   {!! Form::radio('is_building_owned', 'No',true,['id' => 'noCheck']) !!}--}}
{{--                                                            No </label>--}}
                                                    </td>
                                                    <td>
                                                        <label class="radio-inline">  {!! Form::radio('is_building_owned', 'Yes',false, ['id' => 'yesCheck']) !!}
                                                            Yes </label>
                                                        <label class="radio-inline">   {!! Form::radio('is_building_owned', 'No',true,['id' => 'noCheck']) !!}
                                                            No </label>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Employee/Worker Details -->

                        <!-- start -:- Organization/Association Membership -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>সংস্থায়/অ্যাসোসিয়েশনে সদস্যপদ</strong>
                            </div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table" cellspacing="0"
                                                   width="100%" id="organizationAssociationMembershipTable">
                                                <thead>
                                                <tr>
                                                    <th>সংস্থার নাম</th>
                                                    <th>নিবন্ধন নম্বর</th>
                                                    <th>নিবন্ধনের তারিখ</th>
                                                    <th>নবায়নের তারিখ</th>
                                                    <th>
                                                        <a class="btn btn-sm btn-primary addTableRows"
                                                           onclick="addTableRow('organizationAssociationMembershipTable', 'organizationAssociationMembershipRow0');"><i
                                                                    class="fa fa-plus"></i></a>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr id="organizationAssociationMembershipRow0" data-number="1">
                                                    <td>
                                                        {!!Form::select('factory_building_structure_type[]', ['' => 'বাছাই করুন', '1' => 'কনক্রিট কাঠামো', '2' => 'কাঠের কাঠামো'], null, ['class' => 'form-control'])!!}
                                                        {!! $errors->first('factory_building_structure_type','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('factories_in_building[]', '', ['class' => 'form-control input-md', 'placeholder'=>'']) !!}
                                                        {!! $errors->first('factories_in_building','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        <div class="datepicker input-group date"
                                                             data-date-format="dd-mm-yyyy">
                                                            {!! Form::text('factory_organization_date', '', ['class'=>'form-control input-md date', 'id' => 'factory_organization_date', 'placeholder'=>'']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="datepicker input-group date"
                                                             data-date-format="dd-mm-yyyy">
                                                            {!! Form::text('factory_organization_renewal_date', '', ['class'=>'form-control input-md date', 'id' => 'factory_organization_renewal_date', 'placeholder'=>'']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0);"
                                                           class="btn btn-sm btn-danger removeRow"
                                                           onclick="removeTableRow('financeTableId','factoryBuildingTableRow0');">
                                                            <i class="fa fa-times" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <!-- end -:- Organization/Association Membership -->

                        <!-- start -:- Attachment -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong> সংযুক্তি</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>সংযুক্তির ধরণ বাছাই করুন, বাছাই করুন এটা হার্ড কপি কিনা। যদি আপনার কাছে
                                            সফ্‌ট কপি থেকে থাকে, অনুগ্রহ করে ‘ব্রাউজ করুন...’ বোতামে ক্লিক করুন, ফাইলটি
                                            বাছাই করুন এবং ‘সংযুক্তি যোগ করুন’ বোতামে ক্লিক করে ফাইলটি সংযুক্ত করুন।
                                            গ্রহণযোগ্য ফাইলের ধরণসমূহ: png, gif, jpeg, jpg, pdf, doc, docx, xls, xlsx,
                                            dwg। ফাইলপ্রতি সর্বোচ্চ আপলোড সীমা: ১০০মেগাবাইট।</h4>
                                    </div>
                                </div>





                            </div>
                        </div>
                        <!-- end -:- Attachment -->




                        <!-- start -:- Service Fee Payment Details -->
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

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 padding-l-r">
                                            <div class="alert alert-danger" role="alert">
                                                Vat/ tax and service charge is an approximate amount, it may vary
                                                based
                                                on the
                                                Sonali Bank system.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 padding-l-r">
                                            <div class="checkbox">
                                                <label>
                                                    {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms',
                                                    'class'=>'required')) !!}
                                                    All the details and information provided in this form are true and
                                                    complete.
                                                    I am aware that any untrue/incomplete statement may result in delay
                                                    in
                                                    BIN
                                                    issuance and I may be subjected to full penal action under the Value
                                                    Added
                                                    Tax and Supplementary Duty Act, 2012 or any other applicable Act
                                                    Prevailing
                                                    at present.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Service Fee Payment Details -->

                        <!-- start -:- Save As Draft & Payment & Submit Button -->
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="pull-left">
                                    <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn">Save as Draft
                                    </button>
                                </div>

                            </div>
                            <div class="col-md-6 col-xs-12 button_last">
                                <div class="pull-right" style="padding-left: 1em;">
                                    <button type="submit" id="submitForm" style="cursor: pointer;"
                                            class="btn btn-success btn-md" value="Submit" name="actionBtn">Payment &amp;
                                        Submit
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- end -:- Save As Draft & Payment & Submit Button -->
                        {!! Form::close() !!}
                    </div><!-- .panel-body (form panel body) -->
                </div><!-- .panel .panel-primary (form panel)-->
            </div><!-- .box-body -->
        </div><!-- #inputForm -->
    </div><!-- .col-md-12 -->
</section>

@include('partials.image-resize.image-upload')
{{--@include('partials.profile-capture')--}}
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-switcher/js/jquery.switcher.min.js') }}"></script>


<script type="text/javascript">
    $("#NewApplication").validate();
    $('#nid_number').on('blur', function (e) {
        var nid = $('#nid_number').val().length
        if (nid == 10 || nid == 13 || nid == 17) {
            $('#nid_number').removeClass('error')
        } else {
            $('#nid_number').addClass('error')
            // $('#nid_number').val('')
        }
    })

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

    var yyyy = today.getFullYear();
    $('.datepicker').datetimepicker({
        viewMode: 'days',
        format: 'DD-MMM-YYYY',
        maxDate: 'now',
        minDate: '01/01/' + (yyyy - 100),
        ignoreReadonly: true
    });

    $(document).ready(function () {
        var switcherEl = $('#checkbox_testing').switcher();
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/mutation-land/get-refresh-token';

            $('#owner_type').keydown();
        });

        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: "OSS_BIDA"
            },
            {
                key: "agent-id",
                value: "{{ config('stakeholder.agent_id') }}"
            },
        ]

        $('#owner_type').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$ml_service_url}}/info/land-owners";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "owner_type";//dynamic id for callback
            let element_name = "owner_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        function callbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
            var option = '<option value="">Select One</option>';
            if (response.responseCode === 200) {
                $.each(response.data, function (key, row) {
                    let id = row[element_id] + '@' + row[element_name];
                    let value = row[element_name];
                    option += '<option value="' + id + '">' + value + '</option>';
                });
            }

            $("#" + calling_id).html(option)
            $("#" + calling_id).next().hide()
        }

        // function callbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id]) {
        //     let option = '<option value="">Select One</option>';
        //     if (response.responseCode === 200) {
        //         $.each(response.data, function (key, row) {
        //             let id = row[element_id] ;
        //             let value = row[element_name]
        //             if (selected_value == id) {
        //                 option += '<option selected="true" value="' + id + '">' + value + '</option>'
        //             } else {
        //                 option += '<option value="' + id + '">' + value + '</option>'
        //             }
        //         })
        //     } else {
        //         console.log(response.status)
        //     }
        //     $("#" + calling_id).html(option)
        //     $("#" + calling_id).next().hide()
        // }
    });

</script>