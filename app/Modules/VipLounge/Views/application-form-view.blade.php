<?php
$accessMode = ACL::getAccsessRight('VisaRecommendation');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<style>
    .row > .col-md-5, .row > .col-md-7, .row > .col-md-3, .row > .col-md-9, .row > .col-md-12 > strong:first-child {
        padding-bottom: 5px;
        display: block;
    }

    .table-header {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
}

</style>
<section class="content" id="applicationForm">

    @if(in_array($appInfo->status_id,[5,6]))
        @include('ProcessPath::remarks-modal')
    @endif

    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong>Application for VIP Lounge</strong></h5>
                </div>
                <div class="pull-right">
                    @if ($viewMode == 'on' && isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link) && in_array(Auth::user()->user_type, ['1x101','2x202', '4x404', '5x505']))
                        <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-md btn-info"
                           title="Download Approval Copy" target="_blank" rel="noopener">
                            <i class="fa  fa-file-pdf"></i>
                            Download Approval Copy
                        </a>
                    @endif

                    {{--<a class="btn btn-md btn-primary" data-toggle="collapse" href="#basicCompanyInfo" role="button"--}}
                    {{--   aria-expanded="false" aria-controls="collapseExample">--}}
                    {{--    <i class="fas fa-info-circle"></i>--}}
                    {{--    Basic Company Info--}}
                    {{--</a>--}}

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>

                    @if(!in_array($appInfo->status_id,[-1,5,6]))
                        <a href="/vip-lounge/app-pdf/{{ Encryption::encodeId($appInfo->id)}}" target="_blank" rel="noopener" class="btn btn-md btn-danger">
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
                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }} </li>
                    <li><strong> Date of Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }} </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }} </li>
                    <li><strong>Current Desk : </strong> {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }} </li>
                </ol>

                {{--Payment information--}}
                @include('ProcessPath::payment-information')

                {{--Company basic information--}}
                @include('ProcessPath::basic-company-info-view')

                {{-- Basic Information --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Basic Information</strong></div>
                    <div class="panel-body">
                        
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                VIP/CIP longue Purpose:
                            </legend>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6">
                                            <span class="v_label">Purpose for VIP/CIP longue</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-9 col-xs-6">
                                            {{ (!empty($appInfo->vip_longue_purpose_name)) ? $appInfo->vip_longue_purpose_name:''  }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                Reference Number:
                            </legend>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Reference number type</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ref_no_type)) ? $appInfo->ref_no_type : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Reference number</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->reference_number))? $appInfo->reference_number :'' }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                Which Airport do you want to receive the VIP lounge in Bangladesh:
                            </legend>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Desired airport</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->airport_name)) ? $appInfo->airport_name : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Purpose of visit</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->visa_purpose))? $appInfo->visa_purpose :'' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                    </div>
                </div>

                {{-- Information of Expatriate / Investor / Employee --}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Information of Expatriate / Investor / Employee</strong>
                    </div>
                    <div class="panel-body">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                General information
                            </legend>

                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Full name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->emp_name)) ? $appInfo->emp_name : '' }}
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Position/ designation</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->emp_designation)) ? $appInfo->emp_designation:''  }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Brief job description</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->brief_job_description)) ? $appInfo->brief_job_description : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Photo</span>
                                            {{-- <span class="pull-right">&#58;</span> --}}
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <?php
                                            if (!empty($appInfo->investor_photo)) {
                                                $userPic = file_exists('users/upload/' . $appInfo->investor_photo) ? asset('users/upload/' . $appInfo->investor_photo) : asset('uploads/' . $appInfo->investor_photo);
                                            } else {
                                                $userPic = asset('assets/images/photo_default.png');
                                            }
                                            ?>
                                            <img class="img-thumbnail" src="{{ $userPic  }}" alt="Investor Photo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                Passport information
                            </legend>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Passport no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->emp_passport_no)) ? $appInfo->emp_passport_no : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Personal no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->emp_personal_no)) ? $appInfo->emp_personal_no : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Surname</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->emp_surname)) ? $appInfo->emp_surname : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Issuing authority</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->place_of_issue)) ? $appInfo->place_of_issue : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Given name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->emp_given_name)) ? $appInfo->emp_given_name : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Nationality</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->emp_nationality_name)) ? $appInfo->emp_nationality_name : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label"> Date of birth</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->emp_date_of_birth) ? date('d-M-Y', strtotime($appInfo->emp_date_of_birth)) : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Place of birth</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->emp_place_of_birth)) ? $appInfo->emp_place_of_birth : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Date of issue</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->pass_issue_date) ? date('d-M-Y', strtotime($appInfo->pass_issue_date)) : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Date of expiry</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->pass_expiry_date) ? date('d-M-Y', strtotime($appInfo->pass_expiry_date)) : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Spouse/child Information</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table aria-label="Detailed Spouse/child Information" class="table table-striped table-bordered" cellspacing="0" width="100%" id="spouseOrChildTableId">
                                            <thead class="table-header">
                                            <tr>
                                                <th>Spouse/child</th>
                                                <th>Name</th>
                                                <th>Passport/Personal No.</th>
                                                <th>Remarks</th>
                                            </tr>
                                            </thead>
                                            @forelse ($spouse_child_info as $spouse_child)
                                                <tr>
                                                    <td>{{ $spouse_child->spouse_child_type }}</td>
                                                    <td>{{ $spouse_child->spouse_child_name }}</td>
                                                    <td>{{ $spouse_child->spouse_child_passport_per_no }}</td>
                                                    <td>{{ $spouse_child->spouse_child_remarks }}</td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">To whom, the p- pass will be issued</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table aria-label="Detailed Report Data Table" class="table table-striped table-bordered" cellspacing="0" width="100%" id="passportHolderTableId">
                                            <thead class="table-header">
                                            <tr>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Mobile Number</th>
                                                <th>NID/Passport Number</th>
                                                <th>NID/Passport Copy</th>
                                            </tr>
                                            </thead>
                                            @forelse ($passport_holder_info as $passport_holder)
                                                <tr>
                                                    <td>{{ $passport_holder->passport_holder_name }}</td>
                                                    <td>{{ $passport_holder->passport_holder_designation }}</td>
                                                    <td>{{ $passport_holder->passport_holder_mobile }}</td>
                                                    <td>{{ $passport_holder->passport_holder_passport_no }}</td>
                                                    <td>
                                                        @if(!empty($passport_holder->passport_holder_attachment))
                                                            <a target="_blank" rel="noopener" class="btn btn-xs btn-primary documentUrl"
                                                            href="{{URL::to('/uploads/'.(!empty($passport_holder->passport_holder_attachment) ? $passport_holder->passport_holder_attachment : ''))}}"
                                                            title="{{$passport_holder->passport_holder_attachment}}">
                                                                <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                                Open File
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Flight Details of the Visiting Expatriates</strong>
                    </div>
                    <div class="panel-body">
                        @if ($appInfo->vip_longue_purpose_id  !== 2)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Arrival date & time</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->arrival_date)  ? date('d-M-Y', strtotime($appInfo->arrival_date)) : '' }}
                                            &nbsp;{{ date('H:i', strtotime($appInfo->arrival_time)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Arrival flight no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->arrival_flight_no)) ? $appInfo->arrival_flight_no : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($appInfo->vip_longue_purpose_id  !== 1)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Departure date & time</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->departure_date)  ? date('d-M-Y', strtotime($appInfo->departure_date)) : '' }}
                                            &nbsp;{{  date('H:i', strtotime($appInfo->departure_time)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Departure flight no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->departure_flight_no)) ? $appInfo->departure_flight_no : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Necessary documents to be attached here (Only PDF file)</strong>
                    </div>
                    <div class="panel-body">
                        <table aria-label="Detailed Necessary document" class="table table-striped table-bordered table-hover ">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th colspan="6">Required attachments</th>
                                <th colspan="2">
                                    <a class="btn btn-xs btn-primary" target="_blank" rel="noopener" href="{{ url('process/open-attachment/'.Encryption::encodeId($appInfo->process_type_id).'/'.Encryption::encodeId($appInfo->id).'/'.Encryption::encodeId('master')) }}"><i class="fa fa-link" aria-hidden="true"></i> Open all</a>
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
                                            <td colspan="6">
                                                {!!  $row->doc_name !!}
                                            </td>
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

