<?php
$accessMode = ACL::getAccsessRight('TradeLicence');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<style>
    .form-group {
        margin-bottom: 2px;
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
<section class="content">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body" id="inputForm">

                <div class="panel panel-info">

                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong>Apply for Trade License to Bangladesh</strong></h5>
                        </div>

                        <div class="clearfix"></div>
                    </div>

                    <div class="row" style="margin:15px 0 5px 0">
                        <div class="col-md-12">
                            <div class="heading_img">
                                <img class="img-responsive pull-left"
                                     src="{{ asset('assets/images/u34.png') }}"/>
                            </div>
                            <div class="heading_text pull-left">
                                City Corporation
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h5>Application for Trade Licence to Dhaka South City Corporation .</h5>
                        </div>
                    </div>


                    <div class="form-body panel-body">

                        {!! Form::open(array('url' => 'licence-applications/trade-licence/add','method' => 'post','id' => 'trade_licence','role'=>'form','enctype'=>'multipart/form-data')) !!}
                        <input type="hidden" name="selected_file" id="selected_file"/>
                        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                        <input type="hidden" name="isRequired" id="isRequired"/>
                        {!! Form::hidden('app_id', Encryption::encodeId($appInfoTradeLi->id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}
                        <br/>
                        <div class="panel panel-info">
                            <div class="panel-heading margin-for-preview"><strong>A. Application for Trade License
                                    to Dhaka South City Corporation</strong></div>
                            <div class="panel-body ">

                                <div id="validationError"></div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('country') ? 'has-error' : ''}}">
                                            {!! Form::label('country', 'Country', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('country', $countries, $appInfoTradeLi->country, ['class' => 'form-control input-md required','readonly']) !!}
                                                {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('applicant_pic') ? 'has-error' : ''}}">

                                            <div class="col-sm-7">
                                                {!! Form::label('applicant_pic','Picture of CEO/MD/Head of Organization', ['class'=>'text-left required-star','style'=>'']) !!}
                                                @if($viewMode != 'on')
                                                    <input type="file" name="applicant_pic" id="applicant_pic"
                                                           class="form-control input-md {{!empty($appInfoTradeLi->applicant_pic) ? '' : 'required'}}"
                                                           onchange="imageDisplay(this,'applicantPhotoViewer', '300x300')"/>
                                                    <span class="text-success"
                                                          style="font-size: 9px; font-weight: bold; display: block;">[File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]</span>
                                                @endif
                                                {!! $errors->first('applicant_pic','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-5">
                                                @if(!empty($appInfoTradeLi->applicant_pic))
                                                    <input type="hidden" name="applicant_pic"
                                                           value="{{$appInfoTradeLi->applicant_pic}}"/>
                                                @endif
                                                <img class="img-thumbnail" id="applicantPhotoViewer"
                                                     src="{{ (!empty($appInfoTradeLi->applicant_pic)? url('users/upload/'.$appInfoTradeLi->applicant_pic) : url('assets/images/photo_default.png')) }}"
                                                     alt="Investor Photo">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('organization_name') ? 'has-error' : ''}}">
                                            {!! Form::label('organization_name', 'Name of Organization', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('organization_name', $appInfoTradeLi->organization_name, ['class' => 'form-control input-md required','readonly']) !!}
                                                {!! $errors->first('organization_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('spouse_name') ? 'has-error' : ''}}">
                                            {!! Form::label('spouse_name', 'Spouse Name', ['class' => 'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('spouse_name',  $appInfoTradeLi->spouse_name, ['class' => 'form-control input-md']) !!}

                                                {!! $errors->first('spouse_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('applicant_name') ? 'has-error' : ''}}">
                                            {!! Form::label('applicant_name', 'Name of Applicant', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('applicant_name',  $appInfoTradeLi->applicant_name, ['class' => 'form-control input-md required','readonly']) !!}
                                                {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('applicant_email') ? 'has-error' : ''}}">
                                            {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('applicant_email',  $appInfoTradeLi->applicant_email, ['class' => 'form-control input-md required email','readonly']) !!}
                                                {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('applicant_father') ? 'has-error' : ''}}">
                                            {!! Form::label('applicant_father', 'Father\'s Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('applicant_father',  $appInfoTradeLi->applicant_father, ['class' => 'form-control input-md required','readonly']) !!}
                                                {!! $errors->first('applicant_father', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ $errors->has('applicant_license_type') ? 'has-error' : ''}}">
                                            {!! Form::label('applicant_license_type', 'License Type', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('applicant_license_type', $licenceType, $appInfoTradeLi->applicant_license_type, ['class' => 'form-control required ','id'=>'applicant_license_type']) !!}

                                                {!! $errors->first('applicant_license_type', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('applicant_mother') ? 'has-error' : ''}}">
                                            {!! Form::label('applicant_mother', 'Mother\'s Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('applicant_mother',  $appInfoTradeLi->applicant_mother, ['class' => 'form-control input-md required','readonly']) !!}
                                                {!! $errors->first('applicant_mother', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('applicant_dob') ? 'has-error' : ''}}">
                                            {!! Form::label('applicant_dob', 'Date of Birth', ['class' => 'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                <div class="datepickerDob input-group date">
                                                    {!! Form::text('applicant_dob',  ($appInfoTradeLi->applicant_dob == '0000-00-00' ? '' : date('d-M-Y', strtotime($appInfoTradeLi->applicant_dob))), ['class' => 'form-control input-md', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('applicant_dob', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading "><strong>B. Business Information</strong></div>
                            <div class="panel-body ">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_name') ? 'has-error' : ''}}">
                                            {!! Form::label('business_name', 'Business Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('business_name',  $appInfoTradeLi->business_name, ['class' => 'form-control input-md required']) !!}

                                                {!! $errors->first('business_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10 {{ $errors->has('business_details') ? 'has-error' : ''}}">
                                            {!! Form::label('business_details', 'Business Details', ['class' => 'col-md-3 text-left required-star']) !!}
                                            <div class="col-md-9">
                                                {!! Form::textarea('business_details',  $appInfoTradeLi->business_details, ['class' => 'form-control input-md required', 'rows' => 2]) !!}
                                                {!! $errors->first('business_details', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_holding') ? 'has-error' : ''}}">
                                            {!! Form::label('business_holding', 'Holding No', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('business_holding',  $appInfoTradeLi->business_holding, ['class' => 'form-control input-md required']) !!}

                                                {!! $errors->first('business_holding', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10 {{ $errors->has('business_address') ? 'has-error' : ''}}">
                                            {!! Form::label('business_address', 'Address', ['class' => 'col-md-3 text-left required-star']) !!}
                                            <div class="col-md-9">
                                                {!! Form::textarea('business_address',  $appInfoTradeLi->business_address, ['class' => 'form-control input-md required', 'rows' => 2]) !!}
                                                {!! $errors->first('business_address', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_road') ? 'has-error' : ''}}">
                                            {!! Form::label('business_road', 'Road Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('business_road',  $appInfoTradeLi->business_road, ['class' => 'form-control input-md required']) !!}

                                                {!! $errors->first('business_road', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('business_ward') ? 'has-error' : ''}}">
                                            {!! Form::label('business_ward', 'Ward / Market', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                @if($viewMode == 'on')
                                                    <span class="form-control input-md"
                                                          style="background:#eee; height: auto;min-height: 30px;">
                                                            {{$appInfoTradeLi->business_ward_value }}
                                                            </span>
                                                @else
                                                    {!! Form::select('business_ward', [],null, ['class' => 'form-control input-md required']) !!}
                                                    {!! Form::hidden('business_ward_value', $appInfoTradeLi->business_ward_value, []) !!}

                                                    {!! $errors->first('business_ward', '<span class="help-block">:message</span>') !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_zone') ? 'has-error' : ''}}">
                                            {!! Form::label('business_zone', 'Zone / Market Branch', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                @if($viewMode == 'on')
                                                    <span class="form-control input-md"
                                                          style="background:#eee; height: auto;min-height: 30px;">
                                                            {{$appInfoTradeLi->business_zone_value }}
                                                            </span>
                                                @else
                                                    {!! Form::select('business_zone', [], null, ['class' => 'form-control input-md required','id'=>'business_zone']) !!}
                                                    {!! Form::hidden('business_zone_value', $appInfoTradeLi->business_zone_value, []) !!}
                                                    {!! $errors->first('business_zone', '<span class="help-block">:message</span>') !!}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('business_market_name') ? 'has-error' : ''}}">
                                            {!! Form::label('business_market_name', 'Name of Market', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('business_market_name',  $appInfoTradeLi->business_market_name, ['class' => 'form-control input-md required']) !!}

                                                {!! $errors->first('business_market_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_area') ? 'has-error' : ''}}">
                                            {!! Form::label('business_area', 'Business Area', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                @if($viewMode == 'on')
                                                    <span class="form-control input-md"
                                                          style="background:#eee; height: auto;min-height: 30px;">
                                                            {{$appInfoTradeLi->business_area_value }}
                                                            </span>
                                                @else
                                                    {!! Form::select('business_area',[],  null, ['class' => 'form-control input-md required']) !!}
                                                    {!! Form::hidden('business_area_value',$appInfoTradeLi->business_area_value, []) !!}

                                                    {!! $errors->first('business_area', '<span class="help-block">:message</span>') !!}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('business_shop') ? 'has-error' : ''}}">
                                            {!! Form::label('business_shop', 'Shop No', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('business_shop',  $appInfoTradeLi->business_shop, ['class' => 'form-control input-md required']) !!}

                                                {!! $errors->first('business_shop', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_floor') ? 'has-error' : ''}}">
                                            {!! Form::label('business_floor', 'Floor No', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('business_floor',  $appInfoTradeLi->business_floor, ['class' => 'form-control input-md required onlyNumber']) !!}

                                                {!! $errors->first('business_floor', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('business_nature') ? 'has-error' : ''}}">
                                            {!! Form::label('business_nature', 'Nature of Business', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('business_nature', $businessNature,  $appInfoTradeLi->business_nature, ['class' => 'form-control input-md required']) !!}

                                                {!! $errors->first('business_nature', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_start_date') ? 'has-error' : ''}}">
                                            {!! Form::label('business_start_date', 'Date of Business Start', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                <div class="datepicker_business_start input-group date">
                                                    {{--                                                    {!! Form::text('business_start_date',  !empty($appInfoTradeLi->business_start_date) ? (date('d-MMM-YYY', strtotime($appInfoTradeLi->business_start_date))) : '', ['class' => 'form-control input-md required', 'placeholder'=>'dd-mm-yyyy']) !!}--}}
                                                    {!! Form::text('business_start_date',  ($appInfoTradeLi->business_start_date == '0000-00-00' ? '' : date('d-M-Y', strtotime($appInfoTradeLi->business_start_date))), ['class' => 'form-control input-md required', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('business_start_date', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('business_sub_category') ? 'has-error' : ''}}">
                                            {!! Form::label('business_sub_category', 'Business Sub-category', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                @if($viewMode == 'on')
                                                    <span class="form-control input-md"
                                                          style="background:#eee; height: auto;min-height: 30px;">
                                                            {{$appInfoTradeLi->business_sub_category_value }}
                                                            </span>
                                                @else
                                                    {!! Form::select('business_sub_category', [], null, ['class' => 'form-control input-md required','id'=>'business_sub_category']) !!}
                                                    {!! Form::hidden('business_sub_category_value', $appInfoTradeLi->business_sub_category_value, []) !!}
                                                    {!! $errors->first('business_sub_category','<span class="help-block">:message</span>') !!}
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_category') ? 'has-error' : ''}}">
                                            {!! Form::label('business_category', 'Business Category', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                @if($viewMode == 'on')
                                                    <span class="form-control input-md"
                                                          style="background:#eee; height: auto;min-height: 30px;">
                                                            {{$appInfoTradeLi->business_category_value }}
                                                            </span>
                                                @else
                                                    {!! Form::select('business_category', [], null, ['class' => 'form-control input-md required','id'=>'business_category']) !!}
                                                    {!! Form::hidden('business_category_value',$appInfoTradeLi->business_category_value, []) !!}
                                                    {!! $errors->first('business_category','<span class="help-block">:message</span>') !!}
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10 {{ $errors->has('business_address') ? 'has-error' : ''}}">
                                            {!! Form::label('business_address', 'Signboard (Feet)', ['class' => 'col-md-3 text-left required-star']) !!}
                                            <div class="col-md-9">
                                                <div class="col-md-6 {{ $errors->has('business_signboard_height') ? 'has-error' : ''}}">
                                                    {!! Form::label('business_signboard_height', 'Height', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::number('business_signboard_height',  $appInfoTradeLi->business_signboard_height, ['class' => 'form-control onlyNumber input-md required']) !!}

                                                        {!! $errors->first('business_signboard_height', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{ $errors->has('business_signboard_width') ? 'has-error' : ''}}">
                                                    {!! Form::label('business_signboard_width', 'Width', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::number('business_signboard_width',  $appInfoTradeLi->business_signboard_width, ['class' => 'form-control input-md onlyNumber required']) !!}

                                                        {!! $errors->first('business_signboard_width', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_factory') ? 'has-error' : ''}}">
                                            {!! Form::label('business_factory', 'Factory', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('business_factory', $factory,  $appInfoTradeLi->business_factory, ['class' => 'form-control required ','id'=>'business_factory']) !!}
                                                {!! $errors->first('business_factory', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('business_chemical') ? 'has-error' : ''}}">
                                            {!! Form::label('business_chemical', 'Chemical / Explosive', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('business_chemical', $chemical,  $appInfoTradeLi->business_chemical, ['class' => 'form-control required ','id'=>'business_chemical']) !!}
                                                {!! $errors->first('business_chemical', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_plot_type') ? 'has-error' : ''}}">
                                            {!! Form::label('business_plot_type', 'Plot Type', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('business_plot_type', $plotType,  $appInfoTradeLi->business_plot_type, ['class' => 'form-control required ','id'=>'business_plot_type']) !!}
                                                {!! $errors->first('business_plot_type', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('business_plot_category') ? 'has-error' : ''}}">
                                            {!! Form::label('business_plot_category', 'Plot Category', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('business_plot_category', $plotCategory,  $appInfoTradeLi->business_plot_category, ['class' => 'form-control required ','id'=>'business_plot_category']) !!}
                                                {!! $errors->first('business_plot_category', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_place') ? 'has-error' : ''}}">
                                            {!! Form::label('business_place', 'Place of Business', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('business_place', $placeOfBusiness,  $appInfoTradeLi->business_place, ['class' => 'form-control required ','id'=>'business_place']) !!}
                                                {!! $errors->first('business_place', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('business_activity_type') ? 'has-error' : ''}}">
                                            {!! Form::label('business_activity_type', 'Type of Activity', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('business_activity_type', $typeOfActivity,  $appInfoTradeLi->business_activity_type, ['class' => 'form-control required ','id'=>'business_activity_type']) !!}
                                                {!! $errors->first('business_activity_type', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-center stepHeader">Attachments</h3>
                        <fieldset>
                            <div id="docListDiv">
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>Necessary documents to be attached here (Only PDF file to be attach here)</strong>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover ">
                                                <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th colspan="6">Required attachments</th>
                                                    <th colspan="2">Attached PDF file (Each File Maximum size 2MB)
                                                        {{--<span>--}}
                                                        {{--<i title="Attached PDF file (Each File Maximum size 2MB)!" data-toggle="tooltip" data-placement="right" class="fa fa-question-circle" aria-hidden="true"></i>--}}
                                                        {{--</span>--}}
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1; ?>
                                                @foreach($document as $row)
                                                    <tr>
                                                        <td>
                                                            <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                        </td>
                                                        <td colspan="6">{!!  $row->doc_name !!}</td>
                                                        <td colspan="2">
                                                            <input name="document_id_<?php echo $row->id; ?>" type="hidden" value="{{(!empty($clrDocuments[$row->id]['document_id']) ? $clrDocuments[$row->id]['document_id'] : '')}}">
                                                            <input type="hidden" value="{!!  $row->doc_name !!}" id="doc_name_<?php echo $row->id; ?>" name="doc_name_<?php echo $row->id; ?>"/>
                                                            <input name="file<?php echo $row->id; ?>"
                                                                   <?php if (empty($clrDocuments[$row->id]['file']) && empty($allRequestVal["file$row->id"]) && $row->doc_priority == "1") {
                                                                       echo "class='required'";
                                                                   } ?>
                                                                   id="file<?php echo $row->id; ?>" type="file" size="20" onchange="uploadDocument('preview_<?php echo $row->id; ?>', this.id, 'validate_field_<?php echo $row->id; ?>', '<?php echo $row->doc_priority; ?>')"/>

                                                            @if($row->additional_field == 1)
                                                                <table>
                                                                    <tr>
                                                                        <td>Other file Name :</td>
                                                                        <td><input maxlength="64" class="form-control input-md <?php if ($row->doc_priority == "1") {
                                                                                echo 'required';
                                                                            } ?>" name="other_doc_name_<?php echo $row->id; ?>"
                                                                                   type="text" value="{{(!empty($clrDocuments[$row->id]['doc_name']) ? $clrDocuments[$row->id]['doc_name'] : '')}}">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            @endif

                                                            @if(!empty($clrDocuments[$row->id]['file']))
                                                                <div class="save_file saved_file_{{$row->id}}">
                                                                    <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->id]['file']) ?
                                                                $clrDocuments[$row->id]['file'] : ''))}}"
                                                                       title="{{$row->doc_name}}">
                                                                        <i class="fa fa-file-pdf-o"
                                                                           aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row->id]['file']); echo end($file_name); ?>
                                                                    </a>

                                                                    <?php if($viewMode != 'on') {?>
                                                                    <a href="javascript:void(0)" onclick="removeAttachedFile({!! $row->id !!}, {!! $row->doc_priority !!})"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a>
                                                                    <?php } ?>
                                                                </div>
                                                            @endif

                                                            <div id="preview_<?php echo $row->id; ?>">
                                                                <input type="hidden"
                                                                       value="<?php echo !empty($clrDocuments[$row->id]['file']) ?
                                                                           $clrDocuments[$row->id]['file'] : ''?>"
                                                                       id="validate_field_<?php echo $row->id; ?>"
                                                                       name="validate_field_<?php echo $row->id; ?>"
                                                                       class="<?php echo $row->doc_priority == "1" ? "required" : '';  ?>"/>
                                                            </div>

                                                            @if(!empty($allRequestVal["file$row->id"]))
                                                                <label id="label_file{{$row->id}}"><b>File: {{$allRequestVal["file$row->id"]}}</b></label>
                                                                <input type="hidden" class="required" value="{{$allRequestVal["validate_field_".$row->id]}}" id="validate_field_{{$row->id}}" name="validate_field_{{$row->id}}">
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($viewMode != 'off')
                                @include('LicenceApplication::trade-licence.doc-tab')
                            @endif
                        </fieldset>


                    @if(ACL::getAccsessRight('TradeLicence','-E-') && $viewMode == "off")
                            @if($appInfoTradeLi->status_id != 5)
                                <button type="submit" class="btn btn-info btn-md cancel"
                                        value="draft" name="actionBtn">Save as Draft
                                </button>
                                <div class="pull-right">
                                    <button type="submit" id="" style="cursor: pointer;"
                                            class="btn btn-info btn-md submit"
                                            value="Submit" name="actionBtn">Save & Next
                                    </button>
                                </div>
                            @endif

                        @else
                            <style>
                                .wizard > .actions {
                                    top: -15px !important;
                                }
                            </style>
                        @endif
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

<script type="text/javascript">

    $("#country option:not(:selected)").prop('disabled', true);
    $('#trade_licence').validate();

    /********Calculating the numbers of two fields inside multiple rowed tables******/
    function CalculatePercent(id) {
        var oneValue = parseFloat($("#" + id).val());

        if (oneValue > 100) {
            alert("Total percentage can't be more than 100");
        }
        var anotherVal = 100 - oneValue;
        if (id == 'local_sales_per') {
            $("#foreign_sales_per").val(anotherVal);
        } else {
            $("#local_sales_per").val(anotherVal);
        }

    }

    function imageDisplay(input, imageView, requiredSize = 0) {
        if (input.files && input.files[0]) {
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                alert("Image format is not valid. Please upload in jpg,jpeg or png format");
                $('#' + imageView).attr('src', '{{url('assets/images/photo_default.png')}}');
                $(input).val('').addClass('btn-danger').removeClass('btn-primary');
                return false;
            } else {
                $(input).addClass('btn-primary').removeClass('btn-danger');
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                //$('#'+imageView).attr('src', e.target.result);

                // check height-width
                // in funciton calling third parameter should be (requiredWidth x requiredHeight)
                if (requiredSize != 0) {
                    var size = requiredSize.split('x');
                    var requiredwidth = parseInt(size[0]);
                    var requiredheight = parseInt(size[1]);
                    if (requiredheight != 0 && requiredwidth != 0) {
                        var image = new Image();
                        image.src = e.target.result;
                        image.onload = function () {
                            if (requiredheight != this.height || requiredwidth != this.width) {
                                alert("Image size must be " + requiredSize);
                                $('#' + imageView).attr('src', '{{url('assets/images/photo_default.png')}}');
                                $(input).val('').addClass('btn-danger').removeClass('btn-primary');
                                return false;
                            } else {
                                $('#' + imageView).attr('src', e.target.result);
                            }
                        }
                    } else {
                        alert('Error in image required size!');
                    }
                }
                // if image height and width is not defined , means any size will be uploaded
                else {
                    $('#' + imageView).attr('src', e.target.result);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function setSelectedValue(form_field_id) {

        $("#" + form_field_id).change(function () {

            var selectedText = $(this).find(":selected").text();

            $('input[name="' + form_field_id + '_value"]').val(selectedText);
        });
    }

    function getZoneAreaForEdit() {
        var zoneID = parseInt('{{$appInfoTradeLi->business_zone}}');
        var wardID = parseInt('{{$appInfoTradeLi->business_ward}}');
        var areaID = parseInt('{{$appInfoTradeLi->business_area}}');
        getZoneArea('business_zone', {pid: 0, type: 0}, zoneID);
        getZoneArea('business_ward', {pid: zoneID, type: 1}, wardID);
        getZoneArea('business_area', {pid: zoneID, type: 3}, areaID);
    }


    function getZoneArea(form_field_id, data, zoneID) {
        $.ajax({
            type: "GET",
            url: "<?php echo url(); ?>/licence-applications/trade-licence/zone-area/",
            data: data,
            success: function (response) {
                var option = '<option value="">Select One</option>';
                if (response.responseCode == 1) {
                    $.each(response.data, function (id, value) {
                        if (value.id == zoneID) {
                            option += '<option value="' + value.id + '" selected>' + value.loc_name + '</option>';
                        } else {
                            option += '<option value="' + value.id + '">' + value.loc_name + '</option>';
                        }
                    });
                }
                $("#" + form_field_id).html(option);
            }
        });
    }

    function getCetegory(form_field_id, BCat) {
        $.ajax({
            type: "GET",
            url: "<?php echo url(); ?>/licence-applications/trade-licence/category-list",
            success: function (response) {
                var option = '<option value="">Select One</option>';
                if (response.responseCode == 1) {
                    $.each(response.data, function (id, value) {

                        if (value.CAT_ID == BCat) {
                            option += '<option value="' + value.CAT_ID + '" selected>' + value.NAME_EN + '</option>';
                        } else {
                            option += '<option value="' + value.CAT_ID + '">' + value.NAME_EN + '</option>';
                        }

                    });
                }
                $("#" + form_field_id).html(option);
            }
        });
    }

    function getSubCetegory(form_field_id, data, BSubCat) {
        $.ajax({
            type: "GET",
            url: "<?php echo url(); ?>/licence-applications/trade-licence/sub-category-list",
            data: data,
            success: function (response) {
                var option = '<option value="">Select One</option>';
                if (response.responseCode == 1) {
                    $.each(response.data, function (id, value) {
                        if (value.SCAT_ID == BSubCat) {
                            option += '<option value="' + value.SCAT_ID + '" selected>' + value.NAME_EN + '</option>';
                        } else {
                            option += '<option value="' + value.SCAT_ID + '">' + value.NAME_EN + '</option>';
                        }
                    });
                }
                $("#" + form_field_id).html(option);
            }
        });
    }

    function getCatAndSubCatForEdit() {
        var BCat = parseInt('{{$appInfoTradeLi->business_category}}');
        var BSubCat = parseInt('{{$appInfoTradeLi->business_sub_category}}');
        getCetegory('business_category', BCat);
        getSubCetegory('business_sub_category', {cat_id: BCat, cat_grp_id: 0}, BSubCat);

    }


    //--------File Upload Script Start----------//
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

        var inputFile =  $("#" + id).val();
        if(inputFile == ''){
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="'+vField+'" name="'+vField+'">';
            if ($('#label_' + id).length) $('#label_' + id).remove();
            return false;
        }

        try{
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{url('/licence-applications/trade-licence/upload-document')}}";

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
                url:action,
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response){
                    //console.log(response);
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = parseInt(id.substring(4));
                    var newInput = $('<label class="saved_file_'+doc_id+'" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] +
                        ' <a href="javascript:void(0)" onclick="EmptyFile('+ doc_id
                        +')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                    //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    //check valid data
                    var validate_field = $('#'+vField).val();
                    if(validate_field ==''){
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }
    //--------File Upload Script End----------//



    $(document).ready(function () {
        getZoneAreaForEdit();
        getCatAndSubCatForEdit();

        $("#business_category").change(function () {
            var categoryId = $(this).val();

            getSubCetegory('business_sub_category', {cat_id: categoryId, cat_grp_id: 0}, -2);
        });

        $("#business_zone").change(function () {
            var zoneId = $(this).val();
            getZoneArea('business_ward', {pid: zoneId, type: 1}, -2);
            getZoneArea('business_area', {pid: zoneId, type: 3}, -2);
        });
        setSelectedValue('business_zone');
        setSelectedValue('business_ward');
        setSelectedValue('business_area');
        setSelectedValue('business_category');
        setSelectedValue('business_sub_category');
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

        $('.datepicker_business_start').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
        });

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

        $('.datepickerDob').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 150),
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

    // Add table Row script
    function addTableRow1(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length - 1;
        var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
        var production_desc_val = $('#' + tableID).find('tr').last().find('.production_desc_1st').val();
        if (lastTr != '' && typeof lastTr !== "undefined") {
            rowCount = parseInt(lastTr) + 1;
        }
        //var rowCount = table.rows.length;
        //Increment id
        var rowCo = rowCount;
        var idText = 'rowCount' + tableID + rowCount;
        x.id = idText;
        $("#" + tableID).append(x);
        //get select box elements
        var attrSel = $("#" + tableID).find('#' + idText).find('select');
        //edited by ishrat to solve select box id auto increment related bug
        for (var i = 0; i < attrSel.length; i++) {
            var nameAtt = attrSel[i].name;
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
            attrSel[i].name = repText;
        }
        attrSel.val(''); //value reset
        // end of  solving issue related select box id auto increment related bug by ishrat

        //get input elements
        var attrInput = $("#" + tableID).find('#' + idText).find('input');
        for (var i = 0; i < attrInput.length; i++) {
            var nameAtt = attrInput[i].name;
            var nameAtt1 = attrInput[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repText1 = nameAtt.replace('[0]', '_' + rowCo);
            attrInput[i].name = repText;
            attrInput[i].id = repText1;
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
        $("#" + tableID).find('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });
    } // end of addTableRow() functionDistrict

    // Remove Table row script
    function removeTableRow(tableID, removeNum) {
        $('#' + tableID).find('#' + removeNum).remove();
    }


    function calculateAnnulCapacity(event) {
        var id = event.split(/[_ ]+/).pop();
        var no1 = $('#apc_quantity_' + id).val() ? parseFloat($('#apc_quantity_' + id).val()) : 0;
        var no2 = $('#apc_price_usd_' + id).val() ? parseFloat($('#apc_price_usd_' + id).val()) : 0;
        var bdtValue = $('#crvalue').val() ? parseFloat($('#crvalue').val()) : 0;
        var usdToBdt = bdtValue * no2;
        var total = (no1 * usdToBdt) / 1000000;

        $('#apc_value_taka_' + id).val(total);
    }


    function SumArguments() {
        var _arguments = arguments;
        this.sum = function () {
            var i = _arguments.length;
            var result = 0;
            while (i--) {
                result += _arguments[i];
            }
            return result;
        };
    }

    $(document).ready(function () {
                @if ($viewMode != 'on')
        var form = $("#trade_licence").show();
        form.find('#save_as_draft').css('display', 'none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
                @endif

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/visa-recommendation-amendment/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

        $('input[name=is_approval_online]:checked').trigger('click');

    });

    @if ($viewMode == 'on')
    $('#trade_licence .stepHeader').hide();
    $('#trade_licence :input').attr('disabled', true);
    $('#trade_licence').find('.MoreInfo').attr('disabled', false);
    // for those field which have huge content, e.g. Address Line 1
    $('.bigInputField').each(function () {
        if ($(this)[0]['localName'] == 'select') {
            $(this).attr('style', '-webkit-appearance: button; -moz-appearance: button; -webkit-user-select: none; -moz-user-select: none; text-overflow: ellipsis; white-space: pre-wrap; height: auto;');
        } else {
            $(this).replaceWith('<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">' + this.value + '</span>');
        }
    });
    $('#trade_licence :input[type=file]').hide();
    $('.addTableRows').hide();
    @endif // viewMode is on
</script>
