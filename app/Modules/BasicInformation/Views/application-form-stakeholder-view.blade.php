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
    </style>

    <section class="content">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body" id="inputForm">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h5><strong>Application for Basic Information ({{ $applicant_type_name }})</strong></h5>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>A. Company Information</strong>
                                </div>
                                <div class="panel-body">
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
                                            <span class="v_label">Status of the Organization</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8 col-xs-7">
                                            {{ (!empty($appInfo->organization_status) ? $appInfo->organization_status : '') }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-xs-5">
                                            <span class="v_label">Ownership status</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8 col-xs-7">
                                            {{ (!empty($appInfo->ownership_status) ? $appInfo->ownership_status : '') }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-xs-5">
                                            <span class="v_label">Type of the organization</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-8 col-xs-7">
                                            {{ (!empty($appInfo->organization_type) ? $appInfo->organization_type : '') }}
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

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>D. Factory Address [ optional ]</strong>
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

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>Authorized Person Information</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Full Name</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
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
                                                <div class="col-md-4 col-xs-6">
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

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Authorization Letter </span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-4 col-xs-6">
                                                    <a target="_blank" rel="noopener"
                                                        class="btn btn-xs btn-primary show-in-view"
                                                        title="Authorization Letter"
                                                        href="{{URL::to('users/upload/'.$appInfo->auth_letter)}}">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                        Authorization Letter
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-6">
                                                    <span class="v_label">Picture</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-8 col-xs-6">
                                                    <img class="img-thumbnail" id="authImageViewer"
                                                         src="{{ isset($appInfo->auth_image) != '' ? url('users/upload/'.$appInfo->auth_image) : url('users/upload/'.\Illuminate\Support\Facades\Auth::user()->user_pic) }}"
                                                         alt="Auth Image">
                                                </div>
                                            </div>
                                        </div>
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
                                                knowledge and I shall be liable for any false information/ system is given.</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection