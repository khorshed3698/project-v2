
<?php
if($mode == '-E-'){
    $basicInfo = CommonFunction::getBasicInformationByProcessRefId($appInfo->process_type_id, $appInfo->id);
}else{
    $basicInfo = CommonFunction::getBasicInformationByCompanyId($company_id);
}
?>
@if($basicInfo)
    <div class="panel panel-info">
        <div class="panel-heading"><strong>Basic Company Information (Non editable info. pulled from the basic information provided at the first time by your company)</strong></div>
        <div class="panel-body">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Company Information:</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('department_id') ? 'has-error': ''}}">
                            {!! Form::label('department_id','Department',['class'=>'col-md-3 text-left']) !!}
                            <div class="col-md-9">
                                <input class="form-control input-md" type="text" value="{{ $basicInfo->department }}"
                                       readonly>
                                {!! $errors->first('department_id','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 form-group {{$errors->has('company_name') ? 'has-error': ''}}">
                            {!! Form::label('company_name','Name of Organization in English (Proposed)',['class'=>'col-md-3 text-left']) !!}
                            <div class="col-md-9">
                                <input class="form-control input-md" type="text" value="{{ $basicInfo->company_name }}"
                                       readonly>
                                {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-12 form-group {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                            {!! Form::label('company_name_bn','Name of Organization in Bangla (Proposed)',['class'=>'col-md-3 text-left']) !!}
                            <div class="col-md-9">
                                <input class="form-control input-md" type="text"
                                       value="{{ $basicInfo->company_name_bn }}" readonly>
                                {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('service_type') ? 'has-error': ''}}">
                            {!! Form::label('service_type','Desired Service from BIDA',['class'=>'col-md-3 text-left', 'id'=> 'service_type_label']) !!}
                            <div class="col-md-9">
                                <input class="form-control input-md" type="text" value="{{ $basicInfo->service_name }}"
                                       readonly>
                                {!! $errors->first('service_type','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                @if($basicInfo->service_type == 5)
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('reg_commercial_office','Commercial office type', ['class'=>'col-md-3 text-left']) !!}
                                <div class="col-md-9">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->reg_commercial_office_name }}" readonly>
                                    {!! $errors->first('reg_commercial_office','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('ownership_status_id') ? 'has-error': ''}}">
                            {!! Form::label('ownership_status_id','Ownership status',['class'=>'col-md-3 text-left']) !!}
                            <div class="col-md-9">
                                <input class="form-control input-md" type="text"
                                       value="{{ $basicInfo->ea_ownership_status }}" readonly>
                                {!! $errors->first('ownership_status_id','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                            {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-3 text-left']) !!}
                            <div class="col-md-9">
                                <input class="form-control input-md" type="text"
                                       value="{{ $basicInfo->ea_organization_type }}" readonly>
                                {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="form-group col-md-12 {{$errors->has('major_activities') ? 'has-error' : ''}}">
                            {!! Form::label('major_activities','Major activities in brief',['class'=>'col-md-3']) !!}
                            <div class="col-md-9">
                                <span style="height: 100%; background: #eee;"
                                      class="form-control input-md">{{ $basicInfo->major_activities }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Information of Principal Promoter/ Chairman/ Project Manager/ Project Director/ Managing Director/ State
                    CEO:
                </legend>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                            {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('ceo_country_id', $countries, !empty($basicInfo->ceo_country_id)?$basicInfo->ceo_country_id:'', ['placeholder' => 'Select One',
                                    'class' => 'form-control input-md required', 'id'=>"ceo_country_id"]) !!}
                                {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-6 {{$errors->has('ceo_dob') ? 'has-error': ''}}">
                            {!! Form::label('ceo_dob','Date of Birth',['class'=>'col-md-5 required-star']) !!}
                            <div class=" col-md-7">
                                <div class="datepicker input-group date" data-date-format="dd-mm-yyyy">
                                    <input class="form-control input-md required" type="text"
                                           value="{{ !empty($basicInfo->ceo_dob)?date('d-M-Y', strtotime($basicInfo->ceo_dob)):'' }}" name="ceo_dob"/>
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                </div>
                                {!! $errors->first('ceo_dob','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div id="ceo_nid_div" class="col-md-6 hidden {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                            {!! Form::label('ceo_nid','NID No.',['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text" value="{{ !empty($basicInfo->ceo_nid)?$basicInfo->ceo_nid:'' }}"
                                       name="ceo_nid"/>
                                {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div id="ceo_passport_no_div" class="col-md-6 hidden {{$errors->has('ceo_passport_no') ? 'has-error': ''}}">
                            {!! Form::label('ceo_passport_no','Passport No.',['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text"
                                       value="{{ !empty($basicInfo->ceo_passport_no) ? $basicInfo->ceo_passport_no : '' }}" name="ceo_passport_no"/>
                                {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        
                        <div class="col-md-6 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                            {!! Form::label('ceo_designation','Designation', ['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md required" type="text"
                                       value="{{ !empty($basicInfo->ceo_designation) ? $basicInfo->ceo_designation : '' }}" name="ceo_designation"/>
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
                                <input class="form-control input-md required" type="text" value="{{ !empty($basicInfo->ceo_full_name) ? $basicInfo->ceo_full_name : '' }}"
                                       name="ceo_full_name"/>
                                {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div id="ceo_district_id_div" class="col-md-6 hidden {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                            {!! Form::label('ceo_district_id','District/ City/ State ',['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('ceo_district_id', $district_eng, !empty($basicInfo->ceo_district_id) ? $basicInfo->ceo_district_id : '',
                                ['class' => 'form-control input-md', 'placeholder' => 'Select One',
                                'onchange'=>"getThanaByDistrictIdCeo('ceo_district_id', this.value, 'ceo_thana_id', ".(!empty($basicInfo->ceo_thana_id)?$basicInfo->ceo_thana_id:'').")"
                                ])!!}
                                {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div id="ceo_city_div" class="col-md-6 hidden {{$errors->has('ceo_city') ? 'has-error': ''}}">
                            {!! Form::label('ceo_city','District/ City/ State',['class'=>'text-left  col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text" value="{{ !empty($basicInfo->ceo_city) ? $basicInfo->ceo_city : '' }}"
                                       name="ceo_city"/>
                                {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div id="ceo_thana_id_div" class="col-md-6 hidden {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                            {!! Form::label('ceo_thana_id','Police Station/ Town ',['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('ceo_thana_id',[''], !empty($basicInfo->ceo_thana_id) ? $basicInfo->ceo_thana_id : '', ['class' => 'form-control input-md','placeholder' => 'Select district first']) !!}
                                {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div id="ceo_state_div" class="col-md-6 hidden {{$errors->has('ceo_state') ? 'has-error': ''}}">
                            {!! Form::label('ceo_state','State/ Province',['class'=>'text-left  col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text" value="{{ !empty($basicInfo->ceo_state) ? $basicInfo->ceo_state : '' }}"
                                       name="ceo_state"/>
                                {!! $errors->first('ceo_state','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                            {!! Form::label('ceo_post_code','Post/ Zip Code ',['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md required" type="text" value="{{ !empty($basicInfo->ceo_post_code) ? $basicInfo->ceo_post_code : "" }}"
                                       name="ceo_post_code"/>
                                {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 {{$errors->has('ceo_address') ? 'has-error': ''}}">
                            {!! Form::label('ceo_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md required" type="text" value="{{ !empty($basicInfo->ceo_address) ? $basicInfo->ceo_address : '' }}"
                                       name="ceo_address"/>
                                {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6 {{$errors->has('ceo_telephone_no') ? 'has-error': ''}}">
                            {!! Form::label('ceo_telephone_no','Telephone No.',['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                <input id="ceo_telephone_no" class="form-control input-md mobile-plugin phone_or_mobile required" type="text"
                                       value="{{ !empty($basicInfo->ceo_telephone_no) ? $basicInfo->ceo_telephone_no : '' }}" name="ceo_telephone_no"/>
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
                                {!! Form::text("ceo_mobile_no", !empty($basicInfo->ceo_mobile_no) ? $basicInfo->ceo_mobile_no : '', ['class' => ' form-control input-md mobile-plugin phone_or_mobile required', 'id' => 'ceo_mobile_no']) !!}
                                {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-6 {{$errors->has('ceo_father_name') ? 'has-error': ''}}">
                            {!! Form::label('ceo_father_name','Father\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_father_label']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md required" type="text"
                                       value="{{ !empty($basicInfo->ceo_father_name) ? $basicInfo->ceo_father_name : '' }}" name="ceo_father_name"/>
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
                                <input class="form-control input-md required" type="text" value="{{ !empty($basicInfo->ceo_email) ? $basicInfo->ceo_email : '' }}"
                                       name="ceo_email"/>
                                {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6 {{$errors->has('ceo_mother_name') ? 'has-error': ''}}">
                            {!! Form::label('ceo_mother_name','Mother\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_mother_label']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md required" type="text"
                                       value="{{ !empty($basicInfo->ceo_mother_name) ? $basicInfo->ceo_mother_name : '' }}" name="ceo_mother_name"/>
                                {!! $errors->first('ceo_mother_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 {{$errors->has('ceo_fax_no') ? 'has-error': ''}}">
                            {!! Form::label('ceo_fax_no','Fax No. ',['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md required" type="text" value="{{ !empty($basicInfo->ceo_fax_no) ? $basicInfo->ceo_fax_no : '' }}"
                                       name="ceo_fax_no"/>
                                {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6 {{$errors->has('ceo_spouse_name') ? 'has-error': ''}}">
                            {!! Form::label('ceo_spouse_name','Spouse name',['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md required" type="text"
                                       value="{{ !empty($basicInfo->ceo_spouse_name) ? $basicInfo->ceo_spouse_name : '' }}" name="ceo_spouse_name"/>
                                {!! $errors->first('ceo_spouse_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 {{$errors->has('ceo_gender') ? 'has-error': ''}}">
                            {!! Form::label('ceo_gender','Gender', ['class'=>'col-md-5 text-left required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('ceo_gender',['Male' => 'Male', 'Female' => 'Female'], !empty($basicInfo->ceo_gender) ? $basicInfo->ceo_gender : '', ['class' => 'form-control input-md', 'placeholder' => 'Select One', 'id' => 'ceo_gender']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Office Address:</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 {{$errors->has('office_division_id') ? 'has-error': ''}}">
                            {!! Form::label('office_division_id','Division',['class'=>'text-left col-md-5']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text"
                                       value="{{ $basicInfo->office_division_name }}" readonly>
                                {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6 {{$errors->has('office_district_id') ? 'has-error': ''}}">
                            {!! Form::label('office_district_id','District',['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text"
                                       value="{{ $basicInfo->office_district_name }}" readonly>
                                {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 {{$errors->has('office_thana_id') ? 'has-error': ''}}">
                            {!! Form::label('office_thana_id','Police Station', ['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text"
                                       value="{{ $basicInfo->office_thana_name }}" readonly>
                                {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6 {{$errors->has('office_post_office') ? 'has-error': ''}}">
                            {!! Form::label('office_post_office','Post Office',['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text"
                                       value="{{ $basicInfo->office_post_office }}" readonly>
                                {!! $errors->first('office_post_office','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 {{$errors->has('office_post_code') ? 'has-error': ''}}">
                            {!! Form::label('office_post_code','Post Code', ['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text"
                                       value="{{ $basicInfo->office_post_code }}" readonly>
                                {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6 {{$errors->has('office_address') ? 'has-error': ''}}">
                            {!! Form::label('office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text"
                                       value="{{ $basicInfo->office_address }}" readonly>
                                {!! $errors->first('office_address','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 {{$errors->has('office_telephone_no') ? 'has-error': ''}}">
                            {!! Form::label('office_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-7">
                                <input id="office_telephone_no" class="form-control input-md mobile-plugin" type="text"
                                       value="{{ $basicInfo->office_telephone_no }}" readonly />
                                {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6 {{$errors->has('office_mobile_no') ? 'has-error': ''}}">
                            {!! Form::label('office_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-7">
                                <input id="office_mobile_no" class="form-control input-md mobile-plugin" type="text"
                                       value="{{ $basicInfo->office_mobile_no }}" readonly>
                                {!! $errors->first('office_mobile_no','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 {{$errors->has('office_fax_no') ? 'has-error': ''}}">
                            {!! Form::label('office_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text" value="{{ $basicInfo->office_fax_no }}"
                                       readonly>
                                {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6 {{$errors->has('office_email') ? 'has-error': ''}}">
                            {!! Form::label('office_email','Email ',['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-7">
                                <input class="form-control input-md" type="text" value="{{ $basicInfo->office_email }}"
                                       readonly>
                                {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            {{--2 = industrial department --}}
            @if($basicInfo->department_id == 2)
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Factory Address:</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('factory_district_id') ? 'has-error': ''}}">
                                {!! Form::label('factory_district_id','District',['class'=>'col-md-5 text-left','id' => 'factory_district_label']) !!}
                                <div class="col-md-7">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->factory_district_name }}" readonly>
                                    {!! $errors->first('factory_district_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('factory_thana_id') ? 'has-error': ''}}">
                                {!! Form::label('factory_thana_id','Police Station', ['class'=>'col-md-5 text-left', 'id' => 'factory_thana_label']) !!}
                                <div class="col-md-7">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->factory_thana_name }}" readonly>
                                    {!! $errors->first('factory_thana_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('factory_post_office') ? 'has-error': ''}}">
                                {!! Form::label('factory_post_office','Post Office',['class'=>'col-md-5 text-left', 'id'=>'factory_post_label']) !!}
                                <div class="col-md-7">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->factory_post_office }}" readonly>
                                    {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('factory_post_code') ? 'has-error': ''}}">
                                {!! Form::label('factory_post_code','Post Code', ['class'=>'col-md-5 text-left', 'id'=>'factory_post_code_label']) !!}
                                <div class="col-md-7">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->factory_post_code }}" readonly>
                                    {!! $errors->first('factory_post_code','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('factory_address') ? 'has-error': ''}}">
                                {!! Form::label('factory_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left', 'id'=>'factory_address_label']) !!}
                                <div class="col-md-7">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->factory_address }}" readonly>
                                    {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('factory_telephone_no') ? 'has-error': ''}}">
                                {!! Form::label('factory_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->factory_telephone_no }}" readonly>
                                    {!! $errors->first('factory_telephone_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('factory_mobile_no') ? 'has-error': ''}}">
                                {!! Form::label('factory_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left', 'id'=>'factory_mobile_label']) !!}
                                <div class="col-md-7">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->factory_mobile_no }}" readonly>
                                    {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('factory_fax_no') ? 'has-error': ''}}">
                                {!! Form::label('factory_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->factory_fax_no }}" readonly>
                                    {!! $errors->first('factory_fax_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('factory_email') ? 'has-error': ''}}">
                                {!! Form::label('factory_email','Email ',['class'=>'col-md-5 text-left', 'id'=>'factory_email_label']) !!}
                                <div class="col-md-7">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->factory_email }}" readonly>
                                    {!! $errors->first('factory_email','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('factory_mouja') ? 'has-error': ''}}">
                                {!! Form::label('factory_mouja','Mouja No.',['class'=>'col-md-5 text-left', 'id'=>'factory_mouja_label']) !!}
                                <div class="col-md-7">
                                    <input class="form-control input-md" type="text"
                                           value="{{ $basicInfo->factory_mouja }}" readonly>
                                    {!! $errors->first('factory_mouja','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            @endif
        </div>
    </div>
@endif

<script>
    
    var loadedCeoCountryId = "{{ !empty($basicInfo->ceo_country_id) ? $basicInfo->ceo_country_id : '' }}";
    var loadedCeoDistrictId = "{{ !empty($basicInfo->ceo_district_id) ? $basicInfo->ceo_district_id : '' }}";
    var loadedCeoThanaId = "{{ !empty($basicInfo->ceo_thana_id) ? $basicInfo->ceo_thana_id : '' }}";
    
    $(function () {

        countryWiseCeoDataLoad(loadedCeoCountryId);
        getThanaByDistrictIdCeo('ceo_district_id', loadedCeoDistrictId, 'ceo_thana_id', loadedCeoThanaId);
        
        // $("#office_mobile_no").intlTelInput({
        //     hiddenInput: "office_mobile_no",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });

        // $("#office_telephone_no").intlTelInput({
        //     hiddenInput: "office_telephone_no",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });

        $('#ceo_country_id').change(function (e) {
            let ceo_country_id = this.value;
            countryWiseCeoDataLoad(ceo_country_id);
        });
    });
    
    function countryWiseCeoDataLoad(country_id) {
        if (country_id == '18') {
            $("#ceo_passport_no_div").addClass('hidden');
            $("#ceo_city_div").addClass('hidden');
            $("#ceo_state_div").addClass('hidden');

            $("#ceo_passport_no").removeClass('required');
            $("#ceo_city").removeClass('required');
            $("#ceo_state").removeClass('required');

            $("#ceo_nid_div").removeClass('hidden');
            $("#ceo_district_id_div").removeClass('hidden');
            $("#ceo_thana_id_div").removeClass('hidden');

            $("#ceo_nid").addClass('required');
            $("#ceo_district_id").addClass('required');
            $("#ceo_thana_id").addClass('required');
            
        } else {
            $("#ceo_nid_div").addClass('hidden');
            $("#ceo_district_id_div").addClass('hidden');
            $("#ceo_thana_id_div").addClass('hidden');

            $("#ceo_nid").removeClass('required');
            $("#ceo_district_id").removeClass('required');
            $("#ceo_thana_id").removeClass('required');

            $("#ceo_passport_no_div").removeClass('hidden');
            $("#ceo_city_div").removeClass('hidden');
            $("#ceo_state_div").removeClass('hidden');

            $("#ceo_passport_no").addClass('required');
            $("#ceo_city").addClass('required');
            $("#ceo_state").addClass('required');
        }
    }
    
    function getThanaByDistrictIdCeo(district_id, district_value, thana_div, old_data) {
        if (typeof old_data === 'undefined') {
            old_data = 0;
        }

        var _token = $('input[name="_token"]').val();
        
        if (district_value !== '') {
            $.ajax({
                type: "GET",
                url: "/users/get-thana-by-district-id",
                data: {
                    _token: _token,
                    districtId: district_value
                },
                beforeSend: function() {
                    $("#" + district_id).after('<span class="loading_data" id="loading_data_'+district_id+'">Loading...</span>');
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if (id == old_data) {
                                option += '<option value="' + id + '" selected>' + value + '</option>';
                            } else {
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $("#" + thana_div).html(option);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('error occurred !');
                },
                complete: function() {
                 $('#'+'loading_data_'+district_id).remove();
                },
            });
        } else {
            //console.log('Please select a valid district');
        }
    }
</script>