<?php
$accessMode = ACL::getAccsessRight('LsppCDA');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
$yesno = ['হ্যাঁ' => 'হ্যাঁ', 'না ' => 'না '];
$yesnona = ['হ্যাঁ' => 'হ্যাঁ', 'না ' => 'না ', 'অপ্রযোজ্য' => 'প্রযোজ্য নয়'];
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>

    .wizard > .content, .wizard, .tabcontrol {
        overflow: visible;
    }

    .form-group {
        margin-bottom: 5px;
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


    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

        .siteDivLR {
            margin-top: 12px;
            margin-right: 8px;
        }

        .siteDivFB {
            margin-top: 12px;
            margin-right: 8px;
        }

        .pull-right {
            float: none !important;
        }

        .pull-left {
            float: none !important;
        }

        .text-right {
            text-align: left !important;
        }
    }

    @media screen and (min-width: 350px) {

        .siteDivLR {
            margin-top: 12px;
        }

        .siteDivFB {
            margin-top: 12px;
        }


    }
</style>

<div class="col-md-12">
    @include('message.message')
</div>
<div class="col-md-12">
    <div class="panel panel-primary" id="inputForm">
        <div class="panel-heading">
            <h5><strong>বৃহদায়তন বা বিশেষ ধরনের প্রকল্পের জন্য বিশেষ প্রকল্প ছাড়পত্রের আবেদন</strong></h5>
        </div>

        {!! Form::open(array('url' => 'cda-lspp/store','method' => 'post', 'class' => 'form-horizontal', 'id' => 'LsppCDAForm',
        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
        <input type="hidden" name="selected_file" id="selected_file"/>
        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
        <input type="hidden" name="isRequired" id="isRequired"/>
        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"
               id="app_id"/>

        <h3 class="text-center stepHeader">Details Information</h3>
        <fieldset>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4" style="margin-bottom: 10px;">
                        {!! Form::label('land_use_category_id','Land use :',['class'=>'text-left col-md-4 ']) !!}
                        <div class="col-md-8">
                            {!! Form::select('land_use_category_id', [], '', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div id="land_use_sub" class="col-md-8 col-md-offset-2" style="margin-bottom: 10px;">

                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('applicant_name','১। আবেদনকারীর নাম :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_name', !empty($appData->applicant_name) ? $appData->applicant_name:'',['class' => 'form-control bigInputField input-md','size'=>'5x1','maxlength'=>'200']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            {!! Form::label('applicant_mobile_no',' ১.২। আবেদনকারীর মোবাইল নং :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_mobile_no', !empty($appData->applicant_mobile_no) ? $appData->applicant_mobile_no:'',['class' => 'form-control onlyNumber engOnly input-md mobile', 'placeholder'=>'e.g. 01756656565']) !!}
                                <span style="color: #990000;">(পরবর্তিতে প্রদত্ত এই নম্বরটিতে আবেদনের বিষয়ে তথ্য প্রদান বা যোগাযোগ করা হবে)</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {!! Form::label('applicant_email',' ১.৩। আবেদনকারীর ইমেইল :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_email', !empty($appData->applicant_email) ? $appData->applicant_email:'',['class' => 'form-control input-md email']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('applicant_nid_no',' ১.৪। আবেদনকারীর  জাতীয় পরিচয়পত্র:',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_nid_no', !empty($appData->applicant_nid_no) ? $appData->applicant_nid_no:'',['class' => 'form-control onlyNumber engOnly input-md', 'id'=>'applicant_nid_no']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            {!! Form::label('applicant_tin_no',' ১.৫। আবেদনকারীর টি.ই.ন নং:',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_tin_no', !empty($appData->applicant_tin_no) ? $appData->applicant_tin_no:'',['class' => 'form-control onlyNumber engOnly input-md','id'=>'applicant_tin_no']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('applicant_present_address','২। বর্তমান ঠিকানা :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::textarea('applicant_present_address', !empty($appData->applicant_present_address) ? $appData->applicant_present_address:'',['class' => 'form-control input-md ']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('suggested_use_land_plot','৩।জমি/প্লট এর প্রস্তাবিত ব্যবহার :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::textarea('suggested_use_land_plot', !empty($appData->suggested_use_land_plot) ? $appData->suggested_use_land_plot:'',['class' => 'form-control input-md']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--first panel end--}}

            {{--second start --}}
            <div class="panel panel-info">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::label('','৪। প্রস্তাবিত জমি/প্লট এর অবসথান ও পরিমাণ :',['style'=>'margin-top:10px;margin-bottom:20px;']) !!}

                        <div class="form-group col-xs-12">
                            <div class="row ">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        {!! Form::label('city_corporation_id','ক) সিটি কর্পোরেশন/পৌরসভা/গ্রাম/মহল্লা :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('city_corporation_id',  [], '', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('bs_code','খ) বি. এস :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('bs_code', !empty($appData->bs_code) ? $appData->bs_code:'',['class' => 'form-control input-md ']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        {!! Form::label('rs_code','গ) আর. এস  :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('rs_code', !empty($appData->rs_code) ? $appData->rs_code:'',['class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('thana_name','ঘ) থানার নাম :',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('thana_name',  [],'', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        {!! Form::label('mouza_name','ঙ) মৌজা নাম :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('mouza_name',  [],'', ['placeholder' => 'Select Thana First',
                                            'class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('block_no','চ) ব্লক নং :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('block_no',  [],'', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        {!! Form::label('seat_no','ছ) সিট নং :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('seat_no',  [],'', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('ward_no','জ) ওয়াড নং :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('ward_no',  [],'', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        {!! Form::label('sector_no','ঝ) সেক্টর নং :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('sector_no',  [],'', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('road_name','ঞ) রাস্তার নাম :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('road_name', !empty($appData->road_name) ? $appData->road_name:'',['class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        {!! Form::label('arm_size_land_plot_amount','ট) বাহূ মাপ সহ জমি/প্লটের পরিমাণ :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('arm_size_land_plot_amount', !empty($appData->arm_size_land_plot_amount) ? $appData->arm_size_land_plot_amount:'',['class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('existing_house_plot_land_details','ঠ) জমি/প্লট এ বিদ্যমান বাড়ি/ কাঠামোর বিবরণ :',['class'=>'text-left col-md-5 ']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('existing_house_plot_land_details', !empty($appData->existing_house_plot_land_details) ? $appData->existing_house_plot_land_details:'',['class' => 'form-control input-md']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            {{-- second end--}}

            {{-- 3rd start --}}

            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('','৫। প্রযোজ্য ক্ষেত্রে ভূমি ব্যবহার ছাড়পত্র নম্বর (কপি সংযুক্ত ) :',['style'=>'margin-top:10px;margin-bottom:10px;']) !!}

                    <div class="col-md-12">
                        {!! Form::text('suggested_use_land_plot', !empty($appData->suggested_use_land_plot) ? $appData->suggested_use_land_plot:'',['class' => 'form-control input-md ']) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('','৬। প্রস্তাবিত উন্নয়ন কার্যের প্রকার/ প্রকারসমূহ (পরিশিষ্ট-৩ এর বর্ণনা অনুসারে উল্লেখ্য ) :',['style'=>'margin-top:10px;margin-bottom:10px;']) !!}

                    <div class="col-md-12">
                        {!! Form::text('proposed_dev_work_type',  !empty($appData->proposed_dev_work_type) ? $appData->proposed_dev_work_type:'',['class' => 'form-control input-md']) !!}

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    {!! Form::label('','৭। প্রস্তাবিত ব্যবহারের বিস্তারিত বর্ণনা :',['style'=>'margin-top:10px;margin-bottom:10px;']) !!}

                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="col-md-6">
                                    {!! Form::label('land_area','ক) জমির/প্লট এর ক্ষেত্রফল (বর্গমিটার) ',['class'=>'text-left col-md-7']) !!}
                                    <div class="col-md-5">
                                        {!! Form::text('land_area', !empty($appData->land_area) ? $appData->land_area:'',['class' => 'form-control input-md enbnNumber']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('max_floor_area','খ) যে কোন তলার / ফ্লোরের সর্বোচ্চ ক্ষেত্রফল (বর্গমিটার)',['class'=>'text-left col-md-7 ']) !!}
                                    <div class="col-md-5">
                                        {!! Form::text('max_floor_area', !empty($appData->max_floor_area) ? $appData->max_floor_area:'',['class' => 'form-control input-md enbnNumber']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="col-md-6">
                                    {!! Form::label('city_corporation_id','গ) সর্বমোট ফ্লোরের ক্ষেত্রফল (বর্গমিটার) ',['class'=>'text-left col-md-7 ']) !!}
                                    <div class="col-md-5">
                                        {!! Form::text('total_floor_area', !empty($appData->total_floor_area) ? $appData->total_floor_area:'',['class' => 'form-control input-md enbnNumber']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('total_plinth_floor','ঘ) প্লিন্থ (Plinth) এর উপর সর্বমোট ফ্লোরের সংখ্যা',['class'=>'text-left col-md-7 ']) !!}
                                    <div class="col-md-5">
                                        {!! Form::text('total_plinth_floor', !empty($appData->total_plinth_floor) ? $appData->total_plinth_floor:'',['class' => 'form-control input-md enbnNumber']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="col-md-6">
                                    {!! Form::label('basement_floor_no','ঙ) বেসমেণ্ট ফ্লোর/ফ্লোরের সংখ্যা ',['class'=>'text-left col-md-7 ']) !!}
                                    <div class="col-md-5">
                                        {!! Form::text('basement_floor_no', !empty($appData->basement_floor_no) ? $appData->basement_floor_no:'',['class' => 'form-control enbnNumber input-md ']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('total_residential_flat_no','চ) আবাসিক ভবনের ক্ষেত্রে মোট আবাস/এ্যাপাটমেণ্ট /ফ্ল্যাটের সংখ্যা',['class'=>'text-left col-md-7']) !!}
                                    <div class="col-md-5">
                                        {!! Form::text('total_residential_flat_no', !empty($appData->total_residential_flat_no) ? $appData->total_residential_flat_no:'',['class' => 'form-control input-md']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="col-md-6">
                                    {!! Form::label('','জ) বিভিন্ন প্রকার ব্যবহারের উদ্দেশ্যে ফ্লোরের আয়তন ',['class'=>'text-left col-md-10 ']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-xs-12">
                        <div class="row">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="col-md-3 col-xs-6">
                                    {!! Form::label('other_usage_1','ব্যবহার -১(বর্গমিটার):',['class'=>'col-md-12']) !!}
                                    {!! Form::text('other_usage_1', !empty($appData->other_usage_1) ? $appData->other_usage_1:'',['class' => 'form-control enbnNumber input-md']) !!}
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    {!! Form::label('other_usage_2','ব্যবহার -২(বর্গমিটার):',['class'=>'col-md-12']) !!}
                                    {!! Form::text('other_usage_2', !empty($appData->other_usage_2) ? $appData->other_usage_2:'',['class' => 'form-control enbnNumber input-md ']) !!}
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    {!! Form::label('other_usage_3','ব্যবহার -৩(বর্গমিটার):',['class'=>'col-md-12']) !!}
                                    {!! Form::text('other_usage_3', !empty($appData->other_usage_3) ? $appData->other_usage_3:'',['class' => 'form-control enbnNumber col-md-6 input-md ']) !!}
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    {!! Form::label('other_usage_4','ব্যবহার -৪(বর্গমিটার):',['class'=>'col-md-12']) !!}
                                    {!! Form::text('other_usage_4', !empty($appData->other_usage_4) ? $appData->other_usage_4:'',['class' => 'form-control enbnNumber col-md-6 input-md ']) !!}
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    {!! Form::label('other_usage_5','ব্যবহার -৫(বর্গমিটার):',['class'=>'col-md-12']) !!}
                                    {!! Form::text('other_usage_5', !empty($appData->other_usage_5) ? $appData->other_usage_5:'',['class' => 'form-control enbnNumber col-md-6 input-md ']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="margin-top:15px;">
                    {!! Form::label('site_side_main_road','৮। সাইট সংলগ্ন রাস্তাটি একটি প্রধান সড়ক :',['class'=>'col-md-4','style'=>'margin-top:10px;margin-bottom:20px;']) !!}
                    <div class="col-md-4">
                        {!! Form::select('site_side_main_road',$yesno,!empty($appData->site_side_main_road) ? $appData->site_side_main_road:'', ['placeholder' => 'Select One', 'class' => 'form-control col-md-5']) !!}
                    </div>
                    <div class="col-md-11" style="margin-top:15px;">
                        <label class="col-md-4 col-xs-12" for="email">৮.১ সাইট সংলগ্ন রাস্তা বা রাস্তাসমুহের প্রস্থ
                            :</label>
                        <div class="col-md-8" style="margin-top:-10px;">
                            <div class="row ">
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-2 siteDivFB">
                                            সম্মুখে
                                        </div>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('front_road_area', !empty($appData->front_road_area) ? $appData->front_road_area:'',['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <div class="col-md-3 col-xs-4" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-2 siteDivFB">
                                            পিছনে
                                        </div>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('back_road_area', !empty($appData->back_road_area) ? $appData->back_road_area:'',['class' => 'form-control input-md enbnNumber']) !!}
                                        </div>
                                        <div class="col-md-3 col-xs-4" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-2 siteDivLR">
                                            বাঁয়ে
                                        </div>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('left_road_area', !empty($appData->left_road_area) ? $appData->left_road_area:'',['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <div class="col-md-3 col-xs-4" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-2 siteDivLR">
                                            ডানে
                                        </div>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('right_road_area', !empty($appData->right_road_area) ? $appData->right_road_area:'',['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <div class="col-md-3 col-xs-4" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="margin-top:15px;">
                    <label class="col-md-3 col-xs-12" for="email">৯। প্রস্তাবিত সাইটের মধ্যে অবস্থানঃ</label>
                    <div class="col-md-8 col-xs-12" style="margin-top:-10px;">
                        <div class="row">
                            <div class="col-md-4 col-xs-4">
                                <div class="row">
                                    <div class="col-md-5" style="margin-right:-1px;margin-top:12px;">
                                        প্রাকৃতিক বনাঞ্চল
                                    </div>
                                    <div class="col-md-7" style="margin-top:5px;">
                                        {!! Form::select('natural_forrest',$yesno ,!empty($appData->natural_forrest) ? $appData->natural_forrest:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-4">
                                <div class="row">
                                    <div class="col-md-4" style="margin-right:-1px;margin-top:12px;">
                                        পাহাড়
                                    </div>
                                    <div class="col-md-8" style="margin-top:5px;">
                                        {!! Form::select('mountain',$yesno ,!empty($appData->mountain) ? $appData->mountain:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-4">
                                <div class="row">
                                    <div class="col-md-4" style="margin-right:-10px;margin-top:12px;">
                                        ঢাল
                                    </div>
                                    <div class="col-md-8" style="margin-top:5px;">
                                        {!! Form::select('slope',$yesno ,!empty($appData->slope) ? $appData->slope:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="margin-top:15px;">
                    <label class="col-md-3 col-xs-12" for="email">১০।প্রস্তাবিত সাইটের মধ্যে অবস্থানঃ</label>
                    <div class="col-md-8 col-xs-12" style="margin-top:-10px;">
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <div class="row">
                                    <div class="col-md-4" style="margin-right:-1px;margin-top:12px;">
                                        পুকুর
                                    </div>
                                    <div class="col-md-8 " style="margin-top:5px;">
                                        {!! Form::select('pond',$yesno ,!empty($appData->pond) ? $appData->pond:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <div class="row">
                                    <div class="col-md-4" style="margin-right:-1px;margin-top:12px;">
                                        প্রাকৃতিক জলাভূমি
                                    </div>
                                    <div class="col-md-8" style="margin-top:5px;">
                                        {!! Form::select('natural_wetlands',$yesno ,!empty($appData->natural_wetlands) ? $appData->natural_wetlands:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="margin-top:15px;">
                    <label class="col-md-10 " for="email">১১।প্রস্তাবিত সাইটে ২৫০
                        মিটার
                        দূরত্বের অন্তভূক্ত কোন
                        স্থপতিক গুনাগুনসম্পন্ন:</label>
                </div>
            </div>

            <div class="row col-xs-12">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 col-xs-6">
                            <div class="row">
                                <div class="col-md-3 col-md-offset-1" style="margin-right:-10px;margin-top:12px;">
                                    ভবন
                                </div>
                                <div class="col-md-8" style="margin-top:5px;">
                                    {!! Form::select('building',$yesno ,!empty($appData->building) ? $appData->building:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="row">
                                <div class="col-md-5" style="margin-right:-1px;margin-top:12px;">
                                    ঐতিহাসিক গুনাগুনসম্পন্ন ভবন
                                </div>
                                <div class="col-md-7" style="margin-top:5px;">
                                    {!! Form::select('historic_building',$yesno ,!empty($appData->historic_building) ? $appData->historic_building:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="row">
                                <div class="col-md-5" style="margin-right:-1px;margin-top:12px;">
                                    সাইট সংলগ্ন কোন হ্রদ
                                </div>
                                <div class="col-md-7" style="margin-top:5px;">
                                    {!! Form::select('site_side_lake',$yesno ,!empty($appData->site_side_lake) ? $appData->site_side_lake:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="row">
                                <div class="col-md-5" style="margin-right:-10px;margin-top:12px;">
                                    পাশ্বে পাক প্রভৃতি
                                </div>
                                <div class="col-md-7" style="margin-top:5px;">
                                    {!! Form::select('site_side_park',$yesno ,!empty($appData->site_side_park) ? $appData->site_side_park:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 " style="margin-top:25px;">
                    <label class="col-md-4" for="email">১২। প্রস্তাবিত সাইট দৃশ্যগত বৈশিষ্টপূণ
                        এলাকায়ঃ</label>
                    <div class="col-md-4" style="margin-top:-10px;">
                        {!! Form::select('site_in_visually_characteristics_area',$yesno ,!empty($appData->site_in_visually_characteristics_area) ? $appData->site_in_visually_characteristics_area:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12" style="margin-top:15px;">
                    <label class="col-md-10" for="email">
                        ১৩।প্রস্তাবিত সাইটের পার্শ্বে অবস্থিত</label>
                </div>
            </div>

            <div class="form-group col-xs-12">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-1"
                                     style="margin-top:12px;">
                                    বিমানবন্দর
                                </div>
                                <div class="col-md-7" style="margin-top:5px;">
                                    {!! Form::select('airport',$yesno ,!empty($appData->airport) ? $appData->airport:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <div class="row">
                                <div class="col-md-5" style="margin-top:12px;">
                                    রেলওয়ে স্টশন
                                </div>
                                <div class="col-md-6" style="margin-top:5px;">
                                    {!! Form::select('railway_station',$yesno ,!empty($appData->railway_station) ? $appData->railway_station:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <div class="row">
                                <div class="col-md-4" style="margin-top:12px;">
                                    বাস টার্মিনাল
                                </div>
                                <div class="col-md-6" style="margin-top:5px;">
                                    {!! Form::select('bus_terminal',$yesno ,!empty($appData->bus_terminal) ? $appData->bus_terminal:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <div class="row">
                                <div class="col-md-4" style="margin-top:12px;">
                                    নদী বন্দর
                                </div>
                                <div class="col-md-6" style="margin-top:5px;">
                                    {!! Form::select('river_port',$yesno ,!empty($appData->river_port) ? $appData->river_port:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12 " style="margin-top:15px;">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-8 col-xs-12">
                                <label class="" for="email">১৪। প্রস্তাবিত সাইট বর্ন্যাপ্রবন এলাকায় :</label>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                {!! Form::select('flood_prone_area',$yesno ,!empty($appData->flood_prone_area) ? $appData->flood_prone_area:'',['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-7 col-xs-12">
                                <label class="" for="email">এলাকা সংলগ্ন রাস্তার কেন্দ্র হইতে সাইটের অবস্থিত
                                    গড়:</label>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                {!! Form::select('road_center_to_site',['উঁচু '=>'উঁচু ','নিচু '=>'নিচু'], !empty($appData->road_center_to_site) ? $appData->road_center_to_site:'',['class' => 'form-control input-md ','placeholder'=>'Select One']) !!}
                            </div>
                            <div class="col-md-2 col-xs-4">
                                {!! Form::text('road_center_to_site_meter', !empty($appData->road_center_to_site_meter) ? $appData->road_center_to_site_meter:'',['class' => 'form-control input-md enbnNumber']) !!}
                            </div>
                            <div class="col-md-1 col-xs-2">
                                <label class="" for="email">মিটার</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-8 col-xs-12">
                                <label class="" for="email">১৫। প্রস্থবিত সাইটের অবস্থিত বর্তমান ইমারতের সংখ্যা:</label>
                            </div>
                            <div class="col-md-3 col-xs-4 ">
                                {!! Form::text('total_building_site', !empty($appData->total_building_site) ? $appData->total_building_site:'',['class' => 'form-control input-md enbnNumber']) !!}
                            </div>
                            <label class="col-md-1 col-xs-2">টি</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-7 col-xs-12">
                                <label>এবং তাহার সর্বমোট মেঝের ক্ষেত্রফল:</label>
                            </div>
                            <div class="col-md-4 col-xs-3">
                                {!! Form::text('buildings_total_floor_area', !empty($appData->buildings_total_floor_area) ? $appData->buildings_total_floor_area:'',['class' => 'form-control input-md enbnNumber']) !!}
                            </div>
                            <label class="col-md-1 col-xs-2">বর্গমিটার</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <label for="email"> ১৬। সর্বমোট প্রয়োজনীয় বিদুৎ এর চাহিদা</label>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                {!! Form::text('total_electricity_demand', !empty($appData->total_electricity_demand) ? $appData->total_electricity_demand:'',['class' => 'form-control input-md enbnNumber']) !!}
                            </div>
                            <label class="col-md-4 col-xs-8">ওয়াট/কিলোওয়াট (আনুমানিক)</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-5 col-xs-12">
                                <label class="" for="email">
                                    ১৭। সর্বমোট পানির চাহিদা</label>
                            </div>
                            <div class="col-md-3 col-xs-4">
                                {!! Form::text('total_water_demand', !empty($appData->total_water_demand) ? $appData->total_water_demand:'',['class' => 'form-control input-md enbnNumber']) !!}
                            </div>
                            <label class="col-md-4 col-xs-8">লিটার/কিলোলিটার (আনুমানিক)</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5 col-xs-12">
                                <label for="email"> ১৮। প্রস্তাবিত উন্নয়নকার্য সম্পূর্ণভাবে </label>
                            </div>
                            <div class="col-md-3 col-xs-4">
                                {!! Form::text('total_development_time_in_month', !empty($appData->total_development_time_in_month) ? $appData->total_development_time_in_month:'',['class' => 'form-control input-md enbnNumber']) !!}
                            </div>
                            <label class="col-md-4 col-xs-8">মাসের মধ্যে সম্পন্ন হইবে</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-5 col-xs-4">
                                <label>এবং উন্নয়নকার্যকে</label>
                            </div>
                            <div class="col-md-4 col-xs-4">
                                {!! Form::text('total_development_stage', !empty($appData->total_development_stage) ? $appData->total_development_stage:'',['class' => 'form-control input-md enbnNumber']) !!}
                            </div>
                            <label class="col-md-3 col-xs-4">ধাপে এবং </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <div class="col-md-6 col-xs-3">
                                {!! Form::text('total_development_stage_month', !empty($appData->total_development_stage_month) ? $appData->total_development_stage_month:'',['class' => 'form-control input-md enbnNumber']) !!}
                            </div>
                            <label class="col-md-6 col-xs-2">মাসের</label>
                        </div>
                    </div>
                    <label class="col-md-6 col-xs-6" for="email"> মাঝে বিভক্ত কর হইবে।</label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label for="email">১৯। নির্মিতব্য Covered Area এর বিবরণ ( প্রয়োজনে তালিকাটি বিস্তৃত করা
                        যাইতে পারে)</label>
                </div>
                <div class="col-md-10 col-md-offset-1 col-xs-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="text-center">ব্যবহার-১( বর্গমিটার)</th>
                            <th class="text-center">ব্যবহার-২( বর্গমিটার)</th>
                            <th class="text-center">ব্যবহার-৩( বর্গমিটার)</th>
                            <th class="text-center">মোট ফ্লোর( বর্গমিটার)</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="coveredAreaDetails">
                        @if(count($appData->total_floor) > 0)
                            <?php $inc = 0; ?>
                            @foreach($appData->total_floor as $key => $value)
                                <?php $lkey = ($key + 1); ?>
                                <tr id="coveredAreaDetailsRow_{{$inc}}">
                                    <td width="20%"> {!! Form::text('floor_no[]',!empty($appData->floor_no[$key]) ? $appData->floor_no[$key]:'',['readonly'=>'readonly','class'=>'floorNo form-control ','style'=>'outline: none; border:none; box-shadow:none;cursor: context-menu;','id'=>'floorNo_'.$lkey]) !!}</td>
                                    <td>{!! Form::text('usage_1[]',!empty($appData->usage_1[$key]) ? $appData->usage_1[$key]:'',['class' => 'form-control input-md enbnNumber', 'id'=>'usage'.$lkey ]) !!}</td>
                                    <td>{!! Form::text('usage_2[]',!empty($appData->usage_2[$key]) ? $appData->usage_2[$key]:'',['class' => 'form-control input-md enbnNumber', 'id'=>'usage'.$lkey ]) !!}</td>
                                    <td>{!! Form::text('usage_3[]',!empty($appData->usage_3[$key]) ? $appData->usage_3[$key]:'',['class' => 'form-control input-md enbnNumber', 'id'=>'usage3'.$lkey ]) !!}</td>
                                    <td>{!! Form::text('total_floor[]',!empty($appData->total_floor[$key]) ? $appData->total_floor[$key]:'',['class' => 'form-control input-md enbnNumber required', 'id'=>'total_floor_'.$lkey]) !!}</td>
                                    <td style="vertical-align: middle; text-align: center">
                                        <?php if ($inc == 0) { ?>
                                        <a class="btn btn-sm btn-primary addTableRows"
                                           title="Add more"
                                           onclick="addTableRowCDA('coveredAreaDetails', 'coveredAreaDetailsRow_0');">
                                            <i class="fa fa-plus"></i></a>

                                        <?php } else { ?>
                                        <a href="javascript:void(0);"
                                           class="btn btn-sm btn-danger removeRow"
                                           onclick="removeTableRow('coveredAreaDetails', 'coveredAreaDetailsRow_{{$inc}}');">
                                            <i class="fa fa-times" aria-hidden="true"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php $inc++; ?>
                            @endforeach
                        @else
                            <tr id="coveredAreaDetailsRow_0">
                                <td width="20%"> {!! Form::text('floor_no[]','বেসমেন্ট',['readonly'=>'readonly','class'=>'floorNo form-control ','style'=>'outline: none; border:none; box-shadow:none;cursor: context-menu;','id'=>'floorNo_1']) !!}</td>
                                <td>{!! Form::text('usage_1[]','',['class' => 'form-control input-md enbnNumber required', 'id'=>'usage1']) !!}</td>
                                <td>{!! Form::text('usage_2[]','',['class' => 'form-control input-md enbnNumber required', 'id'=>'usage2']) !!}</td>
                                <td>{!! Form::text('usage_3[]','',['class' => 'form-control input-md enbnNumber required', 'id'=>'usage3']) !!}</td>
                                <td>{!! Form::text('total_floor[]','',['class' => 'form-control input-md enbnNumber required', 'id'=>'total_floor_1']) !!}</td>
                                <td style="vertical-align: middle; text-align: center">
                                    <a class="btn btn-sm btn-primary addTableRows"
                                       title="Add more LOAD DETAILS"
                                       onclick="addTableRowCDA('coveredAreaDetails', 'coveredAreaDetailsRow_0');">
                                        <i class="fa fa-plus"></i></a>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    {!! Form::label('','২০। বিশেষ প্রকল্পের ছাড়পত্রের জন্য প্রেশকৃত তথ্যাবলী/দলিলাদি ও নকশার তালিকা :',['style'=>'margin-top:10px;margin-bottom:10px;']) !!}
                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1" style="margin-top:10px;">
                                {!! Form::label('owner_purchase_deed','  ২০.১ ।স্বত্বাধিকারির ইজারা দলিল/ক্রয় দলিল/হেবা/অন্যান্য :',['class'=>'text-left']) !!}
                                <div class="col-md-2">
                                    {!! Form::select('owner_purchase_deed',$yesnona, !empty($appData->owner_purchase_deed) ? $appData->owner_purchase_deed:'',['class' => 'form-control col-md-2 input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1" style="margin-top:10px;">
                                {!! Form::label('govt_assigned_land_deed','২০.২।সরকার কর্তৃক বরাদ্দকৃত ভূমি/জমি হইলে দলিলাদি ও অনুমতিপত্র :',['class'=>'text-left']) !!}
                                <div class="col-md-2">
                                    {!! Form::select('govt_assigned_land_deed',$yesnona, !empty($appData->govt_assigned_land_deed) ? $appData->govt_assigned_land_deed:'',['class' => 'form-control col-md-2 input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1" style="margin-top:10px;">
                                {!! Form::label('paid_fee_and_prove','২০.৩।প্রদেয় ফি এর প্রমানপত্র :',['class'=>'text-left']) !!}
                                <div class="col-md-2">
                                    {!! Form::select('paid_fee_and_prove',$yesnona, !empty($appData->paid_fee_and_prove) ? $appData->paid_fee_and_prove:'',['class' => 'form-control col-md-2 input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1" style="margin-top:10px;">
                                {!! Form::label('land_usage_exemption',' ২০.৪। ভূমি ব্যবহারের ছাড়পত্র :',['class'=>'text-left']) !!}
                                <div class="col-md-2">
                                    {!! Form::select('land_usage_exemption',$yesnona, !empty($appData->land_usage_exemption) ? $appData->land_usage_exemption:'',['class' => 'form-control col-md-2 input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1" style="margin-top:10px;">
                                {!! Form::label('far_calculation','২০.৫। FAR এর হিসাব :',['class'=>'text-left']) !!}
                                <div class="col-md-2">
                                    {!! Form::select('far_calculation',$yesnona, !empty($appData->far_calculation) ? $appData->far_calculation:'',['class' => 'form-control col-md-2 input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1" style="margin-top:10px;">
                                {!! Form::label('all_design_and_documents_detail','২০.৬ বিধি অনুযায়ী সকল নকশা ও দলিলাদি বিবরন:',['class'=>'text-left']) !!}
                                <div class="col-md-2">
                                    {!! Form::select('all_design_and_documents_detail',$yesnona, !empty($appData->all_design_and_documents_detail) ? $appData->all_design_and_documents_detail:'',['class' => 'form-control col-md-2 input-md','placeholder'=>'Select One']) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1" style="margin-top:10px;">
                                <hr>
                                {!! Form::label('city_corporation_id','
আমি/আমরা প্রত্যয়ন করিতেছি য,উপরে উল্লেখিত তথ্যসমূহ চট্টগ্রাম মহানগর ইমারত (নির্মাণ,উন্নয়ন,সংরক্ষণ ও অপসারন) বিধিমালা,২০০৮ এর বিধিতে বর্ণিত বিষয়াদির উপযুক্ততা পূরণ করে এবং আমার/আমাদের জ্ঞান অনুযায়ী প্রদত্ত তথ্যাবলী সঠিক। অনুমোদিত হওয়ার পর যে কোন ভুল তথ্য বা অসামাঞ্জতার কারনে অথবা সরকারের যে কোন প্রয়োজনে ভবিষ্যতে কর্তৃপক্ষ এই বিষয়ে ছাড়পত্র বাতিল করিতে পারবে। তাছাড়া এই বিধিমালার আওতায় অন্য যে কোন তথ্যাবলী বা দলিলাদি প্রদানেও বাধ্য থাকিব।',['class'=>'text-left ']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-xs-12">
                        <div class="row">
                            <div class="col-md-5 col-md-offset-1 col-xs-offset-1">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="form-group">
                                            {!! Form::label('submission_date','জমার তারিখ :',['class'=>'col-md-3 col-xs-12']) !!}
                                            <div class="col-md-4 col-xs-12 datepicker">
                                                {!! Form::text('submission_date', !empty($appData->submission_date) ? $appData->submission_date:'',['class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-xs-offset-1 col-md-offset-0">
                                <div class="card">
                                    <div class="card-block text-right">
                                        <div class="form-group">
                                            <div class="col-md-8 col-xs-12 pull-right">
                                                {!! Form::label('applicantSignature','আবেদনকারীর সাক্ষর :',['class'=>'pull-right']) !!}
                                            </div>
                                            <div class="col-md-8 col-xs-12 pull-right">
                                                {!! Form::file('applicantSignature', ['class'=>'form-control input-md '.!empty($appData->validate_field_applicantSignature) ? '' : 'required','flag'=>'img','id' => 'applicantSignature','onchange'=>"uploadDocument('preview_applicantSignature', this.id, 'validate_field_applicantSignature',1) , imagePreview(this)"]) !!}
                                                <span id="span_applicantSignature"
                                                      style="font-size: 12px; font-weight: bold;color:#993333">*** Maximum file size 150kb and file extension should be only jpg/jpeg/png</span>
                                                <input type="hidden" id="old_image_applicantSignature"
                                                       data-img="{{!empty($appData->validate_field_applicantSignature) ? URL::to('/uploads/'.$appData->validate_field_applicantSignature) : (url('assets/images/no-image.png'))}}"
                                                       value="{{!empty($appData->validate_field_applicantSignature) ? $appData->validate_field_applicantSignature : ''}}">
                                                <div id="preview_applicantSignature">
                                                    {!! Form::hidden('validate_field_applicantSignature',!empty($appData->validate_field_applicantSignature) ? $appData->validate_field_applicantSignature : '', ['class'=>'form-control input-md', 'id' => 'validate_field_applicantSignature','data-img'=>!empty($appData->validate_field_applicantSignature) ? $appData->validate_field_applicantSignature : '']) !!}
                                                </div>
                                                <div class="col-md-5 pull-right"
                                                     style="position:relative;margin-bottom: 5px;">
                                                    <img id="photo_viewer_applicantSignature"
                                                         style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"
                                                         src="{{!empty($appData->validate_field_applicantSignature) ? URL::to('/uploads/'.$appData->validate_field_applicantSignature) : (url('assets/images/no-image.png'))}}"

                                                         alt="applicantSignature">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('applicant_name_2','(১)আবেদনকারীর নাম :',['class'=>'col-md-6 col-xs-12']) !!}
                                                <div class="col-md-6 col-xs-12">
                                                    {!! Form::text('applicant_name_2', !empty($appData->applicant_name_2) ? $appData->applicant_name_2:'',['class' => 'form-control input-md']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('applicant_address','ঠিকানা :',['class'=>'col-md-6 col-xs-12']) !!}
                                                <div class="col-md-6 col-xs-12">
                                                    {!! Form::text('applicant_address', !empty($appData->applicant_address) ? $appData->applicant_address:'',['class' => 'form-control input-md']) !!}
                                                </div>
                                            </div>

                                            <div class="form-group col-md-9 pull-right">
                                                {!! Form::label('technical_person_name','(২) কারিগরি ব্যাক্তিবর্গের (স্থপতি/পুরকৌশলী)নাম :') !!}
                                                {!! Form::text('technical_person_name', !empty($appData->technical_person_name) ? $appData->technical_person_name:'',['class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group col-xs-12">
                            <div class="row ">
                                <div class="col-md-10 col-md-offset-1" style="margin-top:10px;">
                                    <hr>
                                    {!! Form::label('city_corporation_id','আমি/আমরা প্রত্যয়ন করিতেছি যে, উপরোক্ত বর্ণিত প্রকল্প/নির্মাণের সহিত আমি/আমরা জরিত হইয়াছি। এই ব্যাপারে উক্ত প্রকল্পের সহিত আমরা সংশ্লিষ্টতার প্রত্যয়ন করিতাছি।',['class'=>'text-left']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1 col-xs-offset-1">
                                    <div class="card">
                                        <div class="card-block pull-left">
                                            <div class="form-group">
                                                <div class="col-md-8">
                                                    {!! Form::label('architectSignature','স্থপতির সাক্ষর :',[]) !!}
                                                </div>
                                                <div class="col-md-8">
                                                    {!! Form::file('architectSignature', ['class'=>'form-control input-md '.!empty($appData->validate_field_architectSignature) ? '' : 'required','flag'=>'img','id' => 'architectSignature','onchange'=>"uploadDocument('preview_architectSignature', this.id, 'validate_field_architectSignature',1) , imagePreview(this)"]) !!}
                                                    <span id="span_architectSignature"
                                                          style="font-size: 12px; font-weight: bold;color:#993333">*** Maximum file size 150kb and file extension should be only jpg/jpeg/png</span>
                                                    <input type="hidden" id="old_image_architectSignature"
                                                           data-img="{{!empty($appData->validate_field_architectSignature) ? URL::to('/uploads/'.$appData->validate_field_architectSignature) : (url('assets/images/no-image.png'))}}"
                                                           value="{{!empty($appData->validate_field_architectSignature) ? $appData->validate_field_architectSignature : ''}}">
                                                    <div id="preview_architectSignature">
                                                        {!! Form::hidden('validate_field_architectSignature',!empty($appData->validate_field_architectSignature) ? $appData->validate_field_architectSignature : '', ['class'=>'form-control input-md', 'id' => 'validate_field_architectSignature','data-img'=>!empty($appData->validate_field_architectSignature) ? $appData->validate_field_architectSignature : '']) !!}
                                                    </div>
                                                    <div class="col-md-5" style="position:relative;">
                                                        <img id="photo_viewer_architectSignature"
                                                             style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"
                                                             src="{{!empty($appData->validate_field_architectSignature) ? URL::to('/uploads/'.$appData->validate_field_architectSignature) : (url('assets/images/no-image.png'))}}"

                                                             alt="architectSignature">
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('architect_name','নাম :',['class'=>'col-md-4 col-xs-12']) !!}
                                                <div class="col-md-8 col-xs-12">
                                                    {!! Form::text('architect_name',  !empty($appData->architect_name) ? $appData->architect_name:'',['class' => 'form-control input-md']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('architect_address','ঠিকানা :',['class'=>'col-md-4 col-xs-12']) !!}
                                                <div class="col-md-8 col-xs-12">
                                                    {!! Form::text('architect_address', !empty($appData->architect_address) ? $appData->architect_address:'',['class' => 'form-control input-md']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('architect_phone','ফোন নম্বর :',['class'=>'col-md-4 col-xs-12']) !!}
                                                <div class="col-md-8 col-xs-12">
                                                    {!! Form::text('architect_phone', !empty($appData->architect_phone) ? $appData->architect_phone:'',['class' => 'form-control input-md enbnNumber']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('registration_no','নিবন্ধন নম্বর :',['class'=>'col-md-4 col-xs-12']) !!}
                                                <div class="col-md-8 col-xs-12">
                                                    {!! Form::text('registration_no', !empty($appData->registration_no) ? $appData->registration_no:'',['class' => 'form-control input-md']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-xs-offset-1 col-md-offset-0">
                                    <div class="card">
                                        <div class="card-block text-right">
                                            <div class="form-group">
                                                <div class="col-md-8 pull-right">
                                                    {!! Form::label('civilEngineerSignature','পুরকৌশলীর সাক্ষর :',['class'=>'pull-right']) !!}
                                                </div>
                                                <div class="col-md-8 pull-right">
                                                    {!! Form::file('civilEngineerSignature', ['class'=>'form-control input-md '.!empty($appData->validate_field_civilEngineerSignature) ? '' : 'required','flag'=>'img','id' => 'civilEngineerSignature','onchange'=>"uploadDocument('preview_civilEngineerSignature', this.id, 'validate_field_civilEngineerSignature',1) , imagePreview(this)"]) !!}
                                                    <span id="span_civilEngineerSignature"
                                                          style="font-size: 12px; font-weight: bold;color:#993333">*** Maximum file size 150kb and file extension should be only jpg/jpeg/png</span>
                                                    <input type="hidden" id="old_image_civilEngineerSignature"
                                                           data-img="{{!empty($appData->validate_field_civilEngineerSignature) ? URL::to('/uploads/'.$appData->validate_field_civilEngineerSignature) : (url('assets/images/no-image.png'))}}"
                                                           value="{{!empty($appData->validate_field_civilEngineerSignature) ? $appData->validate_field_civilEngineerSignature : ''}}">
                                                    <div id="preview_civilEngineerSignature">
                                                        {!! Form::hidden('validate_field_civilEngineerSignature',!empty($appData->validate_field_civilEngineerSignature) ? $appData->validate_field_civilEngineerSignature : '', ['class'=>'form-control input-md', 'id' => 'validate_field_civilEngineerSignature','data-img'=>!empty($appData->validate_field_civilEngineerSignature) ? $appData->validate_field_civilEngineerSignature : '']) !!}
                                                    </div>
                                                    <div class="col-md-5 pull-right" style="position:relative;">
                                                        <img id="photo_viewer_civilEngineerSignature"
                                                             style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"
                                                             src="{{!empty($appData->validate_field_civilEngineerSignature) ? URL::to('/uploads/'.$appData->validate_field_civilEngineerSignature) : (url('assets/images/no-image.png'))}}"

                                                             alt="civilEngineer Signature">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('civil_engineer_name','নাম :',['class'=>'col-md-4 col-xs-12']) !!}
                                                <div class="col-md-8 col-xs-12">
                                                    {!! Form::text('civil_engineer_name', !empty($appData->civil_engineer_name) ? $appData->civil_engineer_name:'',['class' => 'form-control input-md']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('civil_engineer_address','ঠিকানা :',['class'=>'col-md-4 col-xs-12']) !!}
                                                <div class="col-md-8 col-xs-12">
                                                    {!! Form::text('civil_engineer_address', !empty($appData->civil_engineer_address) ? $appData->civil_engineer_address:'',['class' => 'form-control input-md']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('civil_engineer_phone','ফোন নম্বর :',['class'=>'col-md-4 col-xs-12']) !!}
                                                <div class="col-md-8 col-xs-12">
                                                    {!! Form::text('civil_engineer_phone', !empty($appData->civil_engineer_phone) ? $appData->civil_engineer_phone:'',['class' => 'form-control input-md enbnNumber']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('civil_engineer_registration_no','নিবন্ধন নম্বর :',['class'=>'col-md-4 col-xs-12']) !!}
                                                <div class="col-md-8 col-xs-12">
                                                    {!! Form::text('civil_engineer_registration_no', !empty($appData->civil_engineer_registration_no) ? $appData->civil_engineer_registration_no:'',['class' => 'form-control input-md']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
        </fieldset>

        {{--Attachments--}}
        <h3 class="text-center stepHeader">Attachments</h3>
        <fieldset>
            <div id="docListDiv">
                @include('LsppCDA::documents')
            </div>
        </fieldset>

        <h3 class="text-center stepHeader">Declaration</h3>
        <fieldset>
            <div class="panel panel-info">
                <div class="panel-heading" style="padding-bottom: 4px;">
                    <strong>Declaration and undertaking</strong>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <ol type="a">
                                    <li>
                                        <p>I do hereby declare that the information given above is true to the best
                                            of
                                            my knowledge and I shall be liable for any false information/ statement
                                            given</p>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead class="alert alert-info">
                        <tr>
                            <th colspan="3" style="font-size: 15px">Authorized person of the organization</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {!! Form::label('auth_name','Full name:', ['class'=>'required-star']) !!}
                                {!! Form::text('auth_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required', 'readonly']) !!}
                                {!! $errors->first('auth_name','<span class="help-block">:message</span>') !!}
                            </td>
                            <td>
                                {!! Form::label('auth_email','Email:', ['class'=>'required-star']) !!}
                                {!! Form::email('auth_email', Auth::user()->user_email, ['class' => 'form-control required input-sm email', 'readonly']) !!}
                                {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                            </td>
                            <td>
                                {!! Form::label('auth_cell_number','Cell number:', ['class'=>'required-star']) !!}
                                <br>
                                {!! Form::text('auth_cell_number', Auth::user()->user_phone, ['class' => 'form-control input-sm required phone_or_mobile', 'readonly']) !!}
                                {!! $errors->first('auth_cell_number','<span class="help-block">:message</span>') !!}
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
                                {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms', 'class'=>'required')) !!}
                                আমি/আমরা প্রত্যয়ন করিতেছি যে, উপরে উল্লিখিত তথ্য সমূহ চট্টগ্রাম মহানগর ইমারত
                                (নির্মাণ,
                                উন্নয়ন, সংরক্ষণ ও অপসারণ) বিধিমালা, ২০০৮ এর বিধিতে বর্ণিত বিষয়াদির উপযুক্ততা পূরণ
                                করে
                                এবং আমরা/আমাদের জানামতে প্রদত্ত তথ্যাবলী সঠিক। ইহাছাড়া এই বিধির আওতায় চাহিত অন্য
                                যে
                                কোন তথ্যাবলী বা দলিলাদি প্রদানেও বাধ্য থাকিব।
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
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                                {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('sfp_contact_address', Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('sfp_contact_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
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
                            </div>
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
                            class="btn btn-success btn-md" value="Submit" name="actionBtn">Payment &amp;
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

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>

    $(document).ready(function () {
        var form = $("#LsppCDAForm").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // Always allow previous action even if the current form is not valid!

                if (newIndex == 1) {
                    // to validate the land use sub check boxes
                    if ($('#land_use_category_id').val() != '') {
                        anyBoxesChecked = false;
                        $('.land_use_sub_cat').each(function (i, obj) {
                            if ($(this).is(":checked")) {
                                anyBoxesChecked = true;
                            }
                        });
                        if (anyBoxesChecked == false) {
                            $('.land_use_sub_cat').each(function (i, obj) {
                                $(this).addClass('error');
                            });
                            return false;
                        }
                    }
                }
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

        {{----end step js---}}
        $("#LsppCDAForm").validate({
            rules: {
                field: {
                    required: true,
                    email: true,

                }
            }
        });

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            form.validate().settings.ignore = ":disabled,:hidden";
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=LsppCDAForm@3'); ?>');
            } else {
                return false;
            }
        });

        $(document).on('blur', '.mobile', function () {
            var mobile = $(this).val()
            if (mobile) {
                if (mobile.length !== 11) {
                    $(this).addClass('error');
                    return false;
                } else {
                    $(this).removeClass('error');
                    return true;
                }
            }
        });

        $(document).on('blur', '.enbnNumber', function () {
            var enbn = $(this).val();
            var reg = /^([0-9]|[০-৯])/;
            if (enbn) {
                if (reg.test(enbn)) {
                    $(this).removeClass('error');
                    return true;
                } else {
                    $(this).addClass('error')
                    return false;
                }
            }
        });


        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100),
            ignoreReadonly: true
        });

        $(document).on('keydown', '.mobile', function () {
            var mobile = $(this).val();
            var reg = /^01/;
            if (mobile) {
                if (mobile.length === 2) {
                    if (reg.test(mobile)) {
                        $(this).removeClass('error');
                        return true;
                    } else {
                        $(this).addClass('error')
                        $(this).val('')
                        return false;
                    }
                }
            }


        });

        $(document).on('blur', '#applicant_nid_no', function () {
            var nid_val = $('#applicant_nid_no').val()
            var nid = nid_val.length
            if (nid_val) {
                if (nid == 10 || nid == 13 || nid == 17) {
                    $('#applicant_nid_no').removeClass('error')
                } else {
                    $('#applicant_nid_no').addClass('error')
                }
            }
        })

        $(document).on('blur', '#applicant_tin_no', function () {
            var tin_val = $('#applicant_tin_no').val()
            var tin = tin_val.length
            if (tin_val) {
                if (tin < 15) {
                    $('#applicant_tin_no').removeClass('error')
                } else {
                    $('#applicant_tin_no').addClass('error')
                }
            }
        })


        $("#LsppCDAForm").find('.onlyNumber').on('keydown', function (e) {
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

    })

    $(document).ready(function () {

        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/cda-lspp/get-refresh-token';

            $('#land_use_category_id').keydown()
            $('#city_corporation_id').keydown()
            $('#ward_no').keydown()
            $('#thana_name').keydown()
            $('#block_no').keydown()
            $('#seat_no').keydown()
            $('#sector_no').keydown()

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

        $('#city_corporation_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_lspp_service_url}}/get-list";
            let selected_value = '{{!empty($appData->city_corporation_id) ? $appData->city_corporation_id:''}}'; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "city_corp_id";//dynamic id for callback
            let element_name = "city_corp_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getCityList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#ward_no').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_lspp_service_url}}/get-list";
            let selected_value = '{{!empty($appData->ward_no) ? $appData->ward_no:''}}';// for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "word_id";//dynamic id for callback
            let element_name = "word_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getWardList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#land_use_category_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_lspp_service_url}}/get-list";
            let selected_value = '{{!empty($appData->land_use_category_id) ? $appData->land_use_category_id:''}}'; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "land_type_id";//dynamic id for callback
            let element_name = "land_type_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getLandUseList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        $("#land_use_category_id").on("change", function () {
            var self = $(this)
            $(self).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>');
            var land = $('#land_use_category_id').val()
            var land_id = land.split("@")[0]
            if (land_id) {
                let e = $(this);
                let api_url = "{{$cda_lspp_service_url}}/get-landusedetaillist";
                let selected_value = '{{!empty($appData->land_use_sub_cat_id) ? json_encode($appData->land_use_sub_cat_id):''}}';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "land_use_sub";
                let element_id = "land_sub_id";//dynamic id for callback
                let element_name = "land_sub_name";//dynamic name for callback
                let element_calling_id = "land_type_id";//dynamic name for callback
                let element_details = "land_use_detail";//dynamic name for callback
                let data = '{"land_type_id": ' + land_id + ' }';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, element_details, dependant_select_id]; // for callback

                apiCallPost(e, options, apiHeaders, checkboxCallbackResponse, arrays);

            }

        })

        $('#thana_name').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_lspp_service_url}}/get-list";
            let selected_value = '{{!empty($appData->thana_name) ? $appData->thana_name:''}}'; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "thana_id";//dynamic id for callback
            let element_name = "thana_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getThanaList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        $("#thana_name").on("change", function () {
            var self = $(this)
            $(self).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#mouza_name").html('<option value="">Please Wait...</option>')
            var thana = $('#thana_name').val()
            var thana_id = thana.split("@")[0]
            if (thana_id) {
                let e = $(this);
                let api_url = "{{$cda_lspp_service_url}}/get-mouzalist";
                let selected_value = '{{!empty($appData->mouza_name) ? $appData->mouza_name:''}}';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "mouza_name";
                let element_id = "mouza_id";//dynamic id for callback
                let element_name = "mouza_name";//dynamic name for callback
                let element_calling_id = "thana_id";//dynamic name for callback
                let data = '{"thana_id": ' + thana_id + ' }';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

                apiCallPost(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#mouza_name").html('<option value="">Select Thana First</option>')
                $(self).next().hide()
            }

        })

        $('#block_no').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_lspp_service_url}}/get-list";
            let selected_value = '{{!empty($appData->block_no) ? $appData->block_no:''}}';
            let calling_id = $(this).attr('id');// for callback
            let element_id = "block_id";//dynamic id for callback
            let element_name = "block_no";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getBlockList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#seat_no').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_lspp_service_url}}/get-list";
            let selected_value = '{{!empty($appData->seat_no) ? $appData->seat_no:''}}';
            let calling_id = $(this).attr('id');// for callback
            let element_id = "sit_id";//dynamic id for callback
            let element_name = "sit_no";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getSitList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#sector_no').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_lspp_service_url}}/get-list";
            let selected_value = '{{!empty($appData->sector_no) ? $appData->sector_no:''}}';
            let calling_id = $(this).attr('id');// for callback
            let element_id = "sector_id";//dynamic id for callback
            let element_name = "sector_no";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getSectorList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

    })

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id]) {
        var option = '<option value="">Select One</option>';
        if (response.status === 200) {
            $.each(response.data.resonse.result, function (key, row) {
                let id = (element_calling_id === '' || element_calling_id == null) ? (row[element_id] + '@' + row[element_name]) : (row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name]);
                let value = row[element_name]
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + calling_id).html(option)
        $("#" + calling_id).trigger('change')
        $("#" + calling_id).next().hide()
    }

    function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]) {
        var option = '<option value="">Select One</option>';
        if (response.status === 200) {
            $.each(response.data.resonse.result.data, function (key, row) {
                let id = (element_calling_id === '' || element_calling_id == null) ? (row[element_id] + '@' + row[element_name]) : (row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name]);
                let value = row[element_name]
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + calling_id).next().hide()
    }

    function checkboxCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, element_details, dependant_select_id]) {
        var option = '';
        if (response.status === 200) {
            var dataArr = [];
            if (selected_value != '') {
                $.each(JSON.parse(selected_value.replace(/&quot;/g, '"')), function (key, row) {
                    var data = row.split('@')[0];
                    dataArr.push(data)
                })
            }

            $.each(response.data.resonse.result.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_calling_id] + '@' + row[element_details] + '@' + row[element_name];
                let value = row[element_name] + ':' + row[element_details];
                if (dataArr.includes(id.split('@')[0])) {
                    option += '<input type="checkbox" checked="checked" class="land_use_sub_cat" name="land_use_sub_cat_id[' + key + ']" value="' + id + '"> ' + value;
                } else {
                    option += '<input type="checkbox" class="land_use_sub_cat" name="land_use_sub_cat_id[' + key + ']" value="' + id + '"> ' + value;
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + calling_id).next().hide()
    }


    var floor = ['বেসমেন্ট', 'নিচতলা', 'দোতলা', 'তিনতলা', 'চারতলা', 'পাচতলা', 'ছয়তলা', 'সাততলা']

    // Add table Row script
    function addTableRowCDA(tableID, templateRow) {
        //alert(templateRow)
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length;
        //alert(rowCount)
        if (rowCount < 8) {

            var rowCo = rowCount + 1;
            var idText = tableID + 'Row_' + rowCount;

            x.id = idText;
            $("#" + tableID).append(x);
            var bid = idText.split("_").pop()
            //get input elements
            var attrInput = $("#" + tableID).find('#' + idText).find('input');
            for (var i = 0; i < attrInput.length; i++) {
                var nameAtt = attrInput[i].name;
                var inputId = attrInput[i].id;
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
                var ret = inputId.split('_')[0];
                var repTextId = ret + '_' + rowCo;
                attrInput[i].id = repTextId;
                attrInput[i].name = repText;
            }
            attrInput.val(''); //value reset

            $("#" + tableID).find('#' + idText).find('.floorNo').val(floor[rowCount]);

            //$('.m_currency ').prop('selectedIndex', 102);
            //Class change by btn-danger to btn-primary
            $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                .attr('onclick', 'removeTableRowCustom("' + tableID + '","' + idText + '")');
            $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
            // alert(rowCount);

            //alert(floor[rowCount])
            $('#' + tableID).find('tr').last().attr('data-number', rowCount);

            $("#" + tableID).find('#' + idText).find('.onlyNumber').on('keydown', function (e) {
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
                    $this.val($this.val().replace(/[^.0-9]/g, ''));
                }, 4);
            });

            $("#LsppCDAForm").find('.enbnNumber').on('keydown', function (e) {
                var enbn = $(this).val();
                var reg = /^([0-9]|[০-৯])/;
                if (enbn) {
                    if (reg.test(enbn)) {
                        $(this).removeClass('error');
                        return true;
                    } else {
                        $(this).addClass('error')
                        return false;
                    }
                }
            })

        }
    }

    // Remove Table row script
    function removeTableRowCustom(tableID, removeNum) {
        //alert(removeNum)
        $('#' + tableID).find('#' + removeNum).remove();
        var index = 0
        var rowCo = 0
        $('#' + tableID + ' tr').each(function () {
                var trId = $(this).attr("id")
                var id = trId.split("_").pop()
                var trName = trId.split("_").shift()

                var attrInput = $("#" + tableID).find('#' + trId).find('input');
                for (var i = 0; i < attrInput.length; i++) {
                    var inputId = attrInput[i].id
                    var ret = inputId.split('_')[0]
                    var repTextId = ret + '_' + rowCo
                    attrInput[i].id = repTextId
                }
                var rowCount = $('#' + tableID).find('tr').length;
                var j = 0
                for (var i = 0; i < rowCount; i++) {
                    $("#" + 'floorNo' + '_' + i).val(floor[i]);
                    j++
                }
                var ret = trId.replace('_' + id, '');
                var repTextId = ret + '_' + rowCo;
                $(this).removeAttr("id")
                $(this).attr("id", repTextId)
                $(this).removeAttr("data-number")
                $(this).attr("data-number", rowCo)
                if (rowCo != 0) {
                    $(this).find('.addTableRows').removeAttr('onclick');
                    $(this).find('.addTableRows').attr('onclick', 'removeTableRowCustom("' + tableID + '","' + trName + '_' + rowCo + '")');
                }
                index++;
                rowCo++;
            }
        )
    }

    //Doc and image upload section
    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            $("#validate_field_" + abc).val = '';
            $('#' + abc).val = ''
            var isReq = $('#' + abc).attr('data-required')
            if (isReq == 'required') {
                $('#' + abc).addClass('required error')
            }
            document.getElementById(abc).value = ''
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
            $('#span_' + abc).show()
            let img = $('#old_image_' + abc).val()
            let old_img = $('#old_image_' + abc).attr('data-img')
            $('#validate_field_' + abc).val(img)
            if (!old_img) {
                $('#photo_viewer_' + abc).attr('src', '{{(url('assets/images/no-image.png'))}}')
            } else {
                $('#photo_viewer_' + abc).attr('src', old_img)
            }


        } else {
            return false;
        }
    });

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
            var action = "{{URL::to('/cda-lspp/upload-document')}}";
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
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }
</script>