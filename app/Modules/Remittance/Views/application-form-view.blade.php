<?php
$accessMode = ACL::getAccsessRight('OfficePermissionNew');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<style>
    .row > .col-md-5, .row > .col-md-7, .row > .col-md-3, .row > .col-md-9, .row > .col-md-12 > strong:first-child {
        padding-bottom: 5px;
        display: block;
    }
</style>

<section class="content" id="applicationForm">

    @if(in_array($appInfo->status_id,[5,6,17,22]))
        @include('ProcessPath::remarks-modal')
    @endif


    <div class="col-md-12">
        {{-- Start if this is applicant user and status is 15, 32 (proceed for payment) --}}
        @if($viewMode == 'on' && in_array(Auth::user()->user_type,['5x505']) && in_array($appInfo->status_id, [15, 32]))
            @include('ProcessPath::government-payment-information')
        @endif
        {{-- End if this is applicant user and status is 15, 32 (proceed for payment) --}}

        {{--Start Remarks file for conditional approved status--}}
        @if($viewMode == 'on' && in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [17,31]))
            @include('ProcessPath::conditional-approved-form')
        @endif
        {{--End remarks file for conditional approved status--}}

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong>Application for Outward Remittance Approval</strong></h5>
                </div>
                <div class="pull-right">
                    @if ($viewMode == 'on' && isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link) && in_array(Auth::user()->user_type, ['1x101','2x202', '4x404', '5x505']))
                        <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-sm btn-info"
                           title="Download Approval Copy" target="_blank" rel="noopener">
                            <i class="fa  fa-file-pdf"></i>
                            Download Approval Copy
                        </a>
                    @endif

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>

                    @if(!in_array($appInfo->status_id,[-1,5,6]))
                        <a href="/remittance-new/app-pdf/{{ Encryption::encodeId($appInfo->id)}}"
                           target="_blank" rel="noopener"
                           class="btn btn-danger">
                            <i class="fa fa-download"></i>
                            Application Download as PDF
                        </a>
                    @endif

                    @if(in_array($appInfo->status_id,[5,6,17,22]))
                        <a data-toggle="modal" data-target="#remarksModal">
                            {!! Form::button('<i class="fa fa-eye"></i> <b>Reason of '.$appInfo->status_name.' </b>', array('type' => 'button', 'class' => 'btn btn-sm btn-danger')) !!}
                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <ol class="breadcrumb">
                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                    <li><strong> Date of
                            Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    <li><strong>Current Desk
                            :</strong> {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}
                    </li>
                </ol>

                {{--Payment information--}}

                @include('ProcessPath::payment-information')

                {{--Company basic information--}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>A. Company Information</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Name of organization/ company/ industrial project</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                {{ (!empty($appInfo->company_name)) ? $appInfo->company_name : '' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Name of organization/ company/ industrial project (বাংলা)</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                {{ (!empty($appInfo->company_name_bn)) ? $appInfo->company_name_bn : '' }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Country of origin</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                {{ (!empty($appInfo->origin_country_name)) ? $appInfo->origin_country_name : '' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Type of the organization</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                {{ (!empty($appInfo->organization_type_name)) ? $appInfo->organization_type_name : '' }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Status of the organization</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                {{ (!empty($appInfo->organization_status_name)) ? $appInfo->organization_status_name : ''  }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Ownership status</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                {{ (!empty($appInfo->ownership_status_name)) ? $appInfo->ownership_status_name : '' }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Business sector</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                {{ ($appInfo->business_sector_id == 0) ? 'Others' : $appInfo->business_sector_name }}
                            </div>
                        </div>

                        @if($appInfo->business_sector_id == 0)
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ $appInfo->business_sector_others }}
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Sub sector</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                {{ ($appInfo->business_sub_sector_id == 0) ? 'Others' : $appInfo->business_sub_sector_name }}
                            </div>
                        </div>

                        @if($appInfo->business_sub_sector_id == 0)
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ $appInfo->business_sub_sector_others }}
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Major activities in brief</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                {{ (!empty($appInfo->major_activities)) ? $appInfo->major_activities : '' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{--conditional_approved_file--}}
                @if($viewMode == 'on' && !empty($appInfo->conditional_approved_file))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Conditionally approve information</legend>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-5 v_label">Attachment<span class="pull-right">&#58;</span></div>
                                    <div class="col-md-7">
                                        <a target="_blank" rel="noopener" class="documentUrl btn btn-xs btn-primary" href="{{ URL::to('/uploads/'. $appInfo->conditional_approved_file) }}">
                                            <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-5 v_label">Remarks<span class="pull-right">&#58;</span></div>
                                    <div class="col-md-7">
                                        <span>{{ (!empty($appInfo->conditional_approved_remarks) ? $appInfo->conditional_approved_remarks : 'N/A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif
                {{--conditional_approved_file--}}

                {{--meeting_info--}}
                @if(!empty($metingInformation) && $viewMode == 'on')
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Meeting Info</legend>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Meeting No <span class="pull-right">&#58;</span></div>
                                    <div class="col-md-7">
                                        <span>{{ (!empty($metingInformation->meting_number) ? $metingInformation->meting_number : '') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Meeting Date <span class="pull-right">&#58;</span></div>
                                    <div class="col-md-7">
                                        <span>{{ (!empty($metingInformation->meting_date) ? date('d-M-Y', strtotime($metingInformation->meting_date)) : '') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif

                {{-- Information of principal promoter --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>B. Information of Principal Promoter/ Chairman/ Managing Director/ CEO/ Country manager</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Country</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_country_name)) ? $appInfo->ceo_country_name : 'N/A'  }}
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
                                        {{ (!empty($appInfo->ceo_dob)) ? date('d-M-Y', strtotime($appInfo->ceo_dob)):'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                @if($appInfo->ceo_country_id == 18)
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">NID No.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ceo_nid)) ? $appInfo->ceo_nid:'N/A'  }}
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Passport No.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ceo_passport_no)) ? $appInfo->ceo_passport_no:'N/A'  }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Designation</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_designation)) ? $appInfo->ceo_designation:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Full Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_full_name)) ? $appInfo->ceo_full_name:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if($appInfo->ceo_country_id == 18)
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">City</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ceo_district_name)) ? $appInfo->ceo_district_name : 'N/A'  }}
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">District/City/State</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ceo_city)) ? $appInfo->ceo_city:'N/A'  }}                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                @if($appInfo->ceo_country_id == 18)
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Police Station/Town</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ceo_thana_name)) ? $appInfo->ceo_thana_name :'N/A'  }}
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">State/Province</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ceo_state)) ? $appInfo->ceo_state:'N/A'  }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Post/Zip Code</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_post_code)) ? $appInfo->ceo_post_code:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">House,Flat/Apartment,Road</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_address)) ? $appInfo->ceo_address:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Telephone No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_telephone_no)) ? $appInfo->ceo_telephone_no:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Mobile No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_mobile_no)) ? $appInfo->ceo_mobile_no:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Father's Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_father_name)) ? $appInfo->ceo_father_name:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Email</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_email)) ? $appInfo->ceo_email:'N/A'  }}
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
                                        {{ (!empty($appInfo->ceo_mother_name)) ? $appInfo->ceo_mother_name:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Fax No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_fax_no)) ? $appInfo->ceo_fax_no :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Spouse name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_spouse_name)) ? $appInfo->ceo_spouse_name:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Gender</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ceo_gender)) ? $appInfo->ceo_gender : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Office Address --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>C. Office Address</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Division</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->office_division_name)) ? $appInfo->office_division_name :'N/A'  }}
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
                                        {{ (!empty($appInfo->office_district_name)) ? $appInfo->office_district_name :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Police Station</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->office_thana_name)) ? $appInfo->office_thana_name :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Post Office</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->office_post_office)) ? $appInfo->office_post_office:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Post Code</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->office_post_code)) ? $appInfo->office_post_code:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">House, Flat/Apartment, Road</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->office_address)) ? $appInfo->office_address:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Telephone No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->office_telephone_no)) ? $appInfo->office_telephone_no:'N/A'  }}
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
                                        {{ (!empty($appInfo->office_mobile_no)) ? $appInfo->office_mobile_no:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Fax No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->office_fax_no)) ? $appInfo->office_fax_no:'N/A'  }}
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
                                        {{ (!empty($appInfo->office_email)) ? $appInfo->office_email:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Factory Address --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>D. Factory Address</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">District</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->factory_district_name)) ? $appInfo->factory_district_name :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Police Station</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->factory_thana_name)) ? $appInfo->factory_thana_name :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Post Office</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->factory_post_office)) ? $appInfo->factory_post_office:'N/A'  }}
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
                                        {{ (!empty($appInfo->factory_post_code)) ? $appInfo->factory_post_code:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->factory_address)) ? $appInfo->factory_address:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Telephone No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->factory_telephone_no)) ? $appInfo->factory_telephone_no:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Mobile No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->factory_mobile_no)) ? $appInfo->factory_mobile_no:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Fax No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->factory_fax_no)) ? $appInfo->factory_fax_no:'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Email</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->factory_email)) ? $appInfo->factory_email : 'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Mouja No.</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->factory_mouja)) ? $appInfo->factory_mouja : 'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- basic information --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>1. Basic instructions</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Type of the Remittance</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->remittance_type_name)) ? $appInfo->remittance_type_name :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($appInfo->remittance_type_id == 4 || $appInfo->remittance_type_id == 5)
                            <fieldset class="scheduler-border" style="margin-top: 10px !important;">
                                <legend class="scheduler-border">Whether the Intellectual Property (Trade Mark/
                                    Brand Name/ Recipe other Patent) is registration in Bangladesh:
                                </legend>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="v_label">Copy of Trade Mark Certificate/ Copy of Application for Trade Mark Certificate</span>
                                        </div>
                                        <div class="col-md-6">
                                            @if(!empty($appInfo->int_property_attachment))
                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary documentUrl"
                                                   href="{{URL::to('/uploads/'.$appInfo->int_property_attachment)}}"
                                                   title="{{$appInfo->int_property_attachment}}">
                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                    Open File
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        @endif
                    </div>
                </div>

                {{-- Bida registration information --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>2. BIDA's registration info</strong></div>
                    <div class="panel-body">
                        <table aria-label="Detailed BIDA's registration info" class="table table-responsive table-bordered" width="100%">
                            <thead>
                            <tr class="d-none">
                                <th aria-hidden="true"  scope="col"></th>
                            </tr>
                            <tr>
                                <td>Registration No</td>
                                <td>Date</td>
                                <td>Proposed Investment (BDT)</td>
                                <td>Actual Investment (BDT)</td>
                                <td>Copy of registration</td>
                                <td>Amendment Copy of BIDA Registration</td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($bidaRegInfo as $info)
                                <tr>
                                    <td>{{ $info->registration_no }}</td>
                                    <td>{{ $info->registration_date }}</td>
                                    <td>{{ $info->proposed_investment }}</td>
                                    <td>{{ $info->actual_investment }}</td>
                                    <td>
                                        @if(!empty($info->registration_copy))
                                            <a target="_blank" rel="noopener"
                                               class="btn btn-xs btn-primary documentUrl"
                                               href="{{URL::to('/uploads/'.(!empty($info->registration_copy) ? $info->registration_copy : ''))}}"
                                               title="{{$info->registration_copy}}">
                                                <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                Open File
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($info->amendment_copy))
                                            <a target="_blank" rel="noopener"
                                               class="btn btn-xs btn-primary documentUrl"
                                               href="{{URL::to('/uploads/'.(!empty($info->amendment_copy) ? $info->amendment_copy : ''))}}"
                                               title="{{$info->amendment_copy}}">
                                                <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                Open File
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 3. Foreign collaborator's providing service/ intellectual properties Info --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>3. Foreign collaborator's providing service/ intellectual properties Info</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Name of Organization</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ (!empty($appInfo->organization_name)) ? $appInfo->organization_name : '' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Address</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ (!empty($appInfo->organization_address)) ? $appInfo->organization_address : '' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <span class="v_label">City/ State</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        {{ (!empty($appInfo->property_city)) ? $appInfo->property_city :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <span class="v_label">Post Code/ Zip code</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        {{ (!empty($appInfo->property_post_code)) ? $appInfo->property_post_code :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Country</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ (!empty($appInfo->property_country_name)) ? $appInfo->property_country_name : '' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{--4. Effective date of the agreement--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>4. Effective date of the agreement</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Date of the Agreement</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($appInfo->effective_agreement_date)) ? $appInfo->effective_agreement_date : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--5. Duration of the agreement--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>5. Duration of the agreement</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">From</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->agreement_duration_from)) ? $appInfo->agreement_duration_from :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Duration type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->agreement_duration_type)) ? $appInfo->agreement_duration_type :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($appInfo->agreement_duration_type == 'Fixed Date')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">To</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (($appInfo->agreement_duration_to != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->agreement_duration_to)) : '')  }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Total Duration</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->agreement_total_duration)) ? $appInfo->agreement_total_duration :'N/A'  }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($appInfo->agreement_duration_type == 'Until Valid Contact')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Attach valid contact</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            @if(!empty($appInfo->valid_contact_attachment))
                                                <br>
                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary documentUrl"
                                                   href="{{URL::to('/uploads/'. $appInfo->valid_contact_attachment)}}"
                                                   title="Open file">
                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                    Open file
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{--6. Schedule of payment--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>6. Schedule of payment</strong></div>
                    <div class="panel-body">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Please tick the appropriate one</legend>
                            <div class="row">
                                <div class="col-md-12">{{ (!empty($appInfo->schedule_of_payment)) ? $appInfo->schedule_of_payment : '' }}</div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                {{--7. Marketing of products (%)--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>7. Marketing of products (%)</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Local</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->marketing_of_products_local)) ? $appInfo->marketing_of_products_local :'0'  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Foreign</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->marketing_of_products_foreign)) ? $appInfo->marketing_of_products_foreign :'0'  }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--8. Present status--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>8. Present status</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Present Status</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($appInfo->present_status_name)) ? $appInfo->present_status_name : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--9. Brief description of technological service received--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>9. Brief description of technological service received</strong></div>
                    <div class="panel-body">
                        <table aria-label="Brief description of technological service received" class="table table-responsive table-bordered">
                            <tr>
                                <th aria-hidden="true"  scope="col"></th>
                            </tr>
                            <?php $i = 1; ?>
                            @forelse($briefDescription as $brief)
                                <tr>
                                    <td width="5%">{{ $i++ }}</td>
                                    <td>{{ $brief->brief_description }}</td>
                                </tr>
                            @empty
                            @endforelse
                        </table>
                    </div>
                </div>


                {{--10. Total amount to be paid as per agreement--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>10. Total amount to be paid as per agreement</strong></div>
                    <div class="panel-body">
                        <label>(a) Total amount</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Amount type</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($appInfo->agreement_amount_type)) ? $appInfo->agreement_amount_type : '' }}
                                </div>
                            </div>
                        </div>
                        @if($appInfo->agreement_amount_type == 'Fixed Amount' || $appInfo->agreement_amount_type == 'Percentage')
                            @if($appInfo->agreement_amount_type == 'Percentage')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Percentage of sales%</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ (!empty($appInfo->percentage_of_sales)) ? $appInfo->percentage_of_sales : '0'  }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Taka (BDT)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->total_agreement_amount_bdt)) ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->total_agreement_amount_bdt) :'0'  }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">USD</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->total_agreement_amount_usd)) ? $appInfo->total_agreement_amount_usd :'0'  }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <label>(b) For which period</label>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">From</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (($appInfo->period_from != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->period_from)) : '') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">To</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (($appInfo->period_to != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->period_to)) : '') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Total period</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ !empty($appInfo->total_period) ?  $appInfo->total_period : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <label>(c) Name of products/ services and annual production capacity/ value</label>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Annual production capacity/ value</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ !empty($appInfo->product_name_capacity) ?  $appInfo->product_name_capacity : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{--11. Industrial project status--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>11. Industrial project status</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Project status</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ !empty($appInfo->project_status_name) ?  $appInfo->project_status_name : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- 1 = Proposed/ Under implementation --}}
                @if($appInfo->project_status_id == 1)
                    <div class="panel panel-info">
                        <div class="panel-heading"><strong>12. Cost + Freight (C&F) value of imported machinery (In case of proposed/under implementation project) (If applicable)</strong></div>
                        <div class="panel-body">
                            <table aria-label="Detailed Proposed/ Under implementation" class="table table-bordered table-responsive" width="100%">
                                <thead>
                                <tr class="d-none">
                                    <th aria-hidden="true"  scope="col"></th>
                                </tr>
                                <tr>
                                    <td>#</td>
                                    <td>Year of import</td>
                                    <td>To</td>
                                    <td>C&F Value (BDT)</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; ?>
                                @forelse($importedMachine as $info)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ (($info->import_year_from != '0000-00-00') ? date('d-M-Y', strtotime($info->import_year_from)) : '') }}</td>
                                        <td>{{ (($info->import_year_to != '0000-00-00') ? date('d-M-Y', strtotime($info->import_year_to)) : '') }}</td>
                                        <td>{{ !empty($info->cnf_value) ? \App\Libraries\CommonFunction::convertToBdtAmount($info->cnf_value) : '0' }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- 2 = Industrial unit already in operation --}}
                @if($appInfo->project_status_id == 2)
                    <div class="panel panel-info">
                        <div class="panel-heading"><strong>12. Previous year’s sales as declared in the annual tax return (In case of industrial unit already in operation) (If applicable)</strong></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Sales year</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (($appInfo->prev_sales_year_from != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->prev_sales_year_from)):'') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">To</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (($appInfo->prev_sales_year_to != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->prev_sales_year_to)):'') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label>(a) Value of sales:</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Taka (BDT)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->sales_value_bdt) ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->sales_value_bdt) : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">USD</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->sales_value_usd) ? $appInfo->sales_value_usd : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pull-right">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Dollar conversion rate</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->usd_conv_rate) ? $appInfo->usd_conv_rate : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label>(b) Amount of tax paid:</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Taka (BDT)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->tax_amount_bdt) ? \App\Libraries\CommonFunction::convertToBdtAmount( $appInfo->tax_amount_bdt) : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label><a target="_blank" rel="noopener"
                                      href="https://www.bangladesh-bank.org/econdata/exchangerate.php">Exchange
                                    Rate Ref: Bangladesh Bank. Please Enter Today's Exchange Rate</a></label>
                        </div>
                    </div>
                @endif

                {{--13. Percentage of the total fees (If applicable)--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>13. Percentage of the total fees (If applicable)</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Percentage</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ !empty($appInfo->total_fee_percentage) ?  $appInfo->total_fee_percentage : '0' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--14. Proposed amount of remittances--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>14. Proposed amount of remittances</strong></div>
                    <div class="panel-body">
                        <table aria-label="Detailed Proposed amount of remittances" class="table table-responsive table-bordered">
                            <thead>
                            <tr class="d-none">
                                <th aria-hidden="true"  scope="col"></th>
                            </tr>
                            <tr>
                                <td>Proposed</td>
                                <td>Taka (BDT)</td>
                                <td>USD</td>
                                <td>Expressed %</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">{{ !empty($appInfo->proposed_remittance_type) ? $appInfo->proposed_remittance_type : '' }}</td>
                                <td>{{ !empty($appInfo->proposed_amount_bdt) ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->proposed_amount_bdt) : '0' }}</td>
                                <td>{{ !empty($appInfo->proposed_amount_usd) ? $appInfo->proposed_amount_usd : '0' }}</td>
                                <td>{{ !empty($appInfo->proposed_exp_percentage) ? $appInfo->proposed_exp_percentage : '0' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center"><strong>Sub Total</strong></td>
                                <td>{{ !empty($appInfo->proposed_sub_total_bdt) ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->proposed_sub_total_bdt) : '0' }}</td>
                                <td>{{ !empty($appInfo->proposed_sub_total_usd) ? $appInfo->proposed_sub_total_usd : '0' }}</td>
                                <td>{{ !empty($appInfo->proposed_sub_total_exp_percentage) ? $appInfo->proposed_sub_total_exp_percentage : '0' }}</td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="3"><strong>Total Fee (BDT)</strong></td>
                                <td>{{ !empty($appInfo->total_fee) ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->total_fee) : '0' }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <label>Note: Based on type of the remittance</label>
                    </div>
                </div>

                {{--15. Other remittance made/ to be made during the same calendar/ fiscal year (If applicable--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>15. Other remittance made/ to be made during the same calendar/ fiscal year (If applicable</strong></div>
                    <div class="panel-body">
                        <table aria-label="Detailed Other remittance made" class="table table-responsive table-bordered">
                            <thead>
                            <tr class="d-none">
                                <th aria-hidden="true"  scope="col"></th>
                            </tr>
                            <tr>
                                <td>#</td>
                                <td>Type of fee</td>
                                <td>Taka (BDT)</td>
                                <td>USD</td>
                                <td>%</td>
                                <td>Attachment</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1 ?>
                            @forelse($otherRemittanceInfo as $otherInfo)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ !empty($otherInfo->name) ? $otherInfo->name : '' }}</td>
                                    <td>{{ !empty($otherInfo->remittance_bdt) ? \App\Libraries\CommonFunction::convertToBdtAmount($otherInfo->remittance_bdt) : '0' }}</td>
                                    <td>{{ !empty($otherInfo->remittance_usd) ? $otherInfo->remittance_usd : '0' }}</td>
                                    <td>{{ !empty($otherInfo->remittance_percentage) ? $otherInfo->remittance_percentage : '0' }}</td>
                                    <td>
                                        @if(!empty($otherInfo->attachment))
                                            <a target="_blank" rel="noopener"
                                               class="btn btn-xs btn-primary documentUrl"
                                               href="{{URL::to('/uploads/'.(!empty($otherInfo->attachment) ? $otherInfo->attachment : ''))}}"
                                               title="{{$otherInfo->attachment}}">
                                                <i class="fa fa-file-pdf"
                                                   aria-hidden="true"></i>
                                                Open File
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2" class="text-right"><strong>Sub Total</strong></td>
                                <td>{{ !empty($appInfo->other_sub_total_bdt) ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->other_sub_total_bdt) : '0' }}</td>
                                <td>{{ !empty($appInfo->other_sub_total_usd) ? $appInfo->other_sub_total_usd : '0' }}</td>
                                <td>{{ !empty($appInfo->other_sub_total_percentage) ? $appInfo->other_sub_total_percentage : '0' }}</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{--16. Percentage of total remittances for the year (If applicable)--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>16. Percentage of total remittances for the year (If applicable)</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Percentage</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->total_remittance_percentage)) ? $appInfo->total_remittance_percentage :'0'  }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--17. Brief statement of benefits received/ to be received by the local company/ firm under the agreement--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>17. Brief statement of benefits received/ to be received by the local company/ firm under the agreement</strong></div>
                    <div class="panel-body">
                        <table aria-label="Detailed Brief statement of benefits received" class="table table-bordered table-responsive">
                            <tr>
                                <th aria-hidden="true"  scope="col"></th>
                            </tr>
                            <?php $i = 1 ?>
                            @forelse($briefStatement as $value)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $value->brief_statement }}</td>
                                </tr>
                            @empty
                            @endforelse
                        </table>
                    </div>
                </div>

                {{--18. Brief background of the foreign service provider--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>18. Brief background of the foreign service provider</strong></div>
                    <div class="panel-body">
                        <table aria-label="Detailed Brief background" class="table table-bordered table-responsive">
                            <tr>
                                <th aria-hidden="true"  scope="col"></th>
                            </tr>
                            <tr>
                                <td>{{ !empty($appInfo->brief_background) ? $appInfo->brief_background : '' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{--19. Statement of remittances of such fees for the last 3(three) years (If applicable)--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>19. Statement of remittances of such fees for the last 3(three) years (If applicable)</strong></div>
                    <div class="panel-body">
                        <table aria-label="Detailed Statement of remittances" class="table table-bordered table-responsive" width="100%">
                            <thead>
                            <tr class="d-none">
                                <th aria-hidden="true"  scope="col"></th>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>Year of remittance</td>
                                <td>BIDA's ref. Number</td>
                                <td>Date</td>
                                <td>Approval Copy</td>
                                <td>Amount</td>
                                <td>%</td>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($statementOfRemittance as $value)
                                <tr>
                                    <td>{{ !empty($value->name) ? $value->name : '' }}</td>
                                    <td>{{ !empty($value->remittance_year) ? $value->remittance_year : '' }}</td>
                                    <td>{{ !empty($value->bida_ref_no) ? $value->bida_ref_no : '' }}</td>
                                    <td>{{ (($value->date != '0000-00-00') ? date('d-M-Y', strtotime($value->date)) : '') }}</td>
                                    <td>
                                        @if(!empty($value->approval_copy))
                                            <a target="_blank" rel="noopener"
                                               class="btn btn-xs btn-primary documentUrl"
                                               href="{{URL::to('/uploads/'.(!empty($value->approval_copy) ? $value->approval_copy : ''))}}"
                                               title="{{$value->approval_copy}}">
                                                <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                Open File
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ !empty($value->amount) ? \App\Libraries\CommonFunction::convertToBdtAmount($value->amount) : '0' }}</td>
                                    <td>{{ !empty($value->percentage) ? $value->percentage : '' }}</td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


                {{--20. Statement of actual production/ services for the last 3(three) years (If applicable)--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>20. Statement of actual production/ services for the last 3(three) years (If applicable)</strong></div>
                    <div class="panel-body">
                        <table aria-label="Detailed Statement of remittances" class="table table-bordered table-responsive" width="100%">
                            <thead>
                            <tr class="d-none">
                                <th aria-hidden="true"  scope="col"></th>
                            </tr>
                            <tr>
                                <td>Year</td>
                                <td>Item of production/ service</td>
                                <td>Qty</td>
                                <td>Sales Value/ Revenue</td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($statementOfActualProd as $value)
                                <tr>
                                    <td>{{ !empty($value->year_of_remittance) ? $value->year_of_remittance : '' }}</td>
                                    <td>{{ !empty($value->item_of_production) ? $value->item_of_production : '' }}</td>
                                    <td>{{ !empty($value->quantity) ? $value->quantity : '' }}</td>
                                    <td>{{ !empty($value->sales_value) ? \App\Libraries\CommonFunction::convertToBdtAmount($value->sales_value) : '0' }}</td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{--21. Statement of export earning (If any) (If applicable)--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>21. Statement of export earning (If any) (If applicable)</strong></div>
                    <div class="panel-body">
                        <table aria-label="Detailed Statement of export earning" class="table table-bordered table-responsive" width="100%">
                            <thead>
                            <tr class="d-none">
                                <th aria-hidden="true"  scope="col"></th>
                            </tr>
                            <tr>
                                <td>Year of Export</td>
                                <td>Item of Export</td>
                                <td>Qty</td>
                                <td>C&F/ CIF Value</td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($statementOfExport as $value)
                                <tr>
                                    <td>{{ !empty($value->year_of_remittance) ? $value->year_of_remittance : '' }}</td>
                                    <td>{{ !empty($value->item_of_export) ? $value->item_of_export : '' }}</td>
                                    <td>{{ !empty($value->quantity) ? $value->quantity : '' }}</td>
                                    <td>{{ !empty($value->cnf_cif_value) ? \App\Libraries\CommonFunction::convertToBdtAmount($value->cnf_cif_value) : '0' }}</td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{--22. Name & address of the nominated local bank through which remittance to be effected--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>22. Name & address of the nominated local bank through which remittance to be effected</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Select Bank</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->bank_name)) ? $appInfo->bank_name : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Branch Name </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->local_branch)) ? $appInfo->local_branch :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->local_bank_address)) ? $appInfo->local_bank_address : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">City/ State</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->local_bank_city)) ? $appInfo->local_bank_city :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Post Code/ Zip Code</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->local_bank_post_code)) ? $appInfo->local_bank_post_code : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Country</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->local_bank_country_name)) ? $appInfo->local_bank_country_name :'N/A'  }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Necessary documents to be attached--}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Necessary documents to be attached here (Only PDF file)</strong>
                    </div>
                    <div class="panel-body">
                        <table aria-label="Detailed Necessary documents" class="table table-striped table-bordered table-hover ">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th colspan="6">Required attachments</th>
                                <th colspan="2">
                                    <a class="btn btn-xs btn-primary" target="_blank" rel="noopener" href="{{ url('process/open-attachment/'.Encryption::encodeId($appInfo->process_type_id).'/'.Encryption::encodeId($appInfo->id)) }}"><i class="fa fa-link" aria-hidden="true"></i> Open all</a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @if(count($document) > 0)
                                @foreach($document as $row)
                                    <tr>
                                        <td>
                                            <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                        </td>
                                        <td colspan="6">{!!  $row->doc_name !!}</td>
                                        <td colspan="2">
                                            @if(!empty($row->doc_file_path))
                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                   href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ? $row->doc_file_path : ''))}}"
                                                   title="{{$row->doc_name}}">
                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                    Open File
                                                </a>
                                            @else
                                                No file found
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="9"> No required documents!</td>
                                </tr>
                            @endif
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
                                                    {{ (!empty($appInfo->auth_full_name)) ? $appInfo->auth_full_name : '' }}
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
                                                    {{ (!empty($appInfo->auth_designation)) ? $appInfo->auth_designation : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Mobile No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->auth_mobile_no)) ? $appInfo->auth_mobile_no : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Email address</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->auth_email)) ? $appInfo->auth_email : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Picture</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <img class="img-thumbnail"
                                                         src="{{ (!empty($appInfo->auth_image) ? url('users/upload/'.$appInfo->auth_image) : url('assets/images/photo_default.png')) }}"
                                                         alt="User Photo" width="120px">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Date</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (($appInfo->created_at != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->created_at)) : '') }}
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
                                         src="{{ asset('assets/images/checked.png') }}" alt="checked_icon"/>
                                    I do hereby declare that the Outward Remittance Proposal didn’t submit earlier
                                    to the Bangladesh Investment Development Authority. The above information is
                                    true and I shall be liable for any false information is given.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>