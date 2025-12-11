
    <style>
        .selectInputBank {
            background-color: rgba(61, 181, 215, 1);
            box-sizing: border-box;
            border-width: 1px;
            border-style: solid;
            border-color: rgba(61, 181, 215, 1);
            border-radius: 9px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 2px;
        }

        .img-thumbnail {
            height: 80px;
            width: 100px;
        }

        input[type=radio].error,
        input[type=checkbox].error {
            outline: 1px solid red !important;
        }

        .wizard > .steps > ul > li {
            width: 25% !important;
        }

        .table-striped > tbody#manpower > tr > td, .table-striped > tbody#manpower > tr > th {
            text-align: center;
        }
    </style>

            <div class="box">
                <div class="box-body" id="inputForm">
                    {{--start application form with wizard--}}
                    {!! Session::has('success') ? '
                    <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                    ' : '' !!}
                    {{--{!! Session::has('error') ? '--}}
                    {{--<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>--}}
                    {{--' : '' !!}--}}

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h5><strong> Apply for Bank Account opening to Bangladesh </strong></h5>
                        </div>

                        <div class="panel-body">
                            {!! Form::open(array('url' => '/single-licence/bank-account/add','method' => 'post','id' => 'BankApplicationForm','role'=>'form','enctype'=>'multipart/form-data')) !!}

                            <div class="form-body">
                                <div class="row" style="margin:15px 0 15px 0">
                                    <div class="col-md-12">
                                        <div class="heading_img">
                                            <img class="img-responsive pull-left"
                                                 src="{{ asset('assets/images/u39.png') }}"/>
                                        </div>
                                        <div class="heading_text pull-left">
                                            Sonali Bank Ltd.
                                        </div>
                                    </div>
                                </div>
                                <div class="selectInputBank form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-2">
                                                {!! Form::label('bank_id','Select Bank',['class'=>'required-star']) !!}
                                            </div>
                                            <div class="col-md-4">
                                                {!! Form::select('bank_id',$banks, '',['class'=>'form-control input-md required', 'id' => 'bank_id']) !!}

                                            </div>
                                            <div class="col-md-2">
                                                {!! Form::label('bank_branch_id','Select Branch',['class'=>'required-star']) !!}
                                            </div>
                                            <div class="col-md-4">
                                                {!! Form::select('bank_branch_id',[''=>'Select one'], '',['class'=>'form-control input-md required', 'id' => 'bank_branch_id']) !!}

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <input type="hidden" name="selected_file" id="selected_file"/>
                                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                                    <input type="hidden" name="isRequired" id="isRequired"/>
                                    <input type="hidden" value="{{$usdValue->bdt_value}}" id="crvalue">
                                    <br/>
                                    <fieldset>
                                        <div class="panel panel-info">
                                            <div class="panel-heading margin-for-preview"><strong>A. Company
                                                    Information</strong></div>
                                            <div class="panel-body readOnlyCl">
                                                <div id="validationError"></div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 form-group {{$errors->has('company_name') ? 'has-error': ''}}">
                                                            {!! Form::label('company_name','Name of Organization/ Company/ Industrial Project',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('company_name', \App\Libraries\CommonFunction::getCompanyNameById(\Illuminate\Support\Facades\Auth::user()->company_ids), ['class' => 'form-control input-md required', 'readonly']) !!}
                                                                {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 form-group {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                                            {!! Form::label('company_name_bn','Name of Organization/ Company/ Industrial Project (বাংলা)',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('company_name_bn', \App\Libraries\CommonFunction::getCompanyBnNameById(\Illuminate\Support\Facades\Auth::user()->company_ids), ['class' => 'form-control input-md', 'readonly']) !!}
                                                                {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            {!! Form::label('country_of_origin_id','Country of Origin',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('country_of_origin_id',$countries, $basicAppInfo->country_of_origin_id,['class'=>'form-control input-md ', 'id' => 'country_of_origin_id']) !!}
                                                                {!! $errors->first('country_of_origin_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                                                            {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('organization_type_id', $eaOrganizationType, $basicAppInfo->organization_type_id, ['class' => 'form-control  input-md ','id'=>'organization_type_id']) !!}
                                                                {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('organization_status_id') ? 'has-error': ''}}">
                                                            {!! Form::label('organization_status_id','Status of the organization',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('organization_status_id', $eaOrganizationStatus, $basicAppInfo->organization_status_id, ['class' => 'form-control input-md ','id'=>'organization_status_id']) !!}
                                                                {!! $errors->first('organization_status_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('ownership_status_id') ? 'has-error': ''}}">
                                                            {!! Form::label('ownership_status_id','Ownership status',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('ownership_status_id', $eaOwnershipStatus, $basicAppInfo->ownership_status_id, ['class' => 'form-control  input-md ','id'=>'ownership_status_id']) !!}
                                                                {!! $errors->first('ownership_status_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('business_sector_id') ? 'has-error': ''}}">
                                                            {!! Form::label('business_sector_id','Business sector',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('business_sector_id', $sectors, $basicAppInfo->business_sector_id, ['class' => 'form-control  input-md','id'=>'business_sector_id']) !!}
                                                                {!! $errors->first('business_sector_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('business_sub_sector_id') ? 'has-error': ''}}">
                                                            {!! Form::label('business_sub_sector_id','Sub sector',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('business_sub_sector_id', $sub_sectors, $basicAppInfo->business_sub_sector_id, ['class' => 'form-control  input-md','id'=>'business_sub_sector_id']) !!}
                                                                {!! $errors->first('business_sub_sector_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="form-group col-md-12 {{$errors->has('major_activities') ? 'has-error' : ''}}">
                                                            {!! Form::label('major_activities','Major activities in brief',['class'=>'col-md-3']) !!}
                                                            <div class="col-md-9">
                                                                {!! Form::textarea('major_activities', $basicAppInfo->major_activities, ['class' => 'form-control input-md bigInputField', 'size' =>'5x2','data-rule-maxlength'=>'240', 'placeholder' => 'Maximum 240 characters']) !!}
                                                                {!! $errors->first('major_activities','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-info">
                                            <div class="panel-heading "><strong>B. Information of Principal
                                                    Promoter/Chairman/Managing Director/CEO/Country Manager</strong>
                                            </div>
                                            <div class="panel-body readOnlyCl">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('ceo_country_id', $countries, $basicAppInfo->ceo_country_id, ['class' => 'form-control  input-md ','id'=>'ceo_country_id']) !!}
                                                                {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('ceo_dob') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_dob','Date of Birth',['class'=>'col-md-5']) !!}
                                                            <div class=" col-md-7">
                                                                <div class="datepicker input-group date"
                                                                     data-date-format="dd-mm-yyyy">
                                                                    {!! Form::text('ceo_dob', ($basicAppInfo->ceo_dob == '0000-00-00' ? '' : date('d-M-Y', strtotime($basicAppInfo->ceo_dob))), ['class'=>'form-control input-md', 'id' => 'ceo_dob', 'placeholder'=>'Pick from datepicker']) !!}
                                                                    <span class="input-group-addon"><span
                                                                                class="fa fa-calendar"></span></span>
                                                                </div>
                                                                {!! $errors->first('ceo_dob','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div id="ceo_passport_div"
                                                             class="col-md-6 hidden {{$errors->has('ceo_passport_no') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_passport_no','Passport No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_passport_no', $basicAppInfo->ceo_passport_no, ['maxlength'=>'20',
                                                                'class' => 'form-control input-md ', 'id'=>'ceo_passport_no']) !!}
                                                                {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div id="ceo_nid_div"
                                                             class="col-md-6 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_nid','NID No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_nid', $basicAppInfo->ceo_nid, ['maxlength'=>'20',
                                                                'class' => 'form-control number input-md  bd_nid','id'=>'ceo_nid']) !!}
                                                                {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_designation','Designation', ['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_designation', $basicAppInfo->ceo_designation,
                                                                ['maxlength'=>'80','class' => 'form-control input-md ']) !!}
                                                                {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('ceo_full_name') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_full_name','Full Name',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_full_name', $basicAppInfo->ceo_full_name, ['maxlength'=>'80',
                                                                'class' => 'form-control input-md ']) !!}
                                                                {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div id="ceo_district_div"
                                                             class="col-md-6 {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_district_id','District/City/State ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('ceo_district_id',$districts, $basicAppInfo->ceo_district_id, ['maxlength'=>'80','class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div id="ceo_city_div"
                                                             class="col-md-6 hidden {{$errors->has('ceo_city') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_city','District/City/State',['class'=>'text-left  col-md-5 ']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_city', $basicAppInfo->ceo_city,['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div id="ceo_town_div"
                                                             class="col-md-6 hidden {{$errors->has('ceo_town') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_town','Police Station/Town',['class'=>'text-left  col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_town',$basicAppInfo->ceo_town,['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('ceo_town','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div id="ceo_thana_div"
                                                             class="col-md-6 {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_thana_id','Police Station/Town ',['class'=>'col-md-5 text-left required-star','placeholder'=>'Select district first']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('ceo_thana_id',$thana, $basicAppInfo->ceo_thana_id, ['maxlength'=>'80','class' => 'form-control input-md','placeholder' => 'Select district first']) !!}
                                                                {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_post_code','Post/Zip Code ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_post_code', $basicAppInfo->ceo_post_code, ['maxlength'=>'80','class' => 'form-control input-md engOnly ']) !!}
                                                                {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('ceo_address') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_address','House,Flat/Apartment,Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_address', $basicAppInfo->ceo_address, ['maxlength'=>'80','class' => 'form-control input-md ']) !!}
                                                                {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('ceo_telephone_no') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_telephone_no', $basicAppInfo->ceo_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
                                                                {!! $errors->first('ceo_telephone_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('ceo_mobile_no') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_mobile_no',  $basicAppInfo->ceo_mobile_no, ['class' => 'form-control input-md  phone_or_mobile']) !!}
                                                                {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('ceo_father_name') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_father_name','Father\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_father_label']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_father_name', $basicAppInfo->ceo_father_name, ['class' => 'form-control textOnly input-md ']) !!}
                                                                {!! $errors->first('ceo_father_name','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_email', $basicAppInfo->ceo_email, ['class' => 'form-control email input-md ']) !!}
                                                                {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('ceo_mother_name') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_mother_name','Mother\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_mother_label']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_mother_name', $basicAppInfo->ceo_mother_name, ['class' => 'form-control textOnly  input-md']) !!}
                                                                {!! $errors->first('ceo_mother_name','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('ceo_fax_no') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_fax_no', $basicAppInfo->ceo_fax_no, ['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('ceo_spouse_name') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_spouse_name','Spouse name',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('ceo_spouse_name', $basicAppInfo->ceo_spouse_name, ['class' => 'form-control textOnly input-md']) !!}
                                                                {!! $errors->first('ceo_spouse_name','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-info">
                                            <div class="panel-heading "><strong>C. Office Address</strong></div>
                                            <div class="panel-body readOnlyCl">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('office_division_id') ? 'has-error': ''}}">
                                                        {!! Form::label('office_division_id','Division',['class'=>'text-left required-star col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('office_division_id', $divisions, $basicAppInfo->office_division_id, ['class' => 'form-control  imput-md', 'id' => 'office_division_id']) !!}
                                                            {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 form-group {{$errors->has('office_thana_id') ? 'has-error': ''}}">
                                                        {!! Form::label('office_thana_id','Police Station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('office_thana_id',$thana, $basicAppInfo->office_thana_id, ['class' => 'form-control input-md ','placeholder' => 'Select district first']) !!}
                                                            {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 form-group {{$errors->has('office_district_id') ? 'has-error': ''}}">
                                                        {!! Form::label('office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('office_district_id', $districts, $basicAppInfo->office_district_id, ['class' => 'form-control input-md ','placeholder' => 'Select division first']) !!}
                                                            {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 form-group {{$errors->has('office_post_code') ? 'has-error': ''}}">
                                                        {!! Form::label('office_post_code','Post Code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_post_code', $basicAppInfo->office_post_code, ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 form-group {{$errors->has('office_post_office') ? 'has-error': ''}}">
                                                        {!! Form::label('office_post_office','Post Office',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_post_office', $basicAppInfo->office_post_office, ['class' => 'form-control input-md ']) !!}
                                                            {!! $errors->first('office_post_office','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 form-group {{$errors->has('office_telephone_no') ? 'has-error': ''}}">
                                                        {!! Form::label('office_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_telephone_no', $basicAppInfo->office_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
                                                            {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 form-group {{$errors->has('office_address') ? 'has-error': ''}}">
                                                        {!! Form::label('office_address','House,Flat/Apartment,Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_address', $basicAppInfo->office_address, ['maxlength'=>'80','class' => 'form-control input-md ']) !!}
                                                            {!! $errors->first('office_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 form-group {{$errors->has('office_fax_no') ? 'has-error': ''}}">
                                                        {!! Form::label('office_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_fax_no', $basicAppInfo->office_fax_no, ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 form-group {{$errors->has('office_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('office_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_mobile_no', $basicAppInfo->office_mobile_no, ['class' => 'form-control input-md  phone_or_mobile']) !!}
                                                            {!! $errors->first('office_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 form-group {{$errors->has('office_email') ? 'has-error': ''}}">
                                                        {!! Form::label('office_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_email', $basicAppInfo->office_email, ['class' => 'form-control input-md ']) !!}
                                                            {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-info">
                                            <div class="panel-heading "><strong>D. Factory Address (Optional)</strong>
                                            </div>
                                            <div class="panel-body readOnlyCl">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('factory_district_id') ? 'has-error': ''}}">
                                                            {!! Form::label('factory_district_id','District',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('factory_district_id', $districts, $basicAppInfo->factory_district_id, ['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('factory_district_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('factory_thana_id') ? 'has-error': ''}}">
                                                            {!! Form::label('factory_thana_id','Police Station', ['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('factory_thana_id',$thana, $basicAppInfo->factory_thana_id, ['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('factory_thana_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('factory_post_office') ? 'has-error': ''}}">
                                                            {!! Form::label('factory_post_office','Post Office',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('factory_post_office', $basicAppInfo->factory_post_office, ['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('factory_post_code') ? 'has-error': ''}}">
                                                            {!! Form::label('factory_post_code','Post Code', ['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('factory_post_code', $basicAppInfo->factory_post_code, ['class' => 'form-control input-md engOnly']) !!}
                                                                {!! $errors->first('factory_post_code','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('factory_address') ? 'has-error': ''}}">
                                                            {!! Form::label('factory_address','House,Flat/Apartment,Road ',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('factory_address', $basicAppInfo->factory_address, ['maxlength'=>'80','class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('factory_telephone_no') ? 'has-error': ''}}">
                                                            {!! Form::label('factory_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('factory_telephone_no', $basicAppInfo->factory_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
                                                                {!! $errors->first('factory_telephone_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('factory_mobile_no') ? 'has-error': ''}}">
                                                            {!! Form::label('factory_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('factory_mobile_no', $basicAppInfo->factory_mobile_no, ['class' => 'form-control input-md phone_or_mobile']) !!}
                                                                {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('factory_fax_no') ? 'has-error': ''}}">
                                                            {!! Form::label('factory_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('factory_fax_no', $basicAppInfo->factory_fax_no, ['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('factory_fax_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('factory_email') ? 'has-error': ''}}">
                                                            {!! Form::label('factory_email','Email ',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('factory_email', $basicAppInfo->factory_email, ['class' => 'form-control email input-md']) !!}
                                                                {!! $errors->first('factory_email','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('factory_mouja') ? 'has-error': ''}}">
                                                            {!! Form::label('factory_mouja','Mouja No.',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('factory_mouja', $basicAppInfo->factory_mouja, ['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('factory_mouja','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-info">
                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('tin_no') ? 'has-error': ''}}">
                                                            {!! Form::label('tin_no','TIN Number',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('tin_no', '', ['class' => 'form-control input-md number','id'=>'tin_no']) !!}
                                                                <span class=" btn-success btn-sm"
                                                                      style="display:inline">verified</span>
                                                                {!! $errors->first('tin_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('tin_file') ? 'has-error': ''}}">
                                                            {!! Form::label('tin_file','TIN Certificate Attached', ['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input type="file" name="tin_file"
                                                                       class='form-control input-md '
                                                                       onchange="uploadDocument('tin_file_preview', this.id, 'tin_file_name', '0')"
                                                                       id='tin_file'>
                                                                <small class="text-muted">Max file size 2MB.</small>
                                                                {!! $errors->first('tin_file','<span class="help-block">:message</span>') !!}
                                                                <div id="tin_file_preview" class="uploadbox">
                                                                    <input type="hidden" class="" id="tin_file_name"
                                                                           name="tin_file_name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('trade_licence') ? 'has-error': ''}}">
                                                            {!! Form::label('trade_licence','Trade Licence',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('trade_licence', '', ['class' => 'form-control input-md number','id'=>'trade_licence']) !!}
                                                                <span class=" btn-success btn-sm"
                                                                      style="display:inline">verified</span>
                                                                {!! $errors->first('trade_licence','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('trade_file') ? 'has-error': ''}}">
                                                            {!! Form::label('trade_file','Trade Licence Attached', ['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input type="file" name="trade_file"
                                                                       class='form-control input-md'
                                                                       onchange="uploadDocument('trade_file_preview', this.id, 'trade_file_name', '0')"
                                                                       id='trade_file'>
                                                                <small class="text-muted">Max file size 2MB.</small>
                                                                {!! $errors->first('trade_file','<span class="help-block">:message</span>') !!}
                                                                <div id="trade_file_preview" class="uploadbox">
                                                                    <input type="hidden" class="" id="trade_file_name"
                                                                           name="trade_file_name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('incorporation_no') ? 'has-error': ''}}">
                                                            {!! Form::label('incorporation_no','Incorporation No',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('incorporation_no', '', ['class' => 'form-control input-md number','id'=>'incorporation_no','style'=>"display:inline"]) !!}
                                                                <span class=" btn-success btn-sm"
                                                                      style="display:inline">verified</span>
                                                                {!! $errors->first('incorporation_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('incorporation_file') ? 'has-error': ''}}">
                                                            {!! Form::label('incorporation_file','Incorporation Attached', ['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input type="file" name='incorporation_file'
                                                                       class='form-control input-md'
                                                                       onchange="uploadDocument('incorporation_file_preview', this.id, 'incorporation_file_name', '0')"
                                                                       id='incorporation_file'>
                                                                <small class="text-muted">Max file size 2MB.</small>
                                                                {!! $errors->first('incorporation_file','<span class="help-block">:message</span>') !!}
                                                                <div id="incorporation_file_preview" class="uploadbox">
                                                                    <input type="hidden" class=""
                                                                           id="incorporation_file_name"
                                                                           name="incorporation_file_name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('mem_association') ? 'has-error': ''}}">
                                                            {!! Form::label('mem_association','Memorandum of Association',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input type="file" name="mem_association"
                                                                       id="mem_association"
                                                                       class='form-control input-md'
                                                                       onchange="uploadDocument('mem_association_preview', this.id, 'mem_association_file_name', '0')">
                                                                <small class="text-muted">Max file size 2MB.</small>
                                                                {!! $errors->first('mem_association','<span class="help-block">:message</span>') !!}
                                                                <div id="mem_association_preview" class="uploadbox">
                                                                    <input type="hidden" class=""
                                                                           id="mem_association_file_name"
                                                                           name="mem_association_file_name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('art_association') ? 'has-error': ''}}">
                                                            {!! Form::label('art_association','Article of Association',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input type="file" name="art_association"
                                                                       id="art_association"
                                                                       class='form-control input-md'
                                                                       onchange="uploadDocument('art_association_preview', this.id, 'art_association_file_name', '0')">
                                                                <small class="text-muted">Max file size 2MB.</small>
                                                                {!! $errors->first('art_association','<span class="help-block">:message</span>') !!}
                                                                <div id="art_association_preview" class="uploadbox">
                                                                    <input type="hidden" class=""
                                                                           id="art_association_file_name"
                                                                           name="art_association_file_name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('list_share_holder_n_director') ? 'has-error': ''}}">
                                                            {!! Form::label('list_share_holder_n_director','List of share holder & Director',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input type="file" name="list_share_holder_n_director"
                                                                       id="list_share_holder_n_director"
                                                                       class='form-control input-md'
                                                                       onchange="uploadDocument('list_share_holder_n_director_preview', this.id, 'list_share_holder_n_director_file_name', '0')">
                                                                <small class="text-muted">Max file size 2MB.</small>
                                                                {!! $errors->first('list_share_holder_n_director','<span class="help-block">:message</span>') !!}
                                                                <div id="list_share_holder_n_director_preview"
                                                                     class="uploadbox">
                                                                    <input type="hidden" class=""
                                                                           id="list_share_holder_n_director_file_name"
                                                                           name="list_share_holder_n_director_file_name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">

                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </fieldset>


                                    {{--<fieldset>--}}
                                    {{--<div class="panel panel-info">--}}
                                    {{--<div class="panel-heading">--}}
                                    {{--<strong>Authorized Persons Information</strong>--}}
                                    {{--</div>--}}
                                    {{--<div class="panel-body">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                    {{--<div class="col-md-6 {{$errors->has('auth_full_name') ? 'has-error': ''}}">--}}
                                    {{--{!! Form::label('auth_full_name','Full Name ',['class'=>'col-md-5 text-left required-star']) !!}--}}
                                    {{--<div class="col-md-7">--}}
                                    {{--{!! Form::text('auth_full_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md']) !!}--}}
                                    {{--{!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">--}}
                                    {{--{!! Form::label('auth_designation','Designation ',['class'=>'col-md-5 text-left required-star']) !!}--}}
                                    {{--<div class="col-md-7">--}}
                                    {{--{!! Form::text('auth_designation', \Illuminate\Support\Facades\Auth::user()->designation, ['class' => 'form-control input-md']) !!}--}}
                                    {{--{!! $errors->first('auth_designation','<span class="help-block">:message</span>') !!}--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                    {{--<div class="col-md-6 {{$errors->has('auth_mobile_no') ? 'has-error': ''}}">--}}
                                    {{--{!! Form::label('auth_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}--}}
                                    {{--<div class="col-md-7">--}}
                                    {{--{!! Form::text('auth_mobile_no', \Illuminate\Support\Facades\Auth::user()->user_phone, ['class' => 'form-control input-md phone_or_mobile','id' => 'auth_mobile_no']) !!}--}}
                                    {{--{!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">--}}
                                    {{--{!! Form::label('auth_email','Email address ',['class'=>'col-md-5 text-left required-star']) !!}--}}
                                    {{--<div class="col-md-7">--}}
                                    {{--{!! Form::text('auth_email', \Illuminate\Support\Facades\Auth::user()->user_email, ['class' => 'form-control input-md']) !!}--}}
                                    {{--{!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                    {{--<div class="col-md-6 {{$errors->has('auth_letter') ? 'has-error': ''}}">--}}
                                    {{--{!! Form::label('auth_letter','Authorization Letter ',['class'=>'col-md-5 text-left required-star']) !!}--}}
                                    {{--<div class="col-md-7">--}}

                                    {{--<input type="file" name="auth_letter" id="auth_letter" class="form-control input-md required"/>--}}
                                    {{--<span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span><br/>--}}
                                    {{--{!! $errors->first('auth_letter','<span class="help-block">:message</span>') !!}--}}
                                    {{--<a href="/assets/images/sample_auth_letter.png" target="_blank"> <i class="fa  fa-file-pdf-o"></i> Sample Authorization Letter </a>--}}

                                    {{--<input type="file" name="auth_letter" id="auth_letter" class="form-control input-md"/>--}}
                                    {{--<span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span><br/>--}}
                                    {{--{!! $errors->first('auth_letter','<span class="help-block">:message</span>') !!}--}}

                                    {{--Old Authorization letter--}}
                                    {{--@if(!empty(Auth::user()->authorization_file))--}}
                                    {{--<input type="hidden" name="old_auth_letter" value="{{ Auth::user()->authorization_file }}">--}}
                                    {{--<a href="{{ URL::to('users/upload/'.Auth::user()->authorization_file) }}" target="_blank" class="btn btn-xs btn-primary show-in-view" title="Old Authorization Letter" style="margin-top: 5px;">--}}
                                    {{--<i class="fa  fa-file-pdf-o"></i>--}}
                                    {{--Old Authorization Letter--}}
                                    {{--</a><br/>--}}
                                    {{--@endif--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">--}}
                                    {{--<div class="col-sm-9">--}}
                                    {{--{!! Form::label('auth_image','Profile Picture', ['class'=>'text-left required-star','style'=>'']) !!}--}}
                                    {{--{!! $errors->first('auth_image','<span class="help-block">:message</span>') !!}--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-3">--}}
                                    {{--<img class="img-thumbnail" id="authImageViewer" src="{{ \Illuminate\Support\Facades\Auth::user()->user_pic != '' ? url('users/upload/'.\Illuminate\Support\Facades\Auth::user()->user_pic) : url('assets/images/photo_default.png') }}" alt="Auth Image">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}

                                    {{--<div class="col-md-6 {{$errors->has('auth_signature') ? 'has-error': ''}}">--}}
                                    {{--<div class="col-sm-9">--}}
                                    {{--{!! Form::label('auth_signature','Signature', ['class'=>'text-left required-star','style'=>'']) !!}--}}
                                    {{--{!! $errors->first('auth_signature','<span class="help-block">:message</span>') !!}--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-3">--}}
                                    {{--<img class="img-thumbnail" id="authSignatureViewer" src="{{ \Illuminate\Support\Facades\Auth::user()->signature != '' ? url('users/signature/'.\Illuminate\Support\Facades\Auth::user()->signature) : url('assets/images/photo_default.png') }}" alt="Auth Image">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="panel panel-info">--}}
                                    {{--<div class="panel-heading"><strong>Terms and Conditions</strong></div>--}}
                                    {{--<div class="panel-body">--}}
                                    {{--<div class="row">--}}
                                    {{--<div class="col-md-12 form-group {{$errors->has('acceptTerms') ? 'has-error' : ''}}">--}}
                                    {{--<input id="acceptTerms-2" name="acceptTerms" type="checkbox"--}}
                                    {{--class="required col-md-1 text-left" style="width:3%;">--}}
                                    {{--<label for="acceptTerms-2" class="col-md-11 text-left required-star">I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ system is given. </label>--}}
                                    {{--<div class="clearfix"></div>--}}
                                    {{--{!! $errors->first('acceptTerms','<span class="help-block">:message</span>') !!}--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</fieldset>--}}
                                    @if(ACL::getAccsessRight('SingleLicence','-E-'))
                                        <button type="submit" class="btn btn-info btn-md cancel"
                                                value="draft" name="actionBtn">Save as Draft
                                        </button>
                                        <button type="submit" class="btn btn-info btn-md submit pull-right"
                                                value="save" name="actionBtn">Save & Next
                                        </button>
                                    @endif

                                </div>

                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    {{--End application form with wizard--}}

                </div>
            </div>


    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.css">



    <script type="text/javascript">

        function uploadDocument(targets, id, vField, isRequired) {
            var file_id = document.getElementById(id);
            var file = file_id.files;
            if (file && file[0]) {
                if (!(file[0].type == 'application/pdf')) {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'The file format is not valid! Please upload in pdf format.'
                    });
                    file_id.value = '';
                    return false;
                }

                var file_size = parseFloat((file[0].size) / (1024 * 1024)).toFixed(1); //MB Calculation
                if (!(file_size <= 2)) {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Max file size 2MB. You have uploaded ' + file_size + 'MB'
                    });
                    file_id.value = '';
                    return false;
                }
            }
            var inputFile = $("#" + id).val();
            if (inputFile == '') {
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
                if ($('#label_' + id).length) $('#label_' + id).remove();
                return false;
            }

            try {
                document.getElementById("isRequired").value = isRequired;
                document.getElementById("selected_file").value = id;
                document.getElementById("validateFieldName").value = vField;
                document.getElementById(targets).style.color = "red";
                var action = "{{url('/licence-application/bank-account/upload-document')}}";

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
                    dataType: 'text',  // what to expect back from the PHP script, if anything
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
                        var doc_id = parseInt(id.substring(4));
                        var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] +
                            ' <a href="javascript:void(0)" onclick="EmptyFiles(' + id
                            + ')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                        //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                        $("#" + id).after(newInput);
                        //check valid data
                        var validate_field = $('#' + vField).val();
                        if (validate_field == '') {
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            }
        }


        function EmptyFiles(id) {
            var file_id = id.id;
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    makeBlank_values(file_id);
                    swal(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                } else {
                    return false;
                }
            })

            // var sure_del = confirm("Are you sure you want to delete this file?");
            // if (sure_del) {
            //     makeBlank_value(id);
            // } else {
            //     return false;
            // }
        }

        $(document).ready(function () {

            $('#bank_id').on('change', function () {
                var bank_id = $(this).val();
                var action = "{{url('/licence-application/bank-account/branches')}}";
                var form_data = new FormData();
                form_data.append('bank_id', bank_id);
                form_data.append('_token', "{{ csrf_token() }}");
                $.ajax({
                    url: action,
                    dataType: 'text',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (response) {
                        var bankBranches = JSON.parse(response);
                        if (bankBranches.responseCode) {
                            var html = "<option value='' >Select one</option>";
                            $.each(bankBranches.data, function (key, value) {
                                html += "<option value='" + value.id + "' >" + value.branch_name + "</option>";

                            });
                            $('#bank_branch_id').html(html);
                        }
                    }
                });
            });

            $('#BankApplicationForm').validate();
            $('.readOnlyCl input,.readOnlyCl select,.readOnlyCl textarea,.readOnly').attr('readonly', true);
            $(".readOnlyCl option:not(:selected)").prop('disabled', true);

        });


        $("#office_division_id").change(function () {
            var divisionId = $('#office_division_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/licence-application/get-district-by-division",
                data: {
                    divisionId: divisionId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#office_district_id").html(option);
                    $(self).next().hide();
                }
            });
        });

        $(document).ready(function () {

            $(".other_utility").click(function () {
                $('.other_utility_txt').hide();
                var ischecked = $(this).is(':checked');
                console.log(ischecked);
                if (ischecked == true) {
                    $('.other_utility_txt').show();
                }
            });

            var today = new Date();
            var yyyy = today.getFullYear();

            $('.datepicker_registration_date').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                minDate: '01/01/' + (yyyy - 50),
                maxDate: today,
            });

            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
//            minDate: '01/01/'+(yyyy-10),
//            maxDate: '01/01/'+(yyyy+10)
                maxDate: 'now'
            });


            $('.commercial_operation_date').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                minDate: 'now',
            });

            $("#ceo_district_id").change(function () {
                var districtId = $(this).val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#ceo_thana_id").html(option);
                        $(self).next().hide('slow');
                    }
                });
            });
            $("#office_district_id").change(function () {
                var districtId = $(this).val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#office_thana_id").html(option);
                        $(self).next().hide('slow');
                    }
                });
            });
            $("#factory_district_id").change(function () {
                var districtId = $(this).val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#factory_thana_id").html(option);
                        $(self).next().hide('slow');
                    }
                });
            });
        });




    </script>