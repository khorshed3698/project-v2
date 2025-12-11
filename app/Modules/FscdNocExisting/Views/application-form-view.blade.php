<?php
$accessMode = ACL::getAccsessRight('BfscdNocExiting');
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
                        Application For NOC Exiting Building
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
                    @if(!empty($appInfo->enoc_tracking_no))
                        <li><strong>E-NOC tracking no : </strong>{{ $appInfo->enoc_tracking_no}}</li>
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
                </ol>

                {{--Payment information--}}
                @include('SonaliPaymentStackHolder::payment-information')
{{----}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Safety Firm</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Safety Firm</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->safety_firm) ? explode('@',$appData->safety_firm)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Name and Address of the Applicant</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">User Name </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->user_name) ? $appData->user_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">User Mobile No </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->user_mobile) ? $appData->user_mobile: ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">User Email </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->user_email) ? $appData->user_email: ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">User Address </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->user_address) ? $appData->user_address : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Details of the owner of the proposed Building / Institution</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->owner_name) ? $appData->owner_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Phone No. </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->owner_phone) ? $appData->owner_phone : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Owner Email</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->owner_email) ? $appData->owner_email : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Division </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->owner_division) ? explode('@',$appData->owner_division)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">District</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->owner_district) ? explode('@',$appData->owner_district)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Thana/Upozila </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->owner_thana) ? explode('@',$appData->owner_thana)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Address </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->owner_address) ?$appData->owner_address : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Plot Location</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Division of the Proposed Building </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->proposed_building_division)? explode('@',$appData->proposed_building_division)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">District of the proposed Building </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->proposed_building_district) ? explode('@',$appData->proposed_building_district)[1] : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Thana/Upozila of the proposed building </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->proposed_building_thana) ? explode('@',$appData->proposed_building_thana)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label"> Building No </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->buliding_no) ? $appData->buliding_no : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Road No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->road_no) ? $appData->road_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">City Corporation / Municipality / Union Parishad</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->council) ? explode('@',$appData->council)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                @if( explode('@',$appData->council)[0] == 'city_corporation')
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">City Corporation</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->city_corporation) ? explode('@',$appData->city_corporation)[1] : ''}}
                                        </div>
                                    </div>
                                @endif
                                @if( explode('@',$appData->council)[0] == 'town_council')
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Municipality</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->town_council) ? $appData->town_council : ''}}
                                        </div>
                                    </div>
                                @endif
                                @if( explode('@',$appData->council)[0] == 'union_council')
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Union Parishad</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->union_council) ? $appData->union_council : ''}}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->proposed_building_address) ? $appData->proposed_building_address : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-5 col-xs-12">
                                <span class="v_label">Number of buildings </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($appData->number_of_building) ? $appData->number_of_building : ''}}
                            </div>
                        </div>

                    </div>
                </div>
                @foreach($appData->building_height as $key => $value)
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Details of the proposed multi-storey Building</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Building construction class </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->building_construction[$key]) ? explode('@',$appData->building_construction[$key])[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Class of Building Use </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->building_use[$key]) ? explode('@',$appData->building_use[$key])[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label"> Type of building use </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->building_use_type[$key]) ? explode('@',$appData->building_use_type[$key])[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Building height (m) [If the height of the building is high]</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->building_height[$key]) ? $appData->building_height[$key] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Floor</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->floor[$key]) ? explode('@',$appData->floor[$key])[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Number of Floors</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->floor_number[$key]) ? $appData->floor_number[$key] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Number of Stairs</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->number_stairs[$key]) ? $appData->number_stairs[$key] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Number of Basement Floors</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->number_of_basement[$key]) ? $appData->number_of_basement[$key] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Number of mezzanine Floors</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->number_of_mezzanine[$key]) ? $appData->number_of_mezzanine[$key] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Number of Semi Basement Floors</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->number_of_simi_basement[$key]) ? $appData->number_of_simi_basement[$key] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Area of Each Floor (sq. M.)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->area_of_eash_floor[$key]) ? $appData->area_of_eash_floor[$key] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Size of each basement floor (sq. M.)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->size_of_each_basement[$key]) ? $appData->size_of_each_basement[$key] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Volume of each mezzanine floor (sq. M.)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->vol_each_mezzainine[$key]) ? $appData->vol_each_mezzainine[$key] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Size of each semi basement floor (sq. M.)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->size_of_each_simi_basement[$key]) ? $appData->size_of_each_simi_basement[$key] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Total floor area (sq. M.)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->total_floor_area[$key]) ? $appData->total_floor_area[$key] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Whether there are electric high voltage lines on the proposed plot</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->electric_line[$key]) ? explode('@',$appData->electric_line[$key])[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">The distance of the high voltage line from the plot is horizontal and vertical distance</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->electric_line_distance[$key]) ? $appData->electric_line_distance[$key] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Total number of residences / apartments and flats in case of residential building</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->total_flats_number[$key]) ? $appData->total_flats_number[$key] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">A description of the floors to be used by each use class in the case of mixed class use</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->description[$key]) ? $appData->description[$key] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Description of the road adjacent to the plot</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Name of the main street adjacent to the plot </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->main_street_name) ?$appData->main_street_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Width of main road adjacent to the plot (m)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->main_road_width) ? $appData->main_road_width : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Length of plot connecting road (m) </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->length_connection) ? $appData->length_connection : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label"> Plot connection road width (m.) </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->plot_connecting) ? $appData->plot_connecting : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Recommended road length inside the plot (m)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->recommended_road) ? $appData->recommended_road : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Description of the four sides of the land or plot</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Whats in the North ?</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->north_side) ? $appData->north_side : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Whats in the South ? </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->south_side) ? $appData->south_side : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Whats in the East ?</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->east_side) ? $appData->east_side : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Whats in the West ?</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->west_side) ? $appData->west_side : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Nearby Fire station</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Division</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->nearby_division) ? explode('@',$appData->nearby_division)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">District </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->nearby_district) ? explode('@',$appData->nearby_district)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Thana</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->nearby_tahan) ? explode('@',$appData->nearby_tahan)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Fire station</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->nearby_fire_station) ? explode('@',$appData->nearby_fire_station)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Details of those involved in the construction work</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Name and address of the engineer </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->engineer_name) ? $appData->engineer_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Engineers registration number </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->engineer_reg_no) ? $appData->engineer_reg_no : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Name and address of the architect</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->architect_name) ? $appData->architect_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Architects registration number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->architect_reg_no) ? $appData->architect_reg_no : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group ">
                    <div class="row electrical_station_div">
                        <div class="col-md-8">
                            <div class="col-md-5 col-xs-12">
                                <span class="v_label">There is an electrical substation  </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                @if(isset($appData->electrical_station))
                                    <?php if($appData->electrical_station == 1){
                                     echo 'Yes';
                                    }else{
                                     echo 'No';
                                    }
                                    ?>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(isset($appData->electrical_station) && $appData->electrical_station == 1)
                    <div class="panel panel-info" style="{{($appData->electrical_station == 1)?'display:block;':'display:none;'}}">
                        <div class="panel-heading"><strong>Electrical Substation Information</strong></div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Location of sub station</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->sub_station_location) ? $appData->sub_station_location : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Sub station room size </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->sub_station_room_size) ? $appData->sub_station_room_size : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Number of sub stations</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->number_of_substation) ? $appData->number_of_substation : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Which floors is the electric sub station ?</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->floor_sub_station) ? $appData->floor_sub_station : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">How many kVA is the electric sub station</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->electric_sub_station_kVA) ? $appData->electric_sub_station_kVA : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Whether there is adequate ventilation in the interior of the electrical sub-station room </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->adequate_electtrical) ? $appData->adequate_electtrical : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Whether the safety bestney is protected by a 4 feet high steel net around the transformer</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->safety_bestney) ? $appData->safety_bestney : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Whether flood / rain water is likely to enter inside the electrical substation</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->rain_likely) ? $appData->rain_likely : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Whether the doors and walls of the electrical substation room are properly fire rated</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->properly_fire_rated) ? $appData->properly_fire_rated : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Whether rubber mats have been properly installed inside the electrical substation room</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->rubber_mats) ? $appData->rubber_mats : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">In case of installation of electrical substation inside the building “Inert Gas Fire Suppression System” Whether there are installation arrangements</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->gas_fire_system) ? $appData->gas_fire_system : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Whether the design of the electrical substation is displayed on a separate page with blowup / enlarge and safety legend</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->safety_legend) ? $appData->safety_legend : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>Application Guide</strong></div>
                    <div class="panel-body guide">
                        <div class="form-group">
                            <div class="col-md-12">
                                <h4>The following documents must be submitted to the office of the head of the inspection committee</h4><br>
                            </div>
                            <div class="col-md-12 attested">
                                <ul style="list-style-type: none;  margin: 0; padding: 0;">
                                    <li>(A) Photocopy of the owner's lease or purchase or heba or other documents ............................................ 02 Copy</li>
                                    <li>(B) Attested photocopy of documents and permits if the land or plot is allotted by the government ........ 02 Copy</li>
                                    <li>(C) Attested photocopy of land rent update receipt..................... ...................................................................  02 Copy</li>
                                    <li>(D) Attested photocopy of power of attorney.................... ...............................................................................  02 Copy</li>
                                    <li>(E) Site plan ........................................................................................................................................................  02 Copy</li>
                                    <li>(F) Layout plan ....................................................................................................................................................  02 Copy</li>
                                    <li>(G) Fire Safety Plan: Floor based design (A-2 / A-3 size)................................................................................... 04 Copy</li>
                                    <li>(H) Original copy of the certificate of fee paid (subject to effect).</li>
                                </ul><br>

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <img style="width: 10px; height: auto;" src="{{ asset('assets/images/checked.png') }}"/>
                                                I / we are certifying that, The above information has been duly fulfilled in accordance with fire prevention and extinguishing rules and regulations and the information provided to the best of my / our knowledge is correct. Once the application is approved, the Director General or the authorized officer may revoke the clearance due to any misinformation or any other inconsistency or any need of the Government. Moreover, we will be obliged to provide any other information or documents under these laws and rules.

                                    </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                    </div>
            </div>

        </div>
    </div>

</section>
