<?php
$accessMode = ACL::getAccsessRight('eTINforeigner');
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
                        Application For E-TIN-Foreigner
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
                    <li class="highttext"><strong> Date of Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    @if(!empty($appInfo->tin_no))
                        <li><strong>NBR-TIN No. : </strong>{{ $appInfo->tin_no}}</li>
                    @elseif(!empty($appInfo->etin_foreigner_tracking_no))
                        <li><strong>NBR-Track No. : </strong>{{ $appInfo->etin_foreigner_tracking_no  }}</li>
                    @endif
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link))
                        <li>
                            <a href="{{ url('/uploads/'.$appInfo->certificate_link) }}"
                               class="btn show-in-view btn-xs btn-info"
                               title="Download Approval Letter" target="_blank"> <i
                                        class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                        </li>
                    @endif
                    @if(Auth::user()->user_type == '1x101' && $alreadySubmitted == 0)
                        <li>
                            <a class="btn btn-md btn-success"  href="{{url('/e-tin-foreigner/regenerate-submission-json/'.\App\Libraries\Encryption::encodeId($appInfo->id))}}" >
                                Json Re-Generate
                            </a>
                        </li>
                    @endif
                </ol>

                {{--Payment information--}}
                @include('SonaliPaymentStackHolder::payment-information')
                @if($appInfo->status_id == 6)
                    <div class="panel panel-danger">
                    <div class="panel-heading"><strong>Reject Reason</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <h4 style="text-align: center;">{{$appInfo->process_desc}}</h4>
                            </div>
                        </div>
                        @if(!empty($appInfo->existing_tin_info))
                            <?php $decodedTinInfo = json_decode($appInfo->existing_tin_info)?>
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Existing Tin information</strong></div>
                                <div class="panel-body">
                                    <h4 style="text-align: left;">
                                        Tin No: {{$decodedTinInfo->tinNo}}<br><br>
                                        Name: {{$decodedTinInfo->AssesName}}<br><br>
                                        @if(!empty($appInfo->certificate_link))
                                            Certificate : <a href="{{ url('/uploads/'.$appInfo->certificate_link) }}" class="btn btn-info"> Download Certificate</a>
                                        @endif
                                    </h4>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Registration</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Taxpayers Status / করদাতার ধরণ : a)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->taxpayer_status) ? explode('@',$appData->taxpayer_status)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label"> b) </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->taxpayer_status_b) ? explode('@',$appData->taxpayer_status_b)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6" style="{{!empty($appData->country_id) ?'':'display:none'}}">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Country / দেশ </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->country_id) ? explode('@',$appData->country_id)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Registration Type / রেজিস্ট্রেশন ধরণ </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->registration_type) ? explode('@',$appData->registration_type)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Main Source of Income / আয়ের প্রধান উৎস </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->main_source_income) ? explode('@',$appData->main_source_income)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Location of main source of income </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->localtion_main_source_income) ? explode('@',$appData->localtion_main_source_income)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="col-md-7 col-xs-12">--}}
{{--                                        <span class="v_label">Business (Individual/Firm) </span>--}}
{{--                                        <span class="pull-right">&#58;</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-5">--}}
{{--                                        {{!empty($appData->business_individual) ? explode('@',$appData->business_individual)[1] : ''}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6" style="{{!empty($appData->business_type) ? "display:block":"display:none"}}">--}}
{{--                                    <div class="col-md-7 col-xs-12">--}}
{{--                                        <span class="v_label">Business Type </span>--}}
{{--                                        <span class="pull-right">&#58;</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-5">--}}
{{--                                        {{!empty($appData->business_type) ? explode('@',$appData->business_type)[1] : ''}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6" style="{{!empty($appData->juri_select_list_no) ? "display:block":"display:none"}}">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Type of Employer/ Service Location </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->juri_select_list_no) ? explode('@',$appData->juri_select_list_no)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6" style="{{!empty($appData->juri_sub_list_name) ? "display:block":"display:none"}}">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Organization/Institution Name </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->juri_sub_list_name) ? $appData->juri_sub_list_name : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Basic Information</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Taxpayer s Name </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->taxpayer_name) ? $appData->taxpayer_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Gender </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->gender) ? explode('@',$appData->gender)[1] : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Date of Birth (DoB) </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->date_of_birth) ? $appData->date_of_birth : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label"> Father's Name </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->father_name) ? $appData->father_name : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Mother's Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->mother_name) ? $appData->mother_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Spouse's Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->spouse_name) ? $appData->spouse_name : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Passport Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->passport_number) ? $appData->passport_number : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Passport Type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->passport_type) ? explode('@',$appData->passport_type)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Passport Issue Date</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->passport_issue_date) ? $appData->passport_issue_date : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Passport Expiry Date</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->passport_expiry_date) ? $appData->passport_expiry_date : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Visa Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->visa_number) ? $appData->visa_number : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Visa Issue Date </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->visa_issue_date) ? $appData->visa_issue_date : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $tax_type = !empty($masterData->taxpayer_status_b) ? explode('@', $masterData->taxpayer_status_b)[0]: '';
                        ?>
{{--                        <div id="non_bangladeshi" style="{{($tax_type == 7)?'':'display:none'}}">--}}
                            <div class="form-group" style="display: none">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Director Foreigner without Work Permit? </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ !empty($appData->director_foreigner)&& ($appData->director_foreigner == '1') ? 'Yes' : 'No' }}
                                    </div>
                            </div>
                            <div id="without_workpermit" style="{{!empty($appData->director_foreigner)&& ($appData->director_foreigner == '1')?'':'display:none'}}">
                                <div class="form-group">
                                    <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Company TIN </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->company_tin) ? $appData->company_tin : ''}}
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div id="workpermit" style="{{!empty($appData->director_foreigner )&& ($appData->director_foreigner== '1')? 'display:none':''}}">
                                <h3 style="text-align: center;color:#f0f0f0f; text-decoration: bold;">Work Permit Authority</h3><br/>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Authority Name </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->authority_name) ? explode('@',$appData->authority_name)[1] : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Registration Number </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->registration_number) ? $appData->registration_number : ''}}
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Registration Date </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->registration_date) ? $appData->registration_date : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
{{--                        </div>--}}
                        <div id="non_bangladeshi_minor" style="{{($tax_type == 9)?'':'display:none'}}">
                            <div class="form-group">
                                <div class="col-md-6">
                                    {!! Form::label('guardian_passport','Guardians Passport Number :',['class'=>'text-left col-md-6 required-star']) !!}
                                    <div class="col-md-6">
                                        {!! Form::text('guardian_passport', !empty($appData->guardian_passport) ? $appData->guardian_passport : '',['class' => 'form-control
                                        input-md','id'=>'guardian_passport']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('guardian_passport_issue_date','Guardians Passport Issue Date :',['class'=>'text-left col-md-6 required-star']) !!}
                                    <div class="datepicker col-md-6">
                                        {!! Form::text('guardian_passport_issue_date', !empty($appData->guardian_passport_issue_date) ? $appData->guardian_passport_issue_date : '',['class' => 'form-control
                                        input-md','id'=>'guardian_passport_issue_date','style'=>'background:white;']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Mobile Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->mobile_number) ? $appData->mobile_number : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Facsimile</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->facsimile) ? $appData->facsimile : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Email</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->email) ? $appData->email : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Taxpayer s National ID/ SMART CARD Number/জাতীয় পরিচিতি নম্বর</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->taxpayer_id) ? $appData->taxpayer_id : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Photo</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 {{$errors->has('photo') ? 'has-error': ''}}">
                                    <a target="_blank" class="btn btn-xs btn-primary"
                                       href="{{URL::to('/uploads/'.$appData->validate_field_photo)}}"
                                       title="{{$appData->validate_field_photo}}">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        Open File
                                    </a>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover ">
                                    <thead>
                                    <tr>
                                        <th class="text-center"></th>
                                        <th class="text-center">Country</th>
                                        <th class="text-center">Address</th>
                                        <th class="text-center">District/ State</th>
                                        <th class="text-center">Thana</th>
                                        <th class="text-center">Post Code/ Zip Code</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td width="20%"><p>Current Address (For Indivisual "Present Residential Address")</p></td>
                                        <td width="16%">
                                            {{!empty($appData->present_country) ? explode('@',$appData->present_country)[1] : ''}}
                                            </td>
                                        <td width="30%">
                                            <span class="v_label">Line 1 &#58;</span>{{!empty($appData->address_line1_p) ? $appData->address_line1_p : ''}}<br>
                                            <span class="v_label">Line 2 &#58;</span>{{!empty($appData->address_line2_p) ? $appData->address_line2_p : ''}}
                                        </td>
                                        <td width="12%">
                                            {{!empty($appData->present_district) ? explode('@',$appData->present_district)[1] : ''}}
                                            {{!empty($appData->present_state) ? $appData->present_state : ''}}
                                        </td>
                                        <td width="12%">
                                            {{!empty($appData->present_thana) ? explode('@',$appData->present_thana)[1] : ''}}
                                        </td>
                                        <td width="10%">
                                            {{!empty($appData->present_post_code) ? $appData->present_post_code : ''}}
                                        </td>
                                    </tr>
                                    <tr id="same_as_current_tr">
                                        <td colspan="6">
                                            <div class="col-md-offset-2 col-md-1">
                                                <input type="checkbox" value="1" name="same_as_current" id="same_as_current" {{
                                                    !empty($appData->same_as_current) ? 'checked' : ''}}>
                                            </div>
                                            {!! Form::label('same_as_current','Same as Current Address',['class'=>'text-left col-md-6']) !!}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td width="20%"><p>Permanent Address </p></td>
                                        <td width="16%">
                                            {{!empty($appData->permanent_country) ? explode('@',$appData->permanent_country)[1] : ''}}
                                           </td>
                                        <td width="30%">
                                            <span class="v_label">Line 1 &#58;</span> {{!empty($appData->address_line1_per) ? $appData->address_line1_per : ''}}<br>
                                            <span class="v_label">Line 2 &#58;</span>{{!empty($appData->address_line2_per) ? $appData->address_line2_per : ''}}
                                        </td>
                                        <td width="12%">
                                            {{!empty($appData->permanent_district) ? explode('@',$appData->permanent_district)[1] : ''}}<br>
                                            {{!empty($appData->permanent_state) ? $appData->permanent_state : ''}}
                                        </td>
                                        <td width="12%">
                                            {{!empty($appData->permanent_thana) ? explode('@',$appData->permanent_thana)[1] : ''}}
                                        </td>
                                        <td width="10%">
                                            {{!empty($appData->permanent_post_code) ? $appData->permanent_post_code : ''}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%"><p>Other Address (Working / Bussiness Address)</p></td>
                                        <td width="16%">
                                            {{!empty($appData->other_country) ? explode('@',$appData->other_country)[1] : ''}}
                                            </td>
                                        <td width="30%">
                                            <span class="v_label">Line 1 &#58;</span>{{!empty($appData->address_line1_p) ? $appData->address_line1_p : ''}}<br>
                                            <span class="v_label">Line 2 &#58;</span>{{!empty($appData->address_line2_p) ? $appData->address_line2_p : ''}}
                                        </td>
                                        <td width="12%">
                                            {{!empty($appData->other_district) ? explode('@',$appData->other_district)[1] : ''}}
                                            {{!empty($appData->other_state) ? $appData->other_state : ''}}
                                        </td>
                                        <td width="12%">
                                            {{!empty($appData->other_thana) ? explode('@',$appData->other_thana)[1] : ''}}
                                        </td>
                                        <td width="10%">
                                            {{!empty($appData->other_post_code) ? $appData->other_post_code : ''}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                @if(!empty($appData->dynamicDocumentsId))
                    <div class="form-group" style="">
                        <div class="row">
                            <div class=" col-md-12">
                                <table class="table table-bordered table-hover" id="loadDetails">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Document Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($appData->dynamicDocumentsId as $key => $value)
                                        <?php
                                        $dynamicDocuments = explode('@', $appData->dynamicDocumentsId[$key]);
                                        $dynamicDocumentsId = !empty($dynamicDocuments[0]) ? $dynamicDocuments[0] : '';
                                        $doc_name = 'doc_name_' . $dynamicDocumentsId;
                                        $doc_path = 'validate_field_' . $dynamicDocumentsId;
                                        ?>
                                        @if(!empty($appData->$doc_path))
                                           <tr>
                                            <td>{{$key+1}} .</td>
                                            <td>{{ (!empty($appData->$doc_name)) ? $appData->$doc_name : '' }}</td>

                                            <td>
                                                <a target="_blank" class="btn btn-xs btn-primary"
                                                   href="{{URL::to('/uploads/'.$appData->$doc_path)}}"
                                                   title="{{$appData->$doc_name}}">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    Open File
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
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
