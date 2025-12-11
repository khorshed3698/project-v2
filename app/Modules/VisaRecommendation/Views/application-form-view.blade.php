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
</style>
<section class="content" id="applicationForm">

    @if(in_array($appInfo->status_id,[5,6]))
        @include('ProcessPath::remarks-modal')
    @endif

    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong>Application for Visa Recommendation</strong></h5>
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
                        <a href="/visa-recommendation/app-pdf/{{ Encryption::encodeId($appInfo->id)}}"
                           target="_blank" rel="noopener"
                           class="btn btn-md btn-danger">
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

                @if(!empty($appInfo->divisional_office_name))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Application Approval:</legend>
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Office name</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ $appInfo->divisional_office_name }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Office address</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ $appInfo->divisional_office_address }}
                            </div>
                        </div>
                    </fieldset>
                @endif

                {{--Company basic information--}}
                @include('ProcessPath::basic-company-info-view')

                {{-- Basic Information --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Basic Information</strong></div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Visa type</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ (!empty($appInfo->app_type_name)) ? $appInfo->app_type_name:''  }}
                            </div>
                        </div>

                        {{--Visa on arrival--}}
                        @if($appInfo->app_type_id == 5)
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">
                                    Which airport do you want to receive the visa recommendation in Bangladesh
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
                                                {{ (!empty($appInfo->visa_purpose_name))? $appInfo->visa_purpose_name :'' }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7 col-md-offset-5 col-xs-6 col-xs-offset-6">
                                                {{ $appInfo->visa_purpose_name == 'Others' ? $appInfo->visa_purpose_others : '' }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </fieldset>
                        @else
                            <fieldset style="margin-bottom: 0 !important;" class="scheduler-border">
                                <legend class="scheduler-border">
                                    Embassy/ high commission of Bangladesh in abroad where recommendation letter to be sent
                                </legend>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Country</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ (!empty($appInfo->mission_country_name)) ? $appInfo->mission_country_name : '' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Embassy/ high commission</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ (!empty($appInfo->high_commision_id))? $appInfo->high_commision_name.','.$appInfo->high_commision_address :'' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        @endif
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
                                <div class="col-md-9 col-xs-12">
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

                                    @if($appInfo->app_type_id != 5)
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Brief job description</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ (!empty($appInfo->brief_job_description)) ? $appInfo->brief_job_description : '' }}
                                            </div>
                                        </div>
                                    @endif

                                    @if($appInfo->business_category == 2)
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Marital Status</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ (!empty($appInfo->emp_marital_status)) ? $appInfo->emp_marital_status : '' }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Photo</span>
                                            {{--<span class="pull-right">&#58;</span>--}}
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
                                            {{ ((!empty($appInfo->emp_date_of_birth)) ? date('d-M-Y', strtotime($appInfo->emp_date_of_birth)) : '') }}
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
                                            {{ ((!empty($appInfo->pass_issue_date)) ? date('d-M-Y', strtotime($appInfo->pass_issue_date)) : '') }}
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
                                            {{ ((!empty($appInfo->pass_expiry_date)) ? date('d-M-Y', strtotime($appInfo->pass_expiry_date)) : '') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                Contact address of the expatriate in Bangladesh
                            </legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Division</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ex_office_division_name)) ? $appInfo->ex_office_division_name : '' }}
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
                                            {{ (!empty($appInfo->ex_office_district_id)) ? $appInfo->ex_office_district_name : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Police station</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ex_office_thana_name)) ? $appInfo->ex_office_thana_name : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Post office</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ex_office_post_office)) ? $appInfo->ex_office_post_office : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Post code</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ex_office_post_code)) ? $appInfo->ex_office_post_code : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">House, flat/ apartment, road</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ex_office_address)) ? $appInfo->ex_office_address : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label"> Telephone no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ex_office_telephone_no)) ? $appInfo->ex_office_telephone_no : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Mobile no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ex_office_mobile_no)) ? $appInfo->ex_office_mobile_no : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Fax no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ex_office_fax_no)) ? $appInfo->ex_office_fax_no : '' }}
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
                                            {{ (!empty($appInfo->ex_office_email)) ? $appInfo->ex_office_email : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        @if($appInfo->app_type_id != 5)
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">
                                    Compensation and benefit
                                </legend>
                                <div class="table-responsive">
                                    <table aria-label="Detailed Compensation and benefit" class="table table-striped table-bordered" cellspacing="10" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Salary structure</th>
                                            <th>Payment</th>
                                            <th>Amount</th>
                                            <th>Currency</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <span class="v_label">a. Basic salary / Honorarium</span>
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->basic_payment_type_name)) ? $appInfo->basic_payment_type_name : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->basic_local_amount)) ? $appInfo->basic_local_amount : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->basic_currency_code)) ? $appInfo->basic_currency_code :'' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="v_label">b. Overseas allowance</span>
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->overseas_payment_type_name)) ? $appInfo->overseas_payment_type_name : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->overseas_local_amount)) ? $appInfo->overseas_local_amount : ''  }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->overseas_currency_code)) ? $appInfo->overseas_currency_code : ''  }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="v_label">c. House rent</span>
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->house_payment_type_name)) ? $appInfo->house_payment_type_name : ''  }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->house_local_amount)) ? $appInfo->house_local_amount : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->house_currency_code)) ? $appInfo->house_currency_code : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="v_label">d. Conveyance</span>
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->conveyance_payment_type_name)) ? $appInfo->conveyance_payment_type_name : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->conveyance_local_amount)) ? $appInfo->conveyance_local_amount : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->conveyance_currency_code)) ? $appInfo->conveyance_currency_code : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="v_label">e. Medical allowance</span>
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->medical_payment_type_name)) ? $appInfo->medical_payment_type_name : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->medical_local_amount)) ? $appInfo->medical_local_amount : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->medical_currency_code)) ? $appInfo->medical_currency_code : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="v_label">f. Entertainment allowance</span>
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->ent_payment_type_name)) ? $appInfo->ent_payment_type_name : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->ent_local_amount)) ? $appInfo->ent_local_amount : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->ent_currency_code)) ? $appInfo->ent_currency_code : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="v_label">g. Annual Bonus</span>
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->bonus_payment_type_name)) ? $appInfo->bonus_payment_type_name : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->bonus_local_amount)) ? $appInfo->bonus_local_amount:''  }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->bonus_currency_code)) ? $appInfo->bonus_currency_code : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="v_label"> h. Other fringe benefits (if any)</span>
                                            </td>
                                            <td colspan="5">
                                                {{ (!empty($appInfo->other_benefits)) ? $appInfo->other_benefits : '' }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                            @if($appInfo->business_category == 1)
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">
                                        Others particular of organization
                                    </legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Nature of business</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->nature_of_business)) ? $appInfo->nature_of_business : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Remittance received during the last twelve months (USD)</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->received_remittance)) ? $appInfo->received_remittance : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <Strong>Capital structure</Strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">(i) Authorized Capital (USD)</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->auth_capital)) ? $appInfo->auth_capital : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">(ii) Paid-up Capital (USD)</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->paid_capital)) ? $appInfo->paid_capital : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            @endif
                        @endif

                        {{-- Start business category --}}
                        @if($appInfo->business_category == 2 && $appInfo->emp_marital_status == "married")
                            {{--Spouse Information--}}
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">
                                    Spouse Information
                                </legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Spouse Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ (!empty($appInfo->emp_spouse_name)) ? $appInfo->emp_spouse_name : '' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Passport Number</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ (!empty($appInfo->emp_spouse_passport_no)) ? $appInfo->emp_spouse_passport_no : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Nationality</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ (!empty($appInfo->spouse_nationality_name)) ? $appInfo->spouse_nationality_name : '' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Does he/she work in Bangladesh?</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                {{ (!empty($appInfo->emp_spouse_work_status)) ? $appInfo->emp_spouse_work_status : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($appInfo->emp_spouse_work_status == 'yes')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Organization Name</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->emp_spouse_org_name)) ? $appInfo->emp_spouse_org_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </fieldset>
                        @endif
                    </div>
                </div>


                @if($appInfo->app_type_id != 5)
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong>Previous travel history of the expatriate to Bangladesh</strong>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-9">
                                            <span class="v_label">Have you visited to Bangladesh previously?</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-3">
                                            {{ (!empty($appInfo->travel_history)) ? ucfirst($appInfo->travel_history) : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($appInfo->travel_history == 'yes')
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">
                                        In which period
                                    </legend>
                                    <table aria-label="Detailed Report Data Table" class="table table-striped table-bordered responsive mb5" cellspacing="10">
                                        <thead>
                                        <tr>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Type of visa availed</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($previous_travel_history) > 0)
                                            @foreach($previous_travel_history as $record)
                                                <tr>
                                                    <td>{{ ((!empty($record->th_emp_duration_from)) ? date('d-M-Y', strtotime($record->th_emp_duration_from)) : '') }} </td>
                                                    <td>{{ ((!empty($record->th_emp_duration_to)) ? date('d-M-Y', strtotime($record->th_emp_duration_to)) : '') }} </td>
                                                    <td> {{ (!empty($record->type)) ? $record->type : ''  }}
                                                        @if (!empty($record->th_visa_type_others))
                                                            <br/>
                                                            {{ $record->th_visa_type_others }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">No visa record</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </fieldset>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-9">
                                                Have you visited to Bangladesh with employment visa?
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-3">
                                                {{ (!empty($appInfo->th_visit_with_emp_visa)) ? ucfirst($appInfo->th_visit_with_emp_visa) : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($appInfo->th_visit_with_emp_visa == 'yes')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-9">
                                                    Have you received work permit from Bangladesh?
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-3">
                                                    {{ (!empty($appInfo->th_emp_work_permit)) ? ucfirst($appInfo->th_emp_work_permit) : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($appInfo->th_emp_work_permit == 'yes')
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">
                                                Previous work permit information in Bangladesh
                                            </legend>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            TIN number
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ (!empty($appInfo->th_emp_tin_no)) ? $appInfo->th_emp_tin_no : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            Last work permit ref. no.
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ (!empty($appInfo->th_emp_wp_no)) ? $appInfo->th_emp_wp_no : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            Name of the employer organization
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ (!empty($appInfo->th_emp_org_name)) ? $appInfo->th_emp_org_name : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            Address of the organization
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ (!empty($appInfo->th_emp_org_address)) ? $appInfo->th_emp_org_address : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            City/ district
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ (!empty($appInfo->th_org_district_name)) ? $appInfo->th_org_district_name : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            Thana/ upazilla
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ (!empty($appInfo->th_org_thana_name)) ? $appInfo->th_org_thana_name : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            Post office
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ (!empty($appInfo->th_org_post_office)) ? $appInfo->th_org_post_office : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            Post code
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ (!empty($appInfo->th_org_post_code)) ? $appInfo->th_org_post_code :'' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            Contact number
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ (!empty($appInfo->th_org_telephone_no)) ? $appInfo->th_org_telephone_no : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            Email
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ (!empty($appInfo->th_org_email)) ? $appInfo->th_org_email : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <fieldset style="margin-bottom: 0 !important;" class="scheduler-border">
                                            <legend class="scheduler-border">
                                                Attachments
                                            </legend>
                                            <table aria-label="Detailed Attachments" class="table table-striped table-bordered" cellspacing="10"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Required attachments</th>
                                                    <th>
                                                        @if(count($travel_history_document) > 0)
                                                            <a class="btn btn-xs btn-primary" target="_blank" rel="noopener"
                                                               href="{{ url('process/open-attachment/'.Encryption::encodeId($appInfo->process_type_id).'/'.Encryption::encodeId($appInfo->id).'/'.Encryption::encodeId('type2')) }}"><i
                                                                        class="fa fa-file-pdf" aria-hidden="true"></i>
                                                                Open all</a>
                                                        @else
                                                            Attached PDF file
                                                        @endif
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1; ?>
                                                @if(count($travel_history_document) > 0)
                                                    @foreach($travel_history_document as $row)
                                                        <tr>
                                                            <td>
                                                                <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                            </td>
                                                            <td>
                                                                {!!  $row->doc_name !!}
                                                            </td>
                                                            <td>
                                                                @if(!empty($row->doc_file_path))
                                                                    <a target="_blank" rel="noopener"
                                                                       class="btn btn-xs btn-primary"
                                                                       href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ? $row->doc_file_path : ''))}}"
                                                                       title="{{$row->doc_name}}">
                                                                        <i class="fa fa-file-pdf"
                                                                           aria-hidden="true"></i>
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
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Copy of the first work permit</td>
                                                        <td>
                                                            @if(!empty($appInfo->th_first_work_permit))
                                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                                   title="Copy of the first work permit"
                                                                   href="{{ URL::to('/uploads/'. $appInfo->th_first_work_permit) }}">
                                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                                    Open File
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Copy of the resignation letter</td>
                                                        <td>
                                                            @if(!empty($appInfo->th_resignation_letter))
                                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                                   title="Copy of the resignation letter"
                                                                   href="{{ URL::to('/uploads/'. $appInfo->th_resignation_letter) }}">
                                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                                    Open File
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Copy of the release order/ termination letter/ no. objection
                                                            certificate
                                                        </td>
                                                        <td>
                                                            @if(!empty($appInfo->th_release_order))
                                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                                   title="Copy of the release order/ termination letter/ no. objection certificate"
                                                                   href="{{ URL::to('/uploads/'. $appInfo->th_release_order) }}">
                                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                                    Open File
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Copy of the last extension (if applicable) <span
                                                                    class="required-star"></span></td>
                                                        <td>
                                                            @if(!empty($appInfo->th_last_extension))
                                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                                   title="Copy of the last extension"
                                                                   href="{{ URL::to('/uploads/'. $appInfo->th_last_extension) }}">
                                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                                    Open File
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Copy of the cancellation of the last work permit <span
                                                                    class="required-star"></span></td>
                                                        <td>
                                                            @if(!empty($appInfo->th_last_work_permit))
                                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                                   title="Copy of the cancellation of the last work permit"
                                                                   href="{{ URL::to('/uploads/'. $appInfo->th_last_work_permit) }}">
                                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                                    Open File
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>Copy of the income tax certificate for the last assessment
                                                            year
                                                            of the previous stay <span class="required-star"></span>
                                                        </td>
                                                        <td>
                                                            @if(!empty($appInfo->th_income_tax))
                                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                                   title="Copy of the income tax certificate for the last assessment year of the previous stay"
                                                                   href="{{ URL::to('/uploads/'. $appInfo->th_income_tax) }}">
                                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                                    Open File
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </fieldset>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>

                    @if($appInfo->business_category == 1)
                        {{--Manpower of the organization--}}
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Manpower of the organization</strong></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table aria-label="Detailed Manpower of the organization" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th colspan="3">Local (a)</th>
                                            <th colspan="3">Foreign (b)</th>
                                            <th>Grand Total</th>
                                            <th colspan="2">Ratio</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Executive</td>
                                            <td>Supporting Staff</td>
                                            <td>Total</td>
                                            <td>Executive</td>
                                            <td>Supporting Staff</td>
                                            <td>Total</td>
                                            <td>(a+b)</td>
                                            <td>Local</td>
                                            <td>Foreign</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ (!empty($appInfo->local_executive)) ? $appInfo->local_executive : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->local_stuff)) ? $appInfo->local_stuff : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->local_total)) ? $appInfo->local_total : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->foreign_executive)) ? $appInfo->foreign_executive : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->foreign_stuff)) ? $appInfo->foreign_stuff : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->foreign_total)) ? $appInfo->foreign_total : '' }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->manpower_total)) ? $appInfo->manpower_total : ''  }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->manpower_local_ratio)) ? $appInfo->manpower_local_ratio : ''  }}
                                            </td>
                                            <td>
                                                {{ (!empty($appInfo->manpower_foreign_ratio)) ? $appInfo->manpower_foreign_ratio : ''  }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                {{--Visa on Arrival--}}
                @if($appInfo->app_type_id == 5)
                    <div class="panel panel-info">
                        <div class="panel-heading">Flight Details of the Visiting Expatriates</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Arrival date & time</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ ((!empty($appInfo->arrival_date)) ? date('d-M-Y', strtotime($appInfo->arrival_date)) : '') }}
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Departure date & time</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ ((!empty($appInfo->departure_date)) ? date('d-M-Y', strtotime($appInfo->departure_date)) : '') }}
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
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">On Arrival Information</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">
                                        Type the services required for the visiting expatriate
                                    </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($appInfo->visiting_service_name)) ? $appInfo->visiting_service_name : '' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">
                                        On what circumstances the visa on arrival is sought instead of obtaining Visa from
                                        Bangladesh mission abroad
                                    </span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($appInfo->visa_on_arrival_sought_name)) ? $appInfo->visa_on_arrival_sought_name : '' }}
                                </div>
                            </div>
                            @if($appInfo->visa_on_arrival_sought_id == 6)
                                <div class="row">
                                    <div class="col-md-7 col-xs-6 col-md-offset-5 col-xs-offset-6">
                                        {{ (!empty($appInfo->visa_on_arrival_sought_other)) ? $appInfo->visa_on_arrival_sought_other : '' }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

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

                        {{--                        @include('VisaRecommendation::doc-tab')--}}
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
                                                            {{ (!empty($appInfo->created_at) ? date('d-M-Y', strtotime($appInfo->created_at)) : '') }}
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

