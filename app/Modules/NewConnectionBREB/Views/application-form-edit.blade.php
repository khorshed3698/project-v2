<?php
$accessMode = ACL::getAccsessRight('NewConnectionBREB');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any querysss.');
}
?>
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
        width: 33% !important;
    }

    .wizard > .steps .number {
        font-size: 1.2em;
    }

    .intl-tel-input .country-list {
        z-index: 5;
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
                        <h5><strong>বি.আর.ই.বি (BREB)  নতুন সংযোগের জন্য আবেদন পত্র</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'new-connection-breb/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewConnection',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>
                    <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"
                           id="app_id"/>

                    <h3 class="text-center stepHeader">সাধারণ তথ্য</h3>
                    <fieldset>
                        <!--panel-->
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>বিদ্যুৎ অফিসের বিবরণ </strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('pbs_name','সমিতির নাম :', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 ">
                                                {!! Form::select('pbs_name',[],'', ['class' =>'form-control']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('zonal_office','জোনাল অফিস :', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('zonal_office', [], '', ['class' =>'form-control','id'=>'zonal_office']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/panel-body-->
                        </div>
                        <!--/panel-->
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>সংযোগ</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            {!! Form::label('tariff_name','সংযোগের ট্যারিফ :', ['class'=>'col-md-3']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('tariff_name',[],'', ['class' => 'form-control docloader', 'placeholder'=>'নির্বাচন করুন']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--/panel-body-->
                            </div>
                        </div>
                        <!--panel-->
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>আবেদনকারীর বিবরণ</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-10">
                                        {!! Form::label('organization_name','আবেদনকৃত প্রতিষ্ঠানের নাম :', ['class'=>'col-md-3']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('organization_name',!empty($appData->organization_name) ? $appData->organization_name:'',['class' => 'form-control input-sm']) !!}

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-10">
                                        {!! Form::label('name','আবেদনকারীর নাম (বাংলা) :', ['class'=>'col-md-3']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('name',!empty($appData->name) ? $appData->name:'' ,['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-10">
                                        {!! Form::label('fName','পিতার নাম (বাংলা) :', ['class'=>'col-md-3']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('fName', !empty($appData->fName) ? $appData->fName:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-10">
                                        {!! Form::label('mName','মাতার নাম (বাংলা) :', ['class'=>'col-md-3']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('mName', !empty($appData->mName) ? $appData->mName:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-10">
                                        {!! Form::label('sName',' স্বামী/স্ত্রীর নাম (বাংলা) :', ['class'=>'col-md-3']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('sName', !empty($appData->sName) ? $appData->sName:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('date_of_birth','জন্ম তারিখ (ইংরেজি) :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            <div class="datepickerDob input-group date">
                                                {!! Form::text('date_of_birth', !empty($appData->date_of_birth) ? $appData->date_of_birth:'',['class' => 'form-control input-sm','readonly','style'=>'background:white;']) !!}
                                                <span class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('nationality','জাতীয়তা :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('nationality', ['1@বাংলাদেশী'=>'বাংলাদেশী','2@অন্যান্য'=>'অন্যান্য'], !empty($appData->nationality) ? $appData->nationality:'', ['class' =>'form-control']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('national_id','জাতীয়তা পরিচয় পত্র নম্বর (ইংরেজি) :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('national_id', !empty($appData->national_id) ? $appData->national_id:'',['class' => 'form-control nid bd_nid input-sm']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('passport','পাসপোর্ট (ইংরেজি) :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('passport', !empty($appData->passport) ? $appData->passport:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('mobile','মোবাইল নম্বর (ইংরেজি) :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('mobile', !empty($appData->mobile) ? $appData->mobile:'',['class' => 'form-control input-sm mobile onlyNumber','id'=>'mobile']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('phone','ফোন নম্বর (ইংরেজি) :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('phone', !empty($appData->phone) ? $appData->phone:'',['class' => 'form-control input-sm telephone']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('email','ইমেইল (ইংরেজি) :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7 ">
                                            {!! Form::text('email', !empty($appData->email) ? $appData->email:'',['class' => 'form-control input-sm email']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('gender','লিঙ্গ :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('gender', ['1@পুরুষ'=>'পুরুষ','2@নারী'=>'নারী'], !empty($appData->gender) ? $appData->gender:'', ['class' =>'form-control']) !!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--panel-->
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong> স্থায়ী ঠিকানা </strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        {!! Form::label('perm_dist','জেলা :',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('perm_dist', [],'', ['placeholder' => 'নির্বাচন করুন', 'class' => 'form-control input-md','id'=>'perm_dist']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('perm_upazilla','উপজেলা :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('perm_upazilla', [],'', ['class' => 'form-control input-md','placeholder'=>'প্রথমে জেলা নির্বাচন করুন']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('perm_thana','থানা :',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('perm_thana', [],'', ['class' => 'form-control input-md','placeholder'=>'প্রথমে জেলা নির্বাচন করুন']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        {!! Form::label('perm_union','ইউনিয়ন :',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('perm_union', [],'', ['placeholder' => 'প্রথমে উপজেলা নির্বাচন করুন ','class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        {!! Form::label('perm_post','ডাকঘর : ',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('perm_post', !empty($appData->perm_post) ? $appData->perm_post:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('perm_post_code','পোস্ট কোড (ইংরেজি):', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('perm_post_code', !empty($appData->perm_post_code) ? $appData->perm_post_code:'',['class' => 'form-control input-sm onlyNumber']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        {!! Form::label('perm_village','গ্রাম :',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('perm_village', [],'', ['placeholder' => 'প্রথমে ইউনিয়ন নির্বাচন করুন ','class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('perm_road_no','মহল্লা/রোড নম্বর (বাংলা):',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('perm_road_no', !empty($appData->perm_road_no) ? $appData->perm_road_no:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('perm_house_holding','বাড়ির নাম/হোল্ডিং নম্বর (বাংলা):', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('perm_house_holding', !empty($appData->perm_house_holding) ? $appData->perm_house_holding:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong> প্রস্তাবিত বিদ্যুৎ সংযোগ স্থলের বিবরণ </strong></div>
                            <div class="panel-body">
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class=" col-md-12" style="font-size: 17px;">
                                            <div class="col-md-offset-3 col-md-6">
                                                {!! Form::checkbox('same_as_permanent',1,!empty($appData->same_as_permanent)==1 ,['id'=>'same_as_permanent'])!!}
                                                {!! Form::label('same_as_permanent','প্রস্তাবিত বিদ্যুৎ সংযোগ স্থলের ঠিকানা হিসেবে ব্যবহার করুন :',['class'=>'col-md-10']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4 ">
                                        {!! Form::label('cur_district','জেলা :',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('cur_district', [],'', ['placeholder' => 'নির্বাচন করুন','class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('cur_upazilla','উপজেলা :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('cur_upazilla', [],'', ['placeholder' => 'প্রথমে জেলা নির্বাচন করুন','class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('cur_thana','থানা :',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('cur_thana', [],'', ['placeholder' => 'প্রথমে জেলা নির্বাচন করুন','class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        {!! Form::label('cur_union','ইউনিয়ন :',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('cur_union', [],'', ['placeholder' => 'প্রথমে উপজেলা নির্বাচন করুন','class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('cur_post','ডাকঘর : ',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('cur_post', !empty($appData->cur_post) ? $appData->cur_post:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('cur_post_code','পোস্ট কোড (ইংরেজি):', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('cur_post_code', !empty($appData->cur_post_code) ? $appData->cur_post_code:'',['class' => 'form-control input-sm onlyNumber']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4">
                                        {!! Form::label('cur_village','গ্রাম :',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('cur_village', [],'', ['placeholder' => 'প্রথমে ইউনিয়ন নির্বাচন করুন ','class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        {!! Form::label('cur_road_no','মহল্লা/রোড নম্বর (বাংলা):',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('cur_road_no', !empty($appData->cur_road_no) ? $appData->cur_road_no:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('cur_house_holding','বাড়ির নাম/হোল্ডিং নম্বর (বাংলা):', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('cur_house_holding', !empty($appData->cur_house_holding) ? $appData->cur_house_holding:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4 ">
                                        {!! Form::label('mouja','মৌজা (বাংলা):',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('mouja', !empty($appData->mouja) ? $appData->mouja:'',['class' => 'form-control input-sm ']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        {!! Form::label('dag_no','দাগ নম্বর (ইংরেজি):',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('dag_no', !empty($appData->dag_no) ? $appData->dag_no:'',['class' => 'form-control input-sm ']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('khotian_no','খতিয়ান নম্বর (ইংরেজি):', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('khotian_no', !empty($appData->khotian_no) ? $appData->khotian_no:'',['class' => 'form-control input-sm ']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-10">
                                        {!! Form::label('land_owner_type','জমির মালিকানার ধরণ :', ['class'=>'col-md-3']) !!}
                                        <div class="col-md-7 ">
                                            {!! Form::select('land_owner_type', ['1@নিজ/পৈত্রিক মালিকানাধীন'=>'নিজ/পৈত্রিক মালিকানাধীন','2@ভাড়াকৃত/লিজ (ব্যক্তি মালিকানাধীন)'=>'ভাড়াকৃত/লিজ (ব্যক্তি মালিকানাধীন)','3@ভাড়াকৃত/লিজ (সরকারী মালিকানাধীন)'=>'ভাড়াকৃত/লিজ (সরকারী মালিকানাধীন)'],!empty($appData->land_owner_type) ? $appData->land_owner_type:'', ['placeholder' => 'নির্বাচন করুন',
                                            'class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-10">
                                        {!! Form::label('land_owner_name','জমির আইনগত মালিক (বাংলা):', ['class'=>'col-md-3']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('land_owner_name', !empty($appData->land_owner_name) ? $appData->land_owner_name:'',['class' => 'form-control input-sm']) !!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>জিওগ্রাফিক তথ্য</strong></div>
                            <div class="panel-body">
                                <p class="text-danger">১) নিকটবতী সার্ভিস পোল হইতে সংযোগস্থলের দূরত্বের উপর নির্ভর করে
                                    সংযোগ তারের দৈর্ঘ্য
                                    নির্ধারিত হবে।</p>
                                <p class="text-danger"> ২) দুরত্ব বেশি হলে প্রয়োজনে নতুন খুঁটি ও লাইন নির্মাণ করা
                                    হবে।</p>
                                <p class="text-danger">৩) মিথ্যা তথ্যের জন্য অনাকাংখিত দুর্ঘটনা ঘটলে আপনি নিজেই দায়ী
                                    থাকবেন । প্রয়োজনে
                                    তদন্ত করা হবে।</p>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        {!! Form::label('service_drop_dist_consumer','নিকটবর্তী সার্ভিস পোল হইতে দূরত্ব (ইংরেজি):', ['class'=>'col-md-4']) !!}
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                {!! Form::text('service_drop_dist_consumer', !empty($appData->service_drop_dist_consumer) ? $appData->service_drop_dist_consumer:'',['class' => 'form-control input-sm']) !!}
                                                <span class="input-group-addon">ফুট</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/panel-body-->
                        </div>


                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong> কালেকশন এর বিবরণ </strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-10">
                                        {!! Form::label('connection_type','আবেদন প্রকৃতি', ['class'=>'col-md-3']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('connection_type',['1@স্থায়ী সংযোগ'=>'স্থায়ী সংযোগ','2@অস্থায়ী সংযোগ'=>'অস্থায়ী সংযোগ','3@সাময়িক সংযোগ'=>'সাময়িক সংযোগ'], !empty($appData->connection_type) ? $appData->connection_type:'', ['class' => 'form-control', 'placeholder'=>'নির্বাচন করুন']) !!}
                                        </div>
                                    </div>
                                </div>
                                <!--/panel-body-->
                            </div>
                        </div>
                        <!--/panel-->
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>চাহিদাকৃত লোড </strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        {!! Form::label('total_load_KW','মোট লোড(কি: ও:) : ',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('total_load_KW',!empty($appData->total_load_KW) ? $appData->total_load_KW:'',['class' => 'col-md-7 form-control input-md onlyNumber','placeholder' => '']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('phase','ফেজ :',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('phase', ['1@এক ফেইজ'=>'এক ফেইজ','2@তিন ফেইজ'=>'তিন ফেইজ'],!empty($appData->phase) ? $appData->phase:'', ['placeholder' => 'নির্বাচন করুন','class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {!! Form::label('volt','ভোল্ট :', ['class'=>'col-md-5']) !!}
                                        <div class="col-md-7 ">
                                            {!! Form::select('volt', ['230@২৩০'=>'২৩০','400@৪০০'=>'৪০০','11000@১১কেভি'=>'১১কেভি','33000@৩৩কেভি'=>'৩৩কেভি'],!empty($appData->volt) ? $appData->volt:'', ['placeholder' => 'নির্বাচন করুন','class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>বাড়ির/প্রতিষ্ঠানের লোকেশন এবং মন্তব্য </strong>
                            </div>
                            <div class="panel-body">
                                <div>
                                    {!! Form::textarea('location_remarks',!empty($appData->location_remarks) ? $appData->location_remarks:'',['class'=>'form-control input-sm', 'rows' => 2, 'cols' => 40]) !!}
                                </div>

                            </div>
                        </div>


                        <!--/panel-->

                    </fieldset>

                    <h3 class="text-center stepHeader">সংযুক্তি</h3>
                    <fieldset>
                        @include('NewConnectionBREB::documents')
                    </fieldset>

                    <h3 class="text-center stepHeader">বিবৃতি এবং দাখিল করুন</h3>
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading" style="padding-bottom: 4px;">
                                <strong>বিবৃতি</strong>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-striped">
                                    <thead class="alert alert-info">
                                    <tr>
                                        <th colspan="3" style="font-size: 15px">এর অনুমোদিত ব্যক্তি
                                            সংগঠন
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            {!! Form::label('auth_name','সম্পূর্ণ নাম :', ['class'=>'required-star'])
                                            !!}
                                            {!! Form::text('auth_name',
                                            \App\Libraries\CommonFunction::getUserFullName(), ['class' =>
                                            'form-control input-md required', 'readonly']) !!}
                                            {!! $errors->first('auth_name','<span
                                                class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::label('auth_email','ইমেইল :', ['class'=>'required-star']) !!}
                                            {!! Form::email('auth_email', Auth::user()->user_email, ['class' =>
                                            'form-control required input-sm email', 'readonly']) !!}
                                            {!! $errors->first('auth_email','<span
                                                class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::label('auth_cell_number','মোবাইল নম্বর :',
                                            ['class'=>'required-star']) !!}<br>
                                            {!! Form::text('auth_cell_number', Auth::user()->user_phone, ['class' =>
                                            'form-control input-sm required phone_or_mobile', 'readonly']) !!}
                                            {!! $errors->first('auth_cell_number','<span
                                                class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><strong>তারিখ : </strong><?php echo date('F d,Y')?></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms',
                                            'class'=>'required')) !!}
                                            আমি পল্লী বিদ্যুৎ সমিতি এর নিম্নে উল্লেখ্য পরিষেবা শর্তাবলীর সাথে একমত হচ্ছি
                                            ।
                                            ক) একই পরিবার/খানা এর বিদ্যুৎ সংযোগের জন্য একাধিক আবেদন করি নাই।
                                            খ) অনলাইন আবেদন পত্রে আমার নিজের মোবাইল নম্বর প্রদান করেছি।
                                            গ) অন্যের ঘরে সংযোগের জন্য আবেদন করি নাই।
                                            ঘ) সফলভাবে আবেদন সম্পন্ন হলে, সাত(৭) দিলের মধ্যে ঘর ওয়্যারিং করতঃ তা অনলাইনে
                                            কর্তৃপক্ষকে অবহিত করব।
                                            ঙ) আমি স্বজ্ঞানে কর্তৃপক্ষকে প্রদত্ত সকল তথ্যের সত্যায়ন করছি।
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>


                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="pull-left">
                                <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                        value="draft" name="actionBtn">Save as Draft
                                </button>
                            </div>

                            <div class="pull-left" style="padding-left: 1em;">
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md" value="Submit" name="actionBtn">
                                    Submit
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 button_last">
                            <div class="clearfix"></div>
                        </div>
                    </div> {{--row--}}

                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>

<script>


    var document_onload_status = 1;
    $(document).ready(function () {
        var form = $("#NewConnection").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
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
                if (currentIndex == 2) {
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

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            form.validate().settings.ignore = ":disabled,:hidden";
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=NewConnection@2'); ?>');
            } else {
                return false;
            }
        });

        {{----end step js---}}
        $("#NewConnection").validate({
            rules: {
                field: {
                    required: true,
                    email: true,

                }
            }
        });

        // Datepicker Plugin initialize
        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100)
        });

        var calculatedYear = (new Date).getFullYear() - 18;
        var currentMonth = (new Date).getMonth();
        var currentDay = (new Date).getDate();

        $('.datepickerDob').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 150),
            maxDate: new Date(calculatedYear, currentMonth, currentDay),
            ignoreReadonly: true
        });
        @if(!empty($appData->date_of_birth))
        $('.datepickerDob').find('input').val('{{$appData->date_of_birth}}');
        @else
        $('.datepickerDob').find('input').val('');
        @endif

        $('.datepickerFuture').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            maxDate: '01/01/' + (yyyy + 6)
        });

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

        $(document).on('blur', '#passport', function () {
            var passport = $('#passport').val()
            var regex = /[a-zA-Z]{2}[0-9]{7}/;
            if (passport) {
                if (regex.test(passport) && passport.length == 9) {
                    $(this).removeClass('error');
                    $('#national_id').removeClass('error required');
                    return true;
                } else {
                    $(this).addClass('error')
                    return false;
                }
            }
        })

        $(document).on('blur', '.telephone', function () {
            var telephone = $(this).val()
            if (telephone) {
                if (telephone.length > 11 || telephone.length < 7) {
                    $(this).addClass('error');
                    return false;
                } else {
                    $(this).removeClass('error');
                    return true;
                }
            }
        });


        $(document).on('blur', '.mobile', function () {
            var mobile_telephone = $(this).val();

            if (mobile_telephone.length > 15 || mobile_telephone.length < 11) {
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

        $('.va').on('keydown', function (e) {
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
    var loadFile = function (event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('output');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

    $(document).ready(function () {
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/new-connection-breb/get-refresh-token';

            $('#pbs_name').select2()
            $('#tariff_name').select2()
            $('#perm_dist').select2()
            $('#cur_district').select2()
            $('#land_owner_type').select2()
            $('#connection_type').select2()
            $('#phase').select2()
            $('#volt').select2()

            $('#pbs_name').keydown()
            $('#tariff_name').keydown()
            $('#perm_dist').keydown()
            $('#cur_district').keydown()
            // $('#tariff_name').trigger('change');

        });

        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: 'OSS_BIDA'
            },
        ]

        $('#pbs_name').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$breb_service_url}}/pbs";
            let selected_value = '{{ !empty($appData->pbs_name) ? $appData->pbs_name : '' }}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "pbS_CODE"; //dynamic id for callback
            let element_name = "pbS_NAME";//dynamic name for callback
            let data = ''; //Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, pbsCallbackResponse, arrays);

        })

        $("#pbs_name").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#zonal_office").html('<option value="">Please Wait...</option>');
            var pbs = $('#pbs_name').val();
            var pbsId = pbs.split("@")[0];
            if (pbsId) {
                let e = $(this);
                let api_url = "{{$breb_service_url}}/zonal-list-by-pbs-code/" + pbsId;
                let selected_value = '{{ !empty($appData->zonal_office) ? $appData->zonal_office : '' }}'; // for callback
                let calling_id = $(this).attr('id');
                let dependent_section_id = "zonal_office";// for callback
                let element_id = "zonaL_CODE"; //dynamic id for callback
                let element_name = "zonaL_NAME"; //dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, zonalCallbackResponseDependentSelect, arrays);

            } else {
                $("#zonal_office").html('<option value="">প্রথমে সমিতির নির্বাচন করুন</option>');
                $(self).next().hide();
            }

        })

        $('#tariff_name').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$breb_service_url}}/tariff";
            let selected_value = '{{ !empty($appData->tariff_name) ? $appData->tariff_name : '' }}';  // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "id"; //dynamic id for callback
            let element_name = "text";//dynamic name for callback
            let data = ''; //Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#perm_dist').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$breb_service_url}}/districts";
            let selected_value = '{{ !empty($appData->perm_dist) ? $appData->perm_dist : '' }}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "id"; //dynamic id for callback
            let element_name = "name"; //dynamic name for callback
            let data = ''; //Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, distCallbackResponse, arrays);

        })

        $("#perm_dist").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#perm_upazilla").html('<option value="">Please Wait...</option>');
            var perm_dist = $('#perm_dist').val();
            var perm_dist_id = perm_dist.split("@")[0];
            if (perm_dist_id) {
                let e = $(this);
                let api_url = "{{$breb_service_url}}/upazilla-=list-by-district-id/" + perm_dist_id;
                let selected_value = '{{ !empty($appData->perm_upazilla) ? $appData->perm_upazilla : '' }}'; // for callback
                let calling_id = $(this).attr('id');
                let dependent_section_id = "perm_upazilla";// for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, districtCallbackResponseUpazilaSelect, arrays);

            } else {
                $("#perm_upazilla").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>');
                $(self).next().hide();
            }

        })

        $("#perm_dist").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#perm_thana").html('<option value="">Please Wait...</option>');
            var perm_dist = $('#perm_dist').val();
            var perm_dist_id = perm_dist.split("@")[0];
            if (perm_dist_id) {
                let e = $(this);
                let api_url = "{{$breb_service_url}}/thana-list-by-district-id/" + perm_dist_id;
                let selected_value = '{{ !empty($appData->perm_thana) ? $appData->perm_thana : '' }}'; // for callback
                let calling_id = $(this).attr('id');
                let dependent_section_id = "perm_thana"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, districtCallbackResponseThanaSelect, arrays);

            } else {
                $("#perm_thana").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>');
                $(self).next().hide();
            }

        })

        $("#perm_upazilla").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#perm_union").html('<option value="">Please Wait...</option>');
            var perm_upazilla = $('#perm_upazilla').val();
            var perm_upazilla_id = perm_upazilla.split("@")[0];
            if (perm_upazilla_id) {
                let e = $(this);
                let api_url = "{{$breb_service_url}}/union-list-by-upazilla-id/" + perm_upazilla_id;
                let selected_value = '{{ !empty($appData->perm_union) ? $appData->perm_union : '' }}'; // for callback
                let calling_id = $(this).attr('id');
                let dependent_section_id = "perm_union"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, upazillaCallbackResponseUnionSelect, arrays);

            } else {
                $("#perm_union").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন </option>');
                $(self).next().hide();
            }

        })

        $("#perm_union").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#perm_village").html('<option value="">Please Wait...</option>');
            var perm_union = $('#perm_union').val();
            var perm_union_id = perm_union.split("@")[0];
            if (perm_union_id) {
                let e = $(this);
                let api_url = "{{$breb_service_url}}/village-list-by-union-id/" + perm_union_id;
                let selected_value = '{{ !empty($appData->perm_village) ? $appData->perm_village : '' }}'; // for callback
                let calling_id = $(this).attr('id');
                let dependent_section_id = "perm_village"; // for callback
                let element_id = "id";//dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, unionCallbackResponseVillageSelect, arrays);

            } else {
                $("#perm_village").html('<option value="">প্রথমে ইউনিয়ন নির্বাচন করুন </option>');
                $(self).next().hide();
            }

        })

        $('#cur_district').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$breb_service_url}}/districts";
            let selected_value = '{{ !empty($appData->cur_district) ? $appData->cur_district : '' }}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "id"; //dynamic id for callback
            let element_name = "name"; //dynamic name for callback
            let data = ''; //Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $("#cur_district").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#cur_upazilla").html('<option value="">Please Wait...</option>');
            var checkBox = $('#same_as_permanent').is(':checked');
            var selected_value = '';
            if (checkBox == true) {
                if (document_onload_status == 1) {
                    selected_value = '{{isset($appData->perm_upazilla) ? $appData->perm_upazilla : ''}}';
                } else {
                    selected_value = $("#perm_upazilla").val(); // for callback
                }
            } else {
                selected_value = '{{isset($appData->cur_upazilla) ? $appData->cur_upazilla : ''}}';
            }

            var cur_district = $('#cur_district').val();
            var cur_district_id = cur_district.split("@")[0];
            if (cur_district_id) {
                let e = $(this);
                let api_url = "{{$breb_service_url}}/upazilla-=list-by-district-id/" + cur_district_id;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "cur_upazilla"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, districtCallbackResponseUpazilaSelect, arrays);

            } else {
                $("#cur_upazilla").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>');
                $(self).next().hide();
            }

        })

        $("#cur_district").on("change", function () {

            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#cur_thana").html('<option value="">Please Wait...</option>');
            var checkBox = $('#same_as_permanent').is(':checked');
            var selected_value = '';
            if (checkBox == true) {
                if (document_onload_status == 1) {
                    selected_value = '{{isset($appData->perm_thana) ? $appData->perm_thana : ''}}';
                } else {
                    selected_value = $("#perm_thana").val(); // for callback
                }
            } else {
                selected_value = '{{isset($appData->cur_thana) ? $appData->cur_thana : ''}}';
            }


            var cur_district = $('#cur_district').val();
            var cur_district_id = cur_district.split("@")[0];
            if (cur_district_id) {
                let e = $(this);
                let api_url = "{{$breb_service_url}}/thana-list-by-district-id/" + cur_district_id;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "cur_thana"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, districtCallbackResponseThanaSelect, arrays);

            } else {
                $("#cur_thana").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>');
                $(self).next().hide();
            }

        })

        $("#cur_upazilla").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#cur_union").html('<option value="">Please Wait...</option>');

            var checkBox = $('#same_as_permanent').is(':checked');
            if (checkBox == true) {
                if (document_onload_status == 1) {
                    selected_value = '{{isset($appData->perm_union) ? $appData->perm_union : ''}}';
                } else {
                    selected_value = $("#perm_union").val(); // for callback
                }
            } else {
                selected_value = '{{isset($appData->cur_union) ? $appData->cur_union : ''}}';
            }

            var cur_upazilla = $('#cur_upazilla').val();
            var cur_upazilla_id = cur_upazilla.split("@")[0];
            if (cur_upazilla_id) {
                let e = $(this);
                let api_url = "{{$breb_service_url}}/union-list-by-upazilla-id/" + cur_upazilla_id;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "cur_union"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name";//dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, upazillaCallbackResponseUnionSelect, arrays);

            } else {
                $("#cur_union").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন </option>');
                $(self).next().hide();
            }

        })

        $("#cur_union").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#cur_village").html('<option value="">Please Wait...</option>');

            var checkBox = $('#same_as_permanent').is(':checked');
            if (checkBox == true) {
                if (document_onload_status == 1) {
                    selected_value = '{{isset($appData->perm_village) ? $appData->perm_village : ''}}';
                } else {
                    selected_value = $("#perm_village").val(); // for callback
                }
            } else {
                selected_value = '{{isset($appData->cur_village) ? $appData->cur_village : ''}}';
            }

            var cur_union = $('#cur_union').val();
            var cur_union_id = cur_union.split("@")[0];
            if (cur_union_id) {
                let e = $(this);
                let api_url = "{{$breb_service_url}}/village-list-by-union-id/" + cur_union_id;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "cur_village"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, unionCallbackResponseVillageSelect, arrays);

            } else {
                $("#cur_village").html('<option value="">প্রথমে ইউনিয়ন নির্বাচন করুন </option>');
                $(self).next().hide();
            }
            document_onload_status = 0;
        })


    })

    $(document).on('change', '#same_as_permanent', function () {
        let checkBox = $('#same_as_permanent').is(':checked');
        let perm_dist = $('#perm_dist').find(":selected").val();
        let perm_upazilla = $("#perm_upazilla").find(":selected").val();
        let perm_thana = $("#perm_thana").find(":selected").val();
        let perm_union = $("#perm_union").find(":selected").val();
        let perm_post = $("#perm_post").val();
        let perm_post_code = $("#perm_post_code").val();
        let perm_road_no = $("#perm_road_no").val();
        let perm_house_holding = $("#perm_house_holding").val();
        if (checkBox == true) {
            $("#cur_district").val(perm_dist);
            $("#cur_district").trigger("change");
            $("#cur_upazilla").val(perm_upazilla);
            $("#cur_thana").val(perm_thana);
            $("#cur_union").val(perm_union);
            $("#cur_village").val(perm_village);
            $("#cur_post").val(perm_post);
            $("#cur_post_code").val(perm_post_code);
            $("#cur_road_no").val(perm_road_no);
            $("#cur_house_holding").val(perm_house_holding);
        } else {
            $("#cur_district").val('')
            $("#cur_district").trigger('change');
            $('#cur_union option[value!=""]').remove();
            $('#cur_upazilla option[value!=""]').remove();
            $('#cur_village option[value!=""]').remove();
            $("#cur_post").val('');
            $("#cur_post_code").val('');
            $("#cur_road_no").val('');
            $("#cur_house_holding").val('');
        }

    })

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">নির্বাচন করুন</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = (data == '' || data == null) ? row[element_id] + '@' + row[element_name] : (row[element_id] + '@' + row[data] + '@' + row[element_name])
                let value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            })
        } else {
            console.log(response.status);
        }
        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $("#" + calling_id).trigger('change');
    }

    function distCallbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">নির্বাচন করুন</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = (data == '' || data == null) ? row[element_id] + '@' + row[element_name] : (row[element_id] + '@' + row[data] + '@' + row[element_name])
                let value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            })
        } else {
            console.log(response.status);
        }
        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $("#" + calling_id).trigger('change');
    }

    function pbsCallbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">নির্বাচন করুন</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = (data == '' || data == null) ? row[element_id] + '@' + row[element_name] : (row[element_id] + '@' + row[data] + '@' + row[element_name])
                let value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            })
        } else {
            console.log(response.status);
        }
        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $("#" + calling_id).trigger('change');
    }

    function zonalCallbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">নির্বাচন করুন</option>'
        if (response.responseCode === 200) {
            let pbsCode = response.data.pbsCode;
            $.each(response.data.zonalList, function (key, row) {
                let id = row[element_id] + '@' + pbsCode + '@' + row[element_name];
                let value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            })
        } else {
            console.log(response.status);
        }
        $("#" + dependent_section_id + " option[value]").remove();
        $("#" + dependent_section_id).append(option).trigger('change');
        $("#" + dependent_section_id).select2();
        $("#" + calling_id).next().hide();
        $(".loading_data").hide();
        $(".select2").css('display', 'block');
    }

    function districtCallbackResponseUpazilaSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">নির্বাচন করুন</option>';
        if (response.responseCode === 200) {
            $.each(response.data.upazillaList, function (key, row) {
                let id = row[element_id] + '@' + row[element_name];
                let value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id + " option[value]").remove();
        $("#" + dependent_section_id).append(option).trigger('change');
        $("#" + dependent_section_id).select2();
        $("#" + calling_id).next().hide();
        $(".loading_data").hide();
        $(".select2").css('display', 'block');
    }

    function districtCallbackResponseThanaSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">নির্বাচন করুন</option>';
        if (response.responseCode === 200) {
            $.each(response.data.thanaList, function (key, row) {
                let id = row[element_id] + '@' + row[element_name];
                let value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id + " option[value]").remove();
        $("#" + dependent_section_id).append(option).trigger('change');
        $("#" + dependent_section_id).select2();
        $("#" + calling_id).next().hide();
        $(".loading_data").hide();
        $(".select2").css('display', 'block');
    }

    function upazillaCallbackResponseUnionSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">নির্বাচন করুন</option>'
        if (response.responseCode === 200) {
            $.each(response.data.unionList, function (key, row) {
                let id = row[element_id] + '@' + row[element_name];
                let value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            })
        } else {
            console.log(response.status);
        }
        $("#" + dependent_section_id + " option[value]").remove();
        $("#" + dependent_section_id).append(option).trigger('change');
        $("#" + dependent_section_id).select2();
        $("#" + calling_id).next().hide();
        $(".loading_data").hide();
        $(".select2").css('display', 'block');
    }

    function unionCallbackResponseVillageSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">নির্বাচন করুন</option>';
        if (response.responseCode === 200) {
            $.each(response.data.villageList, function (key, row) {
                let id = row[element_id] + '@' + row[element_name];
                let value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id + " option[value]").remove();
        $("#" + dependent_section_id).append(option).trigger('change');
        $("#" + dependent_section_id).select2();
        $("#" + calling_id).next().hide();
        $(".loading_data").hide();
        $(".select2").css('display', 'block');
    }

    $('#same_as_permanent').trigger('change');


</script>