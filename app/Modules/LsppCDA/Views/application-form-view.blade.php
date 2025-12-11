<?php
$accessMode = ACL::getAccsessRight('LsppCDA');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>

    .form-group {
        margin-bottom: 5px;
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

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .table {
        margin: 0;
    }

    .table > tbody > tr > td,
    .table > tbody > tr > th,
    .table > tfoot > tr > td,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > thead > tr > th {
        padding: 5px;
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
    <div class="panel panel-info" id="inputForm">
        <div class="panel-heading">
            <div class="row">
                <strong class="col-md-10" style="line-height: 30px;">
                    বৃহদায়তন বা বিশেষ ধরনের প্রকল্পের জন্য বিশেষ প্রকল্প ছাড়পত্রের আবেদন
                </strong>
                <div class="col-md-2">
                    <a class="btn btn-md btn-success form-control" data-toggle="collapse" href="#paymentInfo"
                       role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <ol class="breadcrumb">
                <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                <li class="highttext"><strong>CDA Tracking no. : {{ $appInfo->lspp_cda_tracking_id  }}</strong></li>
                <li class="highttext"><strong> Date of
                        Submission:
                        {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                </li>
                <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                @if (isset($appInfo) && isset($appInfo->certificate) && $appInfo->certificate != '0')
                    <li>
                        <a href="{{ url($appInfo->certificate) }}" class="btn show-in-view btn-md btn-info"
                           title="Download Certificate" target="_blank"> <i class="fa  fa-file-pdf-o"></i> Download
                            Certificate</a>
                    </li>
                @endif
                @if($appInfo->sp_case_no != "" && $appInfo->sp_case_no != null)
                    <li>
                        <strong>SP Case No :</strong>
                        {{ $appInfo->sp_case_no}}
                    </li>
                @endif

                @if(empty($appInfo->sp_case_no) || $appInfo->status_id == 2)
                    <li>
                        <strong>Info :</strong>
                        If required you may submit the necessary hard copy to Service Delivery Counter (SDC), Chittagong
                        Development Authority, CDA building, Court Road, Kotowali Circle, Chittagong-4000, Bangladesh.
                    </li>
                @endif

                @if(($appInfo->certificate != '')  && $appInfo->status_id == 25)
                    <li>
                        <strong>Info :</strong>
                        This is a draft approval copy. To collect the final approval copy please contact to Service
                        Delivery Counter (SDC). Chittagong Development Authority, CDA building, Court Road, Kotowali
                        Circle, Chittagong-4000, Bangladesh
                    </li>
                @endif

                @if(($appInfo->certificate == '')  && $appInfo->status_id == 25) <strong>Info :</strong>
                <li>Your application is approved. waiting for preparing the letter and signature.</li>
                @endif
            </ol>


            @include('SonaliPaymentStackHolder::payment-information')

            <div class="form-goup" style="padding:10px;padding-bottom: 0px;">
                @if($appInfo->status_id == 27 && Auth::user()->user_type == '5x505')
                    @include('LsppCDA::resubmit-add')
                @endif

                @if($appInfo->status_id == 32)
                    @include('LsppCDA::resubmit-view')
                @endif
            </div>

            <fieldset>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="text-left col-md-4">
                                <span class="v_label">সংযোগের ট্যারিফ </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-8">
                                {{!empty($appData->land_use_category_id) ? explode('@', $appData->land_use_category_id)[1] : ''}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            @foreach($appData->land_use_sub_cat_id as $land_use_sub)
                                <input type="checkbox" checked>{{explode('@',$land_use_sub)[2]}}
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="text-left col-md-5">
                                    <span class="v_label">১। আবেদনকারীর নাম  </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{!empty($appData->applicant_name) ? $appData->applicant_name:''}}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-left col-md-5">
                                    <span class="v_label">১.২। আবেদনকারীর মোবাইল নং  </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{!empty($appData->applicant_mobile_no) ? $appData->applicant_mobile_no:''}}
                                </div>
                                <br>
                                <span style="color: #990000;">(পরবর্তিতে প্রদত্ত এই নম্বরটিতে আবেদনের বিষয়ে তথ্য প্রদান বা যোগাযোগ করা হবে)</span>

                            </div>

                            <div class="col-md-6">
                                <div class="text-left col-md-5">
                                    <span class="v_label">১.৩। আবেদনকারীর ইমেইল   </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{!empty($appData->applicant_email) ? $appData->applicant_email:''}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="text-left col-md-5">
                                    <span class="v_label">১.৪। আবেদনকারীর  জাতীয় পরিচয়পত্র:  </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{!empty($appData->applicant_nid_no) ? $appData->applicant_nid_no:''}}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-left col-md-5">
                                    <span class="v_label"> ১.৫। আবেদনকারীর টি.ই.ন নং:  </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{!empty($appData->applicant_tin_no) ? $appData->applicant_tin_no:''}}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="text-left col-md-5">
                                    <span class="v_label">২। বর্তমান ঠিকানা </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{!empty($appData->applicant_present_address) ? $appData->applicant_present_address:''}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-left col-md-5">
                                    <span class="v_label">৩।জমি/প্লট এর প্রস্তাবিত ব্যবহার </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{!empty($appData->suggested_use_land_plot) ? $appData->suggested_use_land_plot:''}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--first panel end--}}

                {{--second start --}}
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::label('','৪। প্রস্তাবিত জমি/প্লট এর অবসথান ও পরিমাণ :',['class'=>'v_label','style'=>'margin-top:10px;margin-bottom:20px;margin-left:15px;']) !!}

                        <div class="form-group col-xs-12">
                            <div class="row ">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">ক) সিটি কর্পোরেশন/পৌরসভা/গ্রাম/মহল্লা : </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->city_corporation_id) ? explode('@', $appData->city_corporation_id)[1] : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">খ) বি. এস : </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->bs_code) ? $appData->bs_code:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">গ) আর. এস </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->rs_code) ? $appData->rs_code:''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">ঘ) থানার নাম </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->thana_name) ? explode('@', $appData->thana_name)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">ঙ) মৌজা নাম  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->mouza_name) ? explode('@', $appData->mouza_name)[2] : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">চ) ব্লক নং  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->block_no) ? explode('@', $appData->block_no)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">ছ) সিট নং </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->seat_no) ? explode('@', $appData->seat_no)[1] : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">জ) ওয়াড নং  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->ward_no) ? explode('@', $appData->ward_no)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">ঝ) সেক্টর নং  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->sector_no) ? explode('@', $appData->sector_no)[1] : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">ঞ) রাস্তার নাম  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->road_name) ? $appData->road_name:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">ট) বাহূ মাপ সহ জমি/প্লটের পরিমাণ </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->arm_size_land_plot_amount) ? $appData->arm_size_land_plot_amount:''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">ঠ) জমি/প্লট এ বিদ্যমান বাড়ি/ কাঠামোর বিবরণ  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->existing_house_plot_land_details) ? $appData->existing_house_plot_land_details:''}}
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
                        {!! Form::label('','৫। প্রযোজ্য ক্ষেত্রে ভূমি ব্যবহার ছাড়পত্র নম্বর (কপি সংযুক্ত ) :',['class'=>'v_label','style'=>'margin-top:10px;margin-bottom:10px;margin-left:15px;']) !!}

                        <div class="col-md-12" style="margin-left: 20px;">
                            {{!empty($appData->suggested_use_land_plot) ? $appData->suggested_use_land_plot:''}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {!! Form::label('','৬। প্রস্তাবিত উন্নয়ন কার্যের প্রকার/ প্রকারসমূহ (পরিশিষ্ট-৩ এর বর্ণনা অনুসারে উল্লেখ্য ) :',['class'=>'v_label','style'=>'margin-top:10px;margin-bottom:10px;margin-left:15px;']) !!}

                        <div class="col-md-12" style="margin-left: 20px;">
                            {{ !empty($appData->proposed_dev_work_type) ? $appData->proposed_dev_work_type:''}}

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        {!! Form::label('','৭। প্রস্তাবিত ব্যবহারের বিস্তারিত বর্ণনা :',['class'=>'v_label','style'=>'margin-top:10px;margin-bottom:10px;margin-left:15px;']) !!}

                        <div class="form-group col-xs-12">
                            <div class="row ">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-7">
                                            <span class="v_label">ক) জমির/প্লট এর ক্ষেত্রফল (বর্গমিটার) </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-4">
                                            {{!empty($appData->land_area) ? $appData->land_area:''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-9">
                                            <span class="v_label">খ) যে কোন তলার / ফ্লোরের সরবচ্চ ক্ষেত্রফল (বর্গমিটার) </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-3">
                                            {{ !empty($appData->max_floor_area) ? $appData->max_floor_area:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row ">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-7">
                                            <span class="v_label">গ) সর্বমোট ফ্লোরের ক্ষেত্রফল (বর্গমিটার) </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->total_floor_area) ? $appData->total_floor_area:''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-9">
                                            <span class="v_label">ঘ) প্লিন্থ (Plinth) এর উপর সর্বমোট ফ্লোরের সংখ্যা </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-3">
                                            {{!empty($appData->total_plinth_floor) ? $appData->total_plinth_floor:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <div class="row ">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-7">
                                            <span class="v_label">ঙ) বেসমেণ্ট ফ্লোর/ফ্লোরের সংখ্যা </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{ !empty($appData->basement_floor_no) ? $appData->basement_floor_no:''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-9">
                                            <span class="v_label">চ) আবাসিক ভবনের ক্ষেত্রে মোট আবাস/এ্যাপাটমেণ্ট /ফ্ল্যাটের সংখ্যা </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-3">
                                            {{ !empty($appData->total_residential_flat_no) ? $appData->total_residential_flat_no:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group col-xs-12">
                            <div class="row ">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-10">
                                            <span class="v_label">জ) বিভিন্ন প্রকার ব্যবহারের উদ্দেশ্যে ফ্লোরের আয়তন </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="panel panel-info col-md-10 col-md-offset-1" style="padding:10px;">
                                    <div class="form-group col-md-3 col-xs-6">
                                        <div class="col-md-8">
                                            <span class="v_label">ব্যবহার -১(বর্গমিটার) </span>
                                            <span>&#58;</span>
                                        </div>
                                        <div class="col-md-4">
                                            {{ !empty($appData->other_usage_1) ? $appData->other_usage_1:''}}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 col-xs-6">
                                        <div class="col-md-8">
                                            <span class="v_label">ব্যবহার -২(বর্গমিটার) </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-4">
                                            {{ !empty($appData->other_usage_2) ? $appData->other_usage_2:''}}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 col-xs-6">
                                        <div class="col-md-8">
                                            <span class="v_label">ব্যবহার -৩(বর্গমিটার) </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-4">
                                            {{ !empty($appData->other_usage_3) ? $appData->other_usage_3:''}}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 col-xs-6">
                                        <div class="col-md-8">
                                            <span class="v_label">ব্যবহার -৪(বর্গমিটার) </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-4">
                                            {{ !empty($appData->other_usage_4) ? $appData->other_usage_4:''}}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 col-xs-6">
                                        <div class="col-md-8">
                                            <span class="v_label">ব্যবহার -৫(বর্গমিটার) </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-4">
                                            {{ !empty($appData->other_usage_5) ? $appData->other_usage_5:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-top:15px;">
                        <div class="row">
                            {!! Form::label('site_side_main_road','৮। সাইট সংলগ্ন রাস্তাটি একটি প্রধান সড়ক :',['class'=>'col-md-3 v_label','style'=>'margin-bottom:20px;margin-left:20px;']) !!}
                            <div class="col-md-4 text-left">
                                {{!empty($appData->site_side_main_road) ? $appData->site_side_main_road:''}}
                            </div>
                        </div>


                        <div class="col-md-11">
                            <label class="col-md-3 col-xs-8 v_label" style="margin-left:20px;">৮.১ সাইট সংলগ্ন রাস্তা বা
                                রাস্তাসমুহের
                                প্রস্থ:</label>
                            <div class="col-md-8" style="margin-top:-10px;">
                                <div class="row ">
                                    <div class="col-md-3 col-xs-6">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-2 siteDivFB">
                                                <span class="v_label"> সম্মুখে  </span>
                                            </div>
                                            <div class="col-md-6 col-xs-4 text-center" style="margin-top:12px;">
                                                {{!empty($appData->front_road_area) ? $appData->front_road_area:''}}
                                            </div>
                                            <div class="col-md-3 col-xs-4" style="margin-left:-15px;margin-top:12px;">
                                                <span class="v_label">মিটার </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-2 siteDivFB">
                                                <span class="v_label"> পিছনে </span>
                                            </div>
                                            <div class="col-md-6 col-xs-4 text-center" style="margin-top:12px;">
                                                {{ !empty($appData->back_road_area) ? $appData->back_road_area:''}}
                                            </div>
                                            <div class="col-md-3 col-xs-4" style="margin-left:-15px;margin-top:12px;">
                                                <span class="v_label">মিটার </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-2 siteDivLR">
                                                <span class="v_label"> বাঁয়ে </span>
                                            </div>
                                            <div class="col-md-6 col-xs-4 text-center" style="margin-top:12px;">
                                                {{ !empty($appData->left_road_area) ? $appData->left_road_area:''}}
                                            </div>
                                            <div class="col-md-3 col-xs-4" style="margin-left:-15px;margin-top:12px;">
                                                <span class="v_label">মিটার </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-6">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-2 siteDivLR">
                                                <span class="v_label">ডানে </span>
                                            </div>
                                            <div class="col-md-6 col-xs-4 text-center" style="margin-top:12px;">
                                                {{!empty($appData->right_road_area) ? $appData->right_road_area:''}}
                                            </div>
                                            <div class="col-md-3 col-xs-4" style="margin-left:-15px;margin-top:12px;">
                                                <span class="v_label"> মিটার </span>
                                            </div>
                                            <div class="col-md-12">
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
                        <label class="col-md-3 col-xs-12 v_label" style="margin-left:12px;">৯। প্রস্তাবিত সাইটের মধ্যে
                            অবস্থানঃ</label>
                        <div class="col-md-8 col-xs-12">
                            <div class="row">
                                <div class="col-md-4 col-xs-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="v_label">প্রাকৃতিক বনাঞ্চল </span>
                                        </div>
                                        <div class="col-md-6">
                                            {{!empty($appData->natural_forrest) ? $appData->natural_forrest:''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="v_label">পাহাড় </span>
                                        </div>
                                        <div class="col-md-8">
                                            {{!empty($appData->mountain) ? $appData->mountain:''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="v_label">ঢাল </span>
                                        </div>
                                        <div class="col-md-8">
                                            {{!empty($appData->slope) ? $appData->slope:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-top:15px;margin-left:15px;">
                        <label class="col-md-3 col-xs-12 v_label">১০।প্রস্তাবিত সাইটের মধ্যে
                            অবস্থানঃ</label>
                        <div class="col-md-8 col-xs-12">
                            <div class="row">
                                <div class="col-md-4 col-xs-6">
                                    <div class="row">
                                        <div class="col-md-3 v_label">
                                            পুকুর
                                        </div>
                                        <div class="col-md-8">
                                            {{!empty($appData->pond) ? $appData->pond:''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="row">
                                        <div class="col-md-4 v_label">
                                            প্রাকৃতিক জলাভূমি
                                        </div>
                                        <div class="col-md-8">
                                            {{!empty($appData->natural_wetlands) ? $appData->natural_wetlands:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-top:15px;margin-left:25px;">
                        <label class="v_label ">১১। প্রস্তাবিত সাইটে ২৫০
                            মিটার
                            দূরত্বের অন্তভূক্ত কোন
                            স্থপতিক গুনাগুনসম্পন্ন:</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 col-md-offset-1 col-xs-6">
                        <div class="row">
                            <div class="col-md-3 v_label">
                                ভবন
                            </div>
                            <div class="col-md-8">
                                {{!empty($appData->building) ? $appData->building:''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="row">
                            <div class="col-md-8 v_label">
                                ঐতিহাসিক গুনাগুনসম্পন্ন ভবন
                            </div>
                            <div class="col-md-4">
                                {{!empty($appData->historic_building) ? $appData->historic_building:''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="row">
                            <div class="col-md-6 v_label">
                                সাইট সংলগ্ন কোন হ্রদ
                            </div>
                            <div class="col-md-6">
                                {{!empty($appData->site_side_lake) ? $appData->site_side_lake:''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="row">
                            <div class="col-md-6 v_label">
                                পাশ্বে পাক প্রভৃতি
                            </div>
                            <div class="col-md-6">
                                {{!empty($appData->site_side_park) ? $appData->site_side_park:''}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-top:15px;margin-left:25px;">
                        <label class="v_label">১২। প্রস্তাবিত সাইট দৃশ্যগত বৈশিষ্টপূণ
                            এলাকায়ঃ</label>
                        <div class="col-md-4">
                            {{!empty($appData->site_in_visually_characteristics_area) ? $appData->site_in_visually_characteristics_area:''}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-xs-12" style="margin-top:15px;margin-left:25px;">
                        <label class="v_label">
                            ১৩।প্রস্তাবিত সাইটের পার্শ্বে অবস্থিত</label>
                    </div>
                </div>


                <div class="form-group col-xs-12">
                    <div class="col-md-3 col-xs-6">
                        <div class="row">
                            <div class="col-md-4 col-md-offset-1">
                                <span class="v_label"> বিমানবন্দর</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($appData->airport) ? $appData->airport:''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="row">
                            <div class="col-md-5">
                                <span class="v_label">রেলওয়ে স্টশন</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6">
                                {{!empty($appData->railway_station) ? $appData->railway_station:''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="row">
                            <div class="col-md-4">
                                <span class="v_label"> বাস টার্মিনাল</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6">
                                {{!empty($appData->bus_terminal) ? $appData->bus_terminal:''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="row">
                            <div class="col-md-4">
                                <span class="v_label"> নদী বন্দর</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6">
                                {{!empty($appData->river_port) ? $appData->river_port:''}}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row" style="margin-top:15px;margin-left:15px;">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-8 col-xs-6">
                                <label class="v_label">১৪। প্রস্তাবিত সাইট বর্ন্যাপ্রবন এলাকায় :</label>
                            </div>
                            <div class="col-md-4 col-xs-6">
                                {{!empty($appData->flood_prone_area) ? $appData->flood_prone_area:''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-7 col-xs-12">
                                <label class="v_label">এলাকা সংলগ্ন রাস্তার কেন্দ্র হইতে সাইটের অবস্থিত
                                    গড়:</label>
                            </div>
                            <div class="col-md-1 col-xs-4">
                                {{ !empty($appData->road_center_to_site) ? $appData->road_center_to_site:''}}
                            </div>
                            <div class="col-md-1 col-xs-4">
                                {{!empty($appData->road_center_to_site_meter) ? $appData->road_center_to_site_meter:''}}
                            </div>
                            <div class="col-md-1 col-xs-2">
                                <label class="v_label" for="email">মিটার</label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group" style="margin-top:15px;margin-left:15px;">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-8 col-xs-12">
                                <label class="v_label">১৫। প্রস্থবিত সাইটের অবস্থিত বর্তমান ইমারতের
                                    সংখ্যা:</label>
                            </div>
                            <div class=" col-md-2 col-xs-4 ">
                                {{ !empty($appData->total_building_site) ? $appData->total_building_site:''}}
                            </div>
                            <label class="col-md-1 v_label col-xs-2">টি</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-7 col-xs-12">
                                <label class="v_label">এবং তাহার সর্বমোট মেঝের ক্ষেত্রফল:</label>
                            </div>
                            <div class="col-md-3 col-xs-3">
                                {{!empty($appData->buildings_total_floor_area) ? $appData->buildings_total_floor_area:''}}
                            </div>
                            <label class="v_label col-md-2 col-xs-2">বর্গমিটার</label>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top:15px;margin-left:15px;">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <label class="v_label"> ১৬। সর্বমোট প্রয়োজনীয় বিদুৎ এর চাহিদা</label>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                {{!empty($appData->total_electricity_demand) ? $appData->total_electricity_demand:''}}
                            </div>
                            <label class="v_label col-md-4 col-xs-8">ওয়াট/কিলোওয়াট (আনুমানিক)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5 col-xs-12">
                                <label class="v_label">
                                    ১৭। সর্বমোট পানির চাহিদা</label>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                {{!empty($appData->total_water_demand) ? $appData->total_water_demand:''}}
                            </div>
                            <label class="v_label col-md-5 col-xs-8">লিটার/কিলোলিটার (আনুমানিক)</label>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top:15px;margin-left:15px;">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <label class="v_label"> ১৮। প্রস্তাবিত উন্নয়নকার্য সম্পূর্ণভাবে </label>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                {{!empty($appData->total_development_time_in_month) ? $appData->total_development_time_in_month:''}}
                            </div>
                            <label class="v_label col-md-4 col-xs-8">মাসের মধ্যে সম্পন্ন হইবে</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="v_label col-md-5 col-xs-4">
                                <label>এবং উন্নয়নকার্যকে</label>
                            </div>
                            <div class="col-md-4 col-xs-4">
                                {{!empty($appData->total_development_stage) ? $appData->total_development_stage:''}}
                            </div>
                            <label class="v_label col-md-3 col-xs-4">ধাপে এবং </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <div class="col-md-6 col-xs-3">
                                {{!empty($appData->total_development_stage_month) ? $appData->total_development_stage_month:''}}
                            </div>
                            <label class="col-md-6 col-xs-2 v_label">মাসের</label>
                        </div>
                    </div>
                    <label class="col-md-6 col-xs-6 v_label" style="margin-top:10px;margin-left:15px;"> মাঝে বিভক্ত কর
                        হইবে।</label>
                </div>


                <div class="col-md-12">
                    <label class="v_label" style="margin-top:15px;margin-left:15px;">১৯। নির্মিতব্য Covered Area এর
                        বিবরণ </label>
                </div>
                <div class="col-md-10 col-md-offset-1 col-xs-12">
                    <table class="table table-bordered table-hover table-info">
                        <thead>
                        <tr class="info">
                            <th></th>
                            <th class="text-center">ব্যবহার-১( বর্গমিটার)</th>
                            <th class="text-center">ব্যবহার-২( বর্গমিটার)</th>
                            <th class="text-center">ব্যবহার-৩( বর্গমিটার)</th>
                            <th class="text-center">মোট ফ্লোর( বর্গমিটার)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($appData->total_floor) > 0)
                            @foreach($appData->total_floor as $key => $value)
                                <tr>
                                    <td width="20%"> {{!empty($appData->floor_no[$key]) ? $appData->floor_no[$key]:''}}</td>
                                    <td>{{!empty($appData->usage_1[$key]) ? $appData->usage_1[$key]:''}}</td>
                                    <td>{{!empty($appData->usage_2[$key]) ? $appData->usage_2[$key]:''}}</td>
                                    <td>{{!empty($appData->usage_3[$key]) ? $appData->usage_3[$key]:''}}</td>
                                    <td>{{!empty($appData->usage_1[$key]) ? $appData->usage_1[$key]:''}}</td>

                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>


                <div class="row" style="margin-left:10px;">
                    <div class="col-md-12 col-xs-12">
                        {!! Form::label('','২০। বিশেষ প্রকল্পের ছাড়পত্রের জন্য প্রেশকৃত তথ্যাবলী/দলিলাদি ও নকশার তালিকা :',['class'=>'v_label','style'=>'margin-top:10px;margin-bottom:10px;margin-left:15px;']) !!}
                        <div class="form-group col-xs-12">
                            <div class="col-md-4 col-md-offset-1">
                                <span class="v_label">২০.১ ।স্বত্বাধিকারির ইজারা দলিল/ক্রয় দলিল/হেবা/অন্যান্য </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-2">
                                {{!empty($appData->owner_purchase_deed) ? $appData->owner_purchase_deed:''}}
                            </div>
                        </div>
                        <div class="form-group col-xs-12">
                            <div class="col-md-5 col-md-offset-1">
                                <span class="v_label">২০.২।সরকার কর্তৃক বরাদ্দকৃত ভূমি/জমি হইলে দলিলাদি ও অনুমতিপত্র </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-2">
                                {{!empty($appData->govt_assigned_land_deed) ? $appData->govt_assigned_land_deed:''}}
                            </div>
                        </div>
                        <div class="form-group col-xs-12">
                            <div class="col-md-4 col-md-offset-1">
                                <span class="v_label">২০.৩।প্রদেয় ফি এর প্রমানপত্র </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-2">
                                {{!empty($appData->paid_fee_and_prove) ? $appData->paid_fee_and_prove:''}}
                            </div>
                        </div>
                        <div class="form-group col-xs-12">
                            <div class="col-md-4 col-md-offset-1">
                                <span class="v_label">২০.৪। ভূমি ব্যবহারের ছাড়পত্</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-2">
                                {{!empty($appData->land_usage_exemption) ? $appData->land_usage_exemption:''}}
                            </div>
                        </div>
                        <div class="form-group col-xs-12">
                            <div class="col-md-4 col-md-offset-1">
                                <span class="v_label">২০.৫। FAR এর হিসাব </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-2">
                                {{!empty($appData->far_calculation) ? $appData->far_calculation:''}}
                            </div>
                        </div>
                        <div class="form-group col-xs-12">
                            <div class="col-md-4 col-md-offset-1">
                                <span class="v_label">২০.৬ বিধি অনুযায়ী সকল নকশা ও দলিলাদি বিবরন </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-2">
                                {{ !empty($appData->all_design_and_documents_detail) ? $appData->all_design_and_documents_detail:''}}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group col-xs-12">
                    <div class="row ">
                        <div class="col-md-10 col-md-offset-1" style="margin-top:10px;">
                            <hr>
                            {!! Form::label('city_corporation_id','
            আমি/আমরা প্রত্যয়ন করিতেছি য,উপরে উল্লেখিত তথ্যসমূহ চট্টগ্রাম মহানগর ইমারত (নির্মাণ,উন্নয়ন,সংরক্ষণ ও অপসারন) বিধিমালা,২০০৮ এর বিধিতে বর্ণিত বিষয়াদির উপযুক্ততা পূরণ করে এবং আমার/আমাদের জ্ঞান অনুযায়ী প্রদত্ত তথ্যাবলী সঠিক। অনুমোদিত হওয়ার পর যে কোন ভুল তথ্য বা অসামাঞ্জতার কারনে অথবা সরকারের যে কোন প্রয়োজনে ভবিষ্যতে কর্তৃপক্ষ এই বিষয়ে ছাড়পত্র বাতিল করিতে পারবে। তাছাড়া এই বিধিমালার আওতায় অন্য যে কোন তথ্যাবলী বা দলিলাদি প্রদানেও বাধ্য থাকিব।',['class'=>'v_label text-left ']) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group col-xs-12">
                    <div class="row">
                        <div class="col-md-5 col-md-offset-1 col-xs-offset-1">
                            <div class="card">
                                <div class="card-block">
                                    <div class="form-group">
                                        <div class="v_label col-md-3 col-xs-12">
                                            <span class="v_label">জমার তারিখ </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            {{ !empty($appData->submission_date) ? $appData->submission_date:''}}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-xs-offset-1 col-md-offset-0">
                            <div class="card">
                                <div class="card-block text-right">
                                    <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                        <span class="v_label">আবেদনকারীর সাক্ষর </span>
                                    </div>
                                    <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                        @if(isset($appData->validate_field_applicantSignature))
                                            <a target="_blank" class="btn btn-xs btn-primary"
                                               href="{{URL::to('/uploads/'.$appData->validate_field_applicantSignature)}}"
                                               title="{{$appData->validate_field_applicantSignature}}">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                Open File
                                            </a>
                                        @endif
                                    </div>

                                    <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                        <span class="v_label">(১)আবেদনকারীর নাম </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                        {{!empty($appData->applicant_name_2) ? $appData->applicant_name_2:''}}
                                    </div>
                                    <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                        <span class="v_label">ঠিকানা </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                        {{!empty($appData->applicant_address) ? $appData->applicant_address:''}}
                                    </div>
                                    <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                        <span class="v_label">(২) কারিগরি ব্যাক্তিবর্গের (স্থপতি/পুরকৌশলী)নাম</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                        {{!empty($appData->technical_person_name) ? $appData->technical_person_name:''}}
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
                            {!! Form::label('city_corporation_id','আমি/আমরা প্রত্যয়ন করিতেছি যে, উপরোক্ত বর্ণিত প্রকল্প/নির্মাণের সহিত আমি/আমরা জরিত হইয়াছি। এই ব্যাপারে উক্ত প্রকল্পের সহিত আমরা সংশ্লিষ্টতার প্রত্যয়ন করিতাছি।',['class'=>'v_label text-left']) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group col-xs-12">
                    <div class="row">
                        <div class="col-md-5 col-md-offset-1 col-xs-offset-1">
                            <div class="card">
                                <div class="card-block">
                                    <div class="form-group">
                                        <div class="col-xs-12" style="margin-top:10px;">
                                            <span class="v_label">স্থপতির সাক্ষর :</span>
                                        </div>
                                        <div class="col-xs-12" style="margin-top:10px;">
                                            @if(isset($appData->validate_field_architectSignature))
                                                <a target="_blank" class="btn btn-xs btn-primary"
                                                   href="{{URL::to('/uploads/'.$appData->validate_field_architectSignature)}}"
                                                   title="{{$appData->validate_field_architectSignature}}">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    Open File
                                                </a>
                                            @endif

                                        </div>

                                        <div class="col-xs-12" style="margin-top:10px;">
                                            <span class="v_label">নাম</span>
                                        </div>
                                        <div class="col-xs-12" style="margin-top:10px;">
                                            {{!empty($appData->architect_name) ? $appData->architect_name:''}}
                                        </div>
                                        <div class="col-xs-12" style="margin-top:10px;">
                                            <span class="v_label">ঠিকানা</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-xs-12" style="margin-top:10px;">
                                            {{ !empty($appData->architect_address) ? $appData->architect_address:''}}
                                        </div>
                                        <div class="col-xs-12" style="margin-top:10px;">
                                            <span class="v_label">নিবন্ধন নম্বর</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-xs-12" style="margin-top:10px;">
                                            {{!empty($appData->registration_no) ? $appData->registration_no:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-xs-offset-1 col-md-offset-0">
                            <div class="card">
                                <div class="card-block text-right">
                                    <div class="form-group">
                                        <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                            <span class="v_label">পুরকৌশলীর সাক্ষর</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                            @if(isset($appData->validate_field_civilEngineerSignature))
                                                <a target="_blank" class="btn btn-xs btn-primary"
                                                   href="{{URL::to('/uploads/'.$appData->validate_field_civilEngineerSignature)}}"
                                                   title="{{$appData->validate_field_civilEngineerSignature}}">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    Open File
                                                </a>
                                            @endif

                                        </div>

                                        <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                            <span class="v_label">নাম</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                            {{ !empty($appData->civil_engineer_name) ? $appData->civil_engineer_name:''}}
                                        </div>
                                        <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                            <span class="v_label">ঠিকানা </span>
                                        </div>
                                        <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                            {{!empty($appData->civil_engineer_address) ? $appData->civil_engineer_address:''}}
                                        </div>
                                        <div class="col-xs-12 pull-right" style="margin-top:10px;">
                                            <span class="v_label">নিবন্ধন নম্বর </span>
                                        </div>
                                        <div class="col-xs-12 pull-right">
                                            {{!empty($appData->civil_engineer_registration_no) ? $appData->civil_engineer_registration_no:''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#resubmitForm").validate();
    })

    function uploadSingleDocument(input) {
        var file_id = document.getElementById(input.id);
        var file = file_id.files;
        if (file && file[0]) {
            if (!(file[0].type == 'application/pdf')) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'The file format is not valid! Please upload in pdf format.'
                });
                file_id.value = '';
                return false;
            }

            var file_size = parseFloat((file[0].size) / (1024 * 1024)).toFixed(1); //MB Calculation
            if (!(file_size <= 3)) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Max file size 3MB. You have uploaded ' + file_size + 'MB'
                });
                file_id.value = '';
                return false;
            }
        }
    }

</script>