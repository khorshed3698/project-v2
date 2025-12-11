<?php
$accessMode = ACL::getAccsessRight('BccCDA');
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
        margin-bottom: 8px;
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

    .nogutter {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }


    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

        .siteDivLR {
            margin-top: 12px;
            margin-right: 10px;
        }

        .siteDivFB {
            margin-top: 12px;
            margin-right: 10px;
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
            <h5><strong>নির্মাণ (Building Construction) অনুমোদনের জন্য আবেদন পত্র</strong></h5>
        </div>

        {!! Form::open(array('url' => 'cda-bcc/store','method' => 'post', 'class' => 'form-horizontal', 'id' => 'bccCDA',
        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
        <input type="hidden" name="selected_file" id="selected_file"/>
        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
        <input type="hidden" name="isRequired" id="isRequired"/>
        <h3 class="text-center stepHeader">Details Information</h3>
        <fieldset>

            <div class="form-group">
                <div class="col-md-6">
                    {!! Form::label('applicant_name','১। আবেদনকারীর নাম :',['class'=>'col-md-6 ']) !!}
                    <div class="col-md-6">
                        {!! Form::text('applicant_name', null,['class' => 'form-control input-md','size'=>'5x1','maxlength'=>'200']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('applicant_mobile_no',' ১.২। আবেদনকারীর মোবাইল নং :',['class'=>' col-md-6 ']) !!}
                    <div class="col-md-6">
                        {!! Form::text('applicant_mobile_no', null,['class' => 'form-control onlyNumber engOnly input-md mobile', 'placeholder'=>'e.g. 01756656565']) !!}
                        <span style="color: #990000;">(পরবর্তিতে প্রদত্ত এই নম্বরটিতে আবেদনের বিষয়ে তথ্য প্রদান বা যোগাযোগ করা হবে)</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    {!! Form::label('applicant_email',' ১.৩। আবেদনকারীর ইমেইল :',['class'=>'col-md-6']) !!}
                    <div class="col-md-6">
                        {!! Form::text('applicant_email', null,['class' => 'form-control input-md email']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('applicant_nid_no',' ১.৪। আবেদনকারীর  জাতীয় পরিচয়পত্র:',['class'=>'text-left col-md-6 ']) !!}
                    <div class="col-md-6">
                        {!! Form::text('applicant_nid_no', null,['class' => 'form-control onlyNumber engOnly input-md', 'id'=>'applicant_nid_no']) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6">
                    {!! Form::label('applicant_tin_no',' ১.৫। আবেদনকারীর টি.ই.ন নং:',['class'=>'text-left col-md-6 ']) !!}
                    <div class="col-md-6">
                        {!! Form::text('applicant_tin_no', null,['class' => 'form-control onlyNumber engOnly input-md','id'=>'applicant_tin_no']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('applicant_present_address','২। বর্তমান ঠিকানা :',['class'=>'col-md-6 ']) !!}
                    <div class="col-md-6">
                        {!! Form::textarea('applicant_present_address', null,['class' => 'form-control input-md ']) !!}
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="col-md-6">
                    {!! Form::label('suggested_building_use','৩।প্রস্তাবিত ইমারতের ব্যবহারের ধরণ :',['class'=>' col-md-6']) !!}
                    <div class="col-md-6">
                        {!! Form::select('suggested_building_use',[], null,['class' => 'form-control input-md']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('suggested_building_living_usage','৩.১। প্রস্তাবিত ইমারতের বসবাসের ধরণ :',['class'=>' col-md-6']) !!}
                    <div class="col-md-6">
                        {!! Form::select('suggested_building_living_usage',[], null,['class' => 'form-control input-md','placeholder'=>'Select Building Usage First']) !!}
                    </div>
                </div>
            </div>
            {{--first panel end--}}

            {{--second start --}}
            <div class="panel panel-info">
                <div class="panel-body">

                    {!! Form::label('','৪। প্রস্তাবিত জমি/প্লট এর অবসথান ও পরিমাণ :') !!}

                    <div class="form-group col-xs-12">
                        <div class="row ">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="col-md-6">
                                    {!! Form::label('city_corporation','ক) সিটি কর্পোরেশন :',['class'=>' col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('city_corporation',  [], '', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('bs_code','খ) বি. এস :',['class'=>' col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('bs_code', null,['class' => 'form-control input-md ']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-xs-12">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="col-md-6">
                                    {!! Form::label('rs_code','গ) আর. এস  :',['class'=>' col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('rs_code', null,['class' => 'form-control input-md']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('thana_name','ঘ) থানার নাম :',['class'=>' col-md-5']) !!}
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
                                    {!! Form::label('mouza_name','ঙ) মৌজা নাম :',['class'=>' col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('mouza_name',  [],'', ['placeholder' => 'Select Thana First',
                                        'class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('block_no','চ) ব্লক নং :',['class'=>' col-md-5 ']) !!}
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
                                    {!! Form::label('seat_no','ছ) সিট নং :',['class'=>' col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('seat_no',  [],'', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('ward_no','জ) ওয়াড নং :',['class'=>' col-md-5 ']) !!}
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
                                    {!! Form::label('sector_no','ঝ) সেক্টর নং :',['class'=>' col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('sector_no',  [],'', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('road_name','ঞ) রাস্তার নাম :',['class'=>' col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('road_name', null,['class' => 'form-control input-md']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-xs-12">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="col-md-6">
                                    {!! Form::label('arm_size_land_plot_amount','ট) বাহূ মাপ সহ জমি/প্লটের পরিমাণ :',['class'=>' col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('arm_size_land_plot_amount', null,['class' => 'form-control input-md']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('existing_house_plot_land_details','ঠ) জমি/প্লট এ বিদ্যমান বাড়ি/ কাঠামোর বিবরণ :',['class'=>' col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('existing_house_plot_land_details', null,['class' => 'form-control input-md']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- second end--}}

            {{-- 3rd start --}}

            <div class="form-group">
                <div class="col-md-12">
                    {!! Form::label('','৫। প্রস্তাবিত উন্নয়ন/নির্মাণ কজের বিস্তারিত তথ্যাদি :',['class'=>'col-md-8']) !!}
                </div>
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <div class="form-group">
                        {!! Form::label('','৫.১। প্রস্তাবিত উন্নয়ন/নির্মাণ কজের প্রকার / পপ্রকার / প্রকারসমূহ (পরিশিষ্ট-৩ এর বর্ণনানুসারে) :',['class'=>'col-md-8']) !!}
                        <div class="col-md-12">
                            {!! Form::text('proposed_dev_work_type', null,['class' => 'form-control input-md']) !!}
                        </div>
                    </div>
                    <div class="form-group ">
                        {!! Form::label('','৫.২। উপরে উল্লেখিত ধরণ অনুযায়ী ব্যবহার/ফ্লোরের ক্ষেত্রফল এর বিস্তারিত বর্ণনা :',['class'=>'col-md-8']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('','ক) জমি/প্লট এর ক্ষেত্রফল (বর্গমিটার ) :',['class'=>' col-md-4']) !!}
                        <div class="col-md-8">
                            {!! Form::text('total_land_area', null,['class' => 'form-control input-md enbnNumber']) !!}
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        {!! Form::label('','খ) বাহু সমূহের পরিমাপ :',['class'=>' col-md-3 col-xs-12']) !!}
                        <div class="col-md-9 col-xs-12" style="margin-top:-10px;">
                            <div class="row ">
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivFB">
                                            দক্ষিণে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('arm_size_south', null,['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivFB">
                                            উত্তরে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('arm_size_north', null,['class' => 'form-control input-md enbnNumber']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivLR">
                                            পুর্বে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('arm_size_east', null,['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivLR">
                                            পশ্চিমে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('arm_size_west', null,['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','গ)প্রকল্পের মোট ফ্লোরের ক্ষেত্রফল(বর্গমিটার):',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::text('projects_total_area', null,['class' => 'form-control enbnNumber input-md']) !!}
                        </div>

                        {!! Form::label('','ঘ)আবাসিক ভবনের ক্ষেত্রে প্রতি তলায় আবাস/এ্যাপার্টমেন্ট/ফ্ল্যাটের সংখ্যা:',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::text('flat_per_floor', null,['class' => 'form-control enbnNumber input-md']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','ঙ) প্রকল্পের মোট আবাসন এককের সংখ্যা :',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::text('total_accommodation_unit', null,['class' => 'form-control enbnNumber input-md']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','চ) জমির মোট ভূ-পৃষ্ঠস্থ আচ্ছাদিত অংশের ক্ষেত্রফল (বর্গমিটার)',['class'=>'col-md-5']) !!}
                        <div class="col-md-2">
                            {!! Form::text('total_ground_area', null,['class' => 'form-control input-md']) !!}
                        </div>
                        {!! Form::label('','যাহা ভূমির :',['class'=>'col-md-2']) !!}
                        <div class="col-md-2">
                            {!! Form::text('percent_ground_area', null,['class' => 'form-control input-md']) !!}
                        </div>
                        {!! Form::label('','শতাংশ',['class'=>'col-md-1']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('','ছ) প্লিন্থ এর উপরে সর্বমোট ফ্লোরের সংখ্যা :',['class'=>'col-md-4']) !!}
                        <div class="col-md-3">
                            {!! Form::text('plinth_total_floor', null,['class' => 'form-control input-md']) !!}
                        </div>
                        {!! Form::label('','এবং বেসমেন্ট ফ্লোরের সংখ্যা :',['class'=>'col-md-3']) !!}
                        <div class="col-md-2">
                            {!! Form::text('basement_total_floor', null,['class' => 'form-control input-md']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','জ) বিভিন্ন প্রকার ব্যবহারের উদ্দেশ্যে ফ্লোরের আয়তন :',['class'=>'col-md-10']) !!}
                        <div class="text-center">
                            <div class="col-md-3 col-xs-6">
                                {!! Form::label('other_usage_1','ব্যবহার -১(বর্গমিটার):',['class'=>'col-md-12']) !!}
                                {!! Form::text('other_usage_1', null,['class' => 'form-control enbnNumber input-md']) !!}
                            </div>
                            <div class="col-md-3 col-xs-6">
                                {!! Form::label('other_usage_2','ব্যবহার -২(বর্গমিটার):',['class'=>'col-md-12']) !!}
                                {!! Form::text('other_usage_2', null,['class' => 'form-control enbnNumber input-md ']) !!}
                            </div>
                            <div class="col-md-3 col-xs-6">
                                {!! Form::label('other_usage_3','ব্যবহার -৩(বর্গমিটার):',['class'=>'col-md-12']) !!}
                                {!! Form::text('other_usage_3', null,['class' => 'form-control enbnNumber col-md-6 input-md ']) !!}
                            </div>
                            <div class="col-md-3 col-xs-6">
                                {!! Form::label('other_usage_4','ব্যবহার -৪(বর্গমিটার):',['class'=>'col-md-12']) !!}
                                {!! Form::text('other_usage_4', null,['class' => 'form-control enbnNumber col-md-6 input-md ']) !!}
                            </div>
                            <div class="col-md-3 col-xs-6">
                                {!! Form::label('other_usage_5','ব্যবহার -৫(বর্গমিটার):',['class'=>'col-md-12']) !!}
                                {!! Form::text('other_usage_5', null,['class' => 'form-control enbnNumber col-md-6 input-md ']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('','৫.৩। সাইট সংলগ্ন রাস্তা বা রাস্তাসমুহের প্রস্থ :',['class'=>'col-md-4']) !!}
                        <div class="col-md-8" style="margin-top:-10px;">
                            <div class="row ">
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivFB">
                                            সম্মুখে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('front_road_area', null,['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivFB">
                                            পিছনে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('back_road_area', null,['class' => 'form-control input-md enbnNumber']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivLR">
                                            বাঁয়ে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('left_road_area', null,['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivLR">
                                            ডানে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('right_road_area', null,['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group ">
                        {!! Form::label('','৫.৪। সাইটে পূর্বনির্মিত কাঁচা / পাকা ইমারতের ( যদি থাকে) বিবরণ :',['class'=>'col-md-8']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('','ক. পূর্ব নির্মিত ইমারতের সংখ্যা ও তদদ্বারা বেষ্টিত স্থানের পরিমাণ : ',['class'=>' col-md-5']) !!}
                        <div class="col-md-4">
                            {!! Form::text('previous_build_number', null,['class' => 'form-control input-md enbnNumber']) !!}
                        </div>
                        {!! Form::label('','মিটার',['class'=>' col-md-1']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('','খ. প্রস্তাবিত ইমারত নির্মাণ অনুমোদিত হইলে পূর্বনির্মিত ইমারতের কোন অংশ ভাঙ্গিতে হইলে তদদ্বারা বেষ্টিত স্থানের পরিমাণ : ',['class'=>' col-md-9']) !!}
                        <div class="col-md-2">
                            {!! Form::text('new_build_number', null,['class' => 'form-control input-md enbnNumber']) !!}
                        </div>
                        {!! Form::label('','মিটার',['class'=>' col-md-1']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('','৫.৫। প্রস্তাবিত সাইটের আবশ্যিক উন্মুক্ত স্থান :',['class'=>'col-md-4']) !!}
                        <div class="col-md-8" style="margin-top:-10px;">
                            <div class="row ">
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivFB">
                                            সম্মুখে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('open_space_front', null,['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivFB">
                                            পিছনে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('open_space_back', null,['class' => 'form-control input-md enbnNumber']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivLR">
                                            বাঁয়ে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('open_space_left', null,['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="row">
                                        <label class="col-md-3 col-xs-3 siteDivLR">
                                            ডানে
                                        </label>
                                        <div class="col-md-6 col-xs-4" style="margin-top:5px;">
                                            {!! Form::text('open_space_right', null,['class' => 'form-control enbnNumber input-md']) !!}
                                        </div>
                                        <label class="col-md-3 col-xs-3" style="margin-left:-15px;margin-top:12px;">
                                            মিটার
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    {!! Form::label('','৬। নির্মিত ইমারত বা প্রকল্পের বিবরণ ( প্রয়োজনে তালিকাটি বিস্তৃত করা যাইতে পারে)',['class'=>'col-md-8']) !!}
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
                        <tr id="coveredAreaDetailsRow_0">
                            <td width="20%"> {!! Form::text('floor_no[]','বেসমেন্ট',['readonly'=>'readonly','class'=>'floorNo form-control ','style'=>'outline: none; border:none; box-shadow:none;cursor: context-menu;','id'=>'floorNo_1']) !!}</td>
                            <td>{!! Form::text('usage_1[]','',['class' => 'form-control input-md enbnNumber', 'id'=>'usage1']) !!}</td>
                            <td>{!! Form::text('usage_2[]','',['class' => 'form-control input-md enbnNumber', 'id'=>'usage2']) !!}</td>
                            <td>{!! Form::text('usage_3[]','',['class' => 'form-control input-md enbnNumber', 'id'=>'usage3']) !!}</td>
                            <td>{!! Form::text('total_floor[]','',['class' => 'form-control input-md enbnNumber required', 'id'=>'total_floor_1']) !!}</td>
                            <td style="vertical-align: middle; text-align: center">
                                <a class="btn btn-sm btn-primary addTableRows"
                                   title="Add more LOAD DETAILS"
                                   onclick="addTableRowCDA('coveredAreaDetails', 'coveredAreaDetailsRow_0');">
                                    <i class="fa fa-plus"></i></a>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" style="text-align:right"> মোট তলা / ফ্লোরের ক্ষেত্রফল (বর্গমিটার):</td>
                            <td colspan="2" style="text-align:center">
                                <input type="text" id="sum" name="total" class="form-control input-md"></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

            <div class="form-group">
                <div class="col-md-12">
                    {!! Form::label('','  ৭। নির্মাণ অনুমোদনের জন্য পেশকৃত ফি, দলিলদি ও নকশার তালিকা :',['class'=>'col-md-8']) !!}
                </div>
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <div class="form-group">
                        {!! Form::label('','১। স্বত্বাধিকারী ইজারা দলিল / ক্রয় দলিল / হেবা / অন্যান্য ',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('owner_documents',$yesnona, null,['class' => 'form-control enbnNumber input-md','placeholder'=>'Select One']) !!}
                        </div>
                        {!! Form::label('','২। সরকার কর্তৃক বরাদ্দহকৃত জমি হয়লে ইহার দলিলাদি ও অনুমতিপত্র',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('gov_allocated_land_doc',$yesnona, null,['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <div class="form-group">
                        {!! Form::label('','৩। বিধি অনুযায়ী ফি প্রদানের রশিদ',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('fee_receipt',$yesnona, null,['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                        </div>
                        {!! Form::label('','৪। ভুমি ব্যবহারের ছাড়পত্র (প্রযোজ্য ক্ষেত্রে)',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('land_usage_exemption',$yesnona, null,['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <div class="form-group">
                        {!! Form::label('','৫। বিশেষ প্রকল্প ছাড়পত্র (প্রযোজ্য ক্ষেত্রে)',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('special_project_exemption',$yesnona, null,['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                        </div>
                        {!! Form::label('','৬। ইনডেমনিটি বন্ড (প্রযোজ্য ক্ষেত্রে)',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('indempty_bond',$yesnona, null,['class' => 'form-control input-md ','placeholder'=>'Select One']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <div class="form-group">
                        {!! Form::label('','৭। মৃত্তিকা পরীক্ষার রিপোর্ট (প্রযোজ্য ক্ষেত্রে)',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('soil_test_report',$yesnona, null,['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                        </div>
                        {!! Form::label('','৮। Floor Area Ratio (FAR) এর হিসাব',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('far_calculation',$yesnona, null,['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <div class="form-group">
                        {!! Form::label('','৯। বিধি মোতাবেক যাবতীয় নকশা',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('all_designs',$yesnona, null,['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                        </div>
                        {!! Form::label('','১০। বিধি মোতাবেক গৃহীত ব্যবস্থা',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('action_taken',$yesnona, null,['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <div class="form-group">
                        {!! Form::label('','১১। সংশ্লিট বিভিন্ন কতৃপক্ষ এর ছাড়পত্র/অনাপত্তিপত্র (প্রযোজ্য ক্ষেত্রে)',['class'=>'col-md-4']) !!}
                        <div class="col-md-2">
                            {!! Form::select('authority_exmption',$yesnona, null,['class' => 'form-control input-md','placeholder'=>'Select One']) !!}
                        </div>
                    </div>
                </div>
            </div>

        </fieldset>

        {{--Attachments--}}
        <h3 class="text-center stepHeader">Attachments</h3>
        <fieldset>
            <div class="row">
                <div class="col-md-12">
                    <div id="docListDiv">

                    </div>
                </div>
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
                                {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5  required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5  required-star']) !!}
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
                                {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5  required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5  required-star']) !!}
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
                                {!! Form::label('sfp_status','Payment status',['class'=>'col-md-5 ']) !!}
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
        var form = $("#bccCDA").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
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
        $("#bccCDA").validate({
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
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=bccCDA@3'); ?>');
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


        $("#bccCDA").find('.onlyNumber').on('keydown', function (e) {
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
            tokenUrl = '/cda-bcc/get-refresh-token';

            $('#suggested_building_use').keydown()
            $('#city_corporation').keydown()
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

        $('#suggested_building_use').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_bcc_service_url}}/get-list";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "land_type_id";//dynamic id for callback
            let element_name = "land_type_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getLandUseList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        $("#suggested_building_use").on("change", function () {
            var self = $(this)
            $(self).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#suggested_building_living_usage").html('<option value="">Please Wait...</option>')
            var suggested_building_use = $('#suggested_building_use').val()
            var suggested_building_use_id = suggested_building_use.split("@")[0]
            if (suggested_building_use_id) {
                let e = $(this);
                let api_url = "{{$cda_bcc_service_url}}/get-landusedetaillist";
                let selected_value = ''; // for callback
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "suggested_building_living_usage";
                let element_id = "land_sub_id";//dynamic id for callback
                let element_name = "land_sub_name";//dynamic name for callback
                let element_calling_id = "land_type_id";//dynamic name for callback
                let element_details = "land_use_detail";//dynamic name for callback
                let data = '{"land_type_id": ' + suggested_building_use_id + ' }';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, element_details, dependant_select_id]; // for callback

                apiCallPost(e, options, apiHeaders, usageDependantCallbackResponse, arrays);

            } else {
                $("#suggested_building_living_usage").html('<option value="">Select Usage First</option>')
                $(self).next().hide()
            }

        })


        $('#city_corporation').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_bcc_service_url}}/get-list";
            let selected_value = ''; // for callback
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
            let api_url = "{{$cda_bcc_service_url}}/get-list";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "word_id";//dynamic id for callback
            let element_name = "word_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getWardList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#thana_name').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_bcc_service_url}}/get-list";
            let selected_value = ''; // for callback
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
                let api_url = "{{$cda_bcc_service_url}}/get-mouzalist";
                let selected_value = ''; // for callback
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
            let api_url = "{{$cda_bcc_service_url}}/get-list";
            let selected_value = ''; // for callback
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
            let api_url = "{{$cda_bcc_service_url}}/get-list";
            let selected_value = ''; // for callback
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
            let api_url = "{{$cda_bcc_service_url}}/get-list";
            let selected_value = ''; // for callback
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
        $("#" + calling_id).next().hide()
    }

    function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]) {
        var option = '<option value="">Select One</option>';
        if (response.status === 200) {
            $.each(response.data.resonse.result.data, function (key, row) {
                let id = (element_calling_id === '' || element_calling_id == null) ? (row[element_id] + '@' + row[element_name]) : (row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name]);
                let value = row[element_name]
                option += '<option value="' + id + '">' + value + '</option>'
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + calling_id).next().hide()
    }

    function usageDependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, element_details, dependant_select_id]) {
        var option = '<option value="">Select One</option>';
        if (response.status === 200) {
            $.each(response.data.resonse.result.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name] + '@' + row[element_details];
                let value = row[element_name] + ': ' + row[element_details];
                option += '<option value="' + id + '">' + value + '</option>'
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + calling_id).next().hide()
    }


    var floor = ['বেসমেন্ট', 'নিচতলা', 'দোতলা', 'তিনতলা', 'চারতলা', 'পাচতলা', 'ছয়তলা', 'অন্যান্য তলা']

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
            $("#" + tableID).find('.enbnNumber').on('keydown', function (e) {
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

    $(document).ready(function () {
        var doc_type = 1;
        var app_id = $("#app_id").val();
        var _token = "{{ csrf_token() }}";

        var attachment_key = "cda_bcc_";
        if (doc_type == 1) {
            attachment_key += "mp";
        } else if (doc_type == 2) {
            attachment_key += "pw";
        } else if (doc_type == 3) {
            attachment_key += "ap";
        } else {
            alert('Please Select one type of Land');
        }

        $.ajax({
            type: "POST",
            url: '/cda-bcc/getDocList',
            dataType: "json",
            data: {_token: _token, attachment_key: attachment_key, app_id: app_id},
            success: function (result) {
                if (result.html != undefined) {
                    $('#docListDiv').html(result.html);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //console.log(errorThrown);
                alert('Unknown error occured. Please, try again after reload');
            },
        });
    });

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
            var action = "{{URL::to('/cda-bcc/upload-document')}}";
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