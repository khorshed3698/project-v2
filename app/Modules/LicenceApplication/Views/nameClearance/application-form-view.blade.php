<?php
$accessMode = ACL::getAccsessRight('NameClearance');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<style>
    .form-group {
        margin-bottom: 8px;
    }

    .row > .col-md-6, .row > .col-md-6, .row > .col-md-3, .row > .col-md-9, .row > .col-md-12 > strong:first-child {
        padding-bottom: 5px;
        display: block;
    }
    .custom-legend {
        width: 19%;
        border-bottom: 0px;
        font-size: 16px;
        margin-left: 20px;
        padding-left: 10px;
    }

    .custom-fieldset {
        border: 1px solid rgba(212, 212, 212, 1);
        border-radius: 4px;
        margin: 10px;
        padding: 12px;
    }
</style>

<section class="content">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                <div class="panel panel-primary" id="inputForm">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <b> Application Form</b>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-sm btn-primary" data-toggle="collapse" href="#paymentInfo" role="button"
                               aria-expanded="false" aria-controls="collapseExample">
                                <i class="fa fa-money"></i> Payment Info
                            </a>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                    <div class="panel-body">
                        <ol class="breadcrumb">
                            <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                            <li><strong> Date of
                                    Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }}
                            </li>
                            <li><strong>Current Status : </strong>
                                @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                                @else {!! $appInfo->status_name !!}
                                @endif
                            </li>
                            @if(isset($appInfo) && $appInfo->status_id == 25 && !empty($appInfo->cert_no))
                            <li><strong>Submission No : </strong>
                                {{$appInfo->cert_application_no}}
                            </li>
                            <li><strong>Clearance Letter : </strong>

                                {{$appInfo->cert_no}}
                            </li>
                            @endif

                            @if (isset($appInfo) && ($appInfo->certificate_link != ''))
                                <li>
                                    <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-xs btn-info"
                                       title="Download Approval Letter" target="_blank"> <i class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                                </li>
                            @endif
                        </ol>
                        @include('SonaliPaymentStackHolder::payment-information')
                    </div>
                    <fieldset>
                        <div class="form-group clearfix">
                            <div class="row">
                                <div class="col-md-11 col-md-offset-1">
                                    <div class="col-md-2">
                                        <span class="v_label">Company Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-10">
                                        {{$appInfo->company_name}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="row">
                                <div class="col-md-11 col-md-offset-1">
                                    <div class="col-md-2">
                                        <span class="v_label"> RJSC Office</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-10">
                                        {{$appInfo->rjsc_office_name}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="row">
                                <div class="col-md-11 col-md-offset-1">
                                    <div class="col-md-2">
                                        <span class="v_label"> Company Type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-10">
                                        {{$appInfo->company_type_name}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <fieldset class="custom-fieldset">
                                <legend class="custom-legend">Personal Information</legend>

                                <div class="row" style="margin-right:6px; margin-bottom:10px;">
                                    <div class="col-md-6">
                                        <div class="col-md-4">
                                            <span class="v_label"> Name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8">
                                            {{ $appInfo->applicant_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="col-md-4">
                                            <span class="v_label"> Position</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>

                                        <div class="col-md-8">
                                           {{$appInfo->designation}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-right:6px; margin-bottom:10px;">
                                    <div class="col-md-6">
                                        <div class="col-md-4">
                                            <span class="v_label"> Mobile Phone</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8">
                                            {{$appInfo->mobile_number}}
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="col-md-4">
                                            <span class="v_label">  E-mail</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8">
                                            {{$appInfo->email}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-right:6px; margin-bottom:10px;">
                                    <div class="col-md-6">
                                        <div class="col-md-4">
                                            <span class="v_label"> District</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8">
                                            {{$appInfo->district_name}}
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="col-md-4">
                                            <span class="v_label"> Address</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8">
                                            {{$appInfo->address}}
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                            <div class="col-md-12" style="margin-left: 10px;margin-right: 30px;">
                                <input id="acceptTerms" type="checkbox" checked disabled name="is_accept" class="col-md-1 text-left required" style="width:3%;">
                                <label class="col-md-11 text-left">I agree with the <a data-toggle="modal" data-target="#myModal" style="cursor: grab;">Terms and Conditions.</a> </label>
                            </div>
                        </div>
                    </fieldset>

                </div>
            </div>
        </div>
    </div>
</section>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">RISC Name Clearance Certificate Terms and Condition as follows:</h4>
            </div>
            <div class="modal-body">
                <ol>
                    <li> Same company name is not acceptable, its hearing sound, written style
                        etc.
                    </li>
                    <li> Similar name of international company, organization, social & Cultural
                        organization are not acceptable.
                    </li>
                    <li> Not acceptable existing company, business body, Social, Cultural,
                        Entertainment & Sporting organization's name.
                    </li>
                    <li> Name could not same Govt. Organization or Company.</li>
                    <li> Nationally fame person's name or famous family's name need to permission
                        from particular person and take permission to Government.
                    </li>
                    <li> To take freedom fighter related name for your company must be essential
                        approval of Freedom Fighter Ministry.
                    </li>
                    <li> Not acceptable similar of Govt. development program or development
                        organization.
                    </li>
                    <li> Existing political party's slogan, name and program not acceptable.</li>
                    <li> Must avoid Rebuke, Slang word ....</li>
                    <li> Name could not harm Social, Religious and national harmony.</li>
                    <li> In case of long established (at least 10 years) Social institutions, if
                        they want to register after their current name, they have to apply for
                        name clearance appearing personally along with board of committee's
                        resolution.
                    </li>
                    <li> Must be taken Ministry permission of Social, cultural & sporting
                        Organization for Limited company.
                    </li>
                    <li> Name clearance is not final name for Company Registration, RISC holds
                        power to change.
                    </li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>