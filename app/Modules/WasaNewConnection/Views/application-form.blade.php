<?php
$accessMode = ACL::getAccsessRight('WasaNewConnection');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any querysss.');
}
?>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ url("assets/plugins/select2.min.css") }}">
<script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
<style>
    .wizard > .content,
    .wizard,
    .tabcontrol {
        overflow: visible;
    }

    .form-group {
        margin-bottom: 2px;
        margin-top: 10px;

    }

    .wizard > .steps > ul > li {
        width: 33% !important;
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
                        <h5><strong>Application for new water connection</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'wasa-new-connection/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewConnection',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>

                    <h3 class="text-center stepHeader"> Basic Information</h3>
                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Application Type</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('application_type') ? 'has-error': ''}}">
                                            {!! Form::label('application_type','Application Type',['class'=>'col-md-3 text-left ']) !!}
                                            <div class="col-md-7" id="application_type">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Basic Information</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('application_category','Application Category :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('application_category',[],'',['class' => 'form-control input-md','id'=>'application_category']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('wasa_zone','WASA Zone :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('wasa_zone',[],'',['class' => 'form-control input-md','id'=>'wasa_zone']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('water_connection_size','Water Connection Size :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('water_connection_size',[],'',['class' => 'form-control input-md','id'=>'water_connection_size']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('applicant_name','Applicants Name :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('applicant_name','',['class' => 'form-control input-md','id'=>'applicant_name','placeholder'=>'Type Applicants Name']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('mobile_number','Mobile Number :',['class'=>'col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('mobile_number', '',['class' => 'form-control
                                            input-md bd_mobile','id'=>'mobile_number','placeholder'=>'01799999999']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('email','Email :',['class'=>'col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('email', '',['class' => 'form-control
                                                input-md','id'=>'email','placeholder'=>'example@gmail.com']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="col-md-6" id="application_date">
                                        {!! Form::label('application_date','Application Date :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            <div class="currentDate input-group date" data-date-format="dd-mm-yyyy">

                                                {!! Form::text('application_date','',['class'=>'form-control','rows' => 5, 'cols' => 40,'placeholder'=>'dd-mm-yy','readonly'=>'readonly']) !!}
                                                <span class="input-group-addon calender-icon"
                                                      style="visibility: visible;"><span
                                                            class="glyphicon glyphicon-calendar"></span></span>
                                                {!! $errors->first('application_date','<small class="text-danger">:message</small>') !!}
                                            </div>

                                        </div>
                                    </div>


                                    <div class="col-md-6" id="application_con_date">
                                        {!! Form::label('application_con_date','Approximate Connection Date :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! $errors->first('application_con_date','<span class="help-block">:message</span>') !!}
                                            <div class="addonemonth input-group date" data-date-format="dd-mm-yyyy">

                                                {!! Form::text('application_con_date','',['class'=>'form-control','rows' => 5, 'cols' => 40,'placeholder'=>'dd-mm-yy','readonly'=>'readonly']) !!}
                                                <span class="input-group-addon calender-icon"
                                                      style="visibility: visible;"><span
                                                            class="glyphicon glyphicon-calendar"></span></span>
                                                {!! $errors->first('application_con_date','<small class="text-danger">:message</small>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Applicant Information</strong></div>
                            <div class="panel-body">
                                <div class="" id="InstituteApplication">
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            {!! Form::label('institute_name','Institutes Name :',['class'=>'text-left col-md-6 ']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('institute_name','',['class' => 'form-control input-md','id'=>'institute_name','placeholder'=>'Type Institutes Name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('telephone_number','Telephone Number :',['class'=>'col-md-6 ']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('telephone_number', '',['class' => 'form-control
                                                input-md phone_or_mobile','id'=>'telephone_number','placeholder'=>'01670222222']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="" id="IndividualApplication">
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            {!! Form::label('father_name','Fathers Name :',['class'=>'text-left col-md-6 ']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('father_name','',['class' => 'form-control input-md','id'=>'father_name','placeholder'=>'Type Fathers Name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('mother_name','Mothers Name :',['class'=>'text-left col-md-6 ']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('mother_name','',['class' => 'form-control input-md','id'=>'mother_name','placeholder'=>'Type Mothers Name']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <div class="col-md-6">
                                            {!! Form::label('spouse_name','Spouses Name :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('spouse_name','',['class' => 'form-control input-md','id'=>'spouse_name','placeholder'=>'Type Spouses Name']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('telephone','Telephone :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('telephone','',['class' => 'form-control input-md phone_or_mobile' ,'id'=>'telephone','placeholder'=>'0296888888']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6" id="date_of_birth">
                                            {!! Form::label('date_of_birth','Date of Birth :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                <div class="datepicker input-group date" data-date-format="dd-mm-yyyy">

                                                    {!! Form::text('date_of_birth','',['class'=>'form-control','rows' => 5, 'cols' => 40,'placeholder'=>'dd-mm-yy']) !!}
                                                    <span class="input-group-addon calender-icon"
                                                          style="visibility: visible;"><span
                                                                class="glyphicon glyphicon-calendar"></span></span>
                                                    {!! $errors->first('date_of_birth','<small class="text-danger">:message</small>') !!}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('gender','Gender :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('gender',[],'',['class' => 'form-control input-md','id'=>'gender']) !!}

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="radio-inline col-md-3">{!! Form::radio('niddobpassporttype','nid', '', ['class'=>'custom_readonly', 'id' => 'niddobpassporttype','onclick' => 'NidDobPassport(this.value)']) !!}
                                                    Nid</label>
                                                <label class="radio-inline col-md-3">{!! Form::radio('niddobpassporttype', 'dob', '', ['class'=>'custom_readonly ', 'id' => 'niddobpassporttype','onclick' => 'NidDobPassport(this.value)']) !!}
                                                    Birth Cert.</label>
                                                <label class="radio-inline col-md-3">{!! Form::radio('niddobpassporttype', 'passport', '', ['class'=>'custom_readonly ', 'id' => 'niddobpassporttype','onclick' => 'NidDobPassport(this.value)']) !!}
                                                    Passport</label>
                                            </div>
                                            <div class="col-md-12">
                                                {!! Form::text('niddobpassport','',['class' => 'form-control input-md col-md-6','id'=>'niddobpassportnumber','placeholder'=>'Type NID / Date of Birth / Passport']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('freedomfighter_status','Freedom Fighter Status :',['class'=>'col-md-6 text-left']) !!}
                                            <div class="col-md-6">
                                                <label class="radio-inline">{!! Form::radio('freedomfighter_status','yes', '', ['class'=>'custom_readonly', 'id' => 'freedomfighter_status','onclick' => 'FreedomFighter(this.value)']) !!}
                                                    Yes</label>
                                                <label class="radio-inline">{!! Form::radio('freedomfighter_status', 'no', '', ['class'=>'custom_readonly', 'id' => 'freedomfighter_status','onclick' => 'FreedomFighter(this.value)']) !!}
                                                    No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <div class="col-md-6" style="margin-top:10px">
                                            {!! Form::label('photo','Applicant Photo :', ['class'=>'col-md-6 col-xs-12 ']) !!}
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::file('photo', ['class'=>'form-control input-md', 'id' => 'photo','flag'=>'img','accept'=>'image/*','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1,'img'), imagePreview(this)"]) !!}
                                                <span id="span_photo"
                                                      style="font-size: 12px; font-weight: bold;color:#993333">[N.B. Supported file extension is png/jpg/jpeg.Max size less than 150KB]</span>
                                                <div id="preview_photo">
                                                    {!! Form::hidden('validate_field_photo','', ['class'=>'form-control input-md', 'id' => 'validate_field_photo']) !!}
                                                </div>
                                                <div class="col-md-5" style="position:relative;">
                                                    <img id="photo_viewer_photo"
                                                         style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"
                                                         src="{{(url('assets/images/no-image.png'))}}"
                                                         alt="photo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="FreedomFighterCert">
                                            {!! Form::label('freedom_freedom_photo','Freedom Fighter Certificate :', ['class'=>'col-md-6 col-xs-12 ']) !!}
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::file('freedom_photo', ['class'=>'form-control input-md', 'id' => 'freedom_photo','flag'=>'img','onchange'=>"FreedomuploadDocument('preview_freedom_photo', this.id, 'validate_field_freedom_photo',1,'img'), FreedomimagePreview(this)"]) !!}
                                                <span id="span_freedom_photo"
                                                      style="font-size: 12px; font-weight: bold;color:#993333">[N.B. Supported file extension is png/jpg/jpeg.Max size less than 150KB]</span>
                                                <div id="preview_freedom_photo">
                                                    {!! Form::hidden('validate_field_freedom_photo','', ['class'=>'form-control input-md', 'id' => 'validate_field_freedom_photo']) !!}
                                                </div>
                                                <div class="col-md-5" style="position:relative;">
                                                    <img id="freedom_photo_viewer_freedom_photo"
                                                         style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"
                                                         src="{{(url('assets/images/no-image.png'))}}"
                                                         alt="freedom_photo">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Connection Address</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('conn_address','Address :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::textarea('conn_address','',['class' => 'form-control input-md','id'=>'conn_address','style'=>'height: 60px;','placeholder'=>'Type Adress']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('c_district_citycorporation','District/City Corporation :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('c_district_citycorporation','',['class' => 'form-control input-md','id'=>'c_district_citycorporation','placeholder'=>'Type District/City Corporation']) !!}
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('c_union_ward','Union/Ward :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('c_union_ward','',['class' => 'form-control input-md','id'=>'c_union_ward','placeholder'=>'Type Union/Ward']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('c_district_city_area','District/City Corporation Area :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('c_district_city_area','',['class' => 'form-control input-md','id'=>'c_district_city_area','placeholder'=>'Type District/City Corporation Area']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row" id="presentaddresschecked">
                                    <div class="col-md-3"><strong>Present Address </strong></div>
                                    <div class="col-md-3" style="float:right;">
                                        <div class="col-md-1">
                                            <input type="checkbox" name="same_as_current" id="same_as_current">
                                        </div>
                                        {!! Form::label('same_as_current','Same as Connection Address',['class'=>'text-left col-md-10']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('present_address','Address :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::textarea('present_address','',['class' => 'form-control input-md','id'=>'present_address','style'=>'height: 60px;','placeholder'=>'Type  Address']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('p_district_citycorporation','District/City Corporation :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('p_district_citycorporation','',['class' => 'form-control input-md','id'=>'p_district_citycorporation','placeholder'=>'Type District/City Corporation']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('p_union_ward','Union/Ward :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('p_union_ward','',['class' => 'form-control input-md','id'=>'p_union_ward','placeholder'=>'Type Union/Ward']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('p_district_city_area','District/City Corporation Area :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('p_district_city_area','',['class' => 'form-control input-md','id'=>'p_district_city_area','placeholder'=>'Type District/City Corporation Area']) !!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Connection Information</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('structure_of_home','Structure of Home :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('structure_of_home',[],'',['class' => 'form-control input-md','id'=>'structure_of_home','placeholder'=>'Structure of Home']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('number_of_floor','Number of Floor :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('number_of_floor','',['class' => 'form-control input-md onlyNumber','id'=>'number_of_floor','placeholder'=>'10']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('landsize','Land Size :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            <div class="col-md-6" style="margin: 0px; padding: 0px;">
                                                {!! Form::text('landsize','',['class' => 'form-control input-md onlyNumber','id'=>'landsize','placeholder'=>'20']) !!}

                                            </div>
                                            <div class="col-md-6" style="margin: 0px; padding: 0px;">

                                                {!! Form::select('landsize_type',[],'',['class' => 'form-control input-md','id'=>'landsize_type']) !!}

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('no_of_flat','Number of Flat :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('no_of_flat','',['class' => 'form-control input-md onlyNumber','id'=>'no_of_flat','placeholder'=>'40']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('house_area','House Area :',['class'=>'text-left col-md-6 onlyNumber']) !!}
                                        <div class="col-md-6">
                                            <div class="col-md-6" style="margin: 0px; padding: 0px;">
                                                {{--<input name="house_area" type="text" maxlength="7" id="house_area" class="form-control">--}}

                                                {!! Form::text('house_area','',['class' => 'form-control input-md','id'=>'house_area','placeholder'=>'40']) !!}
                                            </div>
                                            <div class="col-md-6" style="margin: 0px; padding: 0px;">
                                                {!! Form::select('house_area_type',[
                                                       ''=>'Select One',
                                                       'Decimal'=>'Decimal',
                                                       'Square Ft'=>'Square Ft',
                                                       'Square Yard'=>'Square Yard',
                                                       'Katha'=>'Katha',
                                                       'Acre'=>'Acre',
                                                       ],'',['class' => 'form-control input-md onlyNumber','id'=>'house_area_type']) !!}

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('no_of_kitchen','Number of Kitchen :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('no_of_kitchen','',['class' => 'form-control input-md onlyNumber','id'=>'no_of_kitchen','placeholder'=>'2']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('sewer_line_status','Sewer Line Status :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('sewer_line_status',[],'',['class' => 'form-control input-md','id'=>'sewer_line_status']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('number_of_toilet','Number of Toilet :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('number_of_toilet','',['class' => 'form-control input-md onlyNumber','id'=>'number_of_toilet','placeholder'=>'5']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('water_res_capacity','Underground Water Resv. capacity (Litre):',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('water_res_capacity','',['class' => 'form-control input-md onlyNumber','id'=>'water_res_capacity' ,'placeholder'=>'50']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('number_of_user','Number of User:',['class'=>'col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('number_of_user', '',['class' => 'form-control
                                            input-md onlyNumber','id'=>'number_of_user']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('roof_water_res_capacity','Roof Top Water Resv. capacity (Litre):',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('roof_water_res_capacity','',['class' => 'form-control input-md onlyNumber','id'=>'roof_water_res_capacity','placeholder'=>'20']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('number_of_ex_conn','Number of Existing Connection:',['class'=>'col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('number_of_ex_conn',[
                                                        '0'=>'0',
                                                        '1'=>'1',
                                                        '2'=>'2',
                                                        '3'=>'3',
                                                        ],'',['class' => 'form-control input-md','id'=>'number_of_ex_conn']) !!}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">File Attachment</h3>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="docListDiv">
                                    @include('WasaNewConnection::documents')
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Payment and Submission</h3>
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
                                                Vat/ tax and service charge is an approximate amount, it may vary based
                                                on the
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
                                        class="btn btn-success btn-md" value="Submit" name="actionBtn">
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


        $("#InstituteApplication").hide(); //default IndividualApplication hidden
        $("#FreedomFighterCert").hide(); //default Freedom Fighter Photo  hidden
        $("#IndividualApplication").hide(); //default Freedom Fighter Photo  hidden


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
                if (currentIndex == 2) {
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
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=NewConnection@2'); ?>');
            } else {
                return false;
            }
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


    })


    //    Photo Delete Button for Applicant and Freedom Fighter Section start here
    $(document).on('click', '.filedeleteapplicant', function () {
        let abc = $(this).attr('docid');
        let sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = '';
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
            $("#photo_viewer_photo").attr("src", "{{url('assets/images/no-image.png') }}");

        } else {
            return false;
        }
    });

    $(document).on('click', '.filedelete', function () {
        let abc = $(this).attr('docid');
        let sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = '';
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
            $("#freedom_photo_viewer_freedom_photo").attr("src", "{{url('assets/images/no-image.png') }}");

        } else {
            return false;
        }
    });
    //    Photo Delete Button for Applicant and Freedom Fighter Section end here

</script>

<script>
    // set present address as connection address start here
    $(document).ready(function () {
        $("#same_as_current").click(function () {
            var c_union_ward = $('#c_union_ward').val();
            var conn_address = $('#conn_address').val();
            var c_district_city_area = $('#c_district_city_area').val();
            var c_district_citycorporation = $('#c_district_citycorporation').val();

            if ($(this).prop("checked")) {
                $('#p_union_ward').val(c_union_ward);
                $('#present_address').val(conn_address);
                $('#p_district_city_area').val(c_district_city_area);
                $('#p_district_citycorporation').val(c_district_citycorporation);
            } else {
                $('#p_union_ward').val('');
                $('#present_address').val('');
                $('#p_district_city_area').val('');
                $('#p_district_citycorporation').val('');
            }
        });
    });
    // set present address as connection address end here


    //Application type Checked start Here
    function ApplicationType(value) {
        var val = value.split('@')[0];
//        alert(val);
        {{--var val= $(this).val().split('@')[0] ;--}}
        if (val == 1) {
            $("#IndividualApplication").show();
            $("#InstituteApplication").hide();
        } else {
            $("#IndividualApplication").hide();
            $("#InstituteApplication").show();
        }
    }

    //Application type Checked end Here

    //FridoomFighter type Checked start Here
    function FreedomFighter(value) {
        if (value == 'yes') {
            $("#FreedomFighterCert").show();
        } else {
            $("#FreedomFighterCert").hide();
        }
        getDoc();
    }

    //FridoomFighter type Checked end Here


    //NidDobPassport type Checked start Here
    function NidDobPassport(value) {
        if (value == 'nid') {
            $('#niddobpassportnumber').addClass('onlyNumber');
            $('#niddobpassportnumber').addClass('bd_nid');
        } else if (value == 'dob') {
            $('#niddobpassportnumber').addClass('onlyNumber');
            $('#niddobpassportnumber').removeClass('bd_nid');

        } else {
            $('#niddobpassportnumber').removeClass('onlyNumber');
        }
    }

    //NidDobPassport type Checked end Here


    //Datepicker Start Here
    $(document).ready(function () {
        var today = new Date();
        var nextMonth = new Date();
        nextMonth.setMonth(today.getMonth()+1);
        var yyyy = today.getFullYear();
        var mm = today.getMonth();
        var dd = today.getDate();
        //        $("body").on('focus', '.datepicker', function () {
        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            maxDate: today,
            minDate: '01/01/' + (yyyy - 50),
            useCurrent: false
        });

        $('.addonemonth').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            maxDate: nextMonth,
            minDate: '01/01/' + (yyyy - 50),
            defaultDate:nextMonth,
            useCurrent: false
        });


        /* Date must should be minimum today */
        $('.currentDate').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            useCurrent:true,
            defaultDate:'now'
        });
    });
    //Datepicker Start Here


    //FridoomFighter and Applicant photo view start here

    function FreedomuploadDocument(targets, id, vField, isRequired) {
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
            var action = "{{URL::to('/wasa-new-connection/upload-document')}}";
            //alert(action);
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
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong." + err;
        }
    }

    function FreedomimagePreview(input) {
        if (input.files && input.files[0]) {
            var calling_id = input.id;
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#freedom_photo_viewer_" + calling_id).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
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
            var action = "{{URL::to('/wasa-new-connection/upload-document')}}";
            //alert(action);
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
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" class="filedeleteapplicant" docid="' + id + '" ><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
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
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong." + err;
        }
    }

    function imagePreview(input) {
        if (input.files && input.files[0]) {
            var calling_id = input.id;
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#photo_viewer_" + calling_id).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    //FridoomFighter and Applicant photo view end here


    /* Get information from API start here*/
    $(document).ready(function () {
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}"
            tokenUrl = '/wasa-new-connection/get-refresh-token'
            $('#application_category').keydown();
            $('#application_type').keydown();
            $('#wasa_zone').keydown();
            $('#gender').keydown();
            $('#structure_of_home').keydown();
            $('#landsize_type').keydown();
            $('#sewer_line_status').keydown();
            $('#water_connection_size').keydown();
//            alert('fdgjhfgdf');
        });

        $(function () {
            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/app-type";
            var selected_value = ''; // for callback
//            console.log(selected_value);
            var calling_id = 'application_type'; // for callback
            var element_id = "key"; //dynamic id for callback
            var element_name = "value"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

//                alert(element_name);
            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'

                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
                {
                    key: "agent-id",
                    value: '3'
                },
            ];

            apiCallGet(e, options, apiHeaders, appTypeCallBackRes, arrays);


        });


        $('#application_category').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/connection-type";
            var selected_value = ''; // for callback
//            console.log(selected_value);
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "text"; //dynamic name for callback
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
                {
                    key: "agent-id",
                    value: '3'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#application_category').on('change', function (el) {
            getDoc();
        });
        $('#wasa_zone').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/wasa-zones";
            console.log(api_url);
            var selected_value = ''; // for callback
//            console.log(selected_value);
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "text"; //dynamic name for callback
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
                {
                    key: "agent-id",
                    value: '3'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#gender').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/gender";
            console.log(api_url);
            var selected_value = ''; // for callback
//            console.log(selected_value);
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "key"; //dynamic id for callback
            var element_name = "value"; //dynamic name for callback
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
                {
                    key: "agent-id",
                    value: '3'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#structure_of_home').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/building-structure";
            console.log(api_url);
            var selected_value = ''; // for callback
//            console.log(selected_value);
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "key"; //dynamic id for callback
            var element_name = "value"; //dynamic name for callback
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
                {
                    key: "agent-id",
                    value: '3'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#landsize_type').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/land-size-unit";
            console.log(api_url);
            var selected_value = ''; // for callback
//            console.log(selected_value);
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "key"; //dynamic id for callback
            var element_name = "value"; //dynamic name for callback
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
                {
                    key: "agent-id",
                    value: '3'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#sewer_line_status').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/sewer-status";
            console.log(api_url);
            var selected_value = ''; // for callback
//            console.log(selected_value);
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "key"; //dynamic id for callback
            var element_name = "value"; //dynamic name for callback
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
                {
                    key: "agent-id",
                    value: '3'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#water_connection_size').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/water-pipe-diameter";
            console.log(api_url);
            var selected_value = ''; // for callback
//            console.log(selected_value);
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "text"; //dynamic name for callback
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
                {
                    key: "agent-id",
                    value: '3'
                },
            ];

            apiCallGet(e, options, apiHeaders, WaterConnectioncallbackResponse, arrays);

        })
    })

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

    function WaterConnectioncallbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name].replaceAll('"', "'");

                var value = row[element_name];
                if (selected_value == id) {
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

    /* Get information from API end here*/

    function appTypeCallBackRes(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];

//                option += '<label class="radio-inline"><input type="radio" id="app_type_'+row[element_id]+'"  value="' + id + '" onclick="ApplicationType(this.value)"/>'+ value +'</lebel>';

                option += '<input style="float:left;margin-left:5px" id="application_type_' + row[element_id] + '" type="radio" value="' + id + '" name="application_type" onclick="ApplicationType(this.value)"><label style="margin-left:5px" for="application_type_' + row[element_id] + '">' + value + '</label>';
//                option += '<input id="application_type_'+row[element_id]+'" type="radio" value="'+id+'" name="application_type" onclick="ApplicationType(this.value)">  <label for="application_type_'+row[element_id]+'" class="radio-inline">'+value+'</label>';
//                  option += '<input type="radio" name="application_type" id="application_type_'+row[element_id]+'"  value="'+id+'" onclick="ApplicationType(this.value)"><lebel for="application_type_'+row[element_id]+'" class="radio-inline">'+value+'</lebel>';
            });
        }

        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
        $("#" + calling_id).trigger('change')
    }

    /* Get information from API end here*/

</script>