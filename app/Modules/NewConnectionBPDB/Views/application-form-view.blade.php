<?php
$accessMode = ACL::getAccsessRight('NewConectionBPDB');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}

?>
<style>
    .panel-heading {
        padding: 2px 5px;
        overflow: hidden;
    }

    .row>.col-md-5,
    .row>.col-md-7,
    .row>.col-md-3,
    .row>.col-md-9,
    .row>.col-md-12>strong:first-child {
        padding-bottom: 5px;
        display: block;
    }

    legend.scheduler-border {
        font-weight: normal !important;
    }

    .table {
        margin: 0;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
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
                        Application For New Connection (BPDB)
                    </strong>
                </div>
                <div class="pull-right">

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                        aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>

                    @if(!in_array($appInfo->status_id,[-1,5,6]))
                    <a href="/new-connection-bpdb/app-pdf/{{ Encryption::encodeId($appInfo->id)}}" target="_blank"
                        class="btn btn-danger btn-md">
                        <i class="fa fa-download"></i>
                        Application Download as PDF
                    </a>
                    @endif

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
                    <li class="highttext"><strong>BPDB Tracking no. : {{ $appInfo->bpdb_tracking_no  }}</strong></li>
                    <li class="highttext"><strong> Date of
                            Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
{{--                    <li><strong>Current Desk--}}
{{--                            :</strong>--}}
{{--                        {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}--}}
{{--                    </li>--}}
                </ol>

                @if($demand_view == 1)
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
                            @if($appInfo->meter_cost !=null && $appInfo->meter_cost !='')
                            <div class="col-md-3">
                                <a class="btn btn-md btn-info"
                                   href="{{$appInfo->meter_cost}}"
                                   role="button" target="_blank">
                                    <i class="far fa-money-bill-alt"></i>
                                    Meter Cost
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
                        @if($appInfo->demand_status ==1)
                        <div class="panel-footer">
                            <div class="pull-right">
                                <a class="btn btn-md btn-primary"
                                   href="/new-connection-bpdb/view/additional-payment/{{ Encryption::encodeId($appInfo->id)}}"
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



                {{--Company basic information--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>PERSONAL</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Application Type</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $applicationType = $appData->application_type;
                                            if($applicationType == 1){
                                                $type = "Personal";
                                            }else{
                                                $type = "organization";
                                            }

                                            ?>
                                            {{$type}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="personal" style="{{($appData->application_type == 1) ? '' : 'display:none;'}}">
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Connection Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->connection_name}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Applicant Spouse Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ $appData->applicant_spouse_name}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Applicant Name(In English)</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6 ">
                                                {{ $appData->applicant_name_english}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Nation ID</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6 ">
                                                {{$appData->nation_id}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Applicant Name(In Bangla)</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->applicant_name_bangla}}

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Passport No</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->applicant_passport_no}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Father's Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ $appData->father_name}}

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Mobile No</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->applicant_mobile_no}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Mother's Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->mother_name}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="organization" style="{{($appData->application_type == 2) ? '' : 'display:none;'}}">

                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_name}}

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Authorized Person Mobile No</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">

                                                {{$appData->applicant_mobile_no}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Authorized Person’s Name (In English)</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->applicant_name_english}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Nation ID</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->nation_id}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Authorized Person’s Name(In Bangla)</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->applicant_name_bangla}}

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Passport No</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6 ">
                                                {{$appData->applicant_passport_no}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Authorized Person’s Designation</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->authorized_person_designation}}
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Sex</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->sex}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Date of Birth</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <div class="datepicker input-group date">
                                                {{$appData->date_of_birth}}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Signature</span>
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
                                </div>

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








                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>MAILING Address</strong></div>
                    <div class="panel-body">

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">House/Plot No</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->house_no)) ? $appData->house_no : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Lane/Road No</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->lane_no)) ? $appData->lane_no : '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Section/Union</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->union)) ? $appData->union : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Block/Village</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->block)) ? $appData->block : '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">District</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $district = explode('@', $appData->district);
                                            $districtID = !empty($district[1]) ? $district[1] : '';
                                            ?>
                                            {{ $districtID }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Post Code</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->post_code)) ? $appData->post_code : '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6 {{$errors->has('thana') ? 'has-error': ''}}">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Thana</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $thana = explode('@', $appData->thana);
                                            $thanaID = !empty($thana[1]) ? $thana[1] : '';
                                            ?>
                                            {{ $thanaID }}

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Email</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->email)) ? $appData->email : '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>
                <!--/panel-->

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>PERMANENT ADDRESS</strong></div>
                    <div class="panel-body">

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">House/Plot No</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->permanet_house_no)) ? $appData->permanet_house_no : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Lane/Road No</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->permanet_lane_no)) ? $appData->permanet_lane_no : '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Section/Union</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->permanet_union)) ? $appData->permanet_union : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Block/Village</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->permanet_block)) ? $appData->permanet_block : '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6 {{$errors->has('permanet_district') ? 'has-error': ''}}">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">District</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $perDistrict = explode('@', $appData->permanet_district);
                                            $perDistrictDI = !empty($perDistrict[1]) ? $perDistrict[1] : '';
                                            ?>
                                            {{ $perDistrictDI }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Post Code</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->permanet_post_code)) ? $appData->permanet_post_code : '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Thana</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $perThana = explode('@', $appData->permanet_thana);
                                            $perThanaDI = !empty($perThana[1]) ? $perThana[1] : '';
                                            ?>
                                            {{ $perThanaDI }}

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Email</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->permanet_email)) ? $appData->permanet_email : '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>
                <!--/panel-->
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>CONNECTION ADDRESS</strong></div>
                    <div class="panel-body">

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">House/Plot No</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->connection_house_no)) ? $appData->connection_house_no : '' }}

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">District</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>

                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $conDistrict = explode('@', $appData->connection_district);
                                            $conDistrictDI = !empty($conDistrict[1]) ? $conDistrict[1] : '';
                                            ?>
                                            {{ $conDistrictDI }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Lane/Road No</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->connection_lane_no)) ? $appData->connection_lane_no : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Thana</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $conThana = explode('@', $appData->connection_thana);
                                            $conThanaDI = !empty($conThana[1]) ? $conThana[1] : '';
                                            ?>
                                            {{ $conThanaDI }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Section/Union</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 {{$errors->has('connection_union') ? 'has-error': ''}}">
                                            {{ (!empty($appData->connection_union)) ? $appData->connection_union : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">BPDBZone</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $bpdbZone = explode('@', $appData->bpdb_zone);
                                            $bpdbZoneDI = !empty($bpdbZone[1]) ? $bpdbZone[1] : '';
                                            ?>
                                            {{ $bpdbZoneDI }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Block/Village</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->connection_block)) ? $appData->connection_block : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">S&D/ESU</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>

                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $esu = explode('@', $appData->esu);
                                            $esuDI = !empty($esu[1]) ? $esu[1] : '';
                                            ?>
                                            {{ $esuDI }}
                                            {!! $errors->first('esu','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Post Code</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6 ">
                                            {{ (!empty( $appData->connection_post_code)) ?  $appData->connection_post_code : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Connection Area</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>

                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $connectionArea = explode('@', $appData->connection_area);
                                            $connectionAreaDI = !empty($connectionArea[1]) ? $connectionArea[1] : '';
                                            ?>
                                            {{ $connectionAreaDI }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Mobile No</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->connection_mobile_no)) ? $appData->connection_mobile_no : '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>
                <!--/panel-->
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>LOAD DETAILS</strong></div>
                    <div class="panel-body">

                        <div class="form-group" style="">
                            <div class="row">
                                <div class=" col-md-12">
                                    <table class="table table-bordered table-hover" id="loadDetails">
                                        <thead>
                                            <tr>
                                                <th>Description of Load</th>
                                                <th>Load per Item (Watt)</th>
                                                <th>No. of Item</th>
                                                <th>Total Load (Watt)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appData->description_of_load as $key => $value)
                                            <tr>
                                                <?php
                                            $description = explode('@', $appData->description_of_load[$key]);
                                            $load = explode('@', $appData->load_per_item[$key]);
                                            $descriptionDI = !empty($description[1]) ? $description[1] : '';
                                            $loadDI = !empty($description[1]) ? $description[1] : '';
                                            ?>
                                                <td>{{ $descriptionDI }}</td>
                                                <td>{{ $loadDI }}</td>
                                                <td>{{ $appData->no_of_item[$key] }}</td>
                                                <td>{{ $appData->total_load[$key] }} </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" style="text-align:right">Total:</td>
                                                <td colspan="1" style="text-align:center"><input type="text" id="sum"
                                                        name="total" value="{{$appData->total}}"
                                                        class="form-control input-md" readonly></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>


                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>
                <!--/panel-->


                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Attachements</strong></div>
                    <div class="panel-body">

                        <div class="form-group" style="">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Connection Type</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->connectionType)) ? $appData->connectionType : '' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Phase</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            if(!empty($appData->phase)){
                                            $phaseDI = explode('@',$appData->phase);
                                            $phase = !empty($phaseDI[1]) ? $phaseDI[1] : '';
                                            }
                                            ?>
                                            {{$phase}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Category</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                                if(!empty($appData->category)){
                                                    $categoryDI = explode('@',$appData->category);
                                                    $category = !empty($categoryDI[1]) ? $categoryDI[1] : '';
                                                }
                                            ?>
                                            {{$category}}
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
                                            $doc_name = 'doc_name_'.$dynamicDocumentsId;
                                            $doc_path = 'validate_field_'.$dynamicDocumentsId;
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
                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>
                <!--/panel-->


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
                                        src="{{ asset('assets/images/checked.png') }}" />
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