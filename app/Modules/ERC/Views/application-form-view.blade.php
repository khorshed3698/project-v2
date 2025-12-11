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

    .form-group {
        margin-bottom: 5px;
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
                        Application for Genarel ERC
                    </strong>
                </div>
                <div class="pull-right">

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>
                @if($is_shortfall ==1 && $appInfo->status_id == 17)
                        <a class="btn btn-md btn-primary"
                           href="/licence-applications/erc/shortfall-form/{{\App\Libraries\Encryption::encodeId($appInfo->id)}}">
                            <i class="far fa-money-bill-alt"></i>
                            Shortfall Form
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
                    @if(!empty($appInfo->erc_tracking_no))
                    <li class="highttext"><strong>ERC Tracking no. : {{ $appInfo->erc_tracking_no  }}</strong></li>
                    @endif
                    <li class="highttext"><strong> Date of
                            Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    {{--                    <li><strong>Current Desk--}}
                    {{--                            :</strong>--}}
                    {{--                        {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}--}}
                    {{--                    </li>--}}
                    @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate))
                        <li>
                            <a href="{{ $appInfo->certificate }}"
                               class="btn show-in-view btn-xs btn-info"
                               title="Download Approval Letter" target="_blank"> <i
                                        class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                        </li>
                    @endif
                </ol>

                @include('SonaliPaymentStackHolder::payment-information')

                @if($resubmittedData)
                    @include('ERC::shortfall-submit-preview')
                @endif

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12"
                             style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color:#564c4c;">Organization Information</h4>
                        </div>
                        <div class="col-md-11 col-md-offset-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization TIN</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_tin}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Company Title</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->company_title}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Name(Bangla)</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_name_bn}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Address (English)</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_add_en}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Fax</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_fax}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Contact Person Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->contact_person_name}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Division Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <?php
                                                $division = explode('@', $appData->division);
                                                $divisionID = !empty($division[1]) ? $division[1] : '';
                                                ?>
                                                {{$divisionID}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">District Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <?php
                                                $district = explode('@', $appData->district);
                                                $districtID = !empty($district[1]) ? $district[1] : '';
                                                ?>
                                                {{$districtID}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Post Code</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_post_code}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Email</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_email}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Name(English)</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_name_en}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Address (Bangla)</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_add_bn}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Phone</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_phone}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Mobile</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->organization_mobile}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Contact Person Phone</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->contact_person_2}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Holding No</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->holding_no}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Organization Police Station</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <?php
                                                $policestation = explode('@', $appData->police_station);
                                                $policestationID = !empty($policestation[1]) ? $policestation[1] : '';
                                                ?>
                                                {{$policestationID}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                             style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-top: 15px;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Owner Information</h4>
                        </div>

                        <div class="col-md-11 col-md-offset-1">
                            <h5 style="font-weight:bold;text-decoration: underline;">Personal
                                Information</h5>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-xs-6">
                                        <span class="v_label">Organization type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-2 col-xs-6">
                                        <?php
                                        $organizationtype = explode('@', $appData->organization_type);
                                        $organizationtypeID = !empty($organizationtype[1]) ? $organizationtype[1] : '';
                                        ?>
                                        {{$organizationtypeID}}

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-11" id="owner_details" style="margin-top:15px;margin-left:20px;">
                            <table class="table table-responsive table-bordered table-condensed " id="ownerTable"
                                   style="<?php if (isset($appData->owner_name)) { ?>
                                           display: block;
                                   <?php } else { ?>
                                           display: none;
                                   <?php } ?>">
                                <thead>
                                <tr style="background-color: #D9EDF7;">
                                    <th style="display:none;">ID</th>
                                    <th>Name</th>
                                    <th>Taxpayer Identification Number (TIN)</th>
                                    <th>NID</th>
                                    <th>Designation</th>
                                    <th>Mobile Number</th>
                                    <th>Office Phone</th>
                                    <th>Present Address</th>
                                    <th>District</th>
                                    <th class="text-center">Picture</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($appData->nationality)){ ?>
                                @foreach($appData->nationality as $key=>$nationality)

                                    <tr class="ownerRow" id="owner_row_id_{{$key}}">
                                        <td style="display:none;"><input name="nationality[]"
                                                                         value="{{isset($appData->nationality[$key]) ? $appData->nationality[$key] : ''}}"
                                                                         hidden></td>
                                        <td style="display:none;"><input name="owner_father_name[]"
                                                                         value="{{isset($appData->owner_father_name[$key]) ? $appData->owner_father_name[$key] : ''}}"
                                                                         hidden></td>
                                        <td style="display:none;"><input name="passport_no[]"
                                                                         value="{{isset($appData->passport_no[$key]) ? $appData->passport_no[$key] : ''}}"
                                                                         hidden></td>
                                        <td style="display:none;"><input name="passport_expired_date[]"
                                                                         value="{{isset($appData->passport_expired_date[$key]) ? $appData->passport_expired_date[$key] : ''}}"
                                                                         hidden>
                                        </td>
                                        <td style="display:none;"><input name="incorporation_number[]"
                                                                         value="{{isset($appData->incorporation_number[$key]) ? $appData->incorporation_number[$key] : ''}}"
                                                                         hidden>
                                        </td>
                                        <td style="display:none;"><input name="registration_number[]"
                                                                         value="{{isset($appData->registration_number[$key]) ? $appData->registration_number[$key] : ''}}"
                                                                         hidden>
                                        </td>
                                        <td style="display:none;"><input name="mother_name[]"
                                                                         value="{{isset($appData->mother_name[$key]) ? $appData->mother_name[$key] : ''}}"
                                                                         hidden></td>
                                        <td style="display:none;"><input name="permanent_address[]"
                                                                         value="{{isset($appData->permanent_address[$key]) ? $appData->permanent_address[$key] : ''}}"
                                                                         hidden></td>
                                        <td style="display:none;"><input name="incorporation_date[]"
                                                                         value="{{isset($appData->incorporation_date[$key]) ? $appData->incorporation_date[$key] : ''}}"
                                                                         hidden></td>

                                        <td style="display:none;"><input name="country[]"
                                                                         value="{{isset($appData->country[$key]) ? $appData->country[$key] : ''}}"
                                                                         hidden></td>

                                        <td style="display:none;"><input name="registration_date[]"
                                                                         value="{{isset($appData->registration_date[$key]) ? $appData->registration_date[$key] : ''}}"
                                                                         hidden></td>
                                        <td><input name="owner_name[]" value="{{$appData->owner_name[$key]}}"
                                                   hidden>{{$appData->owner_name[$key]}}</td>
                                        <td><input name="owner_tin[]"
                                                   value="{{$appData->owner_tin[$key]}}"
                                                   hidden>{{$appData->owner_tin[$key]}}</td>
                                        <td><input name="owner_nid_or_passport[]"
                                                   value="{{$appData->owner_nid_or_passport[$key]}}"
                                                   hidden>
                                            <?php
                                            if ($appData->owner_nid_or_passport[$key] != '') {
                                                echo $appData->owner_nid_or_passport[$key];
                                            }
                                            ?>

                                        </td>
                                        <td><input name="designation[]" value="{{$appData->designation[$key]}}"
                                                   hidden>
                                            <?php
                                            if ($appData->designation[$key] != '') {
                                                $des = $appData->designation[$key];
                                                $designation = explode('@', $des);
                                                if (isset($designation[1])) {
                                                    echo $designation[1];
                                                } else {
                                                    echo null;
                                                }
                                            }
                                            ?>

                                        </td>
                                        <td><input name="mobile[]" value="{{$appData->mobile[$key]}}"
                                                   hidden>{{$appData->mobile[$key]}}</td>
                                        <td><input name="phone_number_office[]"
                                                   value="{{$appData->phone_number_office[$key]}}"
                                                   hidden>{{$appData->phone_number_office[$key]}}</td>
                                        <td><input name="present_address[]"
                                                   value="{{isset($appData->present_address[$key]) ? $appData->present_address[$key] : ''}}"
                                                   hidden>{{isset($appData->present_address[$key]) ? $appData->present_address[$key] : 'Null'}}
                                        </td>
                                        <td><input name="district_name[]"
                                                   value="{{isset($appData->district_name[$key]) ? $appData->district_name[$key] : ''}}"
                                                   hidden>
                                            <?php
                                            if ($appData->district_name[$key] != '') {
                                                $var = $appData->district_name[$key];
                                                $district = explode('@', $var);
                                                echo $district[1];
                                            }
                                            ?>
                                        </td>


                                        <td><input name="owner_photo[]"
                                                   value="{{isset($appData->owner_photo[$key]) ? $appData->owner_photo[$key] : ''}}"
                                                   hidden>
                                            <img src="{{isset($appData->owner_photo[$key]) ? $appData->owner_photo[$key] : 'Null'}}"
                                                 height="100px"/></td>


                                    </tr>
                                @endforeach
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                             style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-top: 15px;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Share Information %</h4>
                        </div>
                        <div class="col-md-11 col-md-offset-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Share Type</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <?php
                                                $sharetype = explode('@', $appData->share_type);
                                                $sharetypeID = !empty($sharetype[1]) ? $sharetype[1] : '';
                                                ?>
                                                {{$sharetypeID}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"
                                     style="{{($appData->share_type == 'D@Domestic') ? '' : 'display:none;'}}">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Domestic Share Percent</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">

                                                {{$appData->domestic_share}}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6"
                                     style="{{($appData->share_type == 'F@Foreign') ? '' : 'display:none;'}}">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">% of Foreign Share</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->foreign_share}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12"
                             style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-top: 15px;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Nominated bank Information</h4>
                        </div>
                        <div class="col-md-11 col-md-offset-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Bank Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <?php
                                                $bankanme = explode('@', $appData->bank_name);
                                                $bankID = !empty($bankanme[1]) ? $bankanme[1] : '';
                                                ?>
                                                {{$bankID}}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Branch Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <?php
                                                $branchname = explode('@', $appData->branch_no);
                                                $branchID = !empty($branchname[1]) ? $branchname[1] : '';
                                                ?>
                                                {{$branchID}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Branch Address</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{$appData->branch_address}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                             style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-top: 15px;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Trade License</h4>
                        </div>
                        <div class="col-md-11 col-md-offset-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Trade License no</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->trade_license_no}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Trade License Expired Date</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->trade_license_expired_date}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Address</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->trade_license_address}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Trade License Issued Date</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->trade_license_issued_date}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Trade License Issued By</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{!empty($appData->trade_license_issued_by)?$tl_issued_by[$appData->trade_license_issued_by]:''}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Business Type</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->business_type}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                             style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-top: 15px;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Information Of
                                Chamber/Association</h4>
                        </div>
                        <div class="col-md-11 col-md-offset-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Chamber/Association Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                <?php

                                                $chamber = !empty( $appData->association_name)?explode('@', $appData->association_name)[1]:'';
                                                echo $chamber;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Serial No of Chamber Certificate</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->serial_no_chamber}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Chamber/Association Phone</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->association_phone}}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Chamber Category</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->chamber_category}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Issue Date</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->certificate_issue_date}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Chamber Address</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->chamber_address}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">Validity Date</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                {{$appData->validity_date}}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12"
                             style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-top: 15px;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Export Slab</h4>
                        </div>
                        <div class="col-md-11 col-md-offset-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7 col-xs-6">
                                                <span class="v_label">ERC Slab</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-5 col-xs-6">
                                                <?php
                                                $slabData = explode('@', $appData->erc_slab);
                                                $slabName = !empty($slabData[1]) ? $slabData[1] : '';
                                                ?>
                                                {{$slabName}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                             style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-top: 15px;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">
                                Attach Document</h4>
                        </div>
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped table-bordered table-hover ">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th colspan="6">Required Attachments</th>
                                    <th colspan="2">Attached file</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; ?>
                                @foreach($document as $row)
                                    <tr>
                                        <td>{!! $i !!} .</td>
                                        <td colspan="6"> {{$row->doc_name}}</td>
                                        <td colspan="2">
                                            @if($row['doc_path'] !='' &&$row['doc_path'] !=null)
                                                <a target="_blank" class="btn btn-xs btn-primary"
                                                   href="{{URL::to('/uploads/'.$row['doc_path'])}}"
                                                   title="{{$row['document_name_en']}}">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    Open File
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                             style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-top: 15px;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">
                                Undertaking</h4>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1">
                                <img style="width: 10px; height: auto;"
                                     src="{{ asset('assets/images/checked.png') }}"/>
                            </div>
                            <div class="col-md-11">
                                <label for="acceptTerms-2">
                                    I do hereby decleare that the information relating to me/my firm furnished above
                                    and
                                    the
                                    documents attached herewith are correct.if the information furnished above ans
                                    thr
                                    documents attached herewith are found to be false or obtained through
                                    fraud/forgery/misdeclaration etc.then I and my firm will be held liable for that
                                    and
                                    the
                                    authority may
                                    take any legal action against me and my firm including cancellation of
                                    certificate/permit.</label>

                                <div class="clearfix"></div>
                                {!! $errors->first('acceptTerms','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
</section>