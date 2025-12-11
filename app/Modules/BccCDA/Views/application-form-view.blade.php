<?php
$accessMode = ACL::getAccsessRight('BccCDA');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>

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
                <strong class="col-md-10" style="line-height: 30px;"> নির্মাণ (Building Construction) অনুমোদনের জন্য
                    আবেদন পত্র
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
                <li class="highttext"><strong>CDA Tracking no. : {{ $appInfo->bcc_cda_tracking_id  }}</strong></li>
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
                @if($appInfo->bc_case_no != "" && $appInfo->bc_case_no != null)
                    <li>
                        <strong>BC Case No :</strong>
                        {{ $appInfo->bc_case_no}}
                    </li>
                @endif

                @if(empty($appInfo->bc_case) || $appInfo->status_id == 2)
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
                    @include('BccCDA::resubmit-add')
                @endif

                @if($appInfo->status_id == 32)
                    @include('BccCDA::resubmit-view')
                @endif
            </div>


            <fieldset>

                <div class="form-group col-xs-12" style="margin-top: 15px;">

                    <div class="row">
                        <div class="form-group col-md-6 col-xs-12">
                            <div class="col-md-6 col-xs-7">
                                <span class="v_label">১। আবেদনকারীর নাম  </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6 col-xs-5">
                                {{!empty($appData->applicant_name) ? $appData->applicant_name:''}}
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-xs-12">
                            <div class="col-md-6 col-xs-7">
                                <span class="v_label">১.২। আবেদনকারীর মোবাইল নং  </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6 col-xs-5">
                                {{!empty($appData->applicant_mobile_no) ? $appData->applicant_mobile_no:''}}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-xs-12">
                            <div class="col-md-6 col-xs-7">
                                <span class="v_label">১.৩। আবেদনকারীর ইমেইল   </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6 col-xs-5">
                                {{!empty($appData->applicant_email) ? $appData->applicant_email:''}}
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-xs-12">
                            <div class="col-md-6 col-xs-7">
                                <span class="v_label">১.৪। আবেদনকারীর  জাতীয় পরিচয়পত্র  </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6 col-xs-5">
                                {{!empty($appData->applicant_nid_no) ? $appData->applicant_nid_no:''}}
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-xs-12">
                            <div class="col-md-6 col-xs-7">
                                <span class="v_label"> ১.৫। আবেদনকারীর টি.ই.ন নং </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6 col-xs-5">
                                {{!empty($appData->applicant_tin_no) ? $appData->applicant_tin_no:''}}
                            </div>
                        </div>

                        <div class="form-group col-md-6 col-xs-12">
                            <div class="col-md-6 col-xs-7">
                                <span class="v_label">২। বর্তমান ঠিকানা </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6 col-xs-5">
                                {{!empty($appData->applicant_present_address) ? $appData->applicant_present_address:''}}
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-xs-12">
                            <div class="col-md-6 col-xs-7">
                                <span class="v_label">৩।প্রস্তাবিত ইমারতের ব্যবহারের ধরণ  </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6 col-xs-5">
                                {{!empty($appData->suggested_building_use) ? explode('@',$appData->suggested_building_use)[1]:''}}
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-xs-12">
                            <div class="col-md-6 col-xs-7">
                                <span class="v_label">৩.১। প্রস্তাবিত ইমারতের বসবাসের ধরণ </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-6 col-xs-5">
                                {{!empty($appData->suggested_building_living_usage) ? explode('@',$appData->suggested_building_living_usage)[3]:''}}
                            </div>
                        </div>
                    </div>

                </div>

                {{--second start --}}
                <div class="form-group col-xs-12">
                    {!! Form::label('','৪। প্রস্তাবিত জমি/প্লট এর অবসথান ও পরিমাণ :',['class'=>'v_label col-md-8 col-xs-12']) !!}
                    <div class="col-md-10 col-md-offset-1">
                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">ক) সিটি কর্পোরেশন </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->city_corporation_id) ? explode('@', $appData->city_corporation_id)[1] : ''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">খ) বি. এস</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->bs_code) ? $appData->bs_code:''}}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">গ) আর. এস </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->rs_code) ? $appData->rs_code:''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">ঘ) থানার নাম </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->thana_name) ? explode('@', $appData->thana_name)[1] : ''}}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">ঙ) মৌজা নাম  </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->mouza_name) ? explode('@', $appData->mouza_name)[2] : ''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">চ) ব্লক নং  </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->block_no) ? explode('@', $appData->block_no)[1] : ''}}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">ছ) সিট নং </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->seat_no) ? explode('@', $appData->seat_no)[1] : ''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">জ) ওয়াড নং  </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->ward_no) ? explode('@', $appData->ward_no)[1] : ''}}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">ঝ) সেক্টর নং  </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->sector_no) ? explode('@', $appData->sector_no)[1] : ''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">ঞ) রাস্তার নাম  </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->road_name) ? $appData->road_name:''}}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">ট) বাহূ মাপ সহ জমি/প্লটের পরিমাণ </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->arm_size_land_plot_amount) ? $appData->arm_size_land_plot_amount:''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-6 col-xs-6">
                                    <span class="v_label">ঠ) জমি/প্লট এ বিদ্যমান বাড়ি/ কাঠামোর বিবরণ  </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    {{!empty($appData->existing_house_plot_land_details) ? $appData->existing_house_plot_land_details:''}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- second end--}}

                <div class="form-group col-xs-12">
                    {!! Form::label('','৫। প্রস্তাবিত উন্নয়ন/নির্মাণ কজের বিস্তারিত তথ্যাদি :',['class'=>'v_label col-md-8 col-xs-12']) !!}
                    <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                        <div class="form-group">
                            <span class="v_label">৫.১। প্রস্তাবিত উন্নয়ন/নির্মাণ কজের প্রকার / পপ্রকার / প্রকারসমূহ (পরিশিষ্ট-৩ এর বর্ণনানুসারে) :</span>
                            {{!empty($appData->proposed_dev_work_type) ? $appData->proposed_dev_work_type : ''}}
                        </div>
                        <div class="form-group">
                            <span class="v_label">৫.২। উপরে উল্লেখিত ধরণ অনুযায়ী ব্যবহার/ফ্লোরের ক্ষেত্রফল এর বিস্তারিত বর্ণনা :</span>
                        </div>
                        <div class="form-group">
                            <span class="v_label col-md-12 col-xs-12">ক) জমি/প্লট এর ক্ষেত্রফল (বর্গমিটার ) : </span>
                            <span class="col-md-12 col-xs-12">{{!empty($appData->total_land_area) ? $appData->total_land_area : ''}}</span>

                        </div>

                        <div class="form-group">
                            <span class="v_label col-md-3 col-xs-12">খ) বাহু সমূহের পরিমাপ :</span>
                            <div class="form-group col-md-9 col-xs-12">
                                <div class="form-group col-md-3 col-xs-6">
                                    <span class="v_label col-md-3 col-xs-3">দক্ষিণে</span>
                                    <div class="col-md-6 col-xs-4 text-center">{{!empty($appData->arm_size_south) ? $appData->arm_size_south:''}}</div>
                                    <span class="v_label col-md-3 col-xs-3">মিটার</span>
                                </div>
                                <div class="form-group col-md-3 col-xs-6">
                                    <span class="v_label col-md-3 col-xs-3">উত্তরে</span>
                                    <div class="col-md-6 col-xs-4 text-center">{{ !empty($appData->arm_size_north) ? $appData->arm_size_north:''}}</div>
                                    <span class="v_label col-xs-3">মিটার</span>
                                </div>
                                <div class="form-group col-md-3 col-xs-6">
                                    <span class="v_label col-md-3 col-xs-3">পুর্বে</span>
                                    <div class="col-md-6 col-xs-4 text-center">{{ !empty($appData->arm_size_east) ? $appData->arm_size_east:''}}</div>
                                    <span class="v_label col-xs-3">মিটার</span>
                                </div>
                                <div class="form-group col-md-3 col-xs-6">
                                    <span class="v_label col-md-3 col-xs-3">পশ্চিমে</span>
                                    <div class="col-md-6 col-xs-4 text-center">{{ !empty($appData->arm_size_west) ? $appData->arm_size_west:''}}</div>
                                    <span class="v_label col-xs-3">মিটার</span>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    {!! Form::label('','৬। নির্মিত ইমারত বা প্রকল্পের বিবরণ ( প্রয়োজনে তালিকাটি বিস্তৃত করা যাইতে পারে) :',['class'=>'v_label col-md-10']) !!}
                </div>

                <div class="col-md-10 col-md-offset-1 col-xs-12" style="margin-bottom: 10px;">
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

                <div class="form-group">
                    {!! Form::label('','৭। নির্মাণ অনুমোদনের জন্য পেশকৃত ফি, দলিলদি ও নকশার তালিকা :',['class'=>'v_label col-md-8']) !!}
                    <div class="col-md-10 col-md-offset-1 col-xs-12">
                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">১। স্বত্বাধিকারী ইজারা দলিল/ক্রয় দলিল/হেবা/অন্যান্য </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->owner_documents) ? $appData->owner_documents:''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">২। সরকার কর্তৃক বরাদ্দহকৃত জমি হয়লে ইহার দলিলাদি ও অনুমতিপত্র </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->gov_allocated_land_doc) ? $appData->gov_allocated_land_doc:''}}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">৩। বিধি অনুযায়ী ফি প্রদানের রশিদ </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->fee_receipt) ? $appData->fee_receipt:''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">৪। ভুমি ব্যবহারের ছাড়পত্র (প্রযোজ্য ক্ষেত্রে) </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->land_usage_exemption) ? $appData->land_usage_exemption:''}}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">৫। বিশেষ প্রকল্প ছাড়পত্র (প্রযোজ্য ক্ষেত্রে) </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->special_project_exemption) ? $appData->special_project_exemption:''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">৬। ইনডেমনিটি বন্ড (প্রযোজ্য ক্ষেত্রে)</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->indempty_bond) ? $appData->indempty_bond:''}}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">৭। মৃত্তিকা পরীক্ষার রিপোর্ট (প্রযোজ্য ক্ষেত্রে)</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->soil_test_report) ? $appData->soil_test_report:''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">	৮। Floor Area Ratio (FAR) এর হিসাব</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->far_calculation) ? $appData->far_calculation:''}}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">৯। বিধি মোতাবেক যাবতীয় নকশা</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->all_designs) ? $appData->all_designs:''}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">১০। বিধি মোতাবেক গৃহীত ব্যবস্থা</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->action_taken) ? $appData->action_taken:''}}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-xs-12">
                                <div class="col-md-9 col-xs-9">
                                    <span class="v_label">১১। সংশ্লিট বিভিন্ন কতৃপক্ষ এর ছাড়পত্র/অনাপত্তিপত্র (প্রযোজ্য ক্ষেত্রে)</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    {{!empty($appData->authority_exmption) ? $appData->authority_exmption:''}}
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