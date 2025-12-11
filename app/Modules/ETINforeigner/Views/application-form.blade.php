<?php
$accessMode = ACL::getAccsessRight('eTINforeigner');
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
                        <h5><strong>Application For E-TIN-Foreigner</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'e-tin-foreigner/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'TINforeigner',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>

                    <h3 class="text-center stepHeader"> Registration</h3>
                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Registration</strong></div>
                            <div class="panel-body">
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>Work Permit</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('is_approval_online') ? 'has-error': ''}}">
                                                    {!! Form::label('is_approval_online','Did you received work permit through BIDA OSS?',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        <label class="radio-inline">{!! Form::radio('is_approval_online','yes', (Session::get('wpneInfo.is_approval_online') == 'yes' ? true :false), ['class'=>'custom_readonly required helpTextRadio', 'id' => 'is_approval_online_yes', 'onclick' => 'etinApplication(this.value)']) !!}
                                                            Yes</label>
                                                        <label class="radio-inline">{!! Form::radio('is_approval_online', 'no', (Session::get('wpneInfo.is_approval_online') == 'no' ? true :false), ['class'=>'custom_readonly required', 'id' => 'is_approval_online_no', 'onclick' => 'etinApplication(this.value)']) !!}
                                                            No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div id="ref_app_tracking_no_div"
                                                     class="col-md-12 hidden {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ref_app_tracking_no','Please give your approved Work permit reference No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        <div class="input-group">
                                                            {!! Form::text('ref_app_tracking_no',  Session::get('wpneInfo.ref_app_tracking_no'), ['data-rule-maxlength'=>'100', 'class' => 'form-control custom_readonly input-sm']) !!}
                                                            {!! $errors->first('ref_app_tracking_no','<span class="help-block">:message</span>') !!}
                                                            <span class="input-group-btn">
                                                            @if(Session::get('wpneInfo'))
                                                                    <button type="submit" class="btn btn-danger btn-sm cancel"
                                                                            value="clean_load_data"
                                                                            name="actionBtn">Clear Loaded Data</button>
                                                                    <a href=""
                                                                       target="_blank" class="btn btn-success btn-sm">View Certificate</a>
                                                                @else
                                                                    <button type="submit" class="btn btn-success btn-sm"
                                                                            value="searchWPNinfo" name="searchWPNinfo" id="searchWPNinfo">Load Work permit Data</button>
                                                                @endif
                                                        </span>
                                                        </div>
                                                        <small class="text-danger">N.B.: Once you save or submit the application, the work permit
                                                            tracking no cannot be changed anymore.</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="registration_div">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="border-top:2px solid #eee;border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                                                <h4 style="margin-left:15px; color:#564c4c;">Registration</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('taxpayer_status','Taxpayers Status / করদাতার ধরণ : a) :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('taxpayer_status',[],'',['class' => 'form-control input-md none-pointer','readonly','id'=>'taxpayer_status']) !!}
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        {!! Form::label('taxpayer_status_b','b) :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('taxpayer_status_b',[],'',['class' => 'form-control input-md none-pointer','readonly','id'=>'taxpayer_status_b']) !!}

                                        </div>
                                         <span id="NBforForeign">Please note that along with your passport size photograph, you will have to show the original passport to NBR officials or photocopy of relevant pages of your passport verified by any Bangladesh Embassy or an Embassy of the Country of which your passport was issued by.</span>
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <div class="col-md-6" id="country_id_div">
                                            {!! Form::label('country_id','Country / দেশ :',['class'=>'text-left col-md-6 required-star']) !!}
                                            <div class="col-md-6">
                                                <?php
                                                $applicantCountry = '';
                                                if(!empty(Session::get('wpneInfo.emp_nationality_id')))
                                                    $applicantCountry = \App\Modules\Users\Models\Countries::where('id',Session::get('wpneInfo.emp_nationality_id'))->value('name');
                                                ?>
                                                {!! Form::select('country_id',[],'',['class' => 'form-control input-md country_id required search-box','data-value'=>ucwords(strtolower($applicantCountry)),'id'=>'country_id']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('registration_type','Registration Type / রেজিস্ট্রেশন ধরণ :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('registration_type',['1@New Registration'=>'New Registration'],'',['class' => 'form-control input-md','id'=>'registration_type']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            {!! Form::label('main_source_income','Main Source of Income / আয়ের প্রধান উৎস  :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('main_source_income',[],'',['class' => 'form-control input-md none-pointer','id'=>'main_source_income','readonly']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('localtion_main_source_income','Location of main source of income :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('localtion_main_source_income',[],'',['class' => 'form-control input-md search-box','id'=>'localtion_main_source_income']) !!}
                                            </div>
                                        </div>
                                    </div>


{{--                                <div class="form-group" id="businessIndividual">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('business_individual','Business (Individual/Firm) :',['class'=>'text-left col-md-6']) !!}--}}
{{--                                            <div class="col-md-6">--}}
{{--                                                {!! Form::select('business_individual',[''=>'Select One','3@Business Type'=>'Business Type','4@Location'=>'Location'],'',['class' => 'form-control input-md','id'=>'business_individual']) !!}--}}
{{--                                                <span>If Main Business Type is not in list, please click on location from above and select your desired location name.</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    <div class="col-md-6" id="business_type_div">--}}
{{--                                        {!! Form::label('business_type','Business Type :',['class'=>'text-left col-md-6 required-star']) !!}--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::select('juri_select_list_no',[],'',['class' => 'col-md-6 form-control input-md','id'=>'business_type']) !!}--}}
{{--                                            {!! Form::text('juri_sub_list_name','',['class' => 'col-md-6 form-control input-md','readonly','id'=>'business_type_data']) !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    </div>--}}
                                <div class="form-group">
                                        <div class="col-md-6" id="business_location_div">
                                            {!! Form::label('business_location','Type of Employer/ Service Location :',['class'=>'text-left col-md-6 required-star']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('juri_select_list_no',[],'',['class' => 'form-control input-md required search-box','id'=>'business_location']) !!}<br>
                                                {!! Form::text('list_name','',['class' => 'col-md-6 form-control input-md hidden','readonly','id'=>'div_select_list_name']) !!}
                                            </div>
                                        </div>
                                    <div class="col-md-6 hidden" id="organization_id_div">
                                            {!! Form::label('juri_sub_list_name','',['class'=>'text-left col-md-6 required-star','id'=>'organization_id_label']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('juri_sub_list_no',[],'',['class' => 'form-control input-md search-box','id'=>'organization_id']) !!}<br>
                                                {!! Form::text('list_name2','',['class' => 'col-md-6 form-control input-md','readonly','id'=>'organization_id_text']) !!}
                                            </div>
                                        </div>

                                    </div>

                                <div class="form-group">
                                <div class="col-md-6 hidden" id="organization_name_div">
                                    {!! Form::label('juri_sub_list_name','Organization/Institution Name :',['class'=>'text-left col-md-6 required-star']) !!}
                                    <div class="col-md-6">
                                        {!! Form::text('juri_sub_list_name','',['class' => 'form-control input-md','id'=>'organization_name']) !!}
                                    </div>
                                </div>
                                </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </fieldset>
                    <h3 class="text-center stepHeader"> Basic Information</h3>
                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Basic Information</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('taxpayer_name','Taxpayer s Name :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('taxpayer_name',Session::get('wpneInfo.emp_name'),['class' => 'form-control input-md','id'=>'taxpayer_name']) !!}
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
                                        {!! Form::label('date_of_birth','Date of Birth (DoB) :',['class'=>'text-left col-md-6']) !!}
                                        <div class="datepickerDob col-md-6 date">
                                            {!! Form::text('date_of_birth',!empty(Session::get('wpneInfo.emp_date_of_birth'))?date('d-M-Y',strtotime(Session::get('wpneInfo.emp_date_of_birth'))):'',['class' => 'form-control
                                            input-md','id'=>'date_of_birth','style'=>'background:white;','autocomplete'=>'off']) !!}
                                            {!! $errors->first('applicant_dob','<span
                                                class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('father_name','Fathers Name :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('father_name','',['class' => 'form-control input-md','id'=>'father_name']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('mother_name','Mothers Name :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('mother_name','',['class' => 'form-control input-md','id'=>'mother_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('spouse_name','Name of Spouse:',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('spouse_name','',['class' => 'form-control input-md','id'=>'spouse_name']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="" id="incomesourceprofession">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('passport_number','Passport Number:',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('passport_number',Session::get('wpneInfo.emp_passport_no'),['class' => 'form-control input-md','id'=>'passport_number']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('passport_type','Passport Type :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('passport_type',['1@MRP'=>'MRP','0@Hand Written'=>'Hand Written'],'',['class' => 'form-control input-md','id'=>'passport_type']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('passport_issue_date','Passport Issue Date:',['class'=>'col-md-6']) !!}
                                        <div class="datepickerPassportIssue col-md-6 date">
                                            {!! Form::text('passport_issue_date', !empty(Session::get('wpneInfo.pass_issue_date'))?date('d-M-Y',strtotime(Session::get('wpneInfo.pass_issue_date'))):'',['class' => 'form-control
                                            input-md','id'=>'passport_issue_date','style'=>'background:white;','autocomplete'=>'off']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('passport_expiry_date','Passport Expiry Date:',['class'=>'col-md-6']) !!}
                                        <div class="datepickerPassExP col-md-6 date">
                                            {!! Form::text('passport_expiry_date', !empty(Session::get('wpneInfo.pass_expiry_date'))?date('d-M-Y',strtotime(Session::get('wpneInfo.pass_expiry_date'))):'',['class' => 'form-control
                                            input-md','id'=>'passport_expiry_date','style'=>'background:white;','autocomplete'=>'off']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('visa_number','Visa Number :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('visa_number','',['class' => 'form-control input-md','id'=>'visa_number']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('visa_issue_date','Visa Issue Date:',['class'=>'col-md-6']) !!}
                                        <div class="datepickerOld col-md-6 date">
                                            {!! Form::text('visa_issue_date', '',['class' => 'form-control
                                            input-md','id'=>'visa_issue_date','style'=>'background:white;','autocomplete'=>'off']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div id="non_bangladeshi">
                                    <div class="form-group" id="withoutWpdiv" style="display: none;">
                                        <div class="col-md-6">
                                            {!! Form::label('visa_number','Director Foreigner without Work Permit? :',['class'=>'text-left col-md-6']) !!}
                                            <div class="col-md-6">
                                                <input type="checkbox" name="director_foreigner" id="director_foreigner" value="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="without_workpermit">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                {!! Form::label('company_tin','Company TIN:',['class'=>'col-md-6 required-star']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text('company_tin', '',['class' => 'form-control
                                                    input-md','id'=>'company_tin']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="workpermit">
                                        <h3 style="text-align: center;color:#f0f0f0f; text-decoration: bold;">Work Permit Authority</h3><br/>
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                {!! Form::label('authority_name','Authority Name :',['class'=>'col-md-6 required-star']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::select('authority_name',[], '',['class' => 'form-control
                                                    input-md none-pointer','readonly','id'=>'authority_name']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('registration_number','Registration Number :',['class'=>'col-md-6 required-star']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text('registration_number', Session::get('wpneInfo.ref_app_tracking_no'),['class' => 'form-control
                                                    input-md','id'=>'registration_number']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                {!! Form::label('registration_date','Registration Date:',['class'=>'col-md-6 required-star']) !!}
                                                <div class="datepickerOld col-md-6 date">
                                                    {!! Form::text('registration_date', date('d-M-Y',strtotime(Session::get('wpneInfo.approved_date'))),['class' => 'form-control
                                                    input-md','id'=>'registration_date','style'=>'background:white;','autocomplete'=>'off']) !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                    <div id="non_bangladeshi_minor">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                {!! Form::label('guardian_passport','Guardians Passport Number :',['class'=>'text-left col-md-6 required-star']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text('guardian_passport', '',['class' => 'form-control
                                                    input-md','id'=>'guardian_passport']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('guardian_passport_issue_date','Guardians Passport Issue Date :',['class'=>'text-left col-md-6 required-star']) !!}
                                                <div class="datepickerOld col-md-6 date">
                                                    {!! Form::text('guardian_passport_issue_date', '',['class' => 'form-control
                                                    input-md','id'=>'guardian_passport_issue_date','style'=>'background:white;','autocomplete'=>'off']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            {!! Form::label('mobile_number','Mobile Number:',['class'=>'col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('mobile_number', !empty(Session::get('wpneInfo.ex_office_mobile_no'))?str_replace('+','',Session::get('wpneInfo.ex_office_mobile_no')):"",['class' => 'form-control
                                                input-md onlyNumber','id'=>'mobile_number']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('facsimile','Facsimile:',['class'=>'col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('facsimile', Session::get('wpneInfo.ex_office_fax_no'),['class' => 'form-control
                                                input-md','id'=>'facsimile']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            {!! Form::label('email','Email:',['class'=>'col-md-6']) !!}
                                            <div class="col-md-6">
                                                {!! Form::text('email', Session::get('wpneInfo.ex_office_email'),['class' => 'form-control
                                                input-md','id'=>'email']) !!}
                                            </div>
                                        </div>
{{--                                        <div class="col-md-6" id="taxpayer_id_div">--}}
{{--                                            {!! Form::label('taxpayer_id','Taxpayer s National ID/ SMART CARD Number/জাতীয় পরিচিতি নম্বর :',['class'=>'col-md-6']) !!}--}}
{{--                                            <div class="col-md-6">--}}
{{--                                                {!! Form::text('taxpayer_id', '',['class' => 'form-control--}}
{{--                                                input-md','id'=>'taxpayer_id']) !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            {!! Form::label('photo','Photo :', ['class'=>'col-md-6 required-star']) !!}
                                            <div class="col-md-6">
                                                <input type="file" name="photo" id="photo"
                                                       <?php if (empty(Session::get('wpneInfo.investor_photo'))) {
                                                           echo "class='required'";
                                                       } ?>
                                                       flag="img"
                                                       onchange="uploadDocument('preview_photo', this.id, 'validate_field_photo',1)">
                                                {!! $errors->first('photo','<span class="help-block">:message</span>')
                                                !!}
                                                <div id="preview_photo">
                                                    <input type="hidden" value="{{Session::get('wpneInfo.investor_photo')}}" id="validate_field_photo"
                                                           name="validate_field_photo" class="required">
                                                </div>
                                                <p style="color:#993333;">[N.B. Supported file extension is
                                                    pdf,png,jpg,jpeg.Max size less than 2 MB]</p>
                                                @if(!empty(Session::get('wpneInfo.investor_photo')))
                                                    <a target="_blank" class="btn btn-xs btn-primary"
                                                       href="{{URL::to('/uploads/'.Session::get('wpneInfo.investor_photo'))}}"
                                                       title="{{Session::get('wpneInfo.investor_photo')}}">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                        Open File
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                <div class="form-group">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-hover ">
                                                <thead>
                                                <tr>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">Country</th>
                                                    <th class="text-center">Address</th>
                                                    <th class="text-center">District/ State</th>
                                                    <th class="text-center">Thana</th>
                                                    <th class="text-center">Post Code/ Zip Code</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td width="20%">{!! Form::label('present_country','Current Address (For Individual "Present Residential Address")',['class'=>'required-star']) !!}</td>
                                                    <td width="16%">{!! Form::select('present_country',[],null,['class' =>'form-control input-md required country_id search-box','id'=>'present_country','dependent_id'=>'present_district','data-value'=>'Bangladesh','onchange'=>"getdistrictByCountryId('present')"]) !!}</td>
                                                    <td width="20%">
                                                        {!! Form::label('address_line1_p','Line 1:',['class'=>'col-md-6']) !!}
                                                        {!! Form::text('address_line1_p',Session::get('wpneInfo.office_address'),['class' =>'form-control input-md required','id'=>'address_line1_p']) !!}
                                                        {!! Form::label('address_line2_p','Line 2:',['class'=>'col-md-6']) !!}
                                                        {!! Form::text('address_line2_p',Session::get('wpneInfo.office_post_office'),['class' =>'form-control input-md','id'=>'address_line2_p']) !!}
                                                    </td>
                                                    <td width="16%">
                                                        {!! Form::select('present_district',[],null,['class' =>'form-control input-md district_id search-box','dependent_id'=>'present_thana','id'=>'present_district','data-value2'=>\App\Libraries\CommonFunction::getAreaNameByAreaId(Session::get('wpneInfo.office_district_id'))]) !!}<br>

                                                        {!! Form::text('present_state','',['class' =>'form-control input-md hidden','id'=>'present_state']) !!}
                                                    </td>
                                                    <td width="16%">
                                                        {!! Form::select('present_thana',[],null,['class' =>'form-control input-md search-box','id'=>'present_thana','data-value2'=>\App\Libraries\CommonFunction::getAreaNameByAreaId(Session::get('wpneInfo.office_thana_id'))]) !!}
                                                    </td>
                                                    <td width="12%">
                                                        {!! Form::text('present_post_code',Session::get('wpneInfo.office_post_code'),['class' =>'form-control input-md','id'=>'present_post_code']) !!}
                                                    </td>
                                                </tr>
                                                <tr id="same_as_current_tr">
                                                    <td colspan="6">
                                                        <div class="col-md-offset-2 col-md-1">
                                                            <input type="checkbox" name="same_as_current" id="same_as_current">
                                                        </div>
                                                        {!! Form::label('same_as_current','Same as Current Address',['class'=>'text-left col-md-6']) !!}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td width="20%">{!! Form::label('permanent_country','Permanent Address',['class'=>'required-star']) !!}</td>
                                                    <td width="16%">{!! Form::select('permanent_country',[],null,['class' =>'form-control input-md required country_id search-box','id'=>'permanent_country','dependent_id'=>'permanent_district','data-value'=>'Bangladesh','onchange'=>"getdistrictByCountryId('permanent')"]) !!}</td>
                                                    <td width="20%">
                                                        {!! Form::label('address_line1_per','Line 1:',['class'=>'col-md-6']) !!}
                                                        {!! Form::text('address_line1_per',Session::get('wpneInfo.ex_office_address'),['class' =>'form-control input-md required','id'=>'address_line1_per']) !!}
                                                        {!! Form::label('address_line2_per','Line 2:',['class'=>'col-md-6']) !!}
                                                        {!! Form::text('address_line2_per',Session::get('wpneInfo.ex_office_post_office'),['class' =>'form-control input-md','id'=>'address_line2_per']) !!}
                                                    </td>
                                                    <td width="16%">
                                                        {!! Form::select('permanent_district',[],null,['class' =>'form-control input-md district_id search-box','data-value2'=>\App\Libraries\CommonFunction::getAreaNameByAreaId(Session::get('wpneInfo.ex_office_district_id')),'dependent_id'=>'permanent_thana','id'=>'permanent_district']) !!}<br>

                                                        {!! Form::text('permanent_state','',['class' =>'form-control input-md hidden','id'=>'permanent_state']) !!}
                                                    </td>
                                                    <td width="16%">
                                                        {!! Form::select('permanent_thana',[],null,['class' =>'form-control input-md search-box','data-value2'=>\App\Libraries\CommonFunction::getAreaNameByAreaId(Session::get('wpneInfo.ex_office_thana_id')),'id'=>'permanent_thana']) !!}
                                                    </td>
                                                    <td width="12%">
                                                        {!! Form::text('permanent_post_code',Session::get('wpneInfo.ex_office_post_code'),['class' =>'form-control input-md','id'=>'permanent_post_code']) !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="20%"><p>Other Address (Working / Business Address)</p></td>
                                                    <td width="16%">{!! Form::select('other_country',[],null,['class' =>'form-control input-md  country_id search-box','id'=>'other_country','dependent_id'=>'other_district','onchange'=>"getdistrictByCountryId('other')"]) !!}</td>
                                                    <td width="20%">
                                                        {!! Form::label('address_line1_p','Line 1:',['class'=>'col-md-6']) !!}
                                                        {!! Form::text('address_line1_o','',['class' =>'form-control input-md ','id'=>'address_line1_o']) !!}
                                                        {!! Form::label('address_line2_p','Line 2:',['class'=>'col-md-6']) !!}
                                                        {!! Form::text('address_line2_o','',['class' =>'form-control input-md ','id'=>'address_line2_o']) !!}
                                                    </td>
                                                    <td width="16%">
                                                        {!! Form::select('other_district',[],null,['class' =>'form-control input-md district_id search-box','dependent_id'=>'other_thana','id'=>'other_district']) !!}<br>

                                                        {!! Form::text('other_state','',['class' =>'form-control input-md hidden','id'=>'other_state']) !!}
                                                    </td>
                                                    <td width="16%">
                                                        {!! Form::select('other_thana',[],null,['class' =>'form-control input-md search-box','id'=>'other_thana']) !!}
                                                    </td>
                                                    <td width="12%">
                                                        {!! Form::text('other_post_code','',['class' =>'form-control input-md','id'=>'other_post_code']) !!}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Attachments</h3>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="docListDiv">
                                    @include('ETINforeigner::documents')
                                </div>

                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Payment & Submit</h3>
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
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Service Fee Payment</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group clearfix">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' => 'form-control input-md email required']) !!}
                                                {!! $errors->first('sfp_contact_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md phone_or_mobile required']) !!}

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_address', Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' => 'form-control input-md required']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
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
                                <div class="form-group clearfix">
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
                                <div class="form-group clearfix">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                <b>Vat/ Tax</b> and <b>Transaction charge</b> is an approximate amount, those may vary based on the Sonali Bank system and those will be visible here after payment submission.
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
                                        value="draft" name="actionBtn" disabled>Save as Draft
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
{{--@include('partials.image-resize.image-upload')--}}
@include('ETINforeigner::E-tin-scripts_add')