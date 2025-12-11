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

    /*application info*/
    .app-heding {
        font-size: 15px;
        text-align: center;
        font-weight: bold;
    }

    .app-heding-yellow {
        background-color: #f6d10f;
        padding: 5px;
        border: 1px solid #ddd;
    }

    .app-heding-green {
        background-color: #67db38;
        padding: 5px;
        border: 1px solid #ddd;
    }

    .app-common-yellow {
        background-color: #fcf8e3;
        padding: 5px;
        border: 1px solid #ddd;
        border-top: none;
        border-right: none;
    }

    .app-common-green {
        background-color: #dff0d8;
        padding: 5px;
        border: 1px solid #ddd;
        border-top: none;
    }

    .app-common {
        padding: 5px;
        border: 1px solid #ddd;
        border-top: none;
        border-right: none;
    }

</style>

<section class="content" id="applicationForm">

    @if(in_array($appInfo->status_id,[5,6,17,22]))
        @include('ProcessPath::remarks-modal')
    @endif

    <div class="col-md-12">
        <div class="panel panel-info">

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


            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong>Application for Office Permission Amendment</strong></h5>
                </div>
                <div class="pull-right">
                    @if ($viewMode == 'on' && isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link) && in_array(Auth::user()->user_type, ['1x101','2x202', '4x404', '5x505']))
                        <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-md btn-info"
                           title="Download Approval Copy" target="_blank" rel="noopener">
                            <i class="fa  fa-file-pdf"></i>
                            Download Approval Copy
                        </a>
                    @endif

