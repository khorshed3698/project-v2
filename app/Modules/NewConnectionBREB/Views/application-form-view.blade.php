<?php
$accessMode = ACL::getAccsessRight('NewConnectionBREB');
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
    @if(in_array($appInfo->status_id,[5,6,17,22]))
        @include('ProcessPath::remarks-modal')
    @endif

    <div class="col-md-12">


        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <strong style="line-height: 30px;">
                        বি.আর.ই.বি (BREB)  নতুন সংযোগের জন্য আবেদন পত্র
                    </strong>
                </div>
                <div class="pull-right">

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>

                    @if(in_array($appInfo->status_id,[5,6,17,22]))
                        <a data-toggle="modal" data-target="#remarksModal">
                            {!! Form::button('<i class="fa fa-eye"></i> Reason of '.$appInfo->status_name.'', array('type'
                            => 'button', 'class' => 'btn btn-md btn-danger')) !!}
                        </a>
                    @endif
                </div>

            </div>

            <div class="panel-body">
                <ol class="breadcrumb">
                    <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                    <li class="highttext"><strong>BREB Tracking no. : {{ $appInfo->breb_tracking_id  }}</strong></li>
                    <li class="highttext"><strong> Date of
                            Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
{{--                    <li><strong>Current Desk--}}
{{--                            :</strong>--}}
{{--                        {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}--}}
{{--                    </li>--}}
                    @if(!empty($pdf->applicants_pdf))
                    <li style="margin-top:5px;" > <a href="{{ url('uploads/'.$pdf->applicants_pdf) }}" target="_blank" class="btn btn-primary btn-sm">Applicants Copy</a></li>
                    @endif
                </ol>

                @if(isset($demandInfo) && !empty($pdf->demand_note_pdf))
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <strong>Demand Note </strong>

                        </div>
                        <div class="panel-body">
                            @if( !empty($pdf->demand_note_pdf))
                                <a href="{{ url('uploads/'.$pdf->demand_note_pdf) }}" target="_blank" class="btn btn-primary btn-sm">Demand Note</a>
                            @endif
                        </div>
                        @if($appInfo->demand_status != 1)
                            <div class="panel-footer">
                                <div class="pull-right">
                                    <a class="btn btn-md btn-primary"
                                       href="/new-connection-breb/view/additional-payment/{{ Encryption::encodeId($appInfo->id)}}"
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

                {{--Payment information--}}
                @include('SonaliPaymentStackHolder::payment-information')

                @if($appInfo->status_id == '63')
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Shortfall Attachment</strong></div>
                    <div class="panel-body">
                        @include('NewConnectionBREB::shortfall-documents')
                    </div>
                </div>
                @endif

                @if(count($resubmitted_document) > 0)
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Shortfall ফাইল </strong></div>
                    <div class="panel-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <th>No.</th>
                            <th>Document Name</th>
                            <th>Action</th>
                            </thead>
                            <tbody>

                            @foreach($resubmitted_document as $key => $value)
                                <tr>
                                    <td>{{$key+1}} .</td>
                                    <td>{{ (!empty($value->doc_name)) ? $value->doc_name : '' }}</td>

                                    <td>
                                        @if(!empty($value->doc_path))
                                            <a target="_blank"
                                               class="btn btn-xs btn-primary"
                                               href="{{URL::to('/uploads/'.$value->doc_path)}}"
                                               title="{{$value->doc_name}}">
                                                <i class="fa fa-file-pdf-o"
                                                   aria-hidden="true"></i>
                                                Open File
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{--Company basic information--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>বিদ্যুৎ অফিসের বিবরণ</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">সমিতির নাম </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->pbs_name) ? explode('@', $appData->pbs_name)[1] : ''}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">জোনাল অফিস </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->zonal_office) ? explode('@', $appData->zonal_office)[2] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>সংযোগ</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">সংযোগের ট্যারিফ </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->tariff_name) ? explode('@', $appData->tariff_name)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>আবেদনকারীর বিবরণ</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">আবেদনকৃত প্রতিষ্ঠানের নাম  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->organization_name) ? $appData->organization_name : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">আবেদনকারীর নাম (বাংলা)    </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->name) ? $appData->name : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">পিতার নাম (বাংলা) </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->fName) ? $appData->fName : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">মাতার নাম (বাংলা)  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->mName) ? $appData->mName : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">স্বামী/স্ত্রীর নাম (বাংলা)  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->sName) ? $appData->sName : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">জন্ম তারিখ (ইংরেজি)  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->date_of_birth) ? $appData->date_of_birth : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">জাতীয়তা  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->nationality) ? explode('@', $appData->nationality)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">জাতীয়তা পরিচয় পত্র নম্বর (ইংরেজি)  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->national_id) ? $appData->national_id : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">পাসপোর্ট (ইংরেজি)  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->passport) ? $appData->passport : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">মোবাইল নম্বর (ইংরেজি)  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->mobile) ? $appData->mobile : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">ফোন নম্বর (ইংরেজি)  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->phone) ? $appData->phone : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">ইমেইল (ইংরেজি)  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->email) ? $appData->email : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">লিঙ্গ  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->gender) ? explode('@', $appData->gender)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>স্থায়ী ঠিকানা</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">জেলা  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->perm_dist) ? explode('@', $appData->perm_dist)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">উপজেলা   </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->perm_upazilla) ? explode('@', $appData->perm_upazilla)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">থানা  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->perm_thana) ? explode('@', $appData->perm_thana)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">ইউনিয়ন  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->perm_union) ? explode('@', $appData->perm_union)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">ডাকঘর  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->perm_post) ? $appData->perm_post : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">পোস্ট কোড  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->perm_post_code) ? $appData->perm_post_code : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">গ্রাম  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->perm_village) ? explode('@', $appData->perm_village)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">মহল্লা/রোড নম্বর  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->perm_road_no) ? $appData->perm_road_no : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">বাড়ির নাম/হোল্ডিং নম্বর  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->perm_house_holding) ? $appData->perm_house_holding : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>প্রস্তাবিত বিদ্যুৎ সংযোগ স্থলের বিবরণ</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">জেলা  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->cur_district) ? explode('@', $appData->cur_district)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">উপজেলা  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->cur_upazilla) ? explode('@', $appData->cur_upazilla)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">থানা  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->cur_thana) ? explode('@', $appData->cur_thana)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">ইউনিয়ন  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->cur_union) ? explode('@', $appData->cur_union)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">ডাকঘর  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->cur_post) ? $appData->cur_post : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">পোস্ট কোড  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->cur_post_code) ? $appData->cur_post_code : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">গ্রাম  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->cur_village) ? explode('@', $appData->cur_village)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">মহল্লা/রোড নম্বর  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->cur_road_no) ? $appData->cur_road_no : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">বাড়ির নাম/হোল্ডিং নম্বর  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->cur_house_holding) ? $appData->cur_house_holding : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">মৌজা  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->mouja) ? $appData->mouja : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">দাগ নম্বর  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->dag_no) ? $appData->dag_no : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">খতিয়ান নম্বর  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->khotian_no) ? $appData->khotian_no : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">জমির মালিকানার ধরণ  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->land_owner_type) ? explode('@', $appData->land_owner_type)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">জমির আইনগত মালিক  </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->land_owner_name) ? $appData->land_owner_name : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>জিওগ্রাফিক তথ্য</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">নিকটবর্তী সার্ভিস পোল হইতে দূরত্ব </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->service_drop_dist_consumer) ? $appData->service_drop_dist_consumer : ''}}
                                            <span> ফুট</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>কালেকশন এর বিবরণ</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">আবেদন প্রকৃতি </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->connection_type) ? explode('@', $appData->connection_type)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>চাহিদাকৃত লোড</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">মোট লোড(কি: ও:) </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->total_load_KW) ? $appData->total_load_KW : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">ফেজ </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->phase) ? explode('@', $appData->phase)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">ভোল্ট </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{!empty($appData->volt) ? explode('@', $appData->volt)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>বাড়ির/প্রতিষ্ঠানের লোকেশন এবং মন্তব্য</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            {{!empty($appData->location_remarks) ? $appData->location_remarks : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>আপলোডকৃত ফাইল </strong></div>
                    <div class="panel-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <th>No.</th>
                            <th>Document Name</th>
                            <th>Action</th>
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
                                        @if(!empty($appData->$doc_path))
                                            <a target="_blank"
                                               class="btn btn-xs btn-primary"
                                               href="{{URL::to('/uploads/'.$appData->$doc_path)}}"
                                               title="{{$appData->$doc_name}}">
                                                <i class="fa fa-file-pdf-o"
                                                   aria-hidden="true"></i>
                                                Open File
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{--Declaration and undertaking--}}
                <div class="mb0 panel panel-info">
                    <div class="panel-heading"><strong>Declaration and undertaking</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol type="a">
                                        <li>
                                            <p>I do hereby declare that the information given above is true
                                                to the best of my knowledge and I shall be liable for any
                                                false information/ statement given.</p>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Authorized person of the organization</legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Full Name</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">

                                                    {{ (!empty($appData->auth_name)) ? $appData->auth_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Designation</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty(Auth::user()->designation)) ? Auth::user()->designation : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <img style="width: 10px; height: auto;"
                                         src="{{ asset('assets/images/checked.png') }}"/>
                                    I do here by declare that the information given above is true to the best of my
                                    knowledge and I shall be liable for any false information/ system is given.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>