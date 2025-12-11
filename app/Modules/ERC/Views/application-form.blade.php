<?php
$accessMode = ACL::getAccsessRight('ERC');

?>


<style>
    .intl-tel-input .country-list {
        z-index: 5;
    }

    textarea {
        height: 60px !important;
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
        font-size: 14px;
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

    .btn-white {
        background-color: #ffffff !important;
        color: #6688a6 !important;
    }
</style>

<div class="col-md-12">
    @include('message.message')
</div>

<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h5><strong>Application for Genarel ERC </strong></h5>
        </div>

        {!! Form::open(array('url' => 'erc/store','method' => 'post', 'class' => 'form-horizontal', 'id' => 'erc',
        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
        <input type="hidden" name="selected_file" id="selected_file"/>
        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
        <input type="hidden" name="isRequired" id="isRequired"/>
        <?php $ercssion = Session::get('ercInfo')?>
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color:#564c4c;">Organization Information 2</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('organization_tin',' Organization TIN ',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_tin', Session::get('ercInfo.tin_number'),['class' => 'form-control input-md onlyNumber','id'=>'organization_tin','size'=>'5x1','maxlength'=>'200']) !!}
                                    {!! $errors->first('organization_tin','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('company_title',' Company Title ',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('company_title', null,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('company_title','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_name_bn',' Organization Name(Bangla) ',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_name_bn',Session::get('ercInfo.company_name_bn') ,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_name_bn','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('organization_add_en',' Organization Address (English) ',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('organization_add_en', isset($ercssion)?Session::get('ercInfo.office_address').' , '.Session::get('ercInfo.office_thana_name').' , '.Session::get('ercInfo.office_post_office').' , '.Session::get('ercInfo.office_district_name').' , '.Session::get('ercInfo.office_division_name'):"",['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_add_en','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_fax',' Organization Fax ',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_fax', Session::get('ercInfo.office_fax_no'),['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_fax','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('contact_person_name',' Contact Person Name',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('contact_person_name', Session::get('ercInfo.ceo_full_name'),['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('contact_person_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('division_name',' Division Name',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('division', [], '', $attributes = array('class'=>'form-control','data-rule-maxlength'=>'40',
                                         'id'=>"division","selected-value"=>isset($ercssion)?Session::get('ercInfo.office_division_name'):"", "placeholder" => "Select One")) !!}
                                    {!! $errors->first('division','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('district_name',' District Name',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('district',[], '', $attributes = array('class'=>'form-control search-box',
                                'data-rule-maxlength'=>'40','id'=>"district","selected-value"=>isset($ercssion)?Session::get('ercInfo.office_division_name'):"", 'placeholder' => 'Select Division First')) !!}
                                    {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_post_code',' Organization Post Code',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_post_code', Session::get('ercInfo.office_post_code'),['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_post_code','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('organization_email',' Organization Email',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_email',Session::get('ercInfo.office_email') ,['class' => 'form-control input-md email']) !!}
                                    {!! $errors->first('organization_email','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_name_en',' Organization Name(English)',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_name_en', Session::get('ercInfo.company_name'),['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_name_en','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_add_bn',' Organization Address (Bangla) ',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('organization_add_bn', null,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_add_bn','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_phone',' Organization Phone',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_phone',Session::get('ercInfo.office_telephone_no') ,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_mobile',' Organization Mobile',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_mobile', Session::get('ercInfo.office_mobile_no') ,['class' => 'form-control input-md ','placeholder' => '0171122344']) !!}
                                    {!! $errors->first('organization_mobile','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('contact_person_2',' Contact Person Phone',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('contact_person_2', Session::get('ercInfo.ceo_telephone_no'),['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('contact_person_2','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('holding_no',' Holding No',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('holding_no', null,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('holding_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('police_station',' Organization Police Station',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('police_station',  [],'', ['placeholder' => 'Select District First',
                                'class' => 'form-control input-md search-box',"selected-value"=>isset($ercssion)?Session::get('ercInfo.factory_thana_name'):"",'id'=>'thana']) !!}
                                    {!! $errors->first('police_station','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--                    first panel end--}}

                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Owner Information</h4>
                        </div>
                        <p style="font-weight:bold;margin-left:15px;text-decoration: underline;" id="personal_info">
                            Personal
                            Information</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('organization_type',' Organization type',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <select name="organization_type" class="form-control input-md"
                                            id="organization_type">
                                    </select>
                                    {!! $errors->first('organization_type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div id="personal">
                                <div class="col-md-12">
                                    {!! Form::label('nationality',' Nationality',['class'=>'text-left  col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        <select id="nationality" class="form-control input-md owner_req">
                                            <option value="">Select one</option>
                                            <option value="B" {{isset($ercssion) && Session::get('ercInfo.ceo_country_id') == 1 ? 'selected':''}}>Bangladeshi</option>
                                            <option value="F" {{isset($ercssion) && Session::get('ercInfo.ceo_country_id') != 1 ? 'selected':''}}>Foreigner</option>
                                        </select>
                                        {!! $errors->first('nationality','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12" id="owner_nid_or_pass">
                                    {!! Form::label('owner_nid_or_passport','NID/Passport/Birth Reg. Cert.',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {{--                                        {!! Form::text('owner_nid_or', null,['class' => 'form-control input-md ','id'=>'owner_nid_or_pass']) !!}--}}
                                        <input type="text" class="form-control input-md owner_req "
                                               value="{{Session::get('ercInfo.ceo_nid')}}" id="owner_nid_or_passport">
                                        {!! $errors->first('owner_nid_or_passport','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('owner_name','Name',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        <input type="text" class="form-control input-md owner_req "
                                               id="owner_name"
                                               value="{{Session::get('ercInfo.ceo_full_name')}}">
                                        {!! $errors->first('owner_name','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('owner_father_name',' Father Name ',['class'=>'text-left col-md-5']) !!}
                                    <div class="col-md-7">
                                        <input type="text" class="form-control input-md "
                                               value="{{Session::get('ercInfo.ceo_father_name')}}"
                                               id="owner_father_name">
                                        {!! $errors->first('owner_father_name','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('phone_number_office','Phone Number(Office)',['class'=>'text-left col-md-5']) !!}
                                    <div class="col-md-7">
                                        {{--                                        {!! Form::text('phone_number_office', null,['class' => 'form-control input-md ','id'=>'phone_number_office']) !!}--}}
                                        <input type="text" class="form-control input-md "
                                               value="{{Session::get('ercInfo.office_telephone_no')}}"
                                               id="phone_number_office">
                                        {!! $errors->first('phone_number_office','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('present_address',' Present Address',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {{--                                        {!! Form::textarea('present_address', null,['class' => 'form-control input-md ','id'=>'present_address']) !!}--}}
                                        <input type="text" class="form-control owner_req input-md "
                                               value="{{isset($ercssion) ? Session::get('ercInfo.ceo_address').' , '.Session::get('ercInfo.ceo_thana_name').' , '.Session::get('ercInfo.ceo_district_name').' , '.Session::get('ercInfo.ceo_post_code'):''}}"
                                               id="present_address">
                                        {!! $errors->first('present_address','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12" id="passport_no_div">
                                    {!! Form::label('passport_no','Passport No',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {{--                                        {!! Form::text('passport_no_nid', null,['class' => 'form-control input-md ','id'=>'passport_no_nid']) !!}--}}
                                        <input type="text" class="form-control input-md " value="{{isset($ercssion) ? Session::get('ercInfo.ceo_passport_no'):''}}"
                                               id="passport_no">
                                        {!! $errors->first('passport_no','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12" id="passport_expired_date_div">
                                    {!! Form::label('passport_expired_date',' Passport Expired Date',['class'=>'text-left col-md-5']) !!}
                                    <div class="col-md-7">
                                        <div class="currentDate input-group date">
                                            {{--                                            {!! Form::text('passport_expired_date', '',['class' => 'form-control  input-sm required']) !!}--}}
                                            <input type="text" class="form-control input-md "
                                                   id="passport_expired_date">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                        </div>
                                        {!! $errors->first('passport_expired_date','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12" id="part_district_name">
                                    {!! Form::label('part_district_name','District Name',['class'=>'text-left  col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {{--                                        {!! Form::select('part_district_name',  [],'', ['placeholder' => 'Select one',--}}
                                        {{--                                    'class' => 'form-control input-md','id'=>'district_name']) !!}--}}
                                        <select class="form-control input-md  owner_req search-box"
                                                id="district_name"></select>
                                        {!! $errors->first('part_district_name','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="col-md-12" id="incorpornum">
                                    {!! Form::label('incorporation_number','Incorporation Number',['class'=>'text-left col-md-5']) !!}
                                    <div class="col-md-7">
                                        {{--                                        {!! Form::text('incorporation_number', null,['class' => 'form-control input-md ']) !!}--}}
                                        <input type="text" class="form-control input-md "
                                               value="{{Session::get('ercInfo.inc_number')}}"
                                               id="incorporation_number">
                                        {!! $errors->first('incorporation_number','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="col-md-12" id="registrationnum">
                                    {!! Form::label('registration_number','Registration Number',['class'=>'text-left col-md-5']) !!}
                                    <div class="col-md-7">
                                        {{--                                        {!! Form::text('registration_number', null,['class' => 'form-control input-md ']) !!}--}}
                                        <input type="text" class="form-control input-md "
                                               id="registration_number">
                                        {!! $errors->first('registration_number','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="owner">
                            <div class="col-md-12">
                                {!! Form::label('owner_photo',' Owner Photo',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <input type="file" id="owner_image"
                                           class="required owner_req"
                                           onchange="ownerphoto(this)">
                                    {!! $errors->first('owner_photo','<span class="help-block">:message</span>') !!}
                                    <span style="font-size: 12px; font-weight: bold;color:#564c4c">Image size will not more than 200KB file extension should be jpg/jpeg/png</span>
                                    <div class="col-md-5" style="position:relative;">
                                        <img id="owner_photo_viewer"
                                             style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"
                                             src="{{(url('assets/images/no-image.png'))}}"
                                             alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('owner_tin',' TIN',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {{--                                    {!! Form::text('owner_tin', null,['class' => 'form-control input-md ','id'=>'owner_tin']) !!}--}}
                                    <input type="text" class="form-control input-md  owner_req onlyNumber"
                                           id="owner_tin">
                                    {!! $errors->first('owner_tin','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('designation','Designation',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <select class='form-control input-md owner_req' id='designation'>
                                    </select>
                                    {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('mother_name','Mother Name',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {{--                                    {!! Form::text('mother_name', null,['class' => 'form-control input-md ']) !!}--}}
                                    <input type="text" class="form-control input-md "
                                           value="{{ Session::get('ercInfo.ceo_mother_name')}}"
                                           id="mother_name">
                                    {!! $errors->first('mother_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('permanent_address',' Permanent Address',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {{--                                    {!! Form::textarea('permanent_address', null,['class' => 'form-control input-md ']) !!}--}}
                                    <input type="text" class="form-control input-md  owner_req"
                                           value="{{isset($ercssion) ? Session::get('ercInfo.ceo_address').' , '.Session::get('ercInfo.ceo_thana_name').' , '.Session::get('ercInfo.ceo_district_name').'-'.Session::get('ercInfo.ceo_post_code'):''}}"
                                           id="permanent_address">
                                    {!! $errors->first('permanent_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('mobile','Mobile',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {{--                                    {!! Form::text('mobile', null,['class' => 'form-control input-md ','id'=>'mobile']) !!}--}}
                                    <input type="text" class="form-control input-md "
                                           value="{{ Session::get('ercInfo.ceo_mobile_no')}}"
                                           id="mobile">
                                    {!! $errors->first('mobile','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12" id="incorporation">
                                {!! Form::label('incorporation_date','Incorporation Date',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {{--                                        {!! Form::text('incorporation_date', '',['class' => 'form-control  input-sm required']) !!}--}}
                                        <input type="text" class="form-control input-md "
                                               id="incorporation_date">
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('incorporation_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12" id="passport_issuing_country">
                                {!! Form::label('passport_issuing_country','Passport Issuing Country',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <select class="form-control input-md search-box "
                                            id="country"></select>
                                    {!! $errors->first('passport_issuing_country','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12" id="registration">
                                {!! Form::label('registration_date','Registration Date',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        <input type="text" class="form-control input-md "
                                               id="registration_date">
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('registration_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-offset-6 col-md-6" id="addowner" class="addownerclass">

                                <a href="javascript:void(0)" id="owner_save"
                                   class="btn btn-primary btn-xs btn-white btn-bold" style="">
                                    <i class="fa fa-plus"></i> Add New Person / Owner
                                </a>


                            </div>
                        </div>

                        <div class="col-md-12" id="owner_details" style="margin-top:15px;">
                            <table class="table table-responsive table-bordered table-condensed " id="ownerTable"
                                   style="display:none;">
                                <thead>
                                <tr style="background-color: #D9EDF7;">
                                    <th style="display:none;">ID</th>
                                    <th>Name</th>
                                    <th>Taxpayer Identification Number (TIN)</th>
                                    <th>NID</th>
                                    <th>Designation</th>
                                    <th>Mobile Number</th>
                                    <th>Office Phone</th>
                                    <th>Present Address</th>
                                    <th>District</th>
                                    <th class="text-center">Picture</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
                {{--                    second panel end--}}
                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Share Information %</h4>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('share_type','Share Type',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                <select name="share_type" class="form-control input-md" id="share_type">

                                </select>
                                {!! $errors->first('share_type','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6" id="doestic">
                            {!! Form::label('domestic_share','Domestic Share Percent',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::number('domestic_share', null, ['class' => 'form-control input-md ','min'=>0,'max'=>100,'id'=>'domestic_share']) !!}
                                {!! $errors->first('domestic_share','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6" id="foreign">
                            {!! Form::label('foreign_share','% of Foreign Share',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::number('foreign_share', null,['class' => 'form-control input-md ','min'=>0,'max'=>100,'id'=>'foreign_share']) !!}
                                {!! $errors->first('foreign_share','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Nominated bank Information</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('bank_name','Bank Name',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('bank_name',  [],'', ['placeholder' => 'Select Bank',
                              'class' => 'form-control input-md search-box', 'id' => 'bank_name',"selected-value"=>isset($ircInspectionInfo)?Session::get('ircInspectionInfo.bank_name'):""]) !!}
                                    {!! $errors->first('bank_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('branch_no','Branch Name',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('branch_no',  [],'', ['placeholder' => 'Select Bank First',
                              'class' => 'form-control input-md search-box', 'id' => 'branch_name',"selected-value"=>isset($ircInspectionInfo)?Session::get('ircInspectionInfo.branch_name'):""]) !!}
                                    {!! $errors->first('branch_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('branch_address','Branch Address',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('branch_address',isset($ircInspectionInfo)?Session::get('ircInspectionInfo.bank_address'):"",['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('branch_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--                    nominated bank information--}}
                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Trade License</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('trade_license_no','Trade License no',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('trade_license_no', Session::get('ircInspectionInfo.trade_licence_num'),['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('trade_license_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('trade_license_expired_date','Trade License Expired Date',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">

                                    <div class="currentDate input-group date">
                                        {!! Form::text('trade_license_expired_date', Session::get('ircInspectionInfo.trade_licence_validity_period')?date('d-M-Y', strtotime(Session::get('ircInspectionInfo.trade_licence_validity_period'))):'',['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('trade_license_expired_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('trade_license_address','Address',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('trade_license_address', null,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('trade_license_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('trade_license_issued_date','Trade License Issued Date',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('trade_license_issued_date', Session::get('ircInspectionInfo.trade_licence_issue_date')?date('d-M-Y', strtotime(Session::get('ircInspectionInfo.trade_licence_issue_date'))):'',['class' => 'form-control  input-sm required']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('trade_license_issued_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('trade_license_issued_by','Trade License Issued By',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('trade_license_issued_by',  $tl_issued_by,'', ['placeholder' => 'Select one',
                              'class' => 'form-control input-md']) !!}
                                    {!! $errors->first('trade_license_issued_by','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('business_type','Business Type',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('business_type', null,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('business_type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Information Of Chamber/Association</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('association_name','Chamber/Association Name',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('association_name',  [],'', ['placeholder' => 'Select one',
                          'class' => 'form-control input-md search-box']) !!}
                                    {!! $errors->first('association_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('serial_no_chamber','Serial No of Chamber Certificate',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('serial_no_chamber', isset($ircInspectionInfo)?Session::get('ircInspectionInfo.assoc_membership_number'):"",['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('serial_no_chamber','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('association_phone','Chamber/Association Phone',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('association_phone', null,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('association_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('validity_date','Validity Date',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <div class="currentDate input-group date">
                                        {!! Form::text('validity_date',Session::get('ircInspectionInfo.assoc_expire_date')?date('d-M-Y', strtotime(Session::get('ircInspectionInfo.assoc_expire_date'))):'',['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('validity_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('chamber_category','Chamber Category',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('chamber_category', null,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('chamber_category','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('certificate_issue_date','Issue Date',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('certificate_issue_date', Session::get('ircInspectionInfo.assoc_issuing_date')?date('d-M-Y', strtotime(Session::get('ircInspectionInfo.assoc_issuing_date'))):'',['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('certificate_issue_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('chamber_address','Chamber Address',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('chamber_address', null,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('chamber_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Export Slab</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('erc_slab','ERC Slab',['class'=>'text-left col-md-5 ']) !!}
                    <div class="col-md-7">
                        <select name="erc_slab" id="erc_slab" class="form-control input-md">
                        </select>
                        {!! $errors->first('erc_slab','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-md-6" id="reg_frees">
                    <table id="slab_table" class="table table-bordered table-condensed table-hover"
                           style="text-align: center;display:none;">
                        <thead>
                        <tr>
                            <td>Registration Slab (BDT)</td>
                            <td>Registration Fee (BDT)</td>
                            <td>Pass Book Fee (BDT)</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id="slab_data">

                        </tr>

                        <tr style="text-align:right;">
                            <td></td>
                            <td>Total</td>
                            <td>300</td>
                        </tr>
                        </tbody>

                    </table>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;margin-top:50px;">
                            <p style="color:red;margin-left:25px;">Note:(Registration Certificate & Renewal Book Have to
                                be Submitted)</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="showDocumentDiv">
                        </div>

                    </div>
                </div>

                {{--                    attachment file end--}}

                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Undertaking</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group {{$errors->has('acceptTerms') ? 'has-error' : ''}}">
                        <div style="margin-left: 20px">
                            <input id="acceptTerms-2" name="acceptTerms" type="checkbox"
                                   class="required col-md-1 text-left" style="width:3%;">
                            <label for="acceptTerms-2" class="col-md-11 text-left ">
                                I do hereby decleare that the information relating to me/my firm furnished above and the
                                documents attached herewith are correct.if the information furnished above ans thr
                                documents attached herewith are found to be false or obtained through
                                fraud/forgery/misdeclaration etc.then I and my firm will be held liable for that and the
                                authority may
                                take any legal action against me and my firm including cancellation of
                                certificate/permit.</label>

                            <div class="clearfix"></div>
                            {!! $errors->first('acceptTerms','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-left: 49px;color:red;">
                        <p>আপনি যদি Draft চেক বক্সে ক্লিক করেন ,তাহলে পুনঃরায় অপূরণীয় তথ্য গুলো আপডেট করুন এবং Update &
                            save বাটনে ক্লিক করুন ।</p>
                        <p>Note:যদি Draft Save বাটনে ক্লিক করেন ,তাহলে এপ্লিকেশন টি প্রোসেসিং এর জন্য জমা হবে না ।</p>
                    </div>

                </div>
                {{--                    condition--}}
            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                            value="draft" name="actionBtn">Save as Draft
                    </button>
                </div>
                <div class="pull-right">
                    {{--                    @if(ACL::getAccsessRight('user','A'))--}}
                    <button type="submit" id="submitForm" class="btn btn-block btn-sm btn-primary" value="Submit"
                            name="actionBtn">
                        <b>Submit</b></button>
                    {{--                    @endif--}}
                </div>
                <div class="clearfix"></div>
            </div>
        </div> <!--/panel-body-->

        {!! Form::close() !!}
    </div>
</div>
@include('ERC::erc-scripts_add')