{{--                    <a class="btn btn-md btn-primary" data-toggle="collapse" href="#basicCompanyInfo" role="button"--}}
{{--                       aria-expanded="false" aria-controls="collapseExample">--}}
{{--                        <i class="fas fa-info-circle"></i>--}}
{{--                        Basic Company Info--}}
{{--                    </a>--}}

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>

                    @if(!in_array($appInfo->status_id,[-1,5,6]))
                        <a href="/office-permission-amendment/app-pdf/{{ Encryption::encodeId($appInfo->id)}}"
                           target="_blank" rel="noopener" class="btn btn-md btn-danger">
                            <i class="fa fa-download"></i>
                            Application Download as PDF
                        </a>
                    @endif

                    @if(in_array($appInfo->status_id,[5,6,17,22]))
                        <a data-toggle="modal" data-target="#remarksModal">
                            {!! Form::button('<i class="fa fa-eye"></i> Reason of '.$appInfo->status_name.'', array('type' => 'button', 'class' => 'btn btn-md btn-danger')) !!}
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

                {{--conditional_approved_file--}}
                @if($viewMode == 'on' && !empty($appInfo->conditional_approved_file))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Conditionally approve information</legend>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2"><span class="v_label">Attachment</span></div>
                                    <div class="col-md-10">
                                        <a target="_blank" rel="noopener" class="documentUrl btn btn-xs btn-primary" href="{{ URL::to('/uploads/'. $appInfo->conditional_approved_file) }}">
                                            <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-2"><span class="v_label">Remarks</span></div>
                                    <div class="col-md-10">
                                        {{ (!empty($appInfo->conditional_approved_remarks) ? $appInfo->conditional_approved_remarks : '') }}
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
                                    <div class="col-md-5 v_label">Meeting No</div>
                                    <div class="col-md-7">
                                        <span>{{ (!empty($metingInformation->meting_number) ? $metingInformation->meting_number : '') }}</span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Meeting Date</div>
                                    <div class="col-md-7">
                                        <span>{{ (!empty($metingInformation->meting_date) ? date('d-M-Y', strtotime($metingInformation->meting_date)) : '') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif
                {{--meeting_info--}}

                {{-- Basic Information --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Basic information</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Did you receive your Office Permission approval from the online OSS?</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->is_approval_online)) ? ucfirst($appInfo->is_approval_online) : ''  }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                @if($appInfo->is_approval_online == 'yes')
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Approved office recommendation reference no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <a href="{{$data['ref_app_url']}}" target="_blank" rel="noopener">
                                                <span class="label label-success label_tracking_no">{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>
                                            </a>
                                            &nbsp;
                                            &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                        </div>
                                    </div>
                                @endif

                                @if($appInfo->is_approval_online == 'no')
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Manually approved office recommendation reference no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->manually_approved_op_no)) ? $appInfo->manually_approved_op_no : ''  }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-8 col-xs-9">
                                        <span class="v_label">Effective date of the previous office permission</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-4 col-xs-3">
                                        {{ (!empty($appInfo->date_of_office_permission)) ? date('d-M-Y', strtotime($appInfo->date_of_office_permission)) : ''  }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-8 col-xs-9">
                                        <span class="v_label">Office type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-4 col-xs-3">
                                        {{ (!empty($appInfo->n_office_type_name)) ? $appInfo->n_office_type_name : $appInfo->office_type_name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Company basic information--}}
                @include('ProcessPath::basic-company-info-view')
                
                <!-- Effective date of amendment -->
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Effective date of amendment</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-5 v_label">Effective date</div>
                                <div class="col-md-7">
                                    <span>{{ (!empty($appInfo->effective_date) ? date('d-M-Y', strtotime($appInfo->effective_date)) : '') }}</span>
                                </div>
                            </div>
                            @if((in_array($appInfo->status_id, [15, 16, 25]) && Auth::user()->user_type == '5x505') || (in_array(Auth::user()->user_type, ['1x101', '2x202', '4x404'])))
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Approved effective date</div>
                                    <div class="col-md-7">
                                        <span>{{ (!empty($appInfo->approved_effective_date) ? date('d-M-Y', strtotime($appInfo->approved_effective_date)) : '') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </fieldset>

                {{-- Previous Information of proposed branch/ liaison/ representative office --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Previous Information of proposed branch/ liaison/ representative office</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="app-heding">
                                    <div class="col-md-1"
                                         style="padding: 5px; border: 1px solid #ddd; border-right: none">#
                                    </div>
                                    <div class="col-md-3"
                                         style="padding: 5px; border: 1px solid #ddd; border-right: none">Field name
                                    </div>
                                    <div class="col-md-4 app-heding-yellow">Existing information</div>
                                    <div class="col-md-4 app-heding-green">Proposed information</div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">1</div>
                                <div class="col-md-3 app-common">Office Type</div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->office_type_name) ? $appInfo->office_type_name : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_office_type_name) ? $appInfo->n_office_type_name : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">2</div>
                                <div class="col-md-3 app-common">Name of the local company</div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->local_company_name) ? $appInfo->local_company_name : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_local_company_name) ? $appInfo->n_local_company_name : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">3</div>
                                <div class="col-md-3 app-common">Division</div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->ex_office_division_name) ? $appInfo->ex_office_division_name : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_ex_office_division_name) ? $appInfo->n_ex_office_division_name : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">4</div>
                                <div class="col-md-3 app-common">District</div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->ex_office_district_name) ? $appInfo->ex_office_district_name : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_ex_office_district_name) ? $appInfo->n_ex_office_district_name : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">5</div>
                                <div class="col-md-3 app-common">Police station</div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->ex_office_thana_name) ? $appInfo->ex_office_thana_name : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_ex_office_thana_name) ? $appInfo->n_ex_office_thana_name : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">6</div>
                                <div class="col-md-3 app-common">Post office</div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->ex_office_post_office) ? $appInfo->ex_office_post_office : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_ex_office_post_office) ? $appInfo->n_ex_office_post_office : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">7</div>
                                <div class="col-md-3 app-common">Post code</div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->ex_office_post_code) ? $appInfo->ex_office_post_code : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_ex_office_post_code) ? $appInfo->n_ex_office_post_code : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">8</div>
                                <div class="col-md-3 app-common">House, Flat/ Apartment, Road </div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->ex_office_address) ? $appInfo->ex_office_address : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_ex_office_address) ? $appInfo->n_ex_office_address : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">9</div>
                                <div class="col-md-3 app-common">Telephone no. </div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->ex_office_telephone_no) ? $appInfo->ex_office_telephone_no : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_ex_office_telephone_no) ? $appInfo->n_ex_office_telephone_no : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">10</div>
                                <div class="col-md-3 app-common">Mobile no.</div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->ex_office_mobile_no) ? $appInfo->ex_office_mobile_no : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_ex_office_mobile_no) ? $appInfo->n_ex_office_mobile_no : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">11</div>
                                <div class="col-md-3 app-common">Fax no.</div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->ex_office_fax_no) ? $appInfo->ex_office_fax_no : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_ex_office_fax_no) ? $appInfo->n_ex_office_fax_no : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">12</div>
                                <div class="col-md-3 app-common">Email</div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->ex_office_email) ? $appInfo->ex_office_email : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_ex_office_email) ? $appInfo->n_ex_office_email : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-1 app-common">13</div>
                                <div class="col-md-3 app-common">
                                    Activities in Bangladesh through the proposed branch/ liaison/ representative office (Max. 250 characters )
                                </div>
                                <div class="col-md-4 app-common-yellow">
                                    {{ (!empty($appInfo->activities_in_bd) ? $appInfo->activities_in_bd : '&nbsp;') }}
                                </div>
                                <div class="col-md-4 app-common-green">
                                    {{ (!empty($appInfo->n_activities_in_bd) ? $appInfo->n_activities_in_bd : '&nbsp;') }}
                                </div>
                                <div class="clearfix"></div>
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
                        <table aria-label="Detailed Report Data Table" class="table table-striped table-bordered table-hover ">
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

                {{-- Information about Declaration and undertaking --}}
                <div id="declaration_undertaking" class="mb0 panel panel-info">
                    <div class="panel-heading"><strong>Declaration and undertaking</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <ol type="a">
                                    <li>I do hereby declare that the information given above is true to the best of my
                                        knowledge and I shall be liable for any false information/ statement given
                                    </li>
                                    <li>I do hereby undertake full responsibility of the expatriate for whom visa
                                        recommendation is sought during their stay in Bangladesh
                                    </li>
                                </ol>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">
                                        Authorized person of the organization
                                    </legend>
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
                                                            {{ empty($appInfo->created_at) ? '' : date('d-M-Y', strtotime($appInfo->created_at)) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div>
                                    <i class="fa fa-check-square"></i>
                                    I do here by declare that the information given above is true to the best of my
                                    knowledge and I shall be liable for any false information/ statement is given.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>

</body>
</html>
