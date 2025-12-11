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
                        <h5><strong>Edit APPLICATION FOR DEEP TUBEWELL</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'wasa-dt/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewConnection',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>

                    {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}
                    {!! Form::hidden('curr_process_status_id', $appInfo->status_id,['class' => 'form-control input-md required', 'id'=>'process_status_id']) !!}


                    <h3 class="text-center stepHeader"> Basic Information</h3>
                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Application Type

                                </strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('application_type','Application Type',['class'=>'col-md-3 text-left required-star']) !!}

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
                                        {!! Form::label('wasa_zone','WASA Zone',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('wasa_zone',[],isset($appData->wasa_zone) ? $appData->wasa_zone : '',['class' => 'form-control input-md required','id'=>'wasa_zone']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('applicant_name','Applicants Name :',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('applicant_name',isset($appData->applicant_name) ? $appData->applicant_name : '',['class' => 'form-control input-md','id'=>'applicant_name','placeholder'=>'Type Applicants Name']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('mobile_number','Mobile Number :',['class'=>'col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('mobile_number',isset($appData->mobile_number) ? $appData->mobile_number : '',['class' => 'form-control
                                            input-md bd_mobile','id'=>'mobile_number','placeholder'=>'01799999999']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('email','Email Number :',['class'=>'col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('email', isset($appData->email) ? $appData->email : '',['class' => 'form-control
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
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Applicant Information</strong></div>
                            <div class="panel-body">
                                <div class="" id="InstituteApplication">
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            {!! Form::label('institute_name','Institutes Name :',['class'=>'text-left col-md-6 required-star']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('institute_name',isset($appData->institute_name) ? $appData->institute_name : '',['class' => 'form-control input-md','id'=>'institute_name','placeholder'=>'Type Institutes Name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('telephone_number','Telephone Number :',['class'=>'col-md-6 required-star']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('telephone_number',isset($appData->telephone_number) ? $appData->telephone_number : '',['class' => 'form-control
                                                input-md phone_or_mobile','id'=>'telephone_number','placeholder'=>'01888788769']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            {!! Form::label('institute_owner_name','Institution Owner\'s Name :',['class'=>'text-left col-md-6 ']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('institute_owner_name',isset($appData->institute_owner_name) ? $appData->institute_owner_name : '',['class' => 'form-control input-md','id'=>'institute_owner_name','placeholder'=>'Institution Owner Name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('head_of_institute','Head of Institute :',['class'=>'col-md-6 ']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('head_of_institute', isset($appData->head_of_institute) ? $appData->head_of_institute : '',['class' => 'form-control
                                                input-md ','id'=>'head_of_institute']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div><!--#/InstituteApplication-->
                                <div class="" id="IndividualApplication">
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            {!! Form::label('father_name','Fathers Name :',['class'=>'text-left col-md-6 required-star']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('father_name',isset($appData->father_name) ? $appData->father_name : '',['class' => 'form-control input-md','id'=>'father_name','placeholder'=>'Type Fathers Name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('mother_name','Mothers Name :',['class'=>'text-left col-md-6 required-star']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('mother_name',isset($appData->mother_name) ? $appData->mother_name : '',['class' => 'form-control input-md','id'=>'mother_name','placeholder'=>'Type Mothers Name']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <div class="col-md-6">
                                            {!! Form::label('spouse_name','Spouses Name :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('spouse_name',isset($appData->spouse_name) ? $appData->spouse_name : '',['class' => 'form-control input-md','id'=>'spouse_name','placeholder'=>'Type Spouse Name']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('telephone','Telephone :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('telephone',isset($appData->telephone) ? $appData->telephone : '',['class' => 'form-control input-md phone_or_mobile','id'=>'telephone','placeholder'=>'0296777777']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            {!! Form::label('date_of_birth','Date of Birth :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                <div class="datepicker input-group date" data-date-format="dd-mm-yyyy">

                                                    {!! Form::text('date_of_birth',isset($appData->date_of_birth) ? $appData->date_of_birth : '',['class'=>'form-control','rows' => 5,'cols' => 40,'placeholder'=>'dd-mm-yy','id'=>'date_of_birth']) !!}
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
                                                {!! Form::select('gender',[],isset($appData->gender) ? $appData->gender : 'Select' ,['class' => 'form-control input-md','id'=>'gender']) !!}

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label class="radio-inline col-md-3">{!! Form::radio('niddobpassporttype','nid', (!empty($appData->niddobpassporttype) && $appData->niddobpassporttype =='nid' ? true:false ), ['class'=>'custom_readonly', 'id' => 'niddobpassporttype','onclick' => 'NidDobPassport(this.value)']) !!}
                                                    Nid {{$appInfo->niddobpassporttype}}</label>
                                                <label class="radio-inline col-md-3">{!! Form::radio('niddobpassporttype', 'dob',(!empty($appData->niddobpassporttype) && $appData->niddobpassporttype =='dob' ? true:false ), ['class'=>'custom_readonly ', 'id' => 'niddobpassporttype','onclick' => 'NidDobPassport(this.value)']) !!}
                                                    Birth Cert.</label>
                                                <label class="radio-inline col-md-3">{!! Form::radio('niddobpassporttype', 'passport',(!empty($appData->niddobpassporttype) && $appData->niddobpassporttype =='passport' ? true:false ), ['class'=>'custom_readonly ', 'id' => 'niddobpassporttype','onclick' => 'NidDobPassport(this.value)']) !!}
                                                    Passport</label>
                                            </div>
                                            <div class="col-md-12">
                                                {!! Form::text('niddobpassport',isset($appData->niddobpassport) ? $appData->niddobpassport : '',['class' => 'form-control input-md col-md-6','id'=>'niddobpassportnumber','placeholder'=>'Type NID / Date of Birth / Passport']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('freedomfighter_status','Freedom Fighter Status :',['class'=>'col-md-6 text-left']) !!}
                                            <div class="col-md-6">
                                                <label class="radio-inline">{!! Form::radio('freedomfighter_status','yes',(!empty($appData->freedomfighter_status) && $appData->freedomfighter_status =='yes' ? true:false ), ['class'=>'custom_readonly', 'id' => 'freedomfighter_status','onclick' => 'FreedomFighter(this.value)']) !!}
                                                    Yes</label>
                                                <label class="radio-inline">{!! Form::radio('freedomfighter_status', 'no',(!empty($appData->freedomfighter_status) && $appData->freedomfighter_status =='no' ? true:false ), ['class'=>'custom_readonly', 'id' => 'freedomfighter_status','onclick' => 'FreedomFighter(this.value)']) !!}
                                                    No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6 hidden" id="FreedomFighterCert">
                                            {!! Form::label('freedom_freedom_photo','Freedom Fighter Certificate :', ['class'=>'col-md-6 col-xs-12 required-star']) !!}
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::file('freedom_photo', ['class'=>'form-control input-md', 'id' => 'freedom_photo','flag'=>'img','onchange'=>"FreedomuploadDocument('preview_freedom_photo', this.id, 'validate_field_freedom_photo',1,'img'), FreedomimagePreview(this)"]) !!}
                                                <span id="span_freedom_photo"
                                                      style="font-size: 12px; font-weight: bold;color:#993333">[N.B. Supported file extension is png/jpg/jpeg.Max size less than 150KB]</span>
                                                <div id="preview_freedom_photo">
                                                    {!! Form::hidden('validate_field_freedom_photo',$appData->validate_field_freedom_photo, ['class'=>'form-control input-md', 'id' => 'validate_field_freedom_photo']) !!}
                                                </div>
                                                <div class="col-md-5" style="position:relative;">
                                                    <img id="freedom_photo_viewer_freedom_photo"
                                                         style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"
                                                         src="{{  (!empty($appData->validate_field_freedom_photo)? asset('uploads/'.$appData->validate_field_freedom_photo) : asset('assets/images/no-image.png')) }}"
                                                         alt="freedom_photo">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--#/FreedomFighterCert (form-group)-->
                                </div><!--#/IndividualApplication-->
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('photo','Applicant Photo :', ['class'=>'col-md-6 col-xs-12 required-star']) !!}
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::file('photo', ['class'=>'form-control input-md', 'id' => 'photo','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1,'img'), imagePreview(this)"]) !!}
                                            <span id="span_photo"
                                                  style="font-size: 12px; font-weight: bold;color:#993333">[N.B. Supported file extension is png/jpg/jpeg.Max size less than 150KB]</span>
                                            <div id="preview_photo">
                                                {!! Form::hidden('validate_field_photo',$appData->validate_field_photo, ['class'=>'form-control input-md', 'id' => 'validate_field_photo']) !!}
                                            </div>
                                            <div class="col-md-5" style="position:relative;">
                                                <img id="photo_viewer_photo"
                                                     style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"
                                                     src="{{  (!empty($appData->validate_field_photo)? asset('uploads/'.$appData->validate_field_photo) : asset('assets/images/no-image.png')) }}"
                                                     alt="photo">
                                            </div>
                                        </div>
                                    </div>
                                </div><!--#/photo (form-group)-->
                            </div>
                        </div><!--./panel (Applicant Information)-->
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Address of Deep Tubewell (Installation)</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        {!! Form::label('install_address','Address :',['class'=>'text-left col-md-2 ']) !!}
                                        <div class="col-md-10">
                                            {!! Form::textarea('install_address',isset($appData->install_address) ? $appData->install_address : '',['class' => 'form-control input-md','id'=>'install_address','style'=>'height: 60px;','placeholder'=>'Type Adress']) !!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div><!--./panel (Address of Deep Tubewell)-->
                        <div class="panel panel-primary">

                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-12"><strong>Location of the Deep Tubewell </strong></div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('mouza_name','Mouza Name :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::textarea('mouza_name',isset($appData->mouza_name) ? $appData->mouza_name : '',['class' => 'form-control input-md','id'=>'mouza_name','style'=>'height: 60px;','placeholder'=>'Mouza Name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('rs_book_no','R. S. Book No. :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('rs_book_no',isset($appData->rs_book_no) ? $appData->rs_book_no : '',['class' => 'form-control input-md','id'=>'rs_book_no','placeholder'=>'Type R. S. Book No.']) !!}
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('cs_book_no','C. S. Book No :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('cs_book_no',isset($appData->cs_book_no) ? $appData->cs_book_no : '',['class' => 'form-control input-md','id'=>'cs_book_no','placeholder'=>'Type C. S. Book No']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('sa_book_no','S. A. Book No. :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('sa_book_no',isset($appData->sa_book_no) ? $appData->sa_book_no : '',['class' => 'form-control input-md','id'=>'sa_book_no','placeholder'=>'Type S. A. Book No.']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row" id="presentaddresschecked">
                                    <div class="col-md-3"><strong>Present Address </strong></div>
                                    <div class="col-md-4" style="float:right;">
                                        <div class="col-md-1">
                                            <input type="checkbox" name="same_as_current" id="same_as_current">
                                        </div>
                                        {!! Form::label('same_as_current','Same as Installation Address',['class'=>'text-left col-md-10']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        {!! Form::label('present_address','Address :',['class'=>'text-left col-md-2 ']) !!}
                                        <div class="col-md-10">
                                            {!! Form::textarea('present_address',isset($appData->present_address) ? $appData->present_address : '',['class' => 'form-control input-md','id'=>'present_address','style'=>'height: 60px;','placeholder'=>'Type  Address']) !!}
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
                                        {!! Form::label('average_demand','Average Demand of Water (Litre/Day):',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('average_demand',isset($appData->average_demand) ? $appData->average_demand : '',['class' => 'form-control input-md onlyNumber','id'=>'average_demand' ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('usage_type','Type of Usage (in accordance with class of consumers) :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('usage_type',[],'',['class' => 'form-control input-md','id'=>'usage_type']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('screen_diameter','Screen Diameter (mm/inch) :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('screen_diameter',[],isset($appData->screen_diameter) ? $appData->screen_diameter : '',['class' => 'form-control input-md','id'=>'screen_diameter']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('power_of_pump','Power of Pump (kw/hp) :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('power_of_pump',[],isset($appData->power_of_pump) ? $appData->power_of_pump : '',['class' => 'form-control input-md','id'=>'power_of_pump']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('daily_running_time','Daily Pump Running Time (h):',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('daily_running_time',isset($appData->daily_running_time) ? $appData->daily_running_time : '',['class' => 'form-control input-md onlyNumber','id'=>'daily_running_time' ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('depth','Depth of the Deep-Tubewell (feet) :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('depth',isset($appData->depth)?$appData->depth:'',['class' => 'form-control input-md onlyNumber','id'=>'depth' ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row" id="presentaddresschecked">
                                    <div class="col-md-3"><strong>Existing Connections</strong></div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <td>Connection Type</td>
                                        <td>Account No</td>
                                        <td>Diameter</td>
                                        <td>Connection Date</td>
                                        <td>Arrears Paid</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{!! Form::select('conn_type[0]',[''=>'Select','0'=>'Water Connection','1'=>'Sewerage Connection'],isset($appData->conn_type[0])?$appData->conn_type[0]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('account_no[0]',isset($appData->account_no[0])?$appData->account_no[0]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('diameter[0]',isset($appData->diameter[0])?$appData->diameter[0]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('conn_date[0]',isset($appData->conn_date[0])?$appData->conn_date[0]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::select('arrears_paid[0]',['yes'=>'Yes','no'=>'No'],isset($appData->arrears_paid[0])?$appData->arrears_paid[0]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>{!! Form::select('conn_type[1]',[''=>'Select','0'=>'Water Connection','1'=>'Sewerage Connection'],isset($appData->conn_type[1])?$appData->conn_type[1]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('account_no[1]',isset($appData->account_no[1])?$appData->account_no[1]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('diameter[1]',isset($appData->diameter[1])?$appData->diameter[1]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('conn_date[1]',isset($appData->conn_date[1])?$appData->conn_date[1]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::select('arrears_paid[1]',['yes'=>'Yes','no'=>'No'],isset($appData->arrears_paid[1])?$appData->arrears_paid[1]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>{!! Form::select('conn_type[2]',[''=>'Select','0'=>'Water Connection','1'=>'Sewerage Connection'],isset($appData->conn_type[2])?$appData->conn_type[2]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('account_no[2]',isset($appData->account_no[2])?$appData->account_no[2]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('diameter[2]',isset($appData->diameter[2])?$appData->diameter[2]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('conn_date[2]',isset($appData->conn_date[2])?$appData->conn_date[2]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::select('arrears_paid[2]',['yes'=>'Yes','no'=>'No'],isset($appData->arrears_paid[2])?$appData->arrears_paid[2]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                    </tr>
                                    <tr>
                                        <td>{!! Form::select('conn_type[3]',[''=>'Select','0'=>'Water Connection','1'=>'Sewerage Connection'],isset($appData->conn_type[3])?$appData->conn_type[3]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('account_no[3]',isset($appData->account_no[3])?$appData->account_no[3]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('diameter[3]',isset($appData->diameter[3])?$appData->diameter[3]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::text('conn_date[3]',isset($appData->conn_date[3])?$appData->conn_date[3]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                        <td>{!! Form::select('arrears_paid[3]',['yes'=>'Yes','no'=>'No'],isset($appData->arrears_paid[3])?$appData->arrears_paid[3]:'',['class' => 'form-control input-md onlyNumber']) !!}</td>
                                    </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">File Attachment</h3>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="docListDiv">
                                    @include('WasaDeepTubewell::documents')
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Statement and Submission</h3>
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

//        $("#freedomfighter_status").trigger('change');

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


        $('.bd_mobile').on('keydown', function (e) {
            // valid format example: [+8801832782581, 008801832782581, 01832782581]
            return this.optional(element) || /(^(\+88|0088)?(01){1}[56789]{1}(\d){8})$/.test(value);
        });
        $('.phone_or_mobile').on('keydown', function (e) {
            // valid format example: [+8801832782581, 008801832782581, 01832782581]
            return this.optional(element) || /(^(\+88|0088)?(01){1}[56789]{1}(\d){8})$/.test(value);
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

        {{--$('.app_type').on('change', function (el) {--}}
        {{--//        var val = $(this).value;--}}
        {{--alert('dsafhg');--}}

        {{--if($(this).is(":checked")){--}}
        {{--alert('sdfh');--}}
        {{--var val= $(this).val().split('@')[0] ;--}}
        {{--alert(val);--}}
        {{--if (val == '1') {--}}
        {{--alert(val);--}}
        {{--$( "#IndividualApplication" ).removeClass('hidden');--}}
        {{--$( "#InstituteApplication" ).addClass('hidden');--}}
        {{--} else {--}}
        {{--alert(val);--}}
        {{--$('#InstituteApplication').removeClass('hidden');--}}
        {{--$( "#IndividualApplication" ).addClass('hidden');--}}
        {{--}--}}
        {{--}--}}

        {{--//        });--}}


        $("#same_as_current").click(function () {
            let install_address = $('#install_address').val();
            if ($(this).prop("checked")) {
                $('#present_address').val(install_address);
            }else {
                $('#present_address').val('');
            }
        });
    });
    // set present address as connection address end here


    //application type start here
    $(document).on('change', '.app_type', function (el) {
        if ($(this).is(":checked")) {
            var val = $(this).val().split('@')[0];
            if (val == '60') {
                $("#IndividualApplication").removeClass('hidden');
                $("#InstituteApplication").addClass('hidden');
                $("#institute_name").val("");
                $('#telephone_number').val("");

            } else if (val == '61') {
                $('#InstituteApplication').removeClass('hidden');
                $("#IndividualApplication").addClass('hidden');
                $('#father_name').val('');
                $('#mother_name').val('');
                $('#spouse_name').val('');
                $('#telephone').val('');
                $('#date_of_birth').val('');
                $('#gender').val('');
                $('#niddobpassportnumber').val('');
                //$('#photo_viewer_photo').attr("src", '{{(url('assets/images/no-image.png'))}}');
                //$('#freedom_photo_viewer_freedom_photo').attr("src", '{{(url('assets/images/no-image.png'))}}');

            } else {
                $("#IndividualApplication").addClass('hidden');
                $("#InstituteApplication").addClass('hidden');
            }
        }
        getDoc();
    });
    //application type start here


    //FridoomFighter type Checked start Here
    function FreedomFighter(value) {
//        alert(value);
        if (value == 'yes') {
            $("#FreedomFighterCert").removeClass('hidden');
        } else if (value == 'no') {
            $("#FreedomFighterCert").addClass('hidden');
            $('#freedom_photo_viewer_freedom_photo').attr("src", '{{(url('assets/images/no-image.png'))}}');
        } else {
            $("#FreedomFighterCert").addClass('hidden');
            $('#freedom_photo_viewer_freedom_photo').attr("src", '{{(url('assets/images/no-image.png'))}}');
        }
        getDoc();
    }

    //FridoomFighter type Checked end Here


    //NidDobPassport type Checked start Here
    function NidDobPassport(value) {
        if ((value == 'nid') || (value == 'dob')) {
            $('#niddobpassportnumber').addClass('onlyNumber');

        } else {
            $('#niddobpassportnumber').removeClass('onlyNumber');
        }
    }

    //NidDobPassport type Checked end Here


    //Datepicker Start Here
    $(document).ready(function () {
        var today = new Date();
        var nextMonth = new Date();
        nextMonth.setMonth(today.getMonth() + 1);
        var yyyy = today.getFullYear();
        var mm = today.getMonth();
        var dd = today.getDate();
        //        $("body").on('focus', '.datepicker', function () {
        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            maxDate: today,
        });
        $('.addonemonth').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            maxDate: nextMonth,
            minDate: '01/01/' + (yyyy - 50),
            defaultDate: nextMonth,
            useCurrent: false
        });

        /* Date must should be minimum today */
        $('.currentDate').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            useCurrent: true,
            defaultDate: 'now'
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
            var action = "{{URL::to('/wasa-dt/upload-document')}}";
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
            var action = "{{URL::to('/wasa-dt/upload-document')}}";
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

</script>


<script>
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
    $(document).ready(function () {

//    Deafault Freedom Fighter Check start here
        var freedomfighter_status = $("input[type=radio][name=freedomfighter_status]:checked").val();

        if (freedomfighter_status == 'yes') {
            $("#FreedomFighterCert").removeClass('hidden');
        } else if (freedomfighter_status == 'no') {
            $("#FreedomFighterCert").addClass('hidden');
            $('#freedom_photo_viewer_freedom_photo').attr("src", '{{(url('assets/images/no-image.png'))}}');
        } else {
            $("#FreedomFighterCert").addClass('hidden');
        }
//    Deafault Freedom Fighter Check start here

    });

    /* Get information from API start here*/
    $(document).ready(function () {
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });
        $(function () {
            token = "{{$token}}"
            tokenUrl = '/wasa-dt/get-refresh-token'
            $('#usage_type').keydown();
            $('#screen_diameter').keydown();
            $('#wasa_zone').keydown();
            $('#gender').keydown();
            $('#power_of_pump').keydown();
        });

        $(function () {
            var e = $(this);
            var selected_value = '{{!empty($appData->application_type) ? $appData->application_type : '' }}'; // for callback
            var api_url = "{{$wasa_service_url}}/info/app-type";
            var calling_id = 'application_type'; // for callback
            var element_id = "key"; //dynamic id for callback
            var element_name = "value"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, appTypeCallBackRes, arrays);
        });

        $('#usage_type').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/connection-type";
            var selected_value = '{{!empty($appData->usage_type) ? $appData->usage_type : '' }}'; // for callback
//            console.log(selected_value);
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "text"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })


        $('#wasa_zone').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/wasa-zones";
            var selected_value = '{{!empty($appData->wasa_zone) ? $appData->wasa_zone : '' }}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "text"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback


            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#screen_diameter').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/screen-diameter";
            var selected_value = '{{!empty($appData->screen_diameter) ? $appData->screen_diameter : '' }}'; // for callback
            if (selected_value != '') {
                selected_value = selected_value.replaceAll('&#039;', "'");
                selected_value = selected_value.replaceAll('&Prime;', "″");
            }

            var calling_id = $(this).attr('id'); // for callback

            var element_id = "id"; //dynamic id for callback
            var element_name = "text"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, WaterConnectioncallbackResponse, arrays);

        })
        $('#gender').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/gender";
            var selected_value = '{{!empty($appData->gender) ? $appData->gender : '' }}'; // for callback

            var calling_id = $(this).attr('id'); // for callback
            var element_id = "key"; //dynamic id for callback
            var element_name = "value"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#power_of_pump').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');

            var e = $(this);

            var api_url = "{{$wasa_service_url}}/info/power-of-pump";
            var selected_value = '{{!empty($appData->power_of_pump) ? $appData->power_of_pump : '' }}'; // for callback
            //console.log('power_of_pump','selected_value',selected_value);
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "text"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback


            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });


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

    function appTypeCallBackRes(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];

                if (selected_value == id) {
                    option += '<input class="app_type" style="float:left;margin-left:5px" id="application_type_' + row[element_id] + '" type="radio" value="' + id + '" name="application_type" checked><label style="margin-left:5px" for="application_type_' + row[element_id] + '">' + value + '</label>';

                } else {
                    option += '<input class="app_type" style="float:left;margin-left:5px" id="application_type_' + row[element_id] + '" type="radio" value="' + id + '" name="application_type"><label style="margin-left:5px" for="application_type_' + row[element_id] + '">' + value + '</label>';
                }
            });
        }

        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide();
        $(".app_type").trigger('change')
    }

    /* Get information from API end here*/


</script>


