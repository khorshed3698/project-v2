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
                        Application For New Connection (DPDC)
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
                    <li class="highttext"><strong>DPDC Tracking no. :{{$appInfo->dpdc_tracking_no}}</strong></li>
                    <li class="highttext"><strong> Date of Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
{{--                    <li><strong>Current Desk--}}
{{--                            :</strong>--}}
{{--                        {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}--}}
{{--                    </li>--}}
                    @if($appInfo->status_id == 52)
                        <li><strong>Remarks : </strong> Testing</li>
                    @endif
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

                        @if($appInfo->demand_submit != 1)
                            <div class="panel-footer">
                                <div class="pull-right">
                                    <a class="btn btn-md btn-primary"
                                       href="/new-connection-dpdc/view/additional-payment/{{ Encryption::encodeId($appInfo->id)}}"
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

                @if(!empty($shortfallarr) && $appInfo->is_submit_shortfall ==0)
                    <div class=" panel panel-info">
                        <div class="panel-heading"><strong>
                                SHORTFALL DOCUMENTS
                            </strong>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(array('url' => 'new-connection-dpdc/shortfall','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'dpdc-view','enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                            <input type="hidden" name="selected_file"
                                   id="selected_file"/>
                            <input type="hidden" name="validateFieldName"
                                   id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>
                            <input type="hidden" value="{{$appInfo->ref_id}}"
                                   name="ref_id">
                            <div class="form-group" style="">
                                @include('NewConnectionDPDC::dynamic-shortfall-document')
                            </div>
                            <button type="submit" id="shortfallbtn"
                                    class="btn btn-success">Submit
                            </button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                @endif

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>
                            <?php
                            if ($appData->consumer_type == 'P') {
                                echo 'PERSONAL INFORMATION';
                            } elseif ($appData->consumer_type == 'O') {
                                echo 'ORGANIZATIONAL/INSTITUTIONAL INFORMATION';
                            }
                            ?>
                        </strong></div>
                    <div class="panel-body">
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Consumer Type</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            if ($appData->consumer_type == 'P') {
                                                echo 'Personal';
                                            } elseif ($appData->consumer_type == 'O') {
                                                echo 'Organization / Institute';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($appData->consumer_type == 'P') { ?>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->applicant_name}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Spouse Name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->applicant_spouse_name}}
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
                                            {{$appData->applicant_father_name}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Mother's Name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->applicant_mother_name}}
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
                                            <span class="v_label">Gender</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->applicant_gender}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Date of birth</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->date_of_birth}}
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
                           <span class="v_label"><?php
                               if ($appData->identity == 'passport') {
                                   echo 'Passport';
                               } else if ($appData->identity == 'nid') {
                                   echo 'National Id';
                               }
                               ?></span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            if ($appData->identity == 'passport') {
                                                echo $appData->passport;
                                            } else if ($appData->identity == 'nid') {
                                                echo $appData->nid_number;
                                            }
                                            ?>
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
                        <?php }else if ($appData->consumer_type == 'O') { ?>
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
                                            <span class="v_label">Proprietor Name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->proprietor_name}}
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
                                            <span class="v_label">Proprietor Date of Birth</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->pr_date_of_birth}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">
                                                <?php
                                                if ($appData->identity == 'passport') {
                                                    echo 'Passport';
                                                } else if ($appData->identity == 'nid') {
                                                    echo 'National Id';
                                                }
                                                ?>
                                            </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            if ($appData->identity == 'passport') {
                                                echo $appData->passport;
                                            } else if ($appData->identity == 'nid') {
                                                echo $appData->nid_number;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php } ?>
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
                                            <span class="v_label">House/Plot No/Dag No</span>
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
                                            <span class="v_label">Section</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->section)) ? $appData->section : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Block</span>
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
                                            $districtDI = !empty($district[1]) ? $district[1] : '';
                                            ?>
                                            {{ $districtDI }}
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
                    <div class="panel-heading"><strong>CONNECTION ADDRESS</strong></div>
                    <div class="panel-body">

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">House/Plot No/Dag no</span>
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
                                            <span class="v_label">Lane/Road No</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->connection_lane_no)) ? $appData->connection_lane_no : '' }}
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
                                            <span class="v_label">Section</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 {{$errors->has('connection_union') ? 'has-error': ''}}">
                                            {{ (!empty($appData->connection_section)) ? $appData->connection_section : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Block</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->connection_block)) ? $appData->connection_block : '' }}
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
                                            $conDistrict = explode('@', $appData->connection_district);
                                            $conDistrictDI = !empty($conDistrict[1]) ? $conDistrict[1] : '';
                                            ?>
                                            {{ $conDistrictDI }}
                                        </div>
                                    </div>
                                </div>
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
                                            $conThana = explode('@', $appData->connection_thana);
                                            $conThanaDI = !empty($conThana[1]) ? $conThana[1] : '';
                                            ?>
                                            {{ $conThanaDI }}
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
                                            {{ $appData->connection_email}}
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
                                            <span class="v_label">Telephone</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appData->connection_telephone)) ? $appData->connection_telephone : '' }}
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
                                            {{ (!empty($appData->connection_mobile)) ? $appData->connection_mobile : '' }}
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
                                            <span class="v_label">Area</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $conArea = explode('@', $appData->connection_area);
                                            $conAreaDI = !empty($conArea[1]) ? $conArea[1] : '';
                                            ?>
                                            {{ $conAreaDI }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Division</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $conDiv = explode('@', $appData->connection_division);
                                            $conDivDI = !empty($conDiv[1]) ? $conDiv[1] : '';
                                            ?>
                                            {{ $conDivDI }}
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
                    <div class="panel-heading"><strong>Description of Connection</strong></div>
                    <div class="panel-body">
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Connection Type</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            if ($appData->connectionType == 'P') {
                                                echo 'Permanent';
                                            } else if ($appData->connectionType == 'T') {
                                                echo 'Temporary';
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Phase</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            if ($appData->phase == '1') {
                                                echo 'Single';
                                            } else if ($appData->phase == '3') {
                                                echo 'Three';
                                            }
                                            ?>

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
                                            <span class="v_label">Tariff Category</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            $conCategory = explode('@', $appData->category);
                                            $conCategoryDI = !empty($conCategory[1]) ? $conCategory[1] : '';
                                            ?>
                                            {{ $conCategoryDI }}
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
                                            <span class="v_label">Demand Meter</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->demand_meter}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Total Demand Meter</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->demand_load}}
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
                                            <span class="v_label">Existing Meter</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->demand_meter}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Existing Load</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{$appData->existing_load}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!empty($appData->diff_meter_owner))
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Different Meter Owner</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <?php
                                                if ($appData->diff_meter_owner == '1') {
                                                    echo 'Yes';
                                                } else {
                                                    echo 'None';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif

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
                    <!--/panel-body-->
                </div>

                @if(count($dynamic_shortfall) > 0)
                    <div class="panel panel-info">
                        <div class="panel-heading"><strong>
                                UPLOADED SHORTFALL DOCUMENTS
                            </strong>
                        </div>
                        <div class="panel-body">
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class=" col-md-12">
                                        <table class="table table-bordered table-hover" id="loadDetails">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Shortfall Document Name</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($dynamic_shortfall as $key => $shortfall_doc)
                                                <tr>
                                                    <td>{{$key+1}} .</td>
                                                    <td>{{ $shortfall_doc['doc_name']  }}</td>

                                                    <td>
                                                        <a target="_blank" class="btn btn-xs btn-primary"
                                                           href="{{URL::to('/uploads/'. $shortfall_doc['doc_path'])}}"
                                                           title="{{ $shortfall_doc['doc_path']  }}">
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
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </div>
</section>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>
    function uploadDocument(targets, id, vField, isRequired) {
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

// alert(vField)
        document.getElementById("isRequired").value = isRequired;
        document.getElementById("selected_file").value = id;
        document.getElementById("validateFieldName").value = vField;
        document.getElementById(targets).style.color = "red";
        var action = "{{URL::to('/new-connection-dpdc/upload-document')}}";
//alert(action);
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
                $('#' + id).removeClass('required');
//check valid data
                document.getElementById(id).value = '';
                var validate_field = $('#' + vField).val();
                if (validate_field == '') {
                    document.getElementById(id).value = '';
                }
            }
        });

    }

    // shortfallbtn

    $(document).ready(function () {
        $('#shortfallbtn').on('click', function (e) {
            $("form#dpdc-view").validate();
        });
    });


</script>