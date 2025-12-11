
    <?php
    $moduleName = Request::segment(1);
    $user_type = CommonFunction::getUserType();
    $accessMode = "V";
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');

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
            <div class="box">
                <div class="box-body" id="inputForm">
                    {{--start application form with wizard--}}
                    {!! Session::has('success') ? '
                    <div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                    ' : '' !!}

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h5><strong> Apply for Trade License to Bangladesh</strong></h5>
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
                            {!! Form::open(array('url' => 'single-licence/trade-licence/add','method' => 'post','id' => 'trade-licence','role'=>'form','files' => true, 'enctype'=>'multipart/form-data')) !!}
                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>
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
                                                    {!! Form::select('country', $countries, $basicAppInfo->ceo_country_id, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                    {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>


                                            <div class="col-md-6 {{$errors->has('applicant_pic') ? 'has-error': ''}}">
                                                <div class="col-sm-7">
                                                    {!! Form::label('applicant_pic','Picture of CEO/MD/Head of Organization', ['class'=>'text-left required-star','style'=>'']) !!}
                                                    <input type="file" name="applicant_pic" id="applicant_pic"
                                                           class="form-control input-md required"
                                                           onchange="imageDisplay(this,'picture_CEO', '300x300')"/>
                                                    <span class="text-success"
                                                          style="font-size: 9px; font-weight: bold; display: block;">[File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]</span>
                                                    {!! $errors->first('applicant_pic','<span class="help-block">:message</span>') !!}
                                                </div>
                                                <div class="col-md-5">
                                                    <img class="img-thumbnail" id="picture_CEO"
                                                         src="{{ url('assets/images/photo_default.png') }}"
                                                         alt="CEO Photo">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{ $errors->has('organization_name') ? 'has-error' : ''}}">
                                                {!! Form::label('organization_name', 'Name of Organization', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('organization_name', $basicAppInfo->company_name, ['class' => 'form-control input-md required', 'readonly']) !!}

                                                    {!! $errors->first('organization_name', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('spouse_name') ? 'has-error' : ''}}">
                                                {!! Form::label('spouse_name', 'Spouse Name', ['class' => 'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('spouse_name', $basicAppInfo->ceo_spouse_name, ['class' => 'form-control input-md']) !!}

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
                                                    {!! Form::text('applicant_name', $basicAppInfo->ceo_full_name, ['class' => 'form-control input-md required', 'readonly']) !!}

                                                    {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('applicant_email') ? 'has-error' : ''}}">
                                                {!! Form::label('applicant_email', 'E-mail', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::email('applicant_email', $basicAppInfo->ceo_email, ['class' => 'form-control input-md required email', 'readonly']) !!}

                                                    {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{ $errors->has('applicant_father') ? 'has-error' : ''}}">
                                                {!! Form::label('applicant_father', 'Father\'s Name', ['class' => 'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_father', $basicAppInfo->ceo_father_name, ['class' => 'form-control input-md', 'readonly']) !!}

                                                    {!! $errors->first('applicant_father', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('applicant_licence_type') ? 'has-error' : ''}}">
                                                {!! Form::label('applicant_license_type', 'License Type', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('applicant_license_type', $licenceType, null, ['class' => 'form-control required ','id'=>'applicant_license_type']) !!}

                                                    {!! $errors->first('applicant_license_type', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{ $errors->has('applicant_mother') ? 'has-error' : ''}}">
                                                {!! Form::label('applicant_mother', 'Mother\'s Name', ['class' => 'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_mother', $basicAppInfo->ceo_mother_name, ['class' => 'form-control input-md', 'readonly']) !!}

                                                    {!! $errors->first('applicant_mother', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('applicant_dob') ? 'has-error' : ''}}">
                                                {!! Form::label('applicant_dob', 'Date of Birth', ['class' => 'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepickerDob input-group date">
                                                        {!! Form::text('applicant_dob', !empty($basicAppInfo->ceo_dob) ? (date('d-M-Y', strtotime($basicAppInfo->ceo_dob))) : '', ['class' => 'form-control input-md', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span
                                                                    class="fa fa-calendar"></span></span>
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
                                                    {!! Form::text('business_name', null, ['class' => 'form-control input-md required']) !!}

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
                                                    {!! Form::textarea('business_details', null, ['class' => 'form-control input-md required', 'rows' => 2]) !!}
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
                                                    {!! Form::text('business_holding', null, ['class' => 'form-control input-md required']) !!}

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
                                                    {!! Form::textarea('business_address', null, ['class' => 'form-control input-md required', 'rows' => 2]) !!}
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
                                                    {!! Form::text('business_road', null, ['class' => 'form-control input-md required']) !!}

                                                    {!! $errors->first('business_road', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('business_ward') ? 'has-error' : ''}}">
                                                {!! Form::label('business_ward', 'Ward / Market', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_ward', [], null, ['class' => 'form-control input-md required']) !!}
                                                    {!! Form::hidden('business_ward_value', null, []) !!}

                                                    {!! $errors->first('business_ward', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{ $errors->has('business_zone') ? 'has-error' : ''}}">
                                                {!! Form::label('business_zone', 'Zone / Market Branch', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_zone', [], null, ['class' => 'form-control input-md required','id'=>'business_zone']) !!}
                                                    {!! Form::hidden('business_zone_value', '', []) !!}

                                                    {!! $errors->first('business_zone', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('business_market_name') ? 'has-error' : ''}}">
                                                {!! Form::label('business_market_name', 'Name of Market', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('business_market_name', null, ['class' => 'form-control input-md required']) !!}

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
                                                    {!! Form::select('business_area', [], null, ['class' => 'form-control input-md required']) !!}
                                                    {!! Form::hidden('business_area_value', null, []) !!}

                                                    {!! $errors->first('business_area', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('business_shop') ? 'has-error' : ''}}">
                                                {!! Form::label('business_shop', 'Shop No', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('business_shop', null, ['class' => 'form-control input-md required']) !!}

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
                                                    {!! Form::number('business_floor', null, ['class' => 'form-control input-md required onlyNumber']) !!}

                                                    {!! $errors->first('business_floor', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('business_nature') ? 'has-error' : ''}}">
                                                {!! Form::label('business_nature', 'Nature of Business', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_nature', $businessNature, null, ['class' => 'form-control input-md required']) !!}

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
                                                        {!! Form::text('business_start_date', null, ['class' => 'form-control input-md required', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span
                                                                    class="fa fa-calendar"></span></span>
                                                    </div>

                                                    {!! $errors->first('business_start_date', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('business_sub_category') ? 'has-error' : ''}}">
                                                {!! Form::label('business_sub_category', 'Business Sub-category', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_sub_category', [], null, ['class' => 'form-control input-md required','id'=>'business_sub_category']) !!}
                                                    {!! Form::hidden('business_sub_category_value', null, []) !!}
                                                    {!! $errors->first('business_sub_category', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{ $errors->has('business_category') ? 'has-error' : ''}}">
                                                {!! Form::label('business_category', 'Business Category', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_category', [], null, ['class' => 'form-control input-md required','id'=>'business_category']) !!}
                                                    {!! Form::hidden('business_category_value',null, []) !!}
                                                    {!! $errors->first('business_category', '<span class="help-block">:message</span>') !!}
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
                                                            {!! Form::number('business_signboard_height', null, ['class' => 'form-control onlyNumber input-md required']) !!}

                                                            {!! $errors->first('business_signboard_height', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{ $errors->has('business_signboard_width') ? 'has-error' : ''}}">
                                                        {!! Form::label('business_signboard_width', 'Width', ['class' => 'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::number('business_signboard_width', null, ['class' => 'form-control input-md onlyNumber required']) !!}

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
                                                    {!! Form::select('business_factory', $factory, null, ['class' => 'form-control required ','id'=>'business_factory']) !!}
                                                    {!! $errors->first('business_factory', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('business_chemical') ? 'has-error' : ''}}">
                                                {!! Form::label('business_chemical', 'Chemical / Explosive', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_chemical', $chemical, null, ['class' => 'form-control required ','id'=>'business_chemical']) !!}
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
                                                    {!! Form::select('business_plot_type', $plotType, null, ['class' => 'form-control required ','id'=>'business_plot_type']) !!}
                                                    {!! $errors->first('business_plot_type', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('business_plot_category') ? 'has-error' : ''}}">
                                                {!! Form::label('business_plot_category', 'Plot Category', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_plot_category', $plotCategory, null, ['class' => 'form-control required ','id'=>'business_plot_category']) !!}
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
                                                    {!! Form::select('business_place', $placeOfBusiness, null, ['class' => 'form-control required ','id'=>'business_place']) !!}
                                                    {!! $errors->first('business_place', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{ $errors->has('business_activity_type') ? 'has-error' : ''}}">
                                                {!! Form::label('business_activity_type', 'Type of Activity', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_activity_type', $typeOfActivity, null, ['class' => 'form-control required ','id'=>'business_activity_type']) !!}
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
                                                    <?php $i = 1;?>

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
                            </fieldset>

                            @if(ACL::getAccsessRight('SingleLicence','-E-'))
                                <button type="submit" class="btn btn-info btn-md cancel"
                                        value="draft" name="actionBtn">Save as Draft
                                </button>
                                <button type="submit" class="btn btn-info btn-md submit pull-right"
                                        value="save" name="actionBtn">Save & Next
                                </button>
                            @endif
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
    <script type="text/javascript">
        // $('#trade-licence').validate();
        $('.cancel').on('click', function () {
            $('input, select, textarea').removeClass('required');
        });

        $("#country option:not(:selected)").prop('disabled', true);



        function setSelectedValue(form_field_id) {

            $("#" + form_field_id).change(function () {

                var selectedText = $(this).find(":selected").text();

                $('input[name="'+form_field_id+'_value"]').val(selectedText);
            });
        }

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

            getZoneArea('business_zone', {pid : 0, type : 0 });

            getCetegory('business_category');

            $("#business_zone").change(function () {
                var zoneId = $(this).val();

                getZoneArea('business_ward', {pid : zoneId, type : 1 });
                getZoneArea('business_area', {pid : zoneId, type : 3 });
            });

            $("#business_category").change(function () {
                var categoryId = $(this).val();

                getSubCetegory('business_sub_category', {cat_id : categoryId, cat_grp_id : 0 });
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
            $('.datepicker_business_start').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
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

    </script>

