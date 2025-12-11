<?php
$accessMode = ACL::getAccsessRight('CTCC');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>
    .wizard > .content,
    .wizard,
    .tabcontrol {
        overflow: visible;
    }

    .select2 {
        display: block !important;
        max-width: 100%;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
    }

    .wizard > .steps .number {
        font-size: 1.2em;
    }

    .intl-tel-input .country-list {
        z-index: 5;
    }


    .col-md-7 {
        margin-bottom: 10px;
    }

    label {
        float: left !important;
    }

    .col-md-5 {
        position: relative;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 8px;
    }

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .adhoc {
        margin-left: 15px;
    }

    .adhoc button {
        margin-top: 15px;
    }

    table thead {
        background-color: #ddd;
    }
    .select2-selection {
        max-width: 250px !important;
    }

    .none-pointer {
        pointer-events: none;
    }

    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

    }
</style>


<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box" id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h5><strong>Application For New Trade License (CCC)</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'ctcc/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewConnection',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>

                    <h3 class="text-center stepHeader"> General Information</h3>
                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Business Type</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-hover ">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Fiscal Year</th>
                                                <th class="text-center">Business Type 1</th>
                                                <th class="text-center">Business Type 2</th>
                                                <th class="text-center">License Fee</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="businessTypeDetails">
                                            <tr id="rowCountbusinessTypeDetails_0" data-number="0">
                                                <td class="col-md-3 col-xs-3">{!! Form::select('fiscalYear[0]',[],null,['class' =>'form-control input-md fiscal_year required','id'=>'fiscalYear_0']) !!}</td>
                                                <td class="col-md-3 col-xs-3">{!! Form::select('businessType_1[0]',[],null,['class' =>'form-control input-md business_type_1 required','id'=>'businessType1_0']) !!}</td>
                                                <td class="col-md-3 col-xs-3">{!! Form::select('businessType_2[0]',[],null,['class' =>'form-control input-md business_type_2 required','id'=>'businessType2_0','placeholder'=>'Select Business Type 1 First']) !!}</td>
                                                <td class="col-md-3 col-xs-3">{!! Form::text('businessLicense_fee[0]','',['class' =>'form-control input-md businessLicenseFee required','id'=>'businessLicenseFee_0','readonly']) !!}</td>
                                                <td style="vertical-align: middle; text-align: center">
                                                    <a class="btn btn-sm btn-primary addTableRows"
                                                       title="Add more business type"
                                                       onclick="addTableRowTL('businessTypeDetails', 'rowCountbusinessTypeDetails_0');">
                                                        <i class="fa fa-plus"></i></a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Business Organization Details</strong></div>
                            <div class="panel-body">
                                <?php
                                    $companyName = \App\Libraries\CommonFunction::getCompanyNameById(\Illuminate\Support\Facades\Auth::user()->working_company_id)
                                ?>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('business_org_name','Name of the Business organization :',['class'=>'text-left col-md-7']) !!}
                                            <div class="col-md-5">
                                                {!! Form::text('business_org_name',$companyName,['class' => 'form-control input-md ','id'=>'business_org_name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('business_org_nature','Nature of the Business organization :',['class'=>'text-left col-md-7']) !!}
                                            <div class="col-md-5">
                                                {!! Form::select('business_org_nature',[],'',['class' => 'form-control input-md','id'=>'business_org_nature']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group" style="margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('paid_capital','Paid up Capital (in the case of ltd. company) :',['class'=>'text-left col-md-7']) !!}
                                            <div class="col-md-5">
                                                {!! Form::select('paid_capital',[],'',['class' => 'form-control input-md ','id'=>'paid_capital']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Applicant Basic Info</strong></div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('applicant_name','Name of the Applicant :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('applicant_name',!empty($basicAppInfo->ceo_full_name)?$basicAppInfo->ceo_full_name:'',['class' => 'form-control input-md ','id'=>'applicant_name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('applicant_fathers_name','Applicant\'s Father\'s Name :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('applicant_fathers_name',!empty($basicAppInfo->ceo_father_name)?$basicAppInfo->ceo_father_name:'',['class' => 'form-control input-md ','id'=>'applicant_fathers_name']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('applicant_mothers_name','Applicant\'s Mother\'s Name :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('applicant_mothers_name',!empty($basicAppInfo->ceo_mother_name)?$basicAppInfo->ceo_mother_name:'',['class' => 'form-control input-md ','id'=>'applicant_mothers_name']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('spouse_name','Spouse\'s Name :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('spouse_name',!empty($basicAppInfo->ceo_spouse_name)?$basicAppInfo->ceo_spouse_name:'',['class' => 'form-control input-md ','id'=>'spouse_name']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('applicant_relation_org','Applicant\'s relationship with the organization :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('applicant_relation_org','',['class' => 'form-control input-md ','id'=>'_1']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('nationality','Nationality of the Applicant :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('nationality',$ceoNationality,['class' => 'form-control input-md ','id'=>'nationality']) !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('nid_number','NID number :',['class'=>'text-left col-md-6','id'=>'nid_number_label']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('nid_number',!empty($basicAppInfo->ceo_nid)?$basicAppInfo->ceo_nid:'',['class' => 'form-control input-md onlyNumber','id'=>'nid_number']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('passport','Passport :',['class'=>'text-left col-md-6','id'=>'passport_label']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('passport',!empty($basicAppInfo->ceo_passport_no)?$basicAppInfo->ceo_passport_no:'',['class' => 'form-control input-md','id'=>'passport']) !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('birth_reg_no','Birth Reg. No :',['class'=>'text-left col-md-6','id'=>'birth_reg_no_label']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('birth_reg_no','',['class' => 'form-control input-md onlyNumber','id'=>'birth_reg_no']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('bin_no','BIN No. :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('bin_no','',['class' => 'form-control input-md onlyNumber','id'=>'bin_no']) !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('mobile_no','Applicant\'s Mobile No. :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('mobile_no',!empty($basicAppInfo->ceo_mobile_no)?$basicAppInfo->ceo_mobile_no:'',['class' => 'form-control input-md onlyNumber','id'=>'mobile_no']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('email','Applicant\'s Email ID :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('email',!empty($basicAppInfo->ceo_email)?$basicAppInfo->ceo_email:'',['class' => 'form-control input-md email','id'=>'email']) !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('other_identity','Other Identity :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('other_identity','',['class' => 'form-control input-md ','id'=>'other_identity']) !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <?php
                                        $applicantThana = '';
                                        $applicantDistrict = '';
                                        $officeDistrict = '';
                                        $officeThana = '';
                                        $applicantaddres= '';
                                            if ($basicAppInfo){
                                                $officeaddres= '';
                                                if (!empty($basicAppInfo->ceo_address)){
                                                    $applicantaddres = $basicAppInfo->ceo_address.',';
                                                }
                                                if (!empty($applicantThana = \App\Libraries\CommonFunction::getAreaNameByAreaId($basicAppInfo->ceo_thana_id))){
                                                    $applicantaddres .= $applicantThana.',';
                                                }
                                                if (!empty($applicantDistrict = \App\Libraries\CommonFunction::getAreaNameByAreaId($basicAppInfo->ceo_thana_id))){
                                                    $applicantaddres .= $applicantDistrict.',';
                                                }

                                                if (!empty($basicAppInfo->ceo_post_code)){
                                                    $applicantaddres .= $basicAppInfo->ceo_post_code;
                                                }

                                                if (!empty($basicAppInfo->office_address) ){
                                                    $officeaddres = $basicAppInfo->office_address.',';
                                                }
                                                if (!empty($officeThana = \App\Libraries\CommonFunction::getAreaNameByAreaId($basicAppInfo->office_thana_id))){
                                                    $officeaddres .= $officeThana.',';
                                                }
                                                if (!empty($officeDistrict = \App\Libraries\CommonFunction::getAreaNameByAreaId($basicAppInfo->office_district_id))){
                                                    $officeaddres .= $officeDistrict.',';
                                                }



                                                if (!empty($basicAppInfo->ceo_post_code)){
                                                    $officeaddres .= $basicAppInfo->office_post_code;
                                                }
                                            }

                                        ?>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('residential_address','Applicant\'s Residential Address :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::textarea('residential_address',$applicantaddres,['class' => 'form-control input-md ','rows'=>'3','id'=>'residential_address']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('proposed_business_address','Proposed Business Address :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::textarea('proposed_business_address',$officeaddres,['class' => 'form-control input-md ','rows'=>'4','id'=>'proposed_business_address']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Permanent Address of the Applicant</strong></div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('permanent_holding_no','Holding No. :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('permanent_holding_no','',['class' => 'form-control input-md ','id'=>'permanent_holding_no']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('permanent_road_no','Road No. :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('permanent_road_no','',['class' => 'form-control input-md ','id'=>'permanent_road_no']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('permanent_village_or_mahalla','Village\ Mahalla :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('permanent_village_or_mahalla',!empty($basicAppInfo->ceo_address)?$basicAppInfo->ceo_address:'',['class' => 'form-control input-md ','id'=>'permanent_village_or_mahalla']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('permanent_post_code','Post Code :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('permanent_post_code',!empty($basicAppInfo->ceo_post_code)?$basicAppInfo->ceo_post_code:'',['class' => 'form-control input-md ','id'=>'permanent_post_code']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('permanent_division','Division :',['class'=>'text-left col-md-6']) !!}

                                            <div class="col-md-6">
                                                {!! Form::select('permanent_division', $divisions,'',['class' => 'form-control input-md ','id'=>'permanent_division','onchange'=>"getDistrictByDivisionCustom('permanent_district', this.value, 'permanent_district','$applicantDistrict')"]) !!}
                                            </div>
                                        </div>


                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('permanent_district','District  :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('permanent_district',[],'',['class' => 'form-control input-md select2input','placeholder' => 'Select division first','id'=>'permanent_district','onchange'=>"getDistrictByDivisionCustom('permanent_police_station', this.value, 'permanent_police_station','$applicantThana')"]) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('permanent_police_station','Police Station :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('permanent_police_station',[],'',['class' => 'form-control input-md select2input','placeholder' => 'Select district first','id'=>'permanent_police_station']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Owner's Current Address</strong></div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('current_holding_no','Holding No. :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('current_holding_no','',['class' => 'form-control input-md ','id'=>'current_holding_no']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('current_road_no','Road No. :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('current_road_no','',['class' => 'form-control input-md ','id'=>'current_road_no']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('current_village_or_mahalla','Village\ Mahalla :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('current_village_or_mahalla',!empty($basicAppInfo->office_address)?$basicAppInfo->ceo_address:'',['class' => 'form-control input-md ','id'=>'current_village_or_mahalla']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('current_post_code','Post Code :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('current_post_code',!empty($basicAppInfo->office_post_code)?$basicAppInfo->office_post_code:'',['class' => 'form-control input-md ','id'=>'current_post_code']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('current_division','Division :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('current_division',$divisions,'',['class' => 'form-control input-md ','id'=>'current_division','onchange'=>"getDistrictByDivisionCustom('current_district', this.value, 'current_district','$officeDistrict')"]) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('current_district','District  :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('current_district',[],'',['class' => 'form-control input-md select2input','id'=>'current_district','placeholder' => 'Select division first','onchange'=>"getDistrictByDivisionCustom('police_station', this.value, 'police_station','$officeThana')"]) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('police_station','Police Station :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('police_station',[],'',['class' => 'form-control select2input input-md ','id'=>'police_station','placeholder' => 'Select district first']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Business Details</strong></div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('business_capital','Business Capital :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('business_capital','',['class' => 'form-control input-md onlyNumber','id'=>'business_capital']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6  col-xs-12">
                                            {!! Form::label('business_start_date','Business Start Date:',['class'=>'col-md-6']) !!}
                                            <div class="datepicker col-md-6">
                                                {!! Form::text('business_start_date', '',['class' => 'form-control
                                                input-md','id'=>'business_start_date','readonly','style'=>'background:white;']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('tin_number','TIN number :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('tin_number','',['class' => 'form-control input-md onlyNumber','id'=>'tin_number']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('place_of_business','Place of Business (Rent/Own) :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('place_of_business',['rent@Rent'=>'Rent','own@Own'=>'Own'],'',['class' => 'form-control input-md ','id'=>'place_of_business']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('business_shop_rent','Business/Shop Space Own/ Rent (Tax receipt of own house should be attached) :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('business_shop_rent',['Y@Yes'=>'Yes','N@No'=>'No'],'',['class' => 'form-control input-md ','id'=>'_1']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('sign_board','Is there a Sign Board? :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('sign_board',['Y@Yes'=>'Yes','N@No'=>'No'],'',['class' => 'form-control input-md ','id'=>'_1']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('proposed_place','Proposed Shop/Place of Business, Municipal Land/Government Land  :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('proposed_place',['Y@Yes'=>'Yes','N@No'=>'No'],'',['class' => 'form-control input-md ','id'=>'proposed_place']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('shop_floor','Shop Floor/Office on Which Floor :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('shop_floor',['1@Ground Floor'=>'Ground Floor','2@Second Floor'=>'Second Floor','3@Third Floor'=>'Third Floor','4@Fourth Floor'=>'Fourth Floor'],'',['class' => 'form-control input-md ','id'=>'shop_floor']) !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-body">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('zone','Circle :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::select('zone',[],'',['class' => 'form-control input-md ','id'=>'zone']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('ward','Ward  :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::select('ward',[],'',['class' => 'form-control input-md ','id'=>'ward']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('sector_or_section','Sector/Section :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::select('sector_or_section',[],'',['class' => 'form-control input-md ','id'=>'sector_or_section']) !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('area_or_block','Area/Block :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::select('area_or_block',[],'',['class' => 'form-control input-md ','id'=>'area_or_block']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('road','Road :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::select('road',[],'',['class' => 'form-control input-md ','id'=>'road']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('plot_or_holding_no','Plot/Holding no. :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::text('plot_or_holding_no','',['class' => 'form-control input-md ','id'=>'plot_or_holding_no']) !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('shop_no','Shop No. :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::text('shop_no','',['class' => 'form-control input-md ','id'=>'shop_no']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('license_fee','License fee :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::text('license_fee','',['class' => 'form-control input-md onlyNumber','id'=>'license_fee','readonly']) !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('sign_board_sqft','Sign Board sqft :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::text('sign_board_sqft','',['class' => 'form-control input-md onlyNumber','id'=>'sign_board_sqft']) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('sign_board_fee','Sign Board fee :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::text('sign_board_fee','',['class' => 'form-control input-md','id'=>'sign_board_fee','readonly']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('book_price','New TL Book Issue Fee:',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::text('book_price','',['class' => 'form-control input-md onlyNumber','id'=>'book_price']) !!}
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('outstanding','Outstanding :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::text('outstanding','',['class' => 'form-control input-md ','id'=>'outstanding']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('outstanding_surcharge','Outstanding Surcharge :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::text('outstanding_surcharge','',['class' => 'form-control input-md onlyNumber','id'=>'outstanding_surcharge']) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    {!! Form::label('vat_arrears','Vat Arrears :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::text('vat_arrears','',['class' => 'form-control input-md onlyNumber','id'=>'vat_arrears']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-6">
                                            <p style="color: #962121;">NB:TL Amendment Fee (25% of TL Fee for every single correction) </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('number_of_years_for_fee','Number of years for fee :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('number_of_years_for_fee',['1@1'=>'1','2@62'=>'2','3@3'=>'3','4@4'=>'4','5@5'=>'5','6@6'=>'6'],'',['class' => 'form-control input-md none-pointer','id'=>'number_of_years_for_fee','readonly']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('annual_vat','Annual Vat :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('annual_vat','',['class' => 'form-control input-md onlyNumber','id'=>'annual_vat','readonly']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('total_vat','Total Vat :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('total_vat','',['class' => 'form-control input-md onlyNumber calc','id'=>'total_vat','readonly']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('income_tax_money','Income Tax Money :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('income_tax_money','',['class' => 'form-control input-md onlyNumber','id'=>'income_tax_money']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('total_price','Total Payable Fee/Total Assessed Fee:',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('total_price','',['class' => 'form-control input-md onlyNumber calc','id'=>'total_price','readonly']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('new_or_old_trade_license','New/Old Trade License :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('new_or_old_trade_license',['new@New'=>'New','old@Old'=>'Old'],'',['class' => 'form-control input-md none-pointer','id'=>'new_or_old_trade_license','readonly']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </fieldset>

                    <h3 class="text-center stepHeader">Attachments</h3>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="docListDiv">
                                    @include('CTCC::documents')
                                </div>

                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Declaration</h3>
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading" style="padding-bottom: 4px;">
                                <strong>DECLARATION</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ol type="a">
                                                <li>
                                                    <p>I do hereby declare that the information given above is true to
                                                        the best of my knowledge and I shall be liable for any false
                                                        information/ statement given</p>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped">
                                    <thead class="alert alert-info">
                                    <tr>
                                        <th colspan="3" style="font-size: 15px">Authorized person of the
                                            organization
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            {!! Form::label('auth_name','Full name:', ['class'=>'required-star'])
                                            !!}
                                            {!! Form::text('auth_name',
                                            \App\Libraries\CommonFunction::getUserFullName(), ['class' =>
                                            'form-control input-md required', 'readonly']) !!}
                                            {!! $errors->first('auth_name','<span
                                                class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::label('auth_email','Email:', ['class'=>'required-star']) !!}
                                            {!! Form::email('auth_email', Auth::user()->user_email, ['class' =>
                                            'form-control required input-md email', 'readonly']) !!}
                                            {!! $errors->first('auth_email','<span
                                                class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::label('auth_cell_number','Cell number:',
                                            ['class'=>'required-star']) !!}<br>
                                            {!! Form::text('auth_cell_number', Auth::user()->user_phone, ['class' =>
                                            'form-control input-md required phone_or_mobile', 'readonly']) !!}
                                            {!! $errors->first('auth_cell_number','<span
                                                class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><strong>Date : </strong><?php echo date('F d,Y')?></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms',
                                            'class'=>'required')) !!}
                                            All the details and information provided in this form are true and complete.
                                            I am aware that any untrue/incomplete statement may result in delay in BIN
                                            issuance and I may be subjected to full penal action under the Value Added
                                            Tax and Supplementary Duty Act, 2012 or any other applicable Act Prevailing
                                            at present.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Payment & Submit</h3>
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Service fee payment</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(),
                                                ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' =>
                                                'form-control input-md email required']) !!}
                                                {!! $errors->first('sfp_contact_email','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' =>
                                                'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_address', Auth::user()->road_no .
                                                (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' =>
                                                'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_address','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_pay_amount') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_pay_amount','Pay amount',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_pay_amount', $payment_config->amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_vat_on_pay_amount') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_vat_on_pay_amount','VAT on pay amount',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_vat_on_pay_amount', number_format($payment_config->vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                                {!! $errors->first('sfp_vat_on_pay_amount','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_total_amount','Total amount',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_total_amount', number_format($payment_config->amount + $payment_config->vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_status','Payment status',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                <span class="label label-warning">Not Paid</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{--Vat/ tax and service charge is an approximate amount--}}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                Vat/ tax and service charge is an approximate amount, it may vary based on the
                                                Sonali Bank system.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="pull-left">
                                <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                        value="draft" name="actionBtn">Save as Draft
                                </button>
                            </div>

                            <div class="pull-left" style="padding-left: 1em;">
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md" value="Submit" name="actionBtn">Payment &amp;
                                    Submit
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 button_last">
                            <div class="clearfix"></div>
                        </div>
                    </div> {{--row--}}

                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>

    $(document).ready(function () {

        var form = $("#NewConnection").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {

                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }
                if (newIndex == 1) {
                    var nid = $("#nid_number").val();
                    var passport = $("#passport").val();
                    var RegID = $("#birth_reg_no").val();
                    if (nid =='' && passport =='' && RegID =='' ) {
                        $("#nid_number").focus();
                        swal({
                            type: 'warning',
                            title: 'Oops...',
                            text: 'In order to Proceed please provide NID/Passport/ Birth-Reg No.'
                        });
                        return false;
                    }
                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }

                form.validate().settings.ignore = ":disabled,:hidden";
                var signboard_size = $("#sign_board_sqft").val();

                if(signboard_size !='' && signboard_size <6 ){
                    alert('Sign Board Must be grater than 6 squire fest !');
                    $("#sign_board_sqft").addClass('error');
                    return false;
                }
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex != -1) {
                    form.find('#save_as_draft').css('display', 'block');
                    form.find('.actions').css('top', '-42px');
                } else {
                    form.find('#save_as_draft').css('display', 'none');
                    form.find('.actions').css('top', '-15px');
                }
                if (currentIndex == 3) {
                    form.find('#submitForm').css('display', 'block');

                } else {
                    form.find('#submitForm').css('display', 'none');
                }

            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                }
            }
        });

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            form.validate().settings.ignore = ":disabled,:hidden";
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=NewConnection@3'); ?>');
            } else {
                return false;
            }
        });

        {{----end step js---}}
        $("#NewConnection").validate({
            rules: {
                field: {
                    required: true,
                    email: true,

                }
            }
        });

        // Datepicker Plugin initialize
        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100),
            ignoreReadonly: true
        });


        $('#nid_number').on('blur', function (e) {
            var nid = $('#nid_number').val().length
            if (nid == 10 || nid == 13 || nid == 17) {
                $('#nid_number').removeClass('error')
            } else {
                $('#nid_number').addClass('error')
                $('#nid_number').val('')
            }
        })

        $('.onlyNumber').on('keydown', function (e) {
            //period decimal
            if ((e.which >= 48 && e.which <= 57)
                //numpad decimal
                || (e.which >= 96 && e.which <= 105)
                // Allow: backspace, delete, tab, escape, enter and .
                || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                // Allow: Ctrl+A
                || (e.keyCode == 65 && e.ctrlKey === true)
                // Allow: Ctrl+C
                || (e.keyCode == 67 && e.ctrlKey === true)
                // Allow: Ctrl+V
                || (e.keyCode == 86 && e.ctrlKey === true)
                // Allow: Ctrl+X
                || (e.keyCode == 88 && e.ctrlKey === true)
                // Allow: home, end, left, right
                || (e.keyCode >= 35 && e.keyCode <= 39)) {

                var $this = $(this);
                setTimeout(function () {
                    $this.val($this.val().replace(/[^0-9.]/g, ''));
                }, 4);

                var thisVal = $(this).val();
                if (thisVal.indexOf(".") != -1 && e.key == '.') {
                    return false;
                }
                $(this).removeClass('error');
                return true;
            } else {
                $(this).addClass('error');
                return false;
            }
        }).on('paste', function (e) {
            var $this = $(this);
            setTimeout(function () {
                $this.val($this.val().replace(/[^0-9]/g, ''));
            }, 5);
        });

        $('.select2input').select2({
            width: 'element'
        });

    });

    /* Get list from API end */

    $(document).ready(function () {
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });
        $("#businessType1_0").select2({
            width: 'element'
        });
        $("#businessType2_0").select2({
            width: 'element'
        });
        $(function () {
            $('#organization_panel').hide()
            token = "{{$token}}"
            tokenUrl = '/ctcc/get-refresh-token'
            $('.fiscal_year').keydown()
            $('.business_type_1').keydown()
            $('#zone').keydown()
            $('#business_org_nature').keydown()
            $('#paid_capital').keydown()
            getDoc()
        });

        $('.fiscal_year').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/fiscal-years";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "fiscal_id"; //dynamic id for callback
            var element_name = "name_en"; //dynamic name for callback
            var data = null;
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('.business_type_1').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/business-type-1";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "RTN"; //dynamic name for callback
            var data = null;
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })



        $(document).on('change', ".business_type_1", function () {
            //test
            var a = this.id;
            var d_id = a.split("_").pop()

            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $('#business_type_2_' + d_id).html('<option value="">Please Wait...</option>');
            var business_type_1 = $("#businessType1_" + d_id).val();
            //alert(desc_load);
            var business_type_1_id = business_type_1.split("@")[0];
            if (business_type_1_id) {
                var e = $(this);
                var api_url = "{{$service_url}}/info/business-type-2/" + business_type_1_id
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "businessType2_" + d_id; // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "RTN"; //dynamic name for callback
                var element_fee = "amt"
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, element_fee, dependent_section_id];

                var apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                ];
                apiCallGet(e, options, apiHeaders, BusinessTypeDependantCallbackResponse, arrays);

            } else {
                $(".business_type_2").html('<option value="">Select Business Type 1 First</option>');
                $(self).next().hide();
            }

        });

        $('#zone').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/zone";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "RTN"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#business_org_nature').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/business-nature";
            var selected_value = 'LTD'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "bn_id"; //dynamic id for callback
            var element_name = "name_en"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, businessNatureCallbackResponse, arrays);

        })

        $('#paid_capital').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/paid-up-capitals";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "paid_up_id"; //dynamic id for callback
            var element_name = "capital_range"; //dynamic name for callback
            var data = 'fee';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#zone').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide()

            $("#ward").html('<option value="">Please Wait...</option>')

            var zone = $(this).val()
            var zone_id = zone.split('@')[0]

            if (zone_id) {

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/ward/" + zone_id
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "RTN"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "ward";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

                var apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA'
                    },
                ];

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#ward").html('<option value="">Select Zone First</option>')
                $(self).next().hide();
            }
        })
        $('#ward').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide()

            $("#sector_or_section").html('<option value="">Please Wait...</option>')

            var ward = $(this).val()
            var ward_id = ward.split('@')[0]

            if (ward_id) {

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/sector/" + ward_id
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "RTN"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "sector_or_section";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

                var apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA'
                    },
                ];

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#sector_or_section").html('<option value="">Select Ward First</option>')
                $(self).next().hide();
            }
        })
        $('#sector_or_section').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide()

            $("#area_or_block").html('<option value="">Please Wait...</option>')

            var sector = $(this).val()
            var sector_id = sector.split('@')[0]

            if (sector_id) {

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/area/" + sector_id
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "RTN"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "area_or_block";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

                var apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA'
                    },
                ];

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#area_or_block").html('<option value="">Select Ward First</option>')
                $(self).next().hide();
            }
        })
        $('#area_or_block').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide()

            $("#road").html('<option value="">Please Wait...</option>')

            var block = $(this).val()
            var block_id = block.split('@')[0]

            if (block_id) {

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/road/" + block_id
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "RTN"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "road";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

                var apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA'
                    },
                ];

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#road").html('<option value="">Select Block First</option>')
                $(self).next().hide();
            }
        })

        $(document).on('change', ".business_type_2", function () {
            var fee = $(this).find(':selected').attr('data-fee')
            var id = $(this).attr('id')
            var dynamic_id = id.split("_").pop()
            $('#businessLicenseFee_' + dynamic_id).val(fee)
            sum_fee_total()
        })

        $("#business_org_nature").on('change', function () {
            $("#license_fee").val('')
            vatCount()
            var business_nature = $('#business_org_nature').val()
            if (business_nature !== '') {
                var bn = business_nature.split('@')[0]
                if (bn == 'LTD') {
                    $("#paid_capital").trigger('change')
                } else {
                    $('.business_type_2').trigger('change')
                    vatCount()
                }
            } else {
                $("#license_fee").val('')
                vatCount()
            }
        })

        $("#paid_capital").on('change', function () {
            var business_nature = $('#business_org_nature').val();
            if (business_nature !== '' || business_nature == null) {
                var bn = business_nature.split('@')[0];
                if (bn == 'LTD') {
                    if ($("#paid_capital").val() !== '') {
                        var lc_fee_value = $("#paid_capital").val();
                        if (lc_fee_value !== '' && lc_fee_value != null) {
                            var lc_fee = $("#paid_capital").val().split('@')[1]
                            $("#license_fee").val(lc_fee)
                            vatCount()
                        }

                    }
                }
            }
        })

        $(document).on('input', "#sign_board_sqft", function () {
            var sbsqft = $("#sign_board_sqft").val();
            if (sbsqft !== '') {
                var fee = sbsqft * 80
                $("#sign_board_fee").val(fee.toFixed(2))
            } else {
                $("#sign_board_fee").val('')
            }
        })

        $(document).on('change', "#number_of_years_for_fee", function () {
            vatCount()
        })

        $(document).on('input', "#sign_board_sqft", function () {
            vatCount()
        })

        $(document).on('click', '.filedelete', function () {
            var abc = $(this).attr('docid');
            var sure_del = confirm("Are you sure you want to delete this file?");
            if (sure_del) {
                document.getElementById("validate_field_" + abc).value = '';
                document.getElementById(abc).value = '';
                $('.saved_file_' + abc).html('');
                $('.span_validate_field_' + abc).html('');
            } else {
                return false;
            }
        });

    })


    function vatCount() {
        var lc_fee = $("#license_fee").val()
        if (lc_fee !== '') {
            var year = $('#number_of_years_for_fee').find(':selected').val().split('@')[0]
            var an_vat = (lc_fee * 15) / 100
            $("#annual_vat").val(an_vat)
            var total_vat = an_vat * year
            $("#total_vat").val(total_vat)
            var signboard_fee = $("#sign_board_fee").val()
            if (signboard_fee !== '') {
                var total_price = parseInt(total_vat) + parseInt(lc_fee * year) +
                    parseInt(signboard_fee)
                $("#total_price").val(total_price)
            } else {
                var total_price = parseInt(total_vat) + parseInt(lc_fee * year)
                $("#total_price").val(total_price)
            }
        } else {
            $("#annual_vat").val('')
            $("#total_vat").val('')
            $("#total_price").val('')
        }

    }

    function sum_fee_total() {
        var sum = 0;
        $.each($(".businessLicenseFee"), function () {
            sum += +$(this).val();
        });
        if (sum > 0) {
            var business_nature = $('#business_org_nature').val()
            if (business_nature !== '') {
                var bn = business_nature.split('@')[0]
                if (bn !== 'LTD') {
                    $("#license_fee").val(sum)
                }
            }
            $("#number_of_years_for_fee").trigger('change')
        } else {
            $("#license_fee").val('')
        }


    }

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                if (data == '' || data == null) {
                    var id = row[element_id] + '@' + row[element_name];
                } else {
                    var id = row[element_id] + '@' + row[data] + '@' + row[element_name];
                }

                var value = row[element_name];
                if (selected_value == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
        $("#" + calling_id).trigger('change')
    }

    function businessNatureCallbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                if (data == '' || data == null) {
                    var id = row[element_id] + '@' + row[element_name];
                } else {
                    var id = row[element_id] + '@' + row[data] + '@' + row[element_name];
                }

                var value = row[element_name];
                if (selected_value == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
        $("#" + calling_id).trigger('change')
        $("#paid_capital").trigger('change')
    }

    function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>'
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name]
                var value = row[element_name]
                if (selected_value == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            });
        }
        $("#" + dependent_section_id).html(option)
        $("#" + calling_id).next().hide()
    }

    function BusinessTypeDependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_fee, dependent_section_id]) {
        var option = '<option value="">Select One</option>'
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name]
                var value = row[element_name]
                option += '<option value="' + id + '" data-fee="' + row[element_fee] + '">' + value + '</option>'
            });
        }
        $("#" + dependent_section_id).html(option)
        $("#" + calling_id).next().hide()
    }


    // Add table Row script
    function addTableRowTL(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        $('.business_type_1').select2('destroy');
        $('.business_type_2').select2('destroy');
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length - 1;
        var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
        if (lastTr != '' && typeof lastTr !== "undefined") {
            rowCount = parseInt(lastTr) + 1;
        }
        //var rowCount = table.rows.length;
        //Increment id
        var rowCo = rowCount;
        var idText = 'rowCount' + tableID + '_' + rowCount;
        x.id = idText;
        $("#" + tableID).append(x);
        //get select box elements
        var attrSel = $("#" + tableID).find('#' + idText).find('select');
        //edited by ishrat to solve select box id auto increment related bug
        for (var i = 0; i < attrSel.length; i++) {

            var nameAtt = attrSel[i].name;
            var selectId = attrSel[i].id;
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
            var ret = selectId.split('_')[0];
            var repTextId = ret + '_' + rowCo;
            attrSel[i].id = repTextId;
            attrSel[i].name = repText;
        }
        attrSel.val(''); //value reset

        // end of  solving issue related select box id auto increment related bug by ishrat

        //get input elements
        var attrInput = $("#" + tableID).find('#' + idText).find('input');
        for (var i = 0; i < attrInput.length; i++) {
            var nameAtt = attrInput[i].name;
            var inputId = attrInput[i].id;
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
            var ret = inputId.split('_')[0];
            var repTextId = ret + '_' + rowCo;
            attrInput[i].id = repTextId;
            attrInput[i].name = repText;
        }
        attrInput.val(''); //value reset
        //edited by ishrat to solve textarea id auto increment related bug
        //get textarea elements
        var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
        for (var i = 0; i < attrTextarea.length; i++) {
            var nameAtt = attrTextarea[i].name;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            attrTextarea[i].name = repText;
            $('#' + idText).find('.readonlyClass').prop('readonly', true);
        }
        attrTextarea.val(''); //value reset
        // end of  solving issue related textarea id auto increment related bug by ishrat
        attrSel.prop('selectedIndex', 0);
        if ((tableID === 'machinaryTbl' && templateRow === 'rowMachineCount0') || (tableID === 'machinaryTbl' && templateRow === 'rowMachineCount')) {
            $("#" + tableID).find('#' + idText).find('select.m_currency').val("107");  //selected index reset
        } else {
            attrSel.prop('selectedIndex', 0);  //selected index reset
        }
        //$('.m_currency ').prop('selectedIndex', 102);
        //Class change by btn-danger to btn-primary
        $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
            .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
        $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
        $('#' + tableID).find('tr').last().attr('data-number', rowCount);

        $('.business_type_1').each(function (index) {
            $('#businessType1_' + index).select2();
        });
        $('.business_type_2').each(function (index) {
            $('#businessType2_' + index).select2();
        });

        $("#" + tableID).find('#' + idText).find('.onlyNumber').on('keydown', function (e) {
            //period decimal
            if ((e.which >= 48 && e.which <= 57)
                //numpad decimal
                || (e.which >= 96 && e.which <= 105)
                // Allow: backspace, delete, tab, escape, enter and .
                || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                // Allow: Ctrl+A
                || (e.keyCode == 65 && e.ctrlKey === true)
                // Allow: Ctrl+C
                || (e.keyCode == 67 && e.ctrlKey === true)
                // Allow: Ctrl+V
                || (e.keyCode == 86 && e.ctrlKey === true)
                // Allow: Ctrl+X
                || (e.keyCode == 88 && e.ctrlKey === true)
                // Allow: home, end, left, right
                || (e.keyCode >= 35 && e.keyCode <= 39)) {
                var $this = $(this);
                setTimeout(function () {
                    $this.val($this.val().replace(/[^0-9.]/g, ''));
                }, 4);
                var thisVal = $(this).val();
                if (thisVal.indexOf(".") != -1 && e.key == '.') {
                    return false;
                }
                $(this).removeClass('error');
                return true;
            } else {
                $(this).addClass('error');
                return false;
            }
        }).on('paste', function (e) {
            var $this = $(this);
            setTimeout(function () {
                $this.val($this.val().replace(/[^.0-9]/g, ''));
            }, 4);
        });


    } // end of addTableRowTraHis() function

    // Remove Table row script
    function removeTableRow(tableID, removeNum) {
        $('#' + tableID).find('#' + removeNum).remove();
        sum_fee_total()
        var index = 0
        var rowCo = 0
        $('#' + tableID + ' tr').each(function () {
                var trId = $(this).attr("id")
                var id = trId.split("_").pop();
                var trName = trId.split("_").shift();
                var nameIndex = id;

                var attrInput = $("#" + tableID).find('#' + trId).find('input');
                for (var i = 0; i < attrInput.length; i++) {
                    var nameAtt = attrInput[i].name;
                    var inputId = attrInput[i].id;
                    var repText = nameAtt.replace('[' + nameIndex + ']', '[' + index + ']'); //increment all array element name
                    var ret = inputId.replace('_' + id, '');
                    var repTextId = ret + '_' + rowCo;
                    attrInput[i].id = repTextId;
                    attrInput[i].name = repText;
                }


                var attrSel = $("#" + tableID).find('#' + trId).find('select');

                for (var i = 0; i < attrSel.length; i++) {
                    var nameAtt = attrSel[i].name;
                    var inputId = attrSel[i].id;
                    var repText = nameAtt.replace('[' + nameIndex + ']', '[' + index + ']'); //increment all array element name
                    // alert(nameIndex + ' ' + index)
                    var ret = inputId.replace('_' + id, '');
                    var repTextId = ret + '_' + rowCo;
                    attrSel[i].id = repTextId;
                    attrSel[i].name = repText;
                }
                var ret = trId.replace('_' + id, '');
                var repTextId = ret + '_' + rowCo;
                $(this).removeAttr("id")
                $(this).attr("id", repTextId)
                $(this).removeAttr("data-number")
                $(this).attr("data-number", rowCo)

                if (rowCo != 0) {
                    $(this).find('.addTableRows').removeAttr('onclick');
                    $(this).find('.addTableRows').attr('onclick', 'removeTableRow("' + tableID + '","' + trName + '_' + rowCo + '")');
                }
                index++;
                rowCo++;

            }
        )

    }

    function getDistrictByDivisionCustom(division_id, division_value, district_div, old_data) {
        // define old_data as an optional parameter
        if (typeof old_data === 'undefined') {
            old_data = 0;
        }

        var _token = $('input[name="_token"]').val();
        if (division_value !== '') {
            $("#" + division_id).after('<span class="loading_data">Loading...</span>');
            // $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
            $.ajax({
                type: "GET",
                url: "/users/get-district-by-division",
                data: {
                    _token: _token,
                    divisionId: division_value
                },
                success: function (response) {
                    let trigger = 0;
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {

                        $.each(response.data, function (id, value) {
                            var element_id = (id + '@' + value)
                            if (value == old_data) {
                                trigger = 1;
                                option += '<option value="' + element_id + '" selected>' + value + '</option>';
                            } else {
                                option += '<option value="' + element_id + '">' + value + '</option>';
                            }
                        });
                    }

                    $("#" + district_div).html(option);
                    $("#" + division_id).next().hide();
                    if(trigger == 1){
                        $("#" + district_div).trigger('change');
                    }
                }
            });
        } else {
            // console.log('Please select a valid district');
        }
    }


</script>