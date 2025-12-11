@extends('layouts.admin')
<?php
$accessMode = ACL::getAccsessRight('BasicInformation');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
@section('content')
    <style>
        .img-thumbnail {
            height: 100px;
            width: 100px;
        }

        .service_box {
            display: flex;
            align-items: center;
            padding: 5px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            border-radius: 3px;
        }

        .service_box_left {
            flex-grow: 1;
        }

        .service_box_left p {
            margin: 0;
        }

        .service_box_right {
            flex-shrink: 0;
            flex-basis: 70px;
        }

    </style>

    <section class="content">
        {{-- Modal load section start --}}
        <div class="modal fade" id="changeDeptModal" role="dialog"
             aria-labelledby="changeDeptModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content load_modal"></div>
            </div>
        </div>

        <div class="modal fade" id="changeBasicInfoModal" tabindex="-1" role="dialog" aria-labelledby="changeBasicInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content load_modal"></div>
            </div>
        </div>
        {{-- Modal load section end --}}

        <div class="col-md-12">
            <div class="box">
                <div class="box-body" id="inputForm">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h5><strong>Application for Basic Information ({{ $applicant_type_name }})</strong></h5>
                            </div>
                            <div class="pull-right">
                                <?php $user_desk_array = explode(',', Auth::user()->desk_id); ?>
                                @if($count_approved_app == 0 && in_array((!empty($appInfo->status_id) ? $appInfo->status_id : 0),[25]) && (Auth::user()->user_type == '1x101' or in_array(1, $user_desk_array) or in_array(3, $user_desk_array)))
                                    <a class="btn btn-primary" data-toggle="modal" data-target="#changeDeptModal"
                                       onclick="openChangeDeptModal(this)"
                                       data-action="{{ url('basic-information/change-dept/'.Encryption::encodeId($appInfo->id).'/'.Encryption::encodeId($company_id)) }}">
                                        Change Department
                                    </a>
                                @endif

                                @if(in_array(Auth::user()->user_type,['2x202', '1x101']))
                                    <a class="btn btn-info"  href="{{ url('settings/change-basic-info/'.Encryption::encodeId($company_id)) }}">
                                        Change Basic Info
                                    </a>
                                @endif
                                {{--Basic info change in modal end--}}
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>A. Company Information</strong>
                                </div>
                                <div class="panel-body">
                                    @if(in_array(!empty($appInfo->status_id), [25]) && !empty($appInfo->department_id) != '')
                                        <div class="row">
                                            <div class="col-md-4 col-xs-5">
                                                <span class="v_label">Department</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-8 col-xs-7">
                                                {{ (!empty($appInfo->department) ? $appInfo->department : '') }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-4 col-xs-5">
                                            <span class="v_label">Name of Organization in English (Proposed)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8 col-xs-7">
                                            {{ (!empty($appInfo->company_name) ? $appInfo->company_name : '') }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-xs-5">
                                            <span class="v_label">Name of Organization in Bangla (Proposed)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8 col-xs-7">
                                            {{ (!empty($appInfo->company_name_bn) ? $appInfo->company_name_bn : '') }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-xs-5">
                                            <span class="v_label">Desired Service from BIDA</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8 col-xs-7">
                                            {{ (!empty($appInfo->service_name) ? $appInfo->service_name : '') }}
                                        </div>
                                    </div>

                                    @if($appInfo->service_type == '5')  {{---- // 5 = Registered Commercial Offices ---}}
                                    <div class="row">
                                        <div class="col-md-4 col-xs-5">
                                            <span class="v_label">Commercial office type</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8 col-xs-7">
                                            {{ (!empty($appInfo->reg_commercial_office_name)) ? $appInfo->reg_commercial_office_name : '' }}
                                        </div>
                                    </div>
                                    @endif

                                    @if($appInfo->business_category === 1)
                                        <div class="row">
                                            <div class="col-md-4 col-xs-5">
                                                <span class="v_label">Ownership status</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-8 col-xs-7">
                                                {{ (!empty($appInfo->ownership_status) ? $appInfo->ownership_status : '') }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-4 col-xs-5">
                                            <span class="v_label">Type of the organization</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8 col-xs-7">
                                            {{ (!empty($appInfo->organization_type) ? $appInfo->organization_type : '') }}

                                            @if($appInfo->organization_type_id === 14 && $appInfo->business_category === 2)
                                                ({{ (!empty($appInfo->organization_type_other) ? $appInfo->organization_type_other : '') }})
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-xs-5">
                                            <span class="v_label">Major activities in brief</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8 col-xs-7">
                                            {{ (!empty($appInfo->major_activities) ? $appInfo->major_activities : '') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Start business category --}}
                            @if($appInfo->business_category == 2)
                                {{--B. Information of Responsible Person--}}
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <strong>B. Information of Responsible Person</strong>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Country</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_country_name) ? $appInfo->ceo_country_name : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Full Name</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_full_name) ? $appInfo->ceo_full_name : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                @if($appInfo->ceo_country_id == 18) {{-- 18 = Bangladesh --}}
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">NID No.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_nid) ? $appInfo->ceo_nid : '') }}
                                                    </div>
                                                </div>
                                                @else
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6">
                                                            <span class="v_label">Passport No.</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-8 col-xs-6">
                                                            {{ (!empty($appInfo->ceo_passport_no) ? $appInfo->ceo_passport_no : '') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Designation</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_designation) ? $appInfo->ceo_designation : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Mobile No. </span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_mobile_no) ? $appInfo->ceo_mobile_no : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Email </span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_email) ? $appInfo->ceo_email : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Gender </span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_gender) ? $appInfo->ceo_gender : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Authorization Letter </span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        <a target="_blank" rel="noopener" class="btn btn-xs btn-primary show-in-view" title="Authorization Letter"
                                                           href="{{ URL::to('users/upload/'.$appInfo->ceo_auth_letter) }}">
                                                            <i class="fa fa-file-pdf-o"></i>
                                                            Authorization Letter
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{--B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO--}}
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <strong>B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO</strong>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Country</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_country_name) ? $appInfo->ceo_country_name : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Date of Birth</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (empty($appInfo->ceo_dob) ? '' : date('d-M-Y', strtotime($appInfo->ceo_dob))) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                @if($appInfo->ceo_country_id == 18) {{-- 18 = Bangladesh --}}
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">NID No.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_nid) ? $appInfo->ceo_nid : '') }}
                                                    </div>
                                                </div>
                                                @else
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6">
                                                            <span class="v_label">Passport No.</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-8 col-xs-6">
                                                            {{ (!empty($appInfo->ceo_passport_no) ? $appInfo->ceo_passport_no : '') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Designation</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_designation) ? $appInfo->ceo_designation : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Full Name</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_full_name) ? $appInfo->ceo_full_name : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                @if($appInfo->ceo_country_id == 18) {{-- 18 = Bangladesh --}}
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">District/ City/ State</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_district_name) ? $appInfo->ceo_district_name : '') }}
                                                    </div>
                                                </div>
                                                @else
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6">
                                                            <span class="v_label">District/ City/ State</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-8 col-xs-6">
                                                            {{ (!empty($appInfo->ceo_city) ? $appInfo->ceo_city : '') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                @if($appInfo->ceo_country_id == 18) {{-- 18 = Bangladesh --}}
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Police Station/ Town </span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_thana_name) ? $appInfo->ceo_thana_name : '') }}
                                                    </div>
                                                </div>
                                                @else
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6">
                                                            <span class="v_label">State/ Province</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-8 col-xs-6">
                                                            {{ (!empty($appInfo->ceo_state) ? $appInfo->ceo_state : '') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Post/ Zip Code</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_post_code) ? $appInfo->ceo_post_code : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">House, Flat/ Apartment, Road</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_address) ? $appInfo->ceo_address : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Telephone No.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_telephone_no) ? $appInfo->ceo_telephone_no : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Mobile No. </span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_mobile_no) ? $appInfo->ceo_mobile_no : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Father\'s Name</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_father_name) ? $appInfo->ceo_father_name : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Email </span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_email) ? $appInfo->ceo_email : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Mother\'s Name</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_mother_name) ? $appInfo->ceo_mother_name : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Fax No. </span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_fax_no) ? $appInfo->ceo_fax_no : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Spouse name</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_spouse_name) ? $appInfo->ceo_spouse_name : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Gender </span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->ceo_gender) ? $appInfo->ceo_gender : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>C. Office Address</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Division</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    {{ (!empty($appInfo->office_division_name) ? $appInfo->office_division_name : '') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">District</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-8 col-xs-6">
                                                    {{ (!empty($appInfo->office_district_name) ? $appInfo->office_district_name : '') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Police Station</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    {{ (!empty($appInfo->office_thana_name) ? $appInfo->office_thana_name : '') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Post Office</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-8 col-xs-6">
                                                    {{ (!empty($appInfo->office_post_office) ? $appInfo->office_post_office : '') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Post Code</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    {{ (!empty($appInfo->office_post_code) ? $appInfo->office_post_code : '') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">House, Flat/ Apartment, Road</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->office_address) ? $appInfo->office_address : '') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Telephone No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    {{ (!empty($appInfo->office_telephone_no) ? $appInfo->office_telephone_no : '') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Mobile No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-8 col-xs-6">
                                                    {{ (!empty($appInfo->office_mobile_no) ? $appInfo->office_mobile_no : '') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Fax No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    {{ (!empty($appInfo->office_fax_no) ? $appInfo->office_fax_no : '') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Email</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-8 col-xs-6">
                                                    {{ (!empty($appInfo->office_email) ? $appInfo->office_email : '') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            @if($appInfo->service_type == 1 || $appInfo->service_type == 2 || $appInfo->service_type == 3) {{-- 1 = local, 2 = joint, 3 = foreign --}}
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>D. Factory Address</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">District</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    {{ (!empty($appInfo->factory_district_name) ? $appInfo->factory_district_name : '') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Police Station</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    {{ (!empty($appInfo->factory_thana_name) ? $appInfo->factory_thana_name : '') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Post Office</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    {{ (!empty($appInfo->factory_post_office) ? $appInfo->factory_post_office : null) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Post Code</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-8 col-xs-6">
                                                    {{ (!empty($appInfo->factory_post_code) ? $appInfo->factory_post_code : null) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">House, Flat/ Apartment, Road</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->factory_address) ? $appInfo->factory_address : '') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Telephone No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-8 col-xs-6">
                                                    {{ (!empty($appInfo->factory_telephone_no) ? $appInfo->factory_telephone_no : '') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Mobile No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-8 col-xs-6">
                                                    {{ (!empty($appInfo->factory_mobile_no) ? $appInfo->factory_mobile_no : '') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Fax No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    {{ (!empty($appInfo->factory_fax_no) ? $appInfo->factory_fax_no : '') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Email</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-8 col-xs-6">
                                                    {{ (!empty($appInfo->factory_email) ? $appInfo->factory_email : '') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Mouja No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    {{ (!empty($appInfo->factory_mouja) ? $appInfo->factory_mouja : '') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endif

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>Authorized Person Information</strong>
                                </div>
                                <div class="panel-body">
                                    @if($appInfo->business_category != 2)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Full Name</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->auth_full_name) ? $appInfo->auth_full_name : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Designation</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->auth_designation) ? $appInfo->auth_designation : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Mobile No.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->auth_mobile_no) ? $appInfo->auth_mobile_no : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Email address</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        {{ (!empty($appInfo->auth_email) ? $appInfo->auth_email : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Authorization Letter </span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-8 col-xs-6">
                                                    <a target="_blank" rel="noopener" class="btn btn-xs btn-primary show-in-view" title="Authorization Letter"
                                                       href="{{ URL::to('users/upload/'.$appInfo->auth_letter) }}">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                        Authorization Letter
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @if($appInfo->business_category != 2)
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <span class="v_label">Picture</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-6">
                                                        <img class="img-thumbnail" id="authImageViewer" src="{{ url('users/upload/'.$appInfo->auth_image) }}" alt="Auth Image">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Terms and Conditions</strong></div>

                                <div class="panel-body">
                                    <div class="col-md-12">
                                        If you are submitting above
                                        any false information through the system, you shall be
                                        liable under ICT act of Government of
                                        Bangladesh.
                                        <label>
                                            <i class="fa fa-check-square"></i>
                                            <strong class="text-danger">I do here by declare that the information given above is true to the best of my
                                                knowledge and I shall be liable for any false information/ statement is given.</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            @if(count($without_govt_vat_services) > 0)
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <div class="pull-left"><strong>Allowed services without VAT Fee</strong></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            @foreach($without_govt_vat_services as $service)
                                                <div class="col-md-6">
                                                    <div class="service_box">
                                                        <div class="service_box_left">
                                                            <p>{{ $service->service_name }}</p>
                                                        </div>
                                                        @if(Auth::user()->user_type == '5x505')
                                                            <div class="service_box_right">
                                                                <a
                                                                        style="color: #fff; text-decoration: none;"
                                                                        href="{{ url('process/'.$service->form_url).'/add/'.\App\Libraries\Encryption::encodeId($service->process_type_id) }}"
                                                                        class="btn btn-xs btn-success"
                                                                        role="button"
                                                                >
                                                                    Apply Now
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="pull-left"><strong>Services list</strong></div>
                                    <div class="pull-right">
                                        @if(count($service_list) == 10)
                                            <a class="btn btn-xs btn-warning" target="_blank" rel="noopener" href="{{ url("basic-information/show-all-service/".Encryption::encodeId($company_id)) }}"><i class="fa fa-list" aria-hidden="true"></i> More lists</a>
                                        @endif
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-responsive" aria-label="Detailed Basic Info Data Table">
                                                <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Tracking No</th>
                                                    <th>Service Name</th>
                                                    <th>Approved Date</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1; ?>
                                                @forelse($service_list as $list)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $list->tracking_no }}</td>
                                                        <td>{{ $list->service_name }}</td>
                                                        <td>{{ $list->approved_date }}</td>
                                                        <td>
                                                            <a href="{{ url("process/$list->form_url/view-app/".Encryption::encodeId($list->ref_id) ."/". Encryption::encodeId($list->process_type_id)) }}"
                                                               target="_blank"
                                                               class="btn btn-xs btn-success"><i
                                                                        class="fa fa-folder-open"></i>
                                                                Open</a></td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="alert alert-warning text-center"
                                                                 role="alert">
                                                                No service available
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="pull-left"><strong>Company user list</strong></div>
                                    <div class="pull-right">
                                        @if(count($company_user_list) == 10)
                                            <a class="btn btn-xs btn-warning" target="_blank" rel="noopener" href="{{ url("basic-information/show-all-company/".Encryption::encodeId($company_id)) }}"><i class="fa fa-list" aria-hidden="true"></i> More lists</a>
                                        @endif
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-responsive" aria-label="Detailed Basic Info Data Table">
                                                <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>User Name</th>
                                                    <th>Email Address</th>
                                                    <th>User Type</th>
                                                    <th>Designation</th>
                                                    <th>Location</th>
                                                    <th>Member Since</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1; ?>
                                                @forelse($company_user_list as $value)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $value->user_full_name }}</td>
                                                        <td>{{ $value->user_email }}</td>
                                                        <td>{{ $value->working_user_type }}</td>
                                                        <td>{{ $value->designation }}</td>
                                                        <td>{{ $value->users_district }}</td>
                                                        <td>{{ $value->created_at }}</td>
                                                        <td><a href="{{ url('users/view/'.Encryption::encodeId($value->id)) }}"
                                                               target="_blank" rel="noopener"
                                                               class="btn btn-xs btn-success"><i
                                                                        class="fa fa-folder-open"></i>
                                                                Open</a></td>
                                                    </tr>

                                                @empty
                                                    <tr>
                                                        <td colspan="8">
                                                            <div class="alert alert-warning text-center"
                                                                 role="alert">
                                                                No data available
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @if(count($company_user_list) == 10)
                                    <div class="panel-footer">
                                        <div class="pull-right">
                                            <a class="btn btn-info"><i class="fa fa-list" aria-hidden="true"></i> Show all</a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">

        function openChangeBasicInfoModal(btn) {
            var this_action = btn.getAttribute('data-action');
            if (this_action != '') {
                $.get(this_action, function (data, success) {
                    if (success === 'success') {
                        $('#changeBasicInfoModal .load_modal').html(data);
                    } else {
                        $('#changeBasicInfoModal .load_modal').html('Unknown Error!');
                    }
                    $('#changeBasicInfoModal').modal('show', {backdrop: 'static'});
                });
            }
        }
        function openChangeDeptModal(btn) {
            var this_action = btn.getAttribute('data-action');
            if (this_action != '') {
                $.get(this_action, function (data, success) {
                    if (success === 'success') {
                        $('#changeDeptModal .load_modal').html(data);
                    } else {
                        $('#changeDeptModal .load_modal').html('Unknown Error!');
                    }
                    $('#changeDeptModal').modal('show', {backdrop: 'static'});
                });
            }
        }
    </script>
@endsection


