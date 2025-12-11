<?php
$accessMode = ACL::getAccsessRight('DNCC');
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
                        Application For New Trade License (DNCC)
                    </strong>
                </div>
                <div class="pull-right">

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>
                </div>
                @if($appInfo->status_id == 30)
                    <div class="pull-right">
                        <a class="btn btn-md btn-primary"  href="/dncc/check-payment/{{\App\Libraries\Encryption::encodeId($appInfo->id)}}" >
                            <i class="far fa-money-bill-alt"></i>
                            Dncc Fee Payment
                        </a>
                    </div>
                @endif

            </div>

            <div class="panel-body">

                <ol class="breadcrumb">
                    <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                    {{--                    <li><strong>DPDC Tracking no. : </strong>{{$appInfo->dpdc_tracking_no}}</li>--}}
                    <li class="highttext"><strong> Date of Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>

                    @if(!empty($appInfo->dncc_tracking_no) && empty($appInfo->tl_no))
                        <li><strong>DNCC Tracking no. : </strong>{{ $appInfo->dncc_tracking_no  }}</li>
                    @endif
                    @if(!empty($appInfo->tl_no) && $appInfo->status_id == 25)
                        <li><strong>DNCC TL No. : </strong>{{ $appInfo->tl_no  }}</li>
                    @endif
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link))
                        <li>
                            <a href="{{ url($appInfo->certificate_link) }}"
                               class="btn show-in-view btn-xs btn-info"
                               title="Download Approval Letter" target="_blank"> <i
                                        class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                        </li>
                    @endif
                </ol>

                {{--Payment information--}}
                @include('SonaliPaymentStackHolder::payment-information')


                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Business Type</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover" id="businessTypeDetails">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Fiscal Year</th>
                                        <th class="text-center">Business Type 1</th>
                                        <th class="text-center">Business Type 2</th>
                                        <th class="text-center">License Fee</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($appData->fiscalYear) > 0)
                                        @foreach($appData->fiscalYear as $key => $value)
                                            <tr>
                                                <td>{{explode('@',$appData->fiscalYear[$key])[1]}}</td>
                                                <td>{{explode('@',$appData->businessType_1[$key])[1]}}</td>
                                                <td>{{explode('@',$appData->businessType_2[$key])[1]}}</td>
                                                <td>{{$appData->businessLicense_fee[$key]}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Business Organization Details</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Name of the Business organization</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->business_org_name) ? $appData->business_org_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Nature of the Business organization</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->business_org_nature) ? explode('@',$appData->business_org_nature)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Paid up Capital (in the case of ltd. company)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->paid_capital) ? explode('@',$appData->paid_capital)[2] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Applicant Basic Info</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Name of the Applicant </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_name) ? $appData->applicant_name : ''}}
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
                                        <span class="v_label">Applicant's Father's Name </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_fathers_name) ? $appData->applicant_fathers_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Applicant's Mother's Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_mothers_name) ? $appData->applicant_mothers_name : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Applicant's relationship with the organization</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_relation_org) ? $appData->applicant_relation_org : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Nationality Of the Applicant</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->nationality) ? $appData->nationality : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">NID number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->nid_number) ? $appData->nid_number : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Passport</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->passport) ? $appData->passport : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Birth Reg. No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->birth_reg_no) ? $appData->birth_reg_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">BIN No. </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->bin_no) ? $appData->bin_no : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Applicant's Mobile No.</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->mobile_no) ? $appData->birth_reg_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Applicant's Email ID </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->email) ? $appData->email : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Other Identity</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->other_identity) ? $appData->other_identity : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Applicant's Residential Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->residential_address) ? $appData->residential_address : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Proposed Business Address  </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->proposed_business_address) ? $appData->proposed_business_address : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Permanent Address of the Applicant</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Holding No. </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->permanent_holding_no) ? $appData->permanent_holding_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Road No. </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->permanent_road_no) ? $appData->permanent_road_no : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Village\ Mahalla </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->permanent_village_or_mahalla) ? $appData->permanent_village_or_mahalla : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Post Code </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->permanent_post_code) ? $appData->permanent_post_code : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Division </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->permanent_division) ? explode('@',$appData->permanent_division)[1] : ''}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">District </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->permanent_district) ? explode('@',$appData->permanent_district)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Police Station </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->permanent_police_station) ? explode('@',$appData->permanent_police_station)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Owner's Current Address</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Holding No. </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->current_holding_no) ? $appData->current_holding_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Road No. </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->current_road_no) ? $appData->current_road_no : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Village\ Mahalla </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->current_village_or_mahalla) ? $appData->current_village_or_mahalla : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Post Code </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->current_post_code) ? $appData->current_post_code : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Division </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->current_division) ? explode('@',$appData->current_division)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">District </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->current_district) ? explode('@',$appData->current_district)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Police Station </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->police_station) ? explode('@',$appData->police_station)[1] : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Business Details</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Business Capital </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->business_capital) ? $appData->business_capital : ''}}
                                    </div>

                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Business Start Date </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->business_start_date) ? $appData->business_start_date : ''}}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">TIN number </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->tin_number) ? $appData->tin_number : ''}}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Place of Business (Rent/Own) </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->place_of_business) ? explode('@',$appData->place_of_business)[1] : ''}}
                                    </div>

                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Business/Shop Space Own/ Rent (Tax receipt of own house should be attached)  </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->business_shop_rent) ? explode('@',$appData->business_shop_rent)[1] : ''}}
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Is there a Sign Board?  </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->sign_board) ? explode('@',$appData->sign_board)[1] : ''}}
                                    </div>

                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Proposed Shop/Place of Business, Municipal Land/Government Land </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->proposed_place) ? explode('@',$appData->proposed_place)[1] : ''}}
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Shop Floor/Office on Which Floor </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->shop_floor) ? explode('@',$appData->shop_floor)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Zone </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->zone) ? explode('@',$appData->zone)[1] : ''}}
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Ward </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->ward) ? explode('@',$appData->ward)[1] : ''}}
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Sector/Section </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->sector_or_section) ? $appData->sector_or_section : ''}}
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Area/Block </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->area_or_block) ? $appData->area_or_block : ''}}
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Road </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->road) ? $appData->road : ''}}
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Plot/Holding no. </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->plot_or_holding_no) ? $appData->plot_or_holding_no : ''}}
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Shop No. </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->shop_no) ? $appData->shop_no : ''}}
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">License fee </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->license_fee) ? $appData->license_fee : ''}}
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Sign Board fee </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->sign_board_fee) ? $appData->sign_board_fee : ''}}
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Book Price </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->book_price) ? $appData->book_price : ''}}
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Sign Board sqft </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->sign_board_sqft) ? $appData->sign_board_sqft : ''}}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Outstanding </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->outstanding) ? $appData->outstanding : ''}}
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Outstanding Surcharge  </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->outstanding_surcharge) ? $appData->outstanding_surcharge : ''}}
                                            </div>

                                        </div>

                                        <div class="col-md-4 col-xs-12">
                                            <div class="col-md-5 col-xs-12">
                                                <span class="v_label">Vat Arrears  </span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7">
                                                {{!empty($appData->vat_arrears) ? $appData->vat_arrears : ''}}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Number of years for fee </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->number_of_years_for_fee) ? explode('@',$appData->number_of_years_for_fee)[1] : ''}}
                                    </div>

                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Annual Vat </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->annual_vat) ? $appData->annual_vat : ''}}
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Total Vat </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->total_vat) ? $appData->total_vat : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Income Tax Money  </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->income_tax_money) ? $appData->income_tax_money : ''}}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Total Price/Total Assessed Price </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->total_price) ? $appData->total_price : ''}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">New/Old Trade License</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->new_or_old_trade_license) ? explode('@',$appData->new_or_old_trade_license)[1] : ''}}
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
                                                <a target="_blank" class="btn btn-xs btn-primary"
                                                   href="{{URL::to('/uploads/'.$appData->$doc_path)}}"
                                                   title="{{$appData->$doc_name}}">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
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
