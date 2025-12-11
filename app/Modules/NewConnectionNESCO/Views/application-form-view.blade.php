<?php
$accessMode = ACL::getAccsessRight('NewConnectionNESCO');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}

?>
<style>
    .panel-heading {
        padding: 2px 5px;
        overflow: hidden;
    }

    .row > .col-md-5,
    .row > .col-md-7,
    .row > .col-md-3,
    .row > .col-md-9,
    .row > .col-md-12 > strong:first-child {
        padding-bottom: 5px;
        display: block;
    }

    legend.scheduler-border {
        font-weight: normal !important;
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

    .mb5 {
        margin-bottom: 5px;
    }

    .mb0 {
        margin-bottom: 0;
    }
</style>
<section class="content" id="applicationForm">

    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <strong style="line-height: 30px;">
                        Application For New Connection (NESCO)
                    </strong>
                </div>
                <div class="pull-right">

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>


                </div>

            </div>

            <div class="panel-body">

                <ol class="breadcrumb">
                    <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                                        <li class="highttext"><strong>NESCO Tracking no. : {{$appInfo->nesco_tracking_no}}</strong></li>
                    <li  class="highttext"><strong> Date of Submission: {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                                        <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
{{--                    <li><strong>Current Desk--}}
{{--                            :</strong>--}}
{{--                        {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}--}}
{{--                    </li>--}}
                </ol>

                @if($appInfo->demand_rep !=null && $appInfo->demand_rep !='')
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <strong>Demand Note </strong>

                        </div>
                        <div class="panel-body">
                            @if($appInfo->demand_rep !=null && $appInfo->demand_rep !='')
                                <div class="col-md-3">
                                    <a class="btn btn-md btn-info"
                                       href="{{$appInfo->demand_rep}}"
                                       role="button" target="_blank">
                                        <i class="far fa-money-bill-alt"></i>
                                        Demand Note
                                    </a>
                                </div>
                            @endif
                            @if($appInfo->estimate_rep !=null && $appInfo->estimate_rep !='')
                                <div class="col-md-3">
                                    <a class="btn btn-md btn-info"
                                       href="{{$appInfo->estimate_rep}}"
                                       role="button" target="_blank">
                                        <i class="far fa-money-bill-alt"></i>
                                        Estimate Rep
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if($appInfo->demand_submit != 1)
                            <div class="panel-footer">
                                <div class="pull-right">
                                    <a class="btn btn-md btn-primary"
                                       href="/new-connection-nesco/view/additional-payment/{{ Encryption::encodeId($appInfo->id)}}"
                                       role="button">
                                        <i class="far fa-money-bill-alt"></i>
                                        Demand Fee Pay
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        @endif
                    </div>
                @endif
                @include('SonaliPaymentStackHolder::payment-information')

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>সাধারণ তথ্যাবলী</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">নাম</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_name) ? $appData->applicant_name :''}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">পিতা/প্রতিষ্ঠান</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->father_name_or_organization) ? $appData->father_name_or_organization :''}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">মার নাম</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_mother_name) ? $appData->applicant_mother_name :''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">স্বামী/স্ত্রীর নাম</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_husband_or_wife_name) ? $appData->applicant_husband_or_wife_name :''}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">জন্ম তারিখ</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->applicant_dob) ? $appData->applicant_dob :''}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">লিঙ্গ</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_gender) ? explode('@',$appData->applicant_gender)[1] :''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">ডাকঘর</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_post_office) ? $appData->applicant_post_office :''}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">জেলা</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_district) ?  explode('@',$appData->applicant_district)[2] :''}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label"> জাতীয় পরিচয় পত্র </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_nid_no) ? $appData->applicant_nid_no :''}}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">পোস্ট কোড</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_post_code) ? $appData->applicant_post_code :''}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">টিন নম্বর</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_tin) ?  $appData->applicant_tin :''}}
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>যোগাযোগের তথ্যাবলী</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">১ম ঠিকানা</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_1') ? 'has-error': ''}}">
                                        {{!empty($appData->address_line_1) ? $appData->address_line_1 :''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">২য় ঠিকানা</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_2') ? 'has-error': ''}}">
                                        {{!empty($appData->address_line_2) ? $appData->address_line_2 :''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">মোবাইল নং</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('mobile_no') ? 'has-error': ''}}">
                                        {{!empty($appData->mobile_no) ? $appData->mobile_no :''}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">ই-মেইল</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('email') ? 'has-error': ''}}">
                                        {{!empty($appData->email) ? $appData->email :''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>সংযোগ স্থানের বিবরণ</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">বাড়ি/দাগ নং</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('house_or_dag_no') ? 'has-error': ''}}">
                                        {{!empty($appData->house_or_dag_no) ? $appData->house_or_dag_no :''}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">প্লট নং </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('plot_no') ? 'has-error': ''}}">
                                        {{!empty($appData->plot_no) ? $appData->plot_no :''}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">এভিনিউ/লেন/রাস্তা</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('av_lane_road_no') ? 'has-error': ''}}">
                                        {{!empty($appData->av_lane_road_no) ? $appData->av_lane_road_no :''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">ব্লক</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('block') ? 'has-error': ''}}">
                                        {{!empty($appData->block) ? $appData->block :''}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">জেলা</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('district') ? 'has-error': ''}}">
                                        {{!empty($appData->district) ?  explode('@',$appData->district)[2]  :''}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">থানা</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('thana') ? 'has-error': ''}}">
                                        {{!empty($appData->thana) ? explode('@',$appData->thana)[2] :''}}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">সেকশন</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->section) ? $appData->section :''}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">বিভাগ</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->division) ? explode('@',$appData->division)[2] :''}}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label"> বিদ্যমান হিসাব নং</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->existing_account_no) ? $appData->existing_account_no :''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>সংযোগের বিবরণ/অতিরিক্ত লোড</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">ধরণ</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->connection_type) ? explode('@',$appData->connection_type)[2] :''}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">লোড</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->load) ? $appData->load :''}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">ফেইজ</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->phase) ? explode('@',$appData->phase)[2] :''}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">শ্রেণী</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->tariff) ? explode('@',$appData->tariff)[2] :''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary" style="margin-top: 15px;">
                            <div class="panel-heading"><strong>সংযোগের সংখ্যা</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">মিটারের সংখ্যা</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->meter) ? $appData->meter :''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>কাগজপত্র আপলোড</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">আবেদনকারীর ছবি</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <a target="_blank" class="btn btn-xs btn-primary"
                                           href="{{URL::to('/uploads/'.$appData->validate_field_photo)}}"
                                           title="{{$appData->validate_field_photo}}">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">আবেদনকারীর স্বাক্ষর</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <a target="_blank" class="btn btn-xs btn-primary"
                                           href="{{URL::to('/uploads/'.$appData->validate_field_signature)}}"
                                           title="{{$appData->validate_field_signature}}">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">আবেদনকারীর এন আইডি</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <a target="_blank" class="btn btn-xs btn-primary"
                                           href="{{URL::to('/uploads/'.$appData->validate_field_nid)}}"
                                           title="{{$appData->validate_field_nid}}">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">আবেদনকারীর জমি খারিজের কপি</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <a target="_blank" class="btn btn-xs btn-primary"
                                           href="{{URL::to('/uploads/'.$appData->validate_field_land)}}"
                                           title="{{$appData->validate_field_land}}">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                @if(!empty($appData->dynamicDocumentsId))
                    <div class="form-group" style="">
                        <div class="row">
                            <div class=" col-md-12">
                                <table class="table table-bordered table-hover"
                                       id="loadDetails">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Document Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($appData->dynamicDocumentsId as $key => $value)
                                        <tr>
                                            <?php
                                            $dynamicDocuments = explode('@', $appData->dynamicDocumentsId[$key]);
                                            $dynamicDocumentsId = !empty($dynamicDocuments[0]) ? $dynamicDocuments[0] : '';
                                            $doc_name = 'doc_name_' . $dynamicDocumentsId;
                                            $doc_path = 'validate_field_' . $dynamicDocumentsId;
                                            ?>
                                            <td>{{$key+1}} .</td>
                                            <td>{{ (!empty($appData->$doc_name)) ? $appData->$doc_name : '' }}</td>

                                            <td>
                                                <a target="_blank"
                                                   class="btn btn-xs btn-primary"
                                                   href="{{URL::to('/uploads/'.$appData->$doc_path)}}"
                                                   title="{{$appData->$doc_name}}">
                                                    <i class="fa fa-file-pdf-o"
                                                       aria-hidden="true"></i>
                                                    Open File
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                @endif


            </div>
        </div>
    </div>
</section>
