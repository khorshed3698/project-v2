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
            <h5><strong>Industrial IRC (First Adhoc) Information</strong></h5>
        </div>

        {!! Form::open(array('url' => 'industrial-IRC/store','method' => 'post', 'class' => 'form-horizontal', 'id' => 'industrial-irc',
        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
        <input type="hidden" name="selected_file" id="selected_file"/>
        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
        <input type="hidden" name="isRequired" id="isRequired"/>
        {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}
        {!! Form::hidden('curr_process_status_id', $appInfo->status_id,['class' => 'form-control input-md required', 'id'=>'process_status_id']) !!}

        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color:#564c4c;">Organization Information</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('organization_tin',' Organization TIN ',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_tin', $appData->organization_tin,['class' => 'form-control input-md onlyNumber','id'=>'organization_tin','size'=>'5x1','maxlength'=>'200']) !!}
                                    {!! $errors->first('organization_tin','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('company_title',' Company Title ',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('company_title', $appData->company_title,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('company_title','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_name_bn',' Organization Name(Bangla) ',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_name_bn', $appData->organization_name_bn,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_name_bn','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_add_en',' Organization Address (English) ',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('organization_add_en', $appData->organization_add_en,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_add_en','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('factory_add_en',' Factory Address (English) ',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('factory_add_en', $appData->factory_add_en,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('factory_add_en','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_fax',' Organization Fax ',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_fax', $appData->organization_fax,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_fax','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('contact_person_name',' Contact Person Name',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('contact_person_name', $appData->contact_person_name,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('contact_person_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('division_name',' Division Name',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('division', [], '', ['class'=>'form-control','data-rule-maxlength'=>'40',
                                         'id'=>'division', 'placeholder' => 'Select One']) !!}
                                    {!! $errors->first('division','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('district_name',' District Name',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('district',[], '', $attributes = array('class'=>'form-control',
                                'data-rule-maxlength'=>'40','id'=>"district", 'placeholder' => 'Select Division First')) !!}
                                    {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_post_code',' Organization Post Code',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_post_code', $appData->organization_post_code,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_post_code','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('organization_email',' Organization Email',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_email', $appData->organization_email,['class' => 'form-control email input-md']) !!}
                                    {!! $errors->first('organization_email','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_name_en',' Organization Name(English)',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_name_en', $appData->organization_name_en,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_name_en','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_add_bn',' Organization Address (Bangla) ',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('organization_add_bn', $appData->organization_add_bn,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_add_bn','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('factory_add_bn',' Factory Address (Bangla) ',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('factory_add_bn', $appData->factory_add_bn,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('factory_add_bn','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_phone',' Organization Phone',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_phone', $appData->organization_phone,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('organization_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('organization_mobile',' Organization Mobile',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_mobile', $appData->organization_mobile,['class' => 'form-control input-md ','placeholder' => '0171122344']) !!}
                                    {!! $errors->first('organization_mobile','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('contact_person_2',' Contact Person Phone',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('contact_person_2', $appData->contact_person_2,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('contact_person_2','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('holding_no',' Holding No',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('holding_no', $appData->holding_no,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('holding_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('police_station',' Organization Police Station',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('police_station',  [],'', ['placeholder' => 'Select District First',
                                'class' => 'form-control input-md search-box','id'=>'police_station']) !!}
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
                        <p style="font-weight:bold;margin-left:15px;text-decoration: underline;">Personal
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

                                    {!! Form::label('nationality',' Nationality',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        <select id="nationality" class="form-control input-md owner_req">
                                            <option value="">Select one</option>
                                            <option value="B">Bangladeshi</option>
                                            <option value="F">Foreigner</option>
                                        </select>
                                        {!! $errors->first('nationality','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12" id="owner_nid_or_pass">
                                    {!! Form::label('owner_nid_or_passport','NID/Passport/Birth Reg. Cert.',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        <input type="text" class="form-control input-md  owner_req"
                                               id="owner_nid_or_passport">
                                        {!! $errors->first('owner_nid_or_passport','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('owner_name','Name',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        <input type="text" class="form-control input-md owner_req "
                                               id="owner_name">
                                        {!! $errors->first('owner_name','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('owner_father_name',' Father Name ',['class'=>'text-left col-md-5']) !!}
                                    <div class="col-md-7">
                                        <input type="text" class="form-control input-md "
                                               id="owner_father_name">
                                        {!! $errors->first('owner_father_name','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('phone_number_office','Phone Number(Office)',['class'=>'text-left col-md-5']) !!}
                                    <div class="col-md-7">
                                        {{--                                        {!! Form::text('phone_number_office', null,['class' => 'form-control input-md ','id'=>'phone_number_office']) !!}--}}
                                        <input type="text" class="form-control input-md "
                                               id="phone_number_office">
                                        {!! $errors->first('phone_number_office','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('present_address',' Present Address',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        <input type="text" class="form-control input-md  owner_req"
                                               id="present_address">
                                        {!! $errors->first('present_address','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12" id="passport_no_div">
                                    {!! Form::label('passport_no','Passport No',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {{--                                        {!! Form::text('passport_no_nid', null,['class' => 'form-control input-md ','id'=>'passport_no_nid']) !!}--}}
                                        <input type="text" class="form-control input-md "
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
                                    {!! Form::label('part_district_name','  District Name',['class'=>'text-left col-md-5 ']) !!}
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
                                    <input type="text" class="form-control input-md  owner_req"
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
                                           id="mother_name">
                                    {!! $errors->first('mother_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('permanent_address',' Permanent Address',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {{--                                    {!! Form::textarea('permanent_address', null,['class' => 'form-control input-md ']) !!}--}}
                                    <input type="text" class="form-control input-md  owner_req"
                                           id="permanent_address">
                                    {!! $errors->first('permanent_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('mobile','Mobile',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {{--                                    {!! Form::text('mobile', null,['class' => 'form-control input-md ','id'=>'mobile']) !!}--}}
                                    <input type="text" class="form-control input-md "
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
                                    {{--                                    {!! Form::select('passport_issuing_country',[],'', ['placeholder' => 'Select one',--}}
                                    {{--                                 'class' => 'form-control input-md','id'=>'country']) !!}--}}
                                    <select class="form-control input-md search-box"
                                            id="country"></select>
                                    {!! $errors->first('passport_issuing_country','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12" id="registration">
                                {!! Form::label('registration_date','Registration Date',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {{--                                        {!! Form::text('registration_date', '',['class' => 'form-control  input-sm required']) !!}--}}
                                        <input type="text" class="form-control input-md "
                                               id="registration_date">
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('registration_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-offset-6 col-md-6" id="addowner" class="addownerclass">

                                <a href="javascript:void(0)" id="ow_save"
                                   class="btn btn-primary btn-xs btn-white btn-bold" style="">
                                    <i class="fa fa-plus"></i> Add New Person / Owner
                                </a>
                                <input type="hidden" id="arraykey" value="">
                                <a href="javascript:void(0)" id="owner_edit"
                                   class="btn btn-primary btn-xs btn-white btn-bold" style="display:none;">
                                    <i class="fa fa-plus"></i> Edit Owner
                                </a>
                            </div>
                        </div>

                        <div class="col-md-12" id="owner_details" style="margin-top:15px;">
                            <table class="table table-responsive table-bordered table-condensed " id="ownerTable"
                                   style="<?php if (isset($appData->owner_name)) { ?>
                                           display: block;
                                   <?php } else { ?>
                                           display: none;
                                   <?php } ?>">
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
                                <?php if(isset($appData->nationality)){ ?>
                                @foreach($appData->nationality as $key=>$nationality)

                                    <tr class="ownerRow" id="owner_row_id_{{$key}}">
                                        <td style="display:none;"><input name="nationality[]"
                                                                         value="{{isset($appData->nationality[$key]) ? $appData->nationality[$key] : ''}}"
                                                                         hidden></td>
                                        <td style="display:none;"><input name="owner_father_name[]"
                                                                         value="{{isset($appData->owner_father_name[$key]) ? $appData->owner_father_name[$key] : ''}}"
                                                                         hidden></td>
                                        <td style="display:none;"><input name="passport_no[]"
                                                                         value="{{isset($appData->passport_no[$key]) ? $appData->passport_no[$key] : ''}}"
                                                                         hidden></td>
                                        <td style="display:none;"><input name="passport_expired_date[]"
                                                                         value="{{isset($appData->passport_expired_date[$key]) ? $appData->passport_expired_date[$key] : ''}}"
                                                                         hidden>
                                        </td>
                                        <td style="display:none;"><input name="incorporation_number[]"
                                                                         value="{{isset($appData->incorporation_number[$key]) ? $appData->incorporation_number[$key] : ''}}"
                                                                         hidden>
                                        </td>
                                        <td style="display:none;"><input name="registration_number[]"
                                                                         value="{{isset($appData->registration_number[$key]) ? $appData->registration_number[$key] : ''}}"
                                                                         hidden>
                                        </td>
                                        <td style="display:none;"><input name="mother_name[]"
                                                                         value="{{isset($appData->mother_name[$key]) ? $appData->mother_name[$key] : ''}}"
                                                                         hidden></td>
                                        <td style="display:none;"><input name="permanent_address[]"
                                                                         value="{{isset($appData->permanent_address[$key]) ? $appData->permanent_address[$key] : ''}}"
                                                                         hidden></td>
                                        <td style="display:none;"><input name="incorporation_date[]"
                                                                         value="{{isset($appData->incorporation_date[$key]) ? $appData->incorporation_date[$key] : ''}}"
                                                                         hidden></td>

                                        <td style="display:none;"><input name="country[]"
                                                                         value="{{isset($appData->country[$key]) ? $appData->country[$key] : ''}}"
                                                                         hidden></td>

                                        <td style="display:none;"><input name="registration_date[]"
                                                                         value="{{isset($appData->registration_date[$key]) ? $appData->registration_date[$key] : ''}}"
                                                                         hidden></td>
                                        <td><input name="owner_name[]" value="{{$appData->owner_name[$key]}}"
                                                   hidden>{{$appData->owner_name[$key]}}</td>
                                        <td><input name="owner_tin[]"
                                                   value="{{$appData->owner_tin[$key]}}"
                                                   hidden>{{$appData->owner_tin[$key]}}</td>
                                        <td><input name="owner_nid_or_passport[]"
                                                   value="{{$appData->owner_nid_or_passport[$key]}}"
                                                   hidden>
                                            <?php
                                            if ($appData->owner_nid_or_passport[$key] != '') {
                                                echo $appData->owner_nid_or_passport[$key];
                                            }
                                            ?>

                                        </td>
                                        <td><input name="designation[]" value="{{$appData->designation[$key]}}"
                                                   hidden>
                                            <?php
                                            if ($appData->designation[$key] != '') {
                                                $des = $appData->designation[$key];
                                                $designation = explode('@', $des);
                                                if (isset($designation[1])) {
                                                    echo $designation[1];
                                                } else {
                                                    echo null;
                                                }
                                            }
                                            ?>

                                        </td>
                                        <td><input name="mobile[]" value="{{$appData->mobile[$key]}}"
                                                   hidden>{{$appData->mobile[$key]}}</td>
                                        <td><input name="phone_number_office[]"
                                                   value="{{$appData->phone_number_office[$key]}}"
                                                   hidden>{{$appData->phone_number_office[$key]}}</td>
                                        <td><input name="present_address[]"
                                                   value="{{isset($appData->present_address[$key]) ? $appData->present_address[$key] : ''}}"
                                                   hidden>{{isset($appData->present_address[$key]) ? $appData->present_address[$key] : 'Null'}}
                                        </td>
                                        <td><input name="district_name[]"
                                                   value="{{isset($appData->district_name[$key]) ? $appData->district_name[$key] : ''}}"
                                                   hidden>
                                            <?php
                                            if ($appData->district_name[$key] != '') {
                                                $var = $appData->district_name[$key];
                                                $district = explode('@', $var);
                                                echo $district[1];
                                            }
                                            ?>
                                        </td>


                                        <td><input name="owner_photo[]"
                                                   value="{{isset($appData->owner_photo[$key]) ? $appData->owner_photo[$key] : ''}}"
                                                   hidden>
                                            <img src="{{isset($appData->owner_photo[$key]) ? $appData->owner_photo[$key] : 'Null'}}"
                                                 height="80px"/></td>
                                        <td>
                                            <button class="btn btn-minier btn-info"
                                                    onclick="editOwnership({{$key}})"
                                                    type="button"><i
                                                        class="fa fa-trash"></i> Edit
                                            </button>
                                            <button class="btn btn-minier btn-danger ownership"
                                                    type="button"><i
                                                        class="fa fa-trash"></i> Delete
                                            </button>

                                        </td>

                                    </tr>
                                @endforeach
                                <?php } ?>
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
                                {!! Form::number('domestic_share', isset($appData->domestic_share) ? $appData->domestic_share : '',['class' => 'form-control input-md ','min'=>0,'max'=>100,'id'=>'domesticshare']) !!}
                                {!! $errors->first('domestic_share','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6" id="foreign">
                            {!! Form::label('foreign_share','% of Foreign Share',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::number('foreign_share', isset($appData->foreign_share) ? $appData->foreign_share : '',['class' => 'form-control input-md ','min'=>0,'max'=>100,'id'=>'foreignshare']) !!}
                                {!! $errors->first('foreign_share','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Others Information</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('industrial_sector_name','Industrial Sector Name',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('industrial_sector_name', $appData->industrial_sector_name,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('industrial_sector_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('fire_license_number',' Fire License Number',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('fire_license_number', $appData->fire_license_number,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('fire_license_number','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('environment_license_number','Environment License Number',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('environment_license_number', $appData->environment_license_number,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('environment_license_number','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('registration_number1','Incorporation/Registration Number',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('registration_number1', $appData->registration_number1,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('registration_number1','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('bond_license_number','Bond License Number',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('bond_license_number', $appData->bond_license_number,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('bond_license_number','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('recommendation_number','Recommendation Number',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('recommendation_number', $appData->recommendation_number,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('recommendation_number','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('industrial_sponsor_rg_no','Industrial Sponsor Registration Number',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('industrial_sponsor_rg_no', $appData->industrial_sponsor_rg_no,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('industrial_sponsor_rg_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('half_yearly_import',' Half Yearly Import Entitlement',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7 ">
                                    <div class="input-group">
                                        {!! Form::text('half_yearly_import', $appData->half_yearly_import,['class' => 'form-control ','placeholder' =>'Numeric value or "Unlimited"']) !!}
                                        <span class="input-group-addon">BDT</span>
                                    </div>
                                    {!! $errors->first('half_yearly_import','<span class="help-block">:message</span>') !!}
                                    <span style="font-size: 12px; font-weight: bold;color:#564c4c">You can write any amount or "Unlimited".</span>

                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('fire_license_date',' Fire License Date',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('fire_license_date', $appData->fire_license_date,['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('fire_license_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('environment_license_date','Environment License Date',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('environment_license_date', $appData->environment_license_date,['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('environment_license_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('registration_date1','Incorporation/Registration Date',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('registration_date1', $appData->registration_date1,['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('registration_date1','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('bond_license_date','Bond License Date',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('bond_license_date', $appData->bond_license_date,['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('bond_license_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('recommendation_date','Recommendation Date',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('recommendation_date', $appData->recommendation_date,['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('recommendation_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('industrial_sponsor_rg_date','Industrial Sponsor Registration Date',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('industrial_sponsor_rg_date', $appData->industrial_sponsor_rg_date,['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('industrial_sponsor_rg_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                {{--                        Third panel end--}}
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <h5 style="font-weight:bold;margin-left:15px;text-decoration: underline;">Yearly Capacity
                                Information</h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('sponsor_name','Sponsor Name',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('sponsor_name', $appData->sponsor_name,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('sponsor_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('production_capacity','Production Capacity',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('fire_license_number', $appData->fire_license_number,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('fire_license_number','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('production_start_date','Production Start Date',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    <div class="datepickerall input-group date">
                                        {!! Form::text('production_start_date', $appData->production_start_date,['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('production_start_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('inspection_date','Inspection Date',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('inspection_date', $appData->inspection_date,['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('inspection_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('yearly_production_capacity','Yearly Production Capacity',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <div class="input-group" style="width: 100%;">
                                        {!! Form::text('yearly_production_capacity', $appData->yearly_production_capacity,['class' => 'form-control input-md ']) !!}
                                        {!! $errors->first('yearly_production_capacity','<span class="help-block">:message</span>') !!}
                                        <span class="input-group-btn" style="width: 0px;"></span>
                                        {!! Form::select('ypc_unit',[],'', ['placeholder' => 'Unit',
                                             'class' => 'form-control','id'=>'ypc_unit']) !!}
                                        {!! $errors->first('ypc_unit','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('total_number_of_labour','Total Number of Labour',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('total_number_of_labour', $appData->total_number_of_labour,['class' => 'form-control input-md ']) !!}
                                        {!! $errors->first('total_number_of_labour','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('imported_spare_parts','Imported Spare Parts',['class'=>'text-left col-md-5']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('imported_spare_parts', $appData->imported_spare_parts,['class' => 'form-control input-md ']) !!}
                                        {!! $errors->first('imported_spare_parts','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('inspector_name','Inspector Name',['class'=>'text-left col-md-5']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('inspector_name', $appData->inspector_name,['class' => 'form-control input-md ']) !!}
                                        {!! $errors->first('inspector_name','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--                   yearly panel end--}}
                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h5 style="font-weight:bold;margin-left:15px;text-decoration: underline;">Half Yearly
                                    Raw
                                    material Adhoc Information</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    {!! Form::label('raw_material_percentage','Raw material Production Percentage',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('raw_material_percentage', $appData->raw_material_percentage,['class' => 'form-control input-md ']) !!}
                                        {!! $errors->first('raw_material_percentage','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('half_yearly_production_demand','Half Yearly Production Demand',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('half_yearly_production_demand', $appData->half_yearly_production_demand,['class' => 'form-control input-md ']) !!}
                                        {!! $errors->first('half_yearly_production_demand','<span class="help-block">:message</span>') !!}

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    {!! Form::label('half_yearly_production_capacity','Half Yearly Production Capacity',['class'=>'text-left col-md-5']) !!}
                                    <div class="col-md-7">
                                        <div class="input-group" style="width: 100%;">
                                            {!! Form::text('half_yearly_production_capacity', $appData->half_yearly_production_capacity,['class' => 'form-control ']) !!}
                                            {!! $errors->first('half_yearly_production_capacity','<span class="help-block">:message</span>') !!}
                                            <span class="input-group-btn" style="width: 0px;"></span>
                                            {!! Form::select('hypc_unit',[],'', ['placeholder' => 'Unit',
                                               'class' => 'form-control','id'=>'hypc_unit']) !!}
                                            {!! $errors->first('hypc_unit','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                {{--                  half yearly panel end--}}
                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Adhoc Items</h4>
                        </div>
                    </div>
                </div>
                <div class="row adhoc">
                    <div class="col-md-12">
                        Machineries/Materials এর ইনফরমেশন add করার জন্য "Add Adhoc Item" button এ ক্লিক করুন |একাধিক
                        Item এর জন্য পুনরায় "Add Adhoc Item" button এ ক্লিক করুন |
                    </div>
                    <div class="alert alert-info" id="item_message" style="display:none;">
                        No Item has been added yet.
                    </div>

                    <div class="col-md-12" id="adhocItemsContainer">
                        <table style="margin-left: -12px; <?php if (isset($appData->description_item)) { ?>
                                display: block;
                        <?php } else { ?>
                                display: none;
                        <?php } ?>"
                               class="table table-bordered table-responsive table-condensed table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">Sl.No</th>
                                <th class="text-center">Item Type</th>
                                <th class="text-center">Description of Item</th>
                                <th class="text-center">Issue Date</th>
                                <th class="text-center">H.S Code</th>
                                <th class="text-center">Value</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Total Price</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php if(isset($appData->description_item)){ ?>
                            @foreach($appData->description_item as $key=>$desc_item)

                                <tr class="adhocRow">
                                    <td>{{$key+1}}</td>
                                    <td><input name="item_type[]" value="{{$appData->item_type[$key]}}"
                                               hidden>{{$appData->item_type[$key]}}</td>
                                    <td><input name="description_item[]"
                                               value="{{$appData->description_item[$key]}}"
                                               hidden>{{$appData->description_item[$key]}}</td>
                                    <td><input name="issue_date[]" value="{{$appData->issue_date[$key]}}"
                                               hidden>{{$appData->issue_date[$key]}}</td>
                                    <td><input name="hs_code[]" value="{{$appData->hs_code[$key]}}"
                                               hidden>{{$appData->hs_code[$key]}}</td>
                                    <td><input name="item_value[]" value="{{$appData->item_value[$key]}}"
                                               hidden>{{$appData->item_value[$key]}}</td>
                                    <td><input name="unit_price_no[]" value="{{$appData->unit_price_no[$key]}}"
                                               hidden><input name="unit_price[]"
                                                             value="{{$appData->unit_price[$key]}}"
                                                             hidden>({{$appData->unit_price[$key]}}
                                        ) {{$appData->unit_price_no[$key]}}</td>
                                    <td><input name="quantity_type[]" value="{{$appData->quantity_type[$key]}}"
                                               hidden><input name="quantity[]" value="{{$appData->quantity[$key]}}"
                                                             hidden>({{$appData->quantity_type[$key]}}
                                        ) {{$appData->quantity[$key]}}
                                    </td>
                                    <td><input type="hidden" class="total_price" name="total_price[]"
                                               value="{{$appData->total_price[$key]}}">{{$appData->total_price[$key]}}
                                    </td>
                                    <td>
                                        <button class="btn btn-minier btn-danger deleteAdhocItem" type="button"><i
                                                    class="fa fa-trash"></i> Delete
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                            <?php } ?>
                            </tbody>
                            <tfoot>

                            <tr>
                                <td colspan="8" class="text-right"><b>Grand Total</b></td>
                                <td class="text-right"><input type="text" id="grand_total" name="grand_total"
                                                              value="{{$appData->grand_total}}"
                                                              class="form-control input-md"
                                                              readonly></td>
                                <td>&nbsp;</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <a href="" class="btn col-md-2 btn-sm btn-danger" data-toggle="modal"
                       data-target="#modalContactForm">+Add Adhoc Item</a>

                    <div class="modal fade" id="modalContactForm" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title w-150 font-weight-bold">Add Adhoc Item</h4>
                                </div>
                                <div class="modal-body">

                                    <div class="col-md-12">
                                        <div class="form-group form-group-md">
                                            <label for="description_of_item" class="control-label required-star">Description
                                                of
                                                Item</label>
                                            {{--                                            {!! Form::textarea('description_item', null,['class' => 'form-control','id'=>'form34']) !!}--}}
                                            <textarea class="form-control" id="description_item"></textarea>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label for="item_type">Item
                                                Type</label>
                                            {{--                                            {!! Form::select('item_type',  ['1'=>'Machinaries','2'=>'Materials'],'', ['placeholder' => 'Select one',--}}
                                            {{--                                            'class' => 'form-control input-sm validate required-star','id'=>'item_type']) !!}--}}
                                            <select class="form-control input-sm validate required-star" id="item_type">

                                            </select>

                                        </div>
                                        <div class="col-md-4">

                                            <label data-error="wrong" data-success="right" for="form29">H.S
                                                Code</label>
                                            {{--                                            {!! Form::text('hs_code[]', null,['class' => 'form-control input-sm ','id'=>'hs_code']) !!}--}}
                                            <input type="text" class="form-control input-sm" id="hs_code">
                                        </div>
                                        <div class="col-md-4">

                                            <label data-error="wrong" data-success="right" for="form29">Issue
                                                Date</label>
                                            <div class="col-md-12 datepicker input-group date">
                                                {{--                                                {!! Form::text('issue_date', '',['class' => 'form-control input-sm required','id'=>'issue_date']) !!}--}}
                                                <input type="text" class="form-control input-sm"
                                                       id="issue_date">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4">

                                            <label data-error="wrong" data-success="right"
                                                   for="form29">Quantity</label>
                                            <div class="input-group" style="width: 100%;">
                                                {{--                                                {!! Form::text('quantity', null,['class' => 'form-control input-sm  onlyNumber','id'=>'quantity']) !!}--}}
                                                <input type="text" class="form-control input-sm  onlyNumber"
                                                       id="quantity">
                                                <span class="input-group-btn" style="width: 0;"></span>
                                                {{--                                                {!! Form::select('quantity_type',  [],'', ['class'=>'form-control input-sm','placeholder' => 'Unit','id'=>'quantity_type']) !!}--}}
                                                <select class="form-control input-sm search-box"
                                                        id="quantity_type"></select>
                                            </div>

                                        </div>
                                        <div class="col-md-4">

                                            <label data-error="wrong" data-success="right"
                                                   for="form29">Unit Price</label>
                                            <div class="input-group" style="width: 100%;">
                                                {{--                                                {!! Form::select('unit_price',  ['1'=>'USD','2'=>'EURO'],'', ['class'=>'form-control input-sm onlyNumber','placeholder' => 'Currency','id'=>'unit_price']) !!}--}}
                                                <select class="form-control input-sm onlyNumber"
                                                        id="unit_price">
                                                    <option value="Currency">Currency</option>
                                                    <option value="BDT">BDT</option>
                                                    <option value="Dollar">Dollar</option>
                                                </select>

                                                <span class="input-group-btn" style="width: 0;"></span>
                                                {{--                                                {!! Form::text('unit_price_no', null,['class' => 'form-control input-sm  onlyNumber','id'=>'unit_price_no']) !!}--}}
                                                <input type="text" class="form-control input-sm  onlyNumber"
                                                       id="unit_price_no">
                                            </div>


                                        </div>
                                        <div class="col-md-4">
                                            <label data-error="wrong" data-success="right"
                                                   for="form29">Value</label>
                                            {{--                                            {!! Form::text('item_value', null,['class' => 'form-control input-sm onlyNumber','id'=>'item_value']) !!}--}}
                                            <input type="text" class="form-control input-sm onlyNumber" id="item_value">
                                        </div>
                                    </div>


                                    <div class="modal-footer d-flex">
                                        <button class="btn btn-primary" id="additem" type="button">Add Item</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{--                    add adhoc item end--}}
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
                              'class' => 'form-control input-md search-box', 'id' => 'bank_name']) !!}
                                    {!! $errors->first('bank_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('branch_no','Branch Name',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('branch_no',  [],'', ['placeholder' => 'Select Bank First',
                              'class' => 'form-control input-md search-box', 'id' => 'branch_name']) !!}
                                    {!! $errors->first('branch_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('branch_address','Branch Address',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('branch_address', $appData->branch_address,['class' => 'form-control input-md ']) !!}
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
                                    {!! Form::text('trade_license_no', $appData->trade_license_no,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('trade_license_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('trade_license_expired_date','Trade License Expired Date',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <div class="currentDate input-group date">
                                        {!! Form::text('trade_license_expired_date', $appData->trade_license_expired_date,['class' => 'form-control  input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('trade_license_expired_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('trade_license_address','Address',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('trade_license_address', $appData->trade_license_address,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('trade_license_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('trade_license_issued_date','Trade License Issued Date',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('trade_license_issued_date', $appData->trade_license_issued_date,['class' => 'form-control  input-sm required']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('trade_license_issued_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('trade_license_issued_by','Trade License Issued By',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('trade_license_issued_by',  $tl_issued_by,$appData->trade_license_issued_by, ['placeholder' => 'Select one',
                              'class' => 'form-control input-md']) !!}
                                    {!! $errors->first('trade_license_issued_by','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('business_type','Business Type',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('business_type', $appData->business_type,['class' => 'form-control input-md ']) !!}
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
                                    {!! Form::select('association_name',  [],$appData->association_name, ['placeholder' => 'Select one',
                          'class' => 'form-control input-md search-box']) !!}
                                    {!! $errors->first('association_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('serial_no_chamber','Serial No of Chamber Certificate',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('serial_no_chamber', $appData->serial_no_chamber,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('serial_no_chamber','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('association_phone','Chamber/Association Phone',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('association_phone', $appData->association_phone,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('association_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('validity_date','Validity Date',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <div class="currentDate input-group date">
                                        {!! Form::text('validity_date', $appData->validity_date,['class' => 'form-control  input-sm']) !!}
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
                                    {!! Form::text('chamber_category', $appData->chamber_category,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('chamber_category','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('certificate_issue_date','Issue Date',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('certificate_issue_date', $appData->certificate_issue_date,['class' => 'form-control input-sm']) !!}
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    {!! $errors->first('certificate_issue_date','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                {!! Form::label('chamber_address','Chamber Address',['class'=>'text-left col-md-5 ']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('chamber_address', $appData->chamber_address,['class' => 'form-control input-md ']) !!}
                                    {!! $errors->first('chamber_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                            <h4 style="margin-left:15px; color: #564c4c;">Import Slab</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('irc_slab','IRC Slab',['class'=>'text-left col-md-5 ']) !!}
                    <div class="col-md-7">
                        <select name="irc_slab" id="irc_slab" class="form-control input-md">
                        </select>
                        {!! $errors->first('irc_slab','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-md-6" id="reg_frees">
                    <table class="table table-bordered table-condensed table-hover"
                           style="text-align: center; display:none;">
                        <thead>
                        <tr>
                            <td>Registration Slab (BDT)</td>
                            <td>Registration Fee (BDT)</td>
                            <td>Pass Book Fee (BDT)</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>100000000</td>
                            <td>200</td>
                            <td>100</td>
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
                            <h5 style="margin-left:25px;">Attach Document (Maximum File Size will be 2MB.Scan এর সময় DPI
                                100 এবং color এ ফাইল Scan করতে হবে। ফাইল JPG/PDF ফরম্যাট এ save হবে )</h5>
                            <p style="color:red;margin-left:25px;">Note:(Registration Certificate & Renewal Book
                                Have to
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
                                   <?php if(isset($appData->acceptTerms) && $appData->acceptTerms == 'on') { ?>
                                   checked <?php } ?> class="required col-md-1 text-left" style="width:3%;">
                            <label for="acceptTerms-2" class="col-md-11 text-left ">
                                I do hereby decleare that the information relating to me/my firm furnished above and
                                the
                                documents attached herewith are correct.if the information furnished above ans thr
                                documents attached herewith are found to be false or obtained through
                                fraud/forgery/misdeclaration etc.then I and my firm will be held liable for that and
                                the
                                authority may
                                take any legal action against me and my firm including cancellation of
                                certificate/permit.</label>

                            <div class="clearfix"></div>
                            {!! $errors->first('acceptTerms','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-left: 49px;color:red;">
                        <p>আপনি যদি Draft চেক বক্সে ক্লিক করেন ,তাহলে পুনঃরায় অপূরণীয় তথ্য গুলো আপডেট করুন এবং
                            Update &
                            save বাটনে ক্লিক করুন ।</p>
                        <p>Note:যদি Draft Save বাটনে ক্লিক করেন ,তাহলে এপ্লিকেশন টি প্রোসেসিং এর জন্য জমা হবে না
                            ।</p>
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

<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $(".search-box").select2();
        $('#submitForm').on('click', function (e) {

            var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length)

            if (ownerRowNumber === 0) {
                $("form#industrial-irc").validate();
                $('#owner_save').removeClass('btn-primary btn-white').addClass('btn-danger btn-red').focus()
                alert('Please add at least one owner!!')
                if (ownerSectionvalidate() === true) {
                    return false;
                }

            } else {
                $(".owner_req").removeClass('required')
                $("form#industrial-irc").validate({
                    ignore: ".ignore, .owner_req"
                })
            }
        })

        $('#save_as_draft').on('click', function (e) {
            var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length)
            if (ownerRowNumber === 0) {
                $('#organization_type').focus()
                $('#owner_save').removeClass('btn-primary btn-white').addClass('btn-danger btn-red').focus()
                alert('Please add at least one owner!!')
                return false;
            }
        });
    })

    function ownerphoto(input) {

        if (input.files && input.files[0]) {
            var MatLogo = input.id;
            var counter = MatLogo.split("_")[2];
            var mime_type = input.files[0].type;
            var fileSize = input.files[0].size;
            if ((mime_type !== 'image/jpeg' || mime_type !== 'image/jpg' || mime_type !== 'image/png') && (fileSize > 200000)) {
                alert('File size cannot be over 200 KB and file extension should be only jpg, jpeg and png');
                $("#" + MatLogo).val('');
                return false;
            } else {
                var reader = new FileReader();
                reader.onload = function (e) {

                    if (!isNaN(counter)) {
                        var owner_photo_viewer = "owner_photo_viewer_" + counter;
                        $("#" + owner_photo_viewer).attr('src', e.target.result);
                    } else {
                        $("#owner_photo_viewer").attr('src', e.target.result);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    }


    $(document).ready(function () {
        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            maxDate: (new Date()),
            minDate: '01/01/' + (yyyy - 100),
            useCurrent: false
        });

        $('.datepickerall').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 100),
            useCurrent: false
        });


        /* Date must should be minimum today */
        $('.currentDate').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            useCurrent: false
        });
    });

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

    $(document).ready(function () {

        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "agent-id",
                value: "{{$agent}}"
            },
        ];

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/industrial-IRC/ccie/get-refresh-token';
            $('#division').keydown();
            // $("#district").trigger('change');
            $('#bank_name').keydown();
            $('#organization_type').keydown();
            $('#country').keydown();
            $('#district_name').keydown();
            $('#quantity_type').keydown();
            $('#ypc_unit').keydown();
            $('#hypc_unit').keydown();
            $('#item_type').keydown();
            $('#share_type').keydown();
            $('#irc_slab').keydown();
            $('#association_name').keydown();
        });
        $("#division").on("keydown", function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/division";

            var selected_value = '{{isset($appData->division) ? $appData->division : ''}}';  // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "division_id"; //dynamic id for callback
            var element_name = "division_name"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $("#division").on("change", function () {
            // alert('ss');
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            var division = $('#division').val();
            var divisionId = division.split("@")[0];
            if (divisionId) {
                var e = $(this);
                var api_url = "{{$ccie_service_url}}/info/district" + '/' + divisionId;
                var selected_value = '{{isset($appData->district) ? $appData->district : ''}}'; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "district"; // for callback
                var element_id = "district_id"; //dynamic id for callback
                var element_name = "district_name"; //dynamic name for callback
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, callbackResponseDivision, arrays);

            } else {
                $("#district").html('<option value="">Select Division First</option>');
                $(self).next().hide();
            }

        });
        $("#district").on("change", function () {
            // alert('ss');
            var self = $(this);
            $(self).next().hide();

            var district = $('#district').val();
            //alert(district);
            if (district !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                var districtId = district.split("@")[0];
                if (districtId) {
                    var e = $(this);
                    var api_url = "{{$ccie_service_url}}/info/thana/" + districtId;
                    var selected_value = '{{isset($appData->police_station) ? $appData->police_station : ''}}'; // for callback
                    //alert(selected_value);
                    var calling_id = $(this).attr('id');
                    //alert(calling_id);
                    var dependent_section_id = "police_station"; // for callback
                    var element_id = "police_station_id"; //dynamic id for callback
                    var element_name = "police_station_name_en"; //dynamic name for callback
                    var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                    var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


                    apiCallGet(e, options, apiHeaders, callbackResponseDependantSelect, arrays);

                } else {
                    $("#district").html('<option value="">Select Division First</option>');
                    $(self).next().hide();
                }

            }
        });

        $('#bank_name').on("keydown", function (el) {
            //alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/bank";

            var selected_value = '{{isset($appData->bank_name) ? $appData->bank_name : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "bank_id"; //dynamic id for callback
            var element_name = "bank_name_en"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $("#bank_name").on("change", function () {
            // alert('ss');
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            var bank_name = $('#bank_name').val();
            var bank_name_id = bank_name.split("@")[0];
            if (bank_name_id) {
                var e = $(this);
                var api_url = "{{$ccie_service_url}}/info/bank-branch/" + bank_name_id;
                var selected_value = '{{isset($appData->branch_no) ? $appData->branch_no : ''}}'; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "branch_name"; // for callback
                var element_id = "branch_id"; //dynamic id for callback
                var element_name = "branch_name_en"; //dynamic name for callback
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


                apiCallGet(e, options, apiHeaders, callbackResponseDependantSelect, arrays);

            } else {
                $("#branch_name").html('<option value="">Select Bank First</option>');
                $(self).next().hide();
            }

        });
        $('#organization_type').on("keydown", function (el) {
            //alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/owner-type";

            var selected_value = '{{isset($appData->organization_type) ? $appData->organization_type : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "type"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $('#organization_type').change(function () {
            var type = $(this).val().split('@')[0];
            var _token = $('input[name="_token"]').val();
            var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}';


            if (type != '' && type != 0) {

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$ccie_service_url}}/info/ownership-wise-designation/"+type;

                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var dependent_section_id = "designation"; // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "fullname_en"; //dynamic name for callback
                var data = '';
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, callbackDesignation, arrays);
            }

            if (type == 'P') {
                $('#incorpornum').addClass('hidden');
                $('#incorporation').addClass('hidden');
                $('#registrationnum').addClass('hidden');
                $('#registration').addClass('hidden');
                $('#add_owner').addClass('hidden');
                $('#personal').removeClass('hidden');
                $('#owner').removeClass('hidden');
                $('#addowner').removeClass('hidden');

                var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length);
                if (ownerRowNumber > 1) {
                    $('#owner_details table tbody').html('');
                } else if (ownerRowNumber == 1) {
                    $('#ow_save').hide();
                }

            } else if (type == 'S') {
                $('#incorpornum').addClass('hidden');
                $('#incorporation').addClass('hidden');
                $('#registrationnum').removeClass('hidden');
                $('#registration').removeClass('hidden');
                $('#personal').removeClass('hidden');
                $('#owner').removeClass('hidden');
                $('#add_owner').removeClass('hidden');
                $('#addowner').removeClass('hidden');
                $('#ow_save').show();
            } else if (type == 'L') {
                $('#personal').removeClass('hidden');
                $('#owner').removeClass('hidden');
                $('#incorpornum').removeClass('hidden');
                $('#incorporation').removeClass('hidden');
                $('#registrationnum').addClass('hidden');
                $('#registration').addClass('hidden');
                $('#add_owner').addClass('hidden');
                $('#addowner').removeClass('hidden');
                $('#ow_save').show();
            } else {
                $('#incorpornum').addClass('hidden');
                $('#incorporation').addClass('hidden');
                $('#registrationnum').addClass('hidden');
                $('#registration').addClass('hidden');
                $('#personal').addClass('hidden');
                $('#owner').addClass('hidden');
                $('#addowner').removeClass('hidden');
                $('#ow_save').show();
            }

            if (type != '' && type != 0) {
                if (type) {
                    $.ajax({
                        type: "POST",
                        url: '/industrial-IRC/get-dynamic-doc',
                        dataType: "json",
                        data: {
                            _token: _token,
                            type: type,
                            appId: appId
                        },
                        success: function (result) {
                            console.log(result.responseCode);
                            $("#showDocumentDiv").html(result.data);

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $("#showDocumentDiv").html('');
                        },
                    });
                } else {
                    $("#showDocumentDiv").html('');

                }
            } else {
                $("#showDocumentDiv").html('');

            }
        });
        // $('#organization_type').trigger('change');
        $('#country').on("keydown", function (el) {
            //alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/country";

            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "country_id"; //dynamic id for callback
            var element_name = "country_name_en"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $('#district_name').on("keydown", function (el) {
            // alert('ss');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/district";
            var selected_value = '{{isset($appData->part_district_name) ? $appData->part_district_name : ''}}'; // for callback
            var calling_id = $(this).attr('id');
            var dependent_section_id = ""; // for callback
            var element_id = "district_id"; //dynamic id for callback
            var element_name = "district_name"; //dynamic name for callback
            var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);


        });
        $('#quantity_type').on("keydown", function (el) {
            // alert('ss');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            // $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/unit";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id');
            var dependent_section_id = ""; // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "unit_name"; //dynamic name for callback
            var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);


        });
        $('#ypc_unit').on("keydown", function (el) {
            // alert('ss');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            // $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/unit";
            var selected_value = '{{isset($appData->ypc_unit) ? $appData->ypc_unit : ''}}'; // for callback
            var calling_id = $(this).attr('id');
            var dependent_section_id = ""; // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "unit_name"; //dynamic name for callback
            var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);


        });
        $('#hypc_unit').on("keydown", function (el) {
            // alert('ss');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            // $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/unit";
            var selected_value = '{{isset($appData->hypc_unit) ? $appData->hypc_unit : ''}}'; // for callback
            var calling_id = $(this).attr('id');
            var dependent_section_id = ""; // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "unit_name"; //dynamic name for callback
            var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);


        });
        $('#item_type').on("keydown", function (el) {
            //alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/adhoc-list";

            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "type"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $('#share_type').on("keydown", function (el) {
            // alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/share-type";

            var selected_value = '{{isset($appData->share_type) ? $appData->share_type : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "type"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, shareTypecallbackResponse, arrays);


        });

        $('#irc_slab').on("keydown", function (el) {
            // alert('ss');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/slab";
            var selected_value = '{{isset($appData->irc_slab) ? $appData->irc_slab : ''}}'; // for callback
            var calling_id = $(this).attr('id');
            var dependent_section_id = ""; // for callback
            var element_id = "fees_id"; //dynamic id for callback
            var element_name = "max_price_limit"; //dynamic name for callback
            var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

            setTimeout(function(){
                irccSlabDataLogic();
            },3000);
        });
        $('#association_name').on("keydown", function (el) {
            //alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/association";

            var selected_value = '{{isset($appData->association_name) ? $appData->association_name : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "association_name"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback


            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $("#irc_slab").on("change", function () {
            // alert('ss');
            var self = $(this);
            $(self).next().hide();

            var irc_slab = $('#irc_slab').val();
            var irc_slab_id = irc_slab.split("@")[0];

            if (irc_slab_id) {
                var e = $(this);
                var api_url = "{{$ccie_service_url}}/info/fee/" + irc_slab_id;
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // TODO:: need to change depending id
                var element_id = "fees_id"; //dynamic id for callback
                var element_name = "max_price_limit"; //dynamic name for callback
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name];


                // apiCallGet(e, options, apiHeaders, feeCallbackResponseDependent, arrays);

            } else {
                // $("#irc_slab").html('<option value="">Select Slab First</option>');
                $(self).next().hide();
            }

        });

    });

    $('#organization_tin').on('focusout', function () {
        var tin = $('#organization_tin').val();
        if (tin.toString().length !== 12) {
            $("#organization_tin").addClass("error");
        } else {
            $("#organization_tin").removeClass("error");
        }
    });

    $('#owner_tin').on('focusout', function () {
        var tin = $('#owner_tin').val();
        if (tin.toString().length !== 12) {
            $("#owner_tin").addClass("error");
        } else {
            $("#owner_tin").removeClass("error");
        }
    });

    $(document).on('click', '#additem', function () {
        var count = $('#adhocItemsContainer tbody tr.adhocRow').length + 1;

        var description_item = $('#description_item').val();
        var item_type = $("#item_type option:selected").html();
        var quantity_type = $("#quantity_type option:selected").html();
        var unit_price = $("#unit_price option:selected").html();
        var hs_code = $("#hs_code").val();
        var issue_date = $("#issue_date").val();
        var quantity = $("#quantity").val();
        var unit_price_no = $("#unit_price_no").val();
        var total_price = quantity * unit_price_no;
        var item_value = $("#item_value").val();

        if (description_item == '') {
            $('#description_item').addClass("required").addClass("error");
            return false;
        } else {
            $('#description_item').removeClass("required").removeClass("error");
        }
        if (item_type == 'Select one') {
            $('#item_type').addClass("required").addClass("error");
            return false;
        } else {
            $('#item_type').removeClass("required").removeClass("error");
        }

        if (hs_code == '') {
            $('#hs_code').addClass("required").addClass("error");
            return false;
        } else {
            $('#hs_code').removeClass("required").removeClass("error");
        }
        if (issue_date == '') {
            $('#issue_date').addClass("required").addClass("error");
            return false;
        } else {
            $('#issue_date').removeClass("required").removeClass("error");
        }
        if (quantity == '') {
            $('#quantity').addClass("required").addClass("error");
            return false;
        } else {
            $('#quantity').removeClass("required").removeClass("error");
        }
        if (quantity_type == 'Unit') {
            $('#quantity_type').addClass("required").addClass("error");
            return false;
        } else {
            $('#quantity_type').removeClass("required").removeClass("error");
        }
        if (unit_price == 'Currency') {
            $('#unit_price').addClass("required").addClass("error");
            return false;
        } else {
            $('#unit_price').removeClass("required").removeClass("error");
        }
        if (unit_price_no == '') {
            $('#unit_price_no').addClass("required").addClass("error");
            return false;
        } else {
            $('#unit_price_no').removeClass("required").removeClass("error");
        }
        if (item_value == '') {
            $('#item_value').addClass("required").addClass("error");
            return false;
        } else {
            $('#item_value').removeClass("required").removeClass("error");
        }

        var html = '<tr class="adhocRow">' +
            '<td>' + count + '</td>' +
            '<td><input name="issue_date[]" value="' + issue_date + '" hidden >' + item_type + '</td>' +
            '<td><input name="description_item[]" value="' + description_item + '" hidden >' + description_item + '</td>' +
            '<td><input name="item_type[]" value="' + item_type + '" hidden >' + issue_date + '</td>' +
            '<td><input name="hs_code[]" value="' + hs_code + '" hidden >' + hs_code + '</td>' +
            '<td><input name="item_value[]" value="' + item_value + '" hidden >' + item_value + '</td>' +
            '<td><input name="unit_price_no[]" value="' + unit_price_no + '" hidden ><input name="unit_price[]" value="' + unit_price + '" hidden > (' + unit_price + ') ' + unit_price_no + '</td>' +
            '<td><input name="quantity[]" value="' + quantity + '" hidden ><input name="quantity_type[]" value="' + quantity_type + '" hidden > (' + quantity_type + ') ' + quantity + '</td>' +
            '<td><input hidden name="total_price[]" value="' + total_price + '" class="total_price" >' + total_price + '</td>' +
            '<td><button class="btn btn-minier btn-danger deleteAdhocItem" type="button"><i class="fa fa-trash"></i> Delete </button></td>' +
            '</tr>';


        $('#adhocItemsContainer table').append(html);
        $('#adhocItemsContainer table').show();
        $('#modalContactForm').modal('toggle');
        sum_total();
        $('#description_item').val('');
        $('#item_type').val('1');
        $('#quantity_type').val('');
        $('#unit_price').val('Currency');
        $('#unit_price_no').val('');
        $('#item_value').val('');
        $('#quantity').val('');
        $('#hs_code').val('');
        $('#issue_date').val('');

    });

    function sum_total() {
        var sum = 0;
        $.each($(".total_price"), function () {
            sum += +$(this).val();
        });
        $("#grand_total").val(sum);
    }


    $(document).on('click', 'button.deleteAdhocItem', function () {
        $(this).closest('tr').remove();
        var count = $('#adhocItemsContainer table tbody tr').length;
        $('#adhocItemsContainer table tbody tr').each(function (count) {
            $(this).find("td:first").html(count + 1);
        });
        if (count == 0) {
            // $("#owner_details table").hide();
            $("#adhocItemsContainer table").hide();
            $("#item_message").css("display", "block");

        }
        sum_total();

        return false;
    });

    $(document).on('click', '#addowner', function () {
        var rowcount = $('#ownerTable tbody tr.ownerRow').length + 1;

        if (ownerSectionvalidate() === false) {
            return false
        }

        var nationality = $('#nationality').val();
        var owner_nid_or_passport = '';
        if ($('#owner_nid_or_passport').val() != '') {
            owner_nid_or_passport = $('#owner_nid_or_passport').val();
        }
        var owner_name = $('#owner_name').val();
        var owner_father_name = $('#owner_father_name').val();
        var phone_number_office = $('#phone_number_office').val();
        var present_address = $('#present_address').val();
        var passport_no = $('#passport_no').val();
        var passport_expired_date = $('#passport_expired_date').val();
        var incorporation_number = $('#incorporation_number').val();
        var registration_number = $('#registration_number').val();
        var owner_tin = $('#owner_tin').val();
        var designation = $("#designation").val();
        var designation_val = designation.split('@')[1];
        var mother_name = $('#mother_name').val();
        var permanent_address = $('#permanent_address').val();
        var incorporation_date = $('#incorporation_date').val();
        var mobile = $('#mobile').val();
        var country = $('#country').val();
        var registration_date = $('#registration_date').val();
        var passport_issuing_country = $('#country').val();
        var owner_photo = $("#owner_photo_viewer").attr("src")
        var district = $('#district_name').val();
        var district_val = '';
        if (district != null) {
            district_val = district.split('@')[1];
        }

        var html = '<tr class="ownerRow" data-id="' + rowcount + '" id="owner_row_id_' + rowcount + '">' +
            '<td><input id="owner_name_' + rowcount + '" name="owner_name[]" value="' + owner_name + '" hidden >' + owner_name + '</td>' +
            '<td style="display:none;"><input id="nationality_' + rowcount + '" name="nationality[]" value="' + nationality + '" hidden ></td>' +
            '<td style="display:none;"><input id="owner_father_name_' + rowcount + '" name="owner_father_name[]" value="' + owner_father_name + '" hidden ></td>' +
            '<td style="display:none;"><input id="passport_no_' + rowcount + '" name="passport_no[]" value="' + passport_no + '" hidden ></td>' +
            '<td style="display:none;"><input id="passport_expired_date_' + rowcount + '" name="passport_expired_date[]" value="' + passport_expired_date + '" hidden ></td>' +
            '<td style="display:none;"><input id="incorporation_number_' + rowcount + '" name="incorporation_number[]" value="' + incorporation_number + '" hidden ></td>' +
            '<td style="display:none;"><input id="registration_number_' + rowcount + '" name="registration_number[]" value="' + registration_number + '" hidden ></td>' +
            '<td style="display:none;"><input id="mother_name_' + rowcount + '" name="mother_name[]" value="' + mother_name + '" hidden > < /td>' +
            '<td style="display:none;"><input id="permanent_address_' + rowcount + '" name="permanent_address[]" value="' + permanent_address + '" hidden ></td>' +
            '<td style="display:none;"><input id="incorporation_date_' + rowcount + '" name="incorporation_date[]" value="' + incorporation_date + '" hidden ></td>' +
            '<td style="display:none;"><input id="country_' + rowcount + '" name="country[]" value="' + country + '" hidden ></td>' +
            '<td style="display:none;"><input id="passport_issuing_country_' + rowcount + '" name="passport_issuing_country[]" value="' + passport_issuing_country + '" hidden ></td>' +
            '<td style="display:none;"><input id="registration_date_' + rowcount + '" name="registration_date[]" value="' + registration_date + '" hidden ></td>' +
            '<td><input id="owner_tin_' + rowcount + '" name="owner_tin[]" value="' + owner_tin + '" hidden >' + owner_tin + '</td>' +
            '<td><input id="owner_nid_or_passport_' + rowcount + '" name="owner_nid_or_passport[]" value="' + owner_nid_or_passport + '" hidden >' + owner_nid_or_passport + '</td>' +
            '<td><input id="designation_' + rowcount + '" name="designation[]" value="' + designation + '" hidden >' + designation_val + '</td>' +
            '<td><input id="mobile_' + rowcount + '" name="mobile[]" value="' + mobile + '" hidden >' + mobile + '</td>' +
            '<td><input id="phone_number_office_' + rowcount + '" name="phone_number_office[]" value="' + phone_number_office + '" hidden >' + phone_number_office + '</td>' +
            '<td><input id="present_address_' + rowcount + '" name="present_address[]" value="' + present_address + '" hidden >' + present_address + '</td>' +
            '<td><input id="district_name_' + rowcount + '" name="district_name[]" value="' + district + '" hidden >' + district_val + '</td>' +
            '<td><input id="owner_photo_' + rowcount + '" name="owner_photo[]" value="' + owner_photo + '" hidden ><img src="' + owner_photo + '" height="80px"/></td>' +
            '<td><button class="btn btn-minier btn-danger ownership" type="button"><i class="fa fa-trash"></i> Delete </button></td>' +
            '</tr>';
        $('#owner_details table').append(html);
        $('#owner_details table').show();

        $('#nationality option:contains("Select one")').prop('selected', true);
        $('#owner_tin').val('');
        $('#material_image').val('');
        $("#owner_photo_viewer").attr("src", "{{(url('assets/images/no-image.png'))}}");
        $('#owner_nid_or_passport').val('');
        $('#owner_name').val('');
        $('#owner_father_name').val('');
        $('#phone_number_office').val('');
        $('#present_address').val('');
        $('#passport_no').val('');
        $('#passport_expired_date').val('');
        $('#incorporation_number').val('');
        $('#registration_number').val('');
        $('#owner_tin').val();
        $('#designation option:contains("Select one")').prop('selected', true);
        $('#mother_name').val('');
        $('#permanent_address').val('');
        $('#incorporation_date').val('');
        $('#mobile').val('');
        $('#country').val('');
        $('#registration_date').val('');
        $("#owner_photo_viewer").attr("src");
        $('#district_name').val('');
        $('#passport_issuing_country').val('');

        $('.owner_req').each(function () {
            $(this).removeClass('error')
            $('#owner_image').removeClass('error')
        })

        var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length);

        if (ownerRowNumber === 1) {
            $('#owner_save').addClass('btn-primary btn-white').removeClass('btn-danger btn-red')
        }
        var organization_type_for_oner = $('#organization_type').val();
        if (organization_type_for_oner === 'P@Proprietorship' && ownerRowNumber === 1) {
            $('#ow_save').hide();
        }

    });

    function editOwnership(key) {
        $('#owner_edit').show();
        $('#ow_save').hide();
        var appId = "{{$appId}}";

        $.ajax({
            type: "get",
            url: "<?php echo url('/industrial-IRC/getData'); ?>" + '/' + appId,
            data: {},
            success: function (response) {
                $('#owner_name').val(response.owner_name[key]);
                $('#nationality').val(response.nationality[key]);
                $('#nationality').trigger('change');

                $('#owner_tin').val(response.owner_tin[key]);
                $('#owner_nid_or_passport').val(response.owner_nid_or_passport[key]);
                $('#owner_father_name').val(response.owner_father_name[key]);
                $('#phone_number_office').val(response.phone_number_office[key]);
                $('#present_address').val(response.present_address[key]);
                $('#passport_no').val(response.passport_no[key]);
                $('#passport_expired_date').val(response.passport_expired_date[key]);
                $('#incorporation_number').val(response.incorporation_number[key]);
                $('#registration_number').val(response.registration_number[key]);
                $('#designation').val(response.designation[key]);
                $('#mother_name').val(response.mother_name[key]);

                $("#owner_photo_viewer").attr("src", response.owner_photo[key]);
                $('#permanent_address').val(response.permanent_address[key]);
                $('#incorporation_date').val(response.incorporation_date[key]);
                $('#mobile').val(response.mobile[key]);
                $('#country').val(response.country[key]);
                $('#registration_date').val(response.registration_date[key]);
                $('#district_name').val(response.district_name[key]);
                $('#arraykey').val(key);

            }

        });

    }

    $(document).on('click', '.editJqueryRow', function () {
        $('#owner_edit').show();
        $('#ow_save').hide();
        var id = $(this).closest('tr').attr('data-id')
        $('#arraykey').val(id);
        var owner_name = $("#owner_name_" + id).val();
        var owner_father_name = $("#owner_father_name_" + id).val();
        var nationality = $("#nationality_" + id).val();
        var passport_no = $("#passport_no_" + id).val();
        var passport_expired_date = $("#passport_expired_date_" + id).val();
        var incorporation_number = $("#incorporation_number_" + id).val();
        var registration_number = $("#registration_number_" + id).val();
        var mother_name = $("#mother_name_" + id).val();
        var incorporation_date = $("#incorporation_date_" + id).val();
        var country = $("#country_" + id).val();
        var permanent_address = $("#permanent_address_" + id).val();
        var passport_issuing_country = $("#passport_issuing_country_" + id).val();
        var registration_date = $("#registration_date_" + id).val();
        var owner_tin = $("#owner_tin_" + id).val();
        var owner_nid_or_passport = $("#owner_nid_or_passport_" + id).val();
        var designation = $("#designation_" + id).val();
        var mobile = $("#mobile_" + id).val();
        var phone_number_office = $("#phone_number_office_" + id).val();
        var present_address = $("#present_address_" + id).val();
        var district_name = $("#district_name_" + id).val();
        var owner_photo = $("#owner_photo_" + id).val();
        $('#nationality').val(nationality);
        $('#nationality').trigger('change');
        $('#owner_tin').val();
        $("#owner_photo_viewer").attr("src", "{{(url('assets/images/no-image.png'))}}");
        $('#owner_nid_or_passport').val(owner_nid_or_passport);
        $('#owner_name').val(owner_name);
        $('#owner_father_name').val(owner_father_name);
        $('#phone_number_office').val(phone_number_office);
        $('#present_address').val(present_address);
        $('#passport_no').val(passport_no);
        $('#passport_expired_date').val(passport_expired_date);
        $('#incorporation_number').val(incorporation_number);
        $('#registration_number').val(registration_number);
        $('#owner_tin').val(owner_tin);
        $('#designation').val(designation);
        $('#mother_name').val(mother_name);
        $('#permanent_address').val(permanent_address);
        $('#incorporation_date').val(incorporation_date);
        $('#mobile').val(mobile);
        $('#country').val(country);
        $('#registration_date').val(registration_date);
        $("#owner_photo_viewer").attr("src", owner_photo);
        $('#district_name').val(district_name);
        $('#passport_issuing_country').val(passport_issuing_country);


    });


    $(document).on('click', '#owner_edit', function () {
        $('#owner_edit').hide();
        var organization_type_for_oner = $('#organization_type').val();
        var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length);
        if (organization_type_for_oner === 'P@Proprietorship' && ownerRowNumber === 1) {
            $('#ow_save').hide();
        } else {
            $('#ow_save').show();
        }
        var tr_id = $('#arraykey').val();
        $("#addowner").trigger('click');
        $('#owner_row_id_' + tr_id).remove();
        return false;
    });

    $(document).on('click', 'button.ownership', function () {

        $(this).closest('tr').remove();
        var count = $('#ownerTable tbody tr').length;
        if (count == 0) {
            $("#owner_details table").hide();
            $("#addowner table").hide();
            $('#ow_save').show();
        }

        return false;
    });

    $('#nationality').change(function () {
        var type = $(this).val();
        if (type == 'B') {
            $('#passport_expired_date_div').addClass('hidden');
            $('#passport_no_div').addClass('hidden');
            $('#passport_issuing_country').addClass('hidden');
            $('#owner_nid_or_pass').removeClass('hidden');
            $('#part_district_name').removeClass('hidden');
        } else if (type == 'F') {
            $('#passport_expired_date_div').removeClass('hidden');
            $('#passport_no_div').removeClass('hidden');
            $('#passport_issuing_country').removeClass('hidden');
            $('#owner_nid_or_pass').addClass('hidden');
            $('#part_district_name').addClass('hidden');
        } else {
            $('#passport_expired_date_div').addClass('hidden');
            $('#passport_no_div').addClass('hidden');
            $('#passport_issuing_country').addClass('hidden');
        }
    });

    $("#nationality").trigger('change');

    $('#share_type').change(function () {
        var type = $(this).val();

        if (type != null) {
            var share_type = type.split('@')[0];
            if (share_type == 'D') {
                $('#doestic').show();
                $('#foreign').hide();
                $("#foreign_share").prop('disabled', true);
                $("#domestic_share").prop('disabled', false);

            } else if (share_type == 'F') {
                $('#foreign').show();
                $('#doestic').hide();
                $("#domestic_share").prop('disabled', true);
                $("#foreign_share").prop('disabled', false);
            } else {
                $('#doestic').hide();
                $('#foreign').hide();
            }
        }
    });
    $("#share_type").trigger('change');


    function independantcallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {

        var option = '<option value="">Select One</option>';
        //alert('dd');
        if (response.responseCode === 200) {
            //console.log(response);
            $.each(response.data, function (key, row) {
                //console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).trigger('change');
        $(".search-box").select2();
        $("#" + calling_id).parent().find('.loading_data').hide();
        $("#" + calling_id).next().hide();

        //$(document).trigger(self.MY_FUNCTION);

    }

    function ownerSectionvalidate() {
        var organization_type_for_oner = $('#organization_type').val();
        var organization_type_val = organization_type_for_oner.split('@')[0];

        if (organization_type_val !== '') {
            var return_val = false
            $('.owner_req').each(function () {
                if ($(this).val() == '') {
                    $(this).addClass('error')
                    return_val = false;
                } else {
                    $(this).removeClass('error')
                    return_val = true;
                }
            })
            if ($('#owner_photo_viewer').prop('src') == '{{ URL::asset('assets/images/no-image.png') }}') {
                $('#owner_image').addClass('error')
                return_val = false;
            } else {
                $('#owner_image').removeClass('error')
                return_val = true
            }
            var tin = $('#owner_tin').val();
            if (tin.toString().length !== 12) {
                $("#owner_tin").addClass("error")
                return_val = false;
            } else {
                $("#owner_tin").removeClass("error")
                return_val = true
            }
            if (return_val === false) {
                $('#organization_type').focus()
            }
            return return_val;

        }

    }

    function shareTypecallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        //alert('dd');
        if (response.responseCode === 200) {
            //console.log(response);
            $.each(response.data, function (key, row) {
                //console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                //alert(selected_value);
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $("#share_type").trigger('change');

    }

    function callbackResponseDivision(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        // console.log(response.data);
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                // console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        $("#" + calling_id).next().hide();
        $('#district').trigger('change');

    }

    function callbackResponseDependantSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        // console.log(response.data);
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                // console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        $("#" + calling_id).parent().find('.loading_data').hide();
        $(".search-box").select2();
    }

    function callbackDesignation(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        console.log(response.data);
        if (response.responseCode === 200) {
            $.each(response.data.data, function (key, row) {
                // console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        //alert(dependent_section_id);
        $("#" + calling_id).next().hide();
    }

    function feeCallbackResponseDependent(response, [calling_id, selected_value, element_id, element_name]) {

        if (response.responseCode === 200) {
            var html = '<td>' + response.data.max_price_limit + '</td>' +
                '<td>' + response.data.registration_book + '</td>' +
                '<td>' + response.data.primary_reg_fee + '</td>';
            //console.log(html);
            $('#slab_data').find('td').remove();
            $('#slab_data').append(html);
        } else {
            console.log(response.status)
        }
    }

    function uploadDocument(targets, id, vField, isRequired) {
        var inputFile = $("#" + id).val();
        if (inputFile == '') {
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if ($('#label_' + id).length)
                $('#label_' + id).remove();
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{URL::to('/new-connection-bpdb/upload-document')}}";
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
                dataType: 'text', // what to expect back from the PHP script, if anything
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
                    var doc_id = id;
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" class="filedelete" docid="' + id + '" ><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    $('#' + id).removeClass('required');
                    //check valid data
                    document.getElementById(id).value = '';
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

    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = '';
            var isReq = $('#' + abc).attr('data-required')
            if (isReq == 'required') {
                $('#' + abc).addClass('required error')
            }
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
        } else {
            return false;
        }
    });


    $(document).ready(function () {
        $("#domesticshare").blur(function () {
            var share = $(this).val();
            var total = 100 - parseInt(share);
            if (share > 100) {
                $('#domesticshare').val('');
                $('#domesticshare').addClass('error');
            } else {
                $('#foreignshare').val(total);
                $('#domesticshare').removeClass('error');
            }
        })
    });

    $(document).ready(function () {
        $("#foreignshare").blur(function () {
            var share = $(this).val();
            var total = 100 - parseInt(share);
            if (share > 100) {
                $('#foreignshare').val('');
                $('#foreignshare').addClass('error');
            } else {
                $('#domesticshare').val(total);
                $('#foreignshare').removeClass('error')
            }
        });

        var organization_type_for_oner = $('#organization_type').val();
        var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length);
        if (organization_type_for_oner === 'P@Proprietorship' && ownerRowNumber === 1) {
            $('#ow_save').hide();
        }

    });

    function irccSlabDataLogic()
    {
        var apc_half_yearly_import_total = "{{ !empty($half_yearly_import_total->apc_half_yearly_import_total)?$half_yearly_import_total->apc_half_yearly_import_total:'' }}";
        if(apc_half_yearly_import_total == ""){
            return false;
        }
        var ircSlabTag = document.querySelector('#irc_slab');
        var ircSlabOptions = ircSlabTag.options;
        for (var i = 0; i < ircSlabOptions.length; i++) {
            var option = ircSlabOptions[i].value.split('@')[1];
            if (typeof option !== "undefined") {
                var optionVal = option.trim();
                optionVal = optionVal.replace('Above', '');
                var floatValue = parseFloat(optionVal.replace(/,/g, ''));
                var hasWord = option.includes('Above');
                if(hasWord){
                    if (apc_half_yearly_import_total > floatValue) {
                        $('#irc_slab').val(ircSlabOptions[i].value);
                        $('#irc_slab').css('pointer-events', 'none');
                        $('#irc_slab').css('background', '#eee');
                        // $('#irc_slab').prop('readonly', true);
                        break;
                    }
                }else{
                    if (apc_half_yearly_import_total <= floatValue) {
                        $('#irc_slab').val(ircSlabOptions[i].value);
                        $('#irc_slab').css('pointer-events', 'none');
                        $('#irc_slab').css('background', '#eee');
                        // $('#irc_slab').prop('readonly', true);
                        break;
                    }
                }
            }
        }
    }// end -:- irccSlabDataLogic()

</script>