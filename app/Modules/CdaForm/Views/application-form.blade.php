<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>

    .wizard > .content, .wizard, .tabcontrol {
        overflow: visible;
    }

    .form-group {
        margin-bottom: 2px;
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

<div class="col-md-12">
    @include('message.message')
</div>
<div class="col-md-12">
    <div class="panel panel-primary" id="inputForm">
        <div class="panel-heading">
            <h5><strong>ভূমি ব্যবহার (Land Use) ছাড়পত্রের জন্য আবেদন পত্র</strong></h5>
        </div>

        {!! Form::open(array('url' => 'cda-form/store','method' => 'post', 'class' => 'form-horizontal', 'id' => 'CdaForm',
        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
        <input type="hidden" name="selected_file" id="selected_file"/>
        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
        <input type="hidden" name="isRequired" id="isRequired"/>
        <h3 class="text-center stepHeader">Details Information</h3>
        <fieldset>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        {!! Form::label('land_use_category_id','Land use :',['class'=>'text-left col-md-4 ']) !!}
                        <div class="col-md-8">
                            {!! Form::select('land_use_category_id',  [], '', ['placeholder' => 'Select One',
                            'class' => 'form-control search-box','id'=>'land_use_category_id']) !!}
                            {!! $errors->first('land_use_category_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div id="land_use_sub" class="col-md-8 col-md-offset-2">

                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('applicant_name','১। আবেদনকারীর নাম :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_name', null,['class' => 'form-control bigInputField input-md','size'=>'5x1','maxlength'=>'200', 'id' => 'applicant_name']) !!}
                                {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            {!! Form::label('applicant_father_name',' ১.১। পিতা/স্বামীর নাম :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_father_name', null,['class' => 'form-control input-md oa_req_field','id' => 'applicant_father_name']) !!}
                                {!! $errors->first('applicant_father_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('applicant_mobile_no',' ১.২। আবেদনকারীর মোবাইল নং :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_mobile_no', null,['class' => 'form-control onlyNumber engOnly input-md oa_req_field', 'id'=>'applicant_mobile_no', 'placeholder'=>'e.g. 01756656565']) !!}
                                <span style="color: #990000;">(পরবর্তিতে প্রদত্ত এই নম্বরটিতে আবেদনের বিষয়ে তথ্য প্রদান বা যোগাযোগ করা হবে)</span>
                                {!! $errors->first('applicant_mobile_no','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            {!! Form::label('applicant_email',' ১.৩। আবেদনকারীর ইমেইল :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_email', null,['class' => 'form-control input-md oa_req_field email','id'=>'applicant_email']) !!}
                                <span style="color: #990000;">(পরবর্তিতে প্রদত্ত এই ইমেইল আবেদনের বিষয়ে তথ্য প্রদান বা যোগাযোগ করা হবে)</span>
                                {!! $errors->first('applicant_email','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('applicant_nid_no',' ১.৪। আবেদনকারীর  জাতীয় পরিচয়পত্র/পাসপোর্ট নং:',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_nid_no', null,['class' => 'form-control  engOnly input-md oa_req_field', 'id'=>'applicant_nid_no']) !!}
                                {!! $errors->first('applicant_nid_no','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            {!! Form::label('applicant_tin_no',' ১.৫। আবেদনকারীর টি.ই.ন নং:',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('applicant_tin_no', null,['class' => 'form-control onlyNumber engOnly input-md oa_req_field','id'=>'applicant_tin_no']) !!}
                                {!! $errors->first('applicant_tin_no','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('applicant_present_address','২। বর্তমান ঠিকানা :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::textarea('applicant_present_address', null,['class' => 'form-control input-md oa_req_field','id'=>'applicant_present_address']) !!}
                                {!! $errors->first('applicant_present_address','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('suggested_use_land_plot','৩।জমি/প্লট এর প্রস্তাবিত ব্যবহার :',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::textarea('suggested_use_land_plot', null,['class' => 'form-control input-md oa_req_field','id'=>'suggested_use_land_plot']) !!}
                                {!! $errors->first('suggested_use_land_plot','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--                    first panel end--}}

            {{--second start --}}
            <div class="row">
                <div class="col-md-12">
                    <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                        <h4 style="margin-left:15px; color: #564c4c;"> ৪। প্রস্তাবিত জমি/প্লট এর অবসথান ও পরিমাণ :</h4>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('city_corporation_id','ক) সিটি করপোরেশন :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('city_corporation_id',  [], '', ['placeholder' => 'Select One',
                                        'class' => 'form-control','id'=>'city_corporation_id']) !!}
                                        {!! $errors->first('city_corporation_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('bs','খ) বি. এস :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('bs', null,['class' => 'form-control input-md oa_req_field','id'=>'bs']) !!}
                                        {!! $errors->first('bs','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('rs','গ) আর. এস  :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('rs', null,['class' => 'form-control input-md oa_req_field','id'=>'rs']) !!}
                                        {!! $errors->first('rs','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('thana_id','ঘ) থানার নাম :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('thana_id',  [],'', ['placeholder' => 'Select One',
                                        'class' => 'form-control search-box','id'=>'thana_id']) !!}
                                        {!! $errors->first('thana_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('mouza_id','ঙ) মৌজা নাম :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('mouza_id',  [],'', ['placeholder' => 'Select Thana First',
                                        'class' => 'form-control search-box','id'=>'mouza_id']) !!}
                                        {!! $errors->first('mouza_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('block_id','চ) ব্লক নং :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('block_id',  [],'', ['placeholder' => 'Select One',
                                        'class' => 'form-control','id'=>'block_id']) !!}
                                        {!! $errors->first('block_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('seat_id','ছ) সিট নং :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('seat_id',  [],'', ['placeholder' => 'Select One',
                                        'class' => 'form-control','id'=>'seat_id']) !!}
                                        {!! $errors->first('seat_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('ward_id','জ) ওয়াড নং :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('ward_id',  [],'', ['placeholder' => 'Select One',
                                        'class' => 'form-control search-box','id'=>'ward_id']) !!}
                                        {!! $errors->first('ward_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('sector_id','ঝ) সেক্টর নং :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::select('sector_id',  [],'', ['placeholder' => 'Select One',
                                        'class' => 'form-control','id'=>'sector_id']) !!}
                                        {!! $errors->first('sector_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('road_name','ঞ) রাস্তার নাম :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('road_name', null,['class' => 'form-control input-md oa_req_field','id'=>'road_name']) !!}
                                        {!! $errors->first('road_name','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('arm_size_land_plot_amount','ট) বাহূ মাপ সহ জমি/প্লটের পরিমাণ :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('arm_size_land_plot_amount', null,['class' => 'form-control input-md oa_req_field','id'=>'arm_size_land_plot_amount']) !!}
                                        {!! $errors->first('arm_size_land_plot_amount','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('existing_house_plot_land_details','ঠ) জমি/প্লট এ বিদ্যমান বাড়ি/ কাঠামোর বিবরণ :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('existing_house_plot_land_details', null,['class' => 'form-control input-md oa_req_field','id'=>'existing_house_plot_land_details']) !!}
                                        {!! $errors->first('existing_house_plot_land_details','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- second end--}}

            {{-- 3rd start --}}

            <div class="row">
                <div class="col-md-12">
                    <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                        <h4 style="margin-left:15px; color: #564c4c;"> ৫। প্লটের মালিকানা সংক্রান্ত তথ্যাদি :</h4>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('plot_ownership_type','ক) প্লটের মালিকানার বিবরণ :',['class'=>'text-left col-md-5 col-xs-12']) !!}
                                    <div class="col-md-7 col-xs-12">
                                        <label class="radio-inline">{!! Form::radio('plot_ownership_type',  3,  null, ['class' => '  plot_ownership_type', 'id' => 'plot_ownership_type']) !!}
                                            &nbsp; ব্যক্তি &nbsp;</label>
                                        <label class="radio-inline">{!! Form::radio('plot_ownership_type', 2, null, ['class' => ' plot_ownership_type', 'id' => 'plot_ownership_type']) !!}
                                            &nbsp; যৌথ </label>
                                        <label class="radio-inline"> {!! Form::radio('plot_ownership_type', 1,  null, ['class' => ' plot_ownership_type', 'id' => 'plot_ownership_type']) !!}
                                            আম মোক্তার &nbsp;</label>

                                        {!! $errors->first('plot_ownership_type','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('plot_source_date','খ) মালিকানাসূত্র ও তারিখ:ক্রয়/উত্তরাধিকার/হেবা/দান/লিজ/অন্যান্য :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        <div class="datepicker input-group date">
                                            {!! Form::text('plot_source_date', '',
                                            ['class' => 'form-control  input-sm', 'placeholder' => 'dd-mm-yyyy']) !!}
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                        </div>
                                        {!! $errors->first('plot_source_date','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('plot_ownership_source','মালিকানসূত্র  : ',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('plot_ownership_source', null,['class' => 'form-control input-md oa_req_field']) !!}
                                        {!! $errors->first('plot_ownership_source','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('registration_date','গ) রেজিষ্ট্রেশনের তারিখ ও দলিল নং :',['class'=>'text-left col-md-5 col-xs-12 ']) !!}
                                    <div class="col-md-7 col-xs-12 col-xs-12">
                                        <div class="datepicker input-group date">
                                            {!! Form::text('registration_date', '',
                                            ['class' => 'form-control  input-sm', 'placeholder' => 'dd-mm-yyyy']) !!}
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                        </div>
                                        {!! $errors->first('registration_date','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    {!! Form::label('record_no','দলিল : ',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('record_no', null,['class' => 'form-control input-md oa_req_field']) !!}
                                        {!! $errors->first('record_no','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div style="border-bottom:1.5px solid #eee;margin-bottom: 15px;">
                        <h4 style="margin-left:15px; color: #564c4c;">
                            ৬। ভূমির পারিপার্শ্বিক অবস্থান বর্ণনা :</h4>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('pre_land_use','ক) ভূমির বর্তমান ব্যবহার : ',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('pre_land_use', null,['class' => 'form-control input-md oa_req_field']) !!}
                                        {!! $errors->first('pre_land_use','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('pre_land_use_radius_250m','খ) ২৫০ মিটার ব্যাসার্ধে অন্তর্ভূক্ত ভূমির বর্তমান ব্যবহার :',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('pre_land_use_radius_250m', null,['class' => 'form-control input-md oa_req_field']) !!}
                                        {!! $errors->first('pre_land_use_radius_250m','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('plot_nearest_road_name','গ)প্লটের নিকটতম দূরত্বে অবস্থিত প্রধান সড়কের নাম :  ',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('plot_nearest_road_name', null,['class' => 'form-control input-md oa_req_field']) !!}
                                        {!! $errors->first('plot_nearest_road_name','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('nearest_road_amplitude','প্রশস্থতা (মিটার) : ',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('nearest_road_amplitude', null,['class' => 'form-control onlyNumber input-md oa_req_field']) !!}
                                        {!! $errors->first('nearest_road_amplitude','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    {!! Form::label('plot_connecting_road_name','ঘ)প্লটের সংযোগ সড়কের নাম : ',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('plot_connecting_road_name', null,['class' => 'form-control input-md oa_req_field']) !!}
                                        {!! $errors->first('plot_connecting_road_name','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('connecting_road_amplitude','প্রশস্থতা (মিটার) : ',['class'=>'text-left col-md-5 ']) !!}
                                    <div class="col-md-7">
                                        {!! Form::text('connecting_road_amplitude', null,['class' => 'form-control onlyNumber input-md oa_req_field']) !!}
                                        {!! $errors->first('connecting_road_amplitude','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <br>
                            ঙ) প্লটের ২৫০ মিটার দূরত্বের মধ্যে অবস্থান:
                        </div>
                        <?php
                        $yesno = array('হ্যাঁ' => 'হ্যাঁ', 'না' => 'না')
                        ?>

                        <fieldset style="border: 1px solid powderblue;margin: 10px;padding: 5px;border-radius: 5px;">
                            <div class="row">
                                <div class="col-md-4">
                                    {!! Form::label('250m_main_road',' প্রধান সড়ক (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_main_road',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_main_road']) !!}
                                    {!! $errors->first('250m_main_road','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('250m_hat_bazaar',' হাট-বাজার (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_hat_bazaar',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_hat_bazaar']) !!}
                                    {!! $errors->first('250m_hat_bazaar','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('250m_railway_station',' রেলওয়ে স্টেশন (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_railway_station',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_railway_station']) !!}
                                    {!! $errors->first('250m_railway_station','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px;">
                                <div class="col-md-4">
                                    {!! Form::label('250m_river_port',' নদী-বন্দর (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_river_port',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_river_port']) !!}
                                    {!! $errors->first('250m_river_port','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('250m_airport',' বিমান বন্দর (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_airport',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_airport']) !!}
                                    {!! $errors->first('250m_airport','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                        </fieldset>
                        <br>
                        <div class="col-md-12">
                            চ) প্লটের ২৫০ মিটার দূরত্বের মধ্যে অবস্থান :
                        </div>
                        <!--                        --><?php
                        //                        $yesno = array('হ্যা'=>'হ্যা',' না'=>'না')
                        //                        ?>

                        <fieldset style="border: 1px solid powderblue;margin: 10px;padding: 5px;border-radius: 5px;">
                            <div class="row">
                                <div class="col-md-4">
                                    {!! Form::label('250m_pond','পুকুর (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_pond',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_pond']) !!}
                                    {!! $errors->first('250m_pond','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('250m_wetland',' জলাভূমি (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_wetland',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_wetland']) !!}
                                    {!! $errors->first('250m_wetland','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('250m_natural_waterway',' প্রাকৃতিক জলপথ (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_natural_waterway',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_natural_waterway']) !!}
                                    {!! $errors->first('250m_natural_waterway','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px;">
                                <div class="col-md-4">
                                    {!! Form::label('250m_flood_control_stream',' বন্যা নিয়ন্ত্রণ জলাধার (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_flood_control_stream',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_flood_control_stream']) !!}
                                    {!! $errors->first('250m_flood_control_stream','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('250m_forest',' বিবনাঞ্চল (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_forest',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_forest']) !!}
                                    {!! $errors->first('250m_forest','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('250m_park_playground',' পার্ক বা খেলার মাঠ (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_park_playground',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_park_playground']) !!}
                                    {!! $errors->first('250m_park_playground','<span class="help-block">:message</span>') !!}
                                </div>

                            </div>
                            <div class="row" style="margin-top: 5px;">
                                <div class="col-md-4">
                                    {!! Form::label('250m_hill',' পাহাড় (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_hill',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_hill']) !!}
                                    {!! $errors->first('250m_hill','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('250m_slope',' ঢাল (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_slope',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_slope']) !!}
                                    {!! $errors->first('250m_slope','<span class="help-block">:message</span>') !!}
                                </div>

                            </div>

                        </fieldset>
                        <br>
                        <div class="col-md-12">

                            (ছ) প্লটের ২৫০ মিটার দূরত্বের মধ্যে অবস্থান :
                        </div>


                        <fieldset style="border: 1px solid powderblue;margin: 10px;padding: 5px;border-radius: 5px;">
                            <div class="row">
                                <div class="col-md-4">
                                    {!! Form::label('250m_historical_imp_site','ঐতিহাসিক গুরুত্বপূর্ণ সাইট (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_historical_imp_site',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_historical_imp_site']) !!}
                                    {!! $errors->first('250m_historical_imp_site','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('250m_military_installation',' সামরিক স্থাপনা (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_military_installation',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_military_installation']) !!}
                                    {!! $errors->first('250m_military_installation','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('250m_key_point_installation',' Key Point Installation (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_key_point_installation',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_key_point_installation']) !!}
                                    {!! $errors->first('250m_key_point_installation','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px;">
                                <div class="col-md-4">
                                    {!! Form::label('250m_limited_dev_area',' বিধিমালা অনুযায়ী সীমিত উন্নয়ন এলাকা (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('250m_limited_dev_area',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'250m_limited_dev_area']) !!}
                                    {!! $errors->first('250m_limited_dev_area','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('25m_special_area',' বিশেষ এলাকা(Special Area) (হ্যাঁ/না):',['class'=>'text-left ']) !!}
                                    {!! Form::select('25m_special_area',  $yesno,'', ['placeholder' => 'Select One',
                               'class' => 'form-control','id'=>'25m_special_area']) !!}
                                    {!! $errors->first('25m_special_area','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>


                        </fieldset>
                        <br>
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('plot_condition_by_adjacent_road',' জ)সংলগ্ন রাস্তা থেকে প্লটের অবস্থা,গড় উঁচু/নীচু :',['class'=>'text-left col-md-4 ']) !!}
                                <div class="col-md-8">
                                    <div class="col-md-7">
                                        {!! Form::textarea('plot_condition_by_adjacent_road', null,['class' => 'form-control input-md oa_req_field']) !!}
                                        {!! $errors->first('plot_condition_by_adjacent_road','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="col-md-12">
                            ঝ)প্লটের চতুঃপার্শ্বস্থ ভূমির ব্যবহার :
                        </div>


                        <fieldset style="border: 1px solid powderblue;margin: 10px;padding: 5px;border-radius: 5px;">
                            <div class="row">
                                <div class="col-md-3">
                                    {!! Form::label('land_use_north','উত্তর :',['class'=>'text-left ']) !!}
                                    {!! Form::text('land_use_north', null,['class' => 'form-control input-md oa_req_field']) !!}
                                    {!! $errors->first('land_use_north','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::label('land_use_south',' দক্ষিণ :',['class'=>'text-left ']) !!}
                                    {!! Form::text('land_use_south', null,['class' => 'form-control input-md oa_req_field']) !!}
                                    {!! $errors->first('land_use_south','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::label('land_use_east',' পূর্ব :',['class'=>'text-left ']) !!}
                                    {!! Form::text('land_use_east', null,['class' => 'form-control input-md oa_req_field']) !!}
                                    {!! $errors->first('land_use_east','<span class="help-block">:message</span>') !!}
                                </div>

                                <div class="col-md-3">
                                    {!! Form::label('land_use_west',' পশ্চিম :',['class'=>'text-left ']) !!}
                                    {!! Form::text('land_use_west', null,['class' => 'form-control input-md oa_req_field']) !!}
                                    {!! $errors->first('land_use_west','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </fieldset>

                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('other_necessary_info',' ঞ) অন্য কোন গুরুত্বপূর্ণ তথ্য (যদি থাকে) :',['class'=>'text-left col-md-4 ']) !!}
                                <div class="col-md-8">
                                    <div class="col-md-7">
                                        {!! Form::textarea('other_necessary_info', null,['class' => 'form-control input-md oa_req_field']) !!}
                                        {!! $errors->first('other_necessary_info','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </fieldset>

        <h3 class="text-center stepHeader">Attachments</h3>
        <fieldset>
            <div class="form-group" style="margin-left: 20px;">
                <div class="row">
                    <div class="col-md-12">
                        <strong>{!! Form::label('document_type',' নিচের যে কোনো একটি নির্বাচন করুন :',['class'=>'text-left col-md-7 col-xs-12 ']) !!}</strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12 col-xs-12">
                            <label class="radio-inline">{!! Form::radio('document_type',  1,  null, ['class' => 'document_type required','id'=>'document_type_1' ]) !!}
                                &nbsp; মৌরশী সম্পত্তি &nbsp;</label>
                            <label class="radio-inline">{!! Form::radio('document_type', 2, null, ['class' => 'document_type required', 'id'=>'document_type_2']) !!}
                                &nbsp; ক্রয় সূত্রে মালিকানাধীন সম্পত্তি </label>
                            <label class="radio-inline"> {!! Form::radio('document_type', 3,  null, ['class' => 'document_type required', 'id'=>'document_type_3']) !!}
                                অনুমোদিত পরিকল্পিত এলাকায় (আবাসিক /বানিজ্যিক /শিল্প ইত্যাদি ) &nbsp;</label>

                            {!! $errors->first('document_type','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 30px">
                <div class="col-md-12">
                    <div id="docListDiv">
                    </div>

                </div>
            </div>
        </fieldset>

        <h3 class="text-center stepHeader">Declaration</h3>
        <fieldset>
            <div class="panel panel-info">
                <div class="panel-heading" style="padding-bottom: 4px;">
                    <strong>Declaration and undertaking</strong>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <ol type="a">
                                    <li>
                                        <p>I do hereby declare that the information given above is true to the best of
                                            my knowledge and I shall be liable for any false information/ statement
                                            given</p>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead class="alert alert-info">
                        <tr>
                            <th colspan="3" style="font-size: 15px">Authorized person of the organization</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {!! Form::label('auth_name','Full name:', ['class'=>'required-star']) !!}
                                {!! Form::text('auth_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required', 'readonly']) !!}
                                {!! $errors->first('auth_name','<span class="help-block">:message</span>') !!}
                            </td>
                            <td>
                                {!! Form::label('auth_email','Email:', ['class'=>'required-star']) !!}
                                {!! Form::email('auth_email', Auth::user()->user_email, ['class' => 'form-control required input-sm email', 'readonly']) !!}
                                {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                            </td>
                            <td>
                                {!! Form::label('auth_cell_number','Cell number:', ['class'=>'required-star']) !!}<br>
                                {!! Form::text('auth_cell_number', Auth::user()->user_phone, ['class' => 'form-control input-sm required phone_or_mobile', 'readonly']) !!}
                                {!! $errors->first('auth_cell_number','<span class="help-block">:message</span>') !!}
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
                                {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms', 'class'=>'required')) !!}
                                আমি/আমরা প্রত্যয়ন করিতেছি যে, উপরে উল্লিখিত তথ্য সমূহ চট্টগ্রাম মহানগর ইমারত (নির্মাণ,
                                উন্নয়ন, সংরক্ষণ ও অপসারণ) বিধিমালা, ২০০৮ এর বিধিতে বর্ণিত বিষয়াদির উপযুক্ততা পূরণ করে
                                এবং আমরা/আমাদের জানামতে প্রদত্ত তথ্যাবলী সঠিক। ইহাছাড়া এই বিধির আওতায় চাহিত অন্য যে
                                কোন তথ্যাবলী বা দলিলাদি প্রদানেও বাধ্য থাকিব।
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
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                                {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('sfp_contact_address', Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('sfp_contact_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
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
                            class="btn btn-success btn-md"
                            value="Submit" name="actionBtn">Payment &amp; Submit
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

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>
    $(document).ready(function () {

        $("#applicant_mobile_no").keypress(function () {
            alert(33);
        });
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/cda-form/get-refresh-token';

            $('#land_use_category_id').keydown()
            $('#city_corporation_id').keydown()
            $('#thana_id').keydown()
            $('#block_id').keydown()
            $('#seat_id').keydown()
            $('#ward_id').keydown()
            $('.search-box').select2();
            $('#sector_id').keydown()

        });

        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: 'OSS_BIDA'
            },
        ]

        $("#CdaForm").validate();

        $(document).on('click', '.cancel', function () {

            $("#CdaForm").validate(function () {
                return true;
            });
        });

        // to remove error class if any land use sub is checked
        $(document).on('click', '.land_use_sub_cat', function (e) {
            if ($(this).is(":checked")) {
                $('.land_use_sub_cat').each(function (i, element) {
                    $(this).removeClass('error');
                });
            }
        });

        var form = $("#CdaForm").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if (newIndex == 1) {
                    if ($('#land_use_category_id').val() === "") {
                        $('#land_use_category_id').addClass('error');
                    } else {
                        $('#land_use_category_id').removeClass('error');
                    }
                    // to validate the land use sub check boxes
                    anyBoxesChecked = false;
                    $('.land_use_sub_cat').each(function (i, obj) {
                        if ($(this).is(":checked")) {
                            anyBoxesChecked = true;
                        }
                    });
                    if (anyBoxesChecked == false) {
                        $('.land_use_sub_cat').each(function (i, obj) {
                            $(this).addClass('error');
                        });
                        return false;
                    }
                }

                if (newIndex == 2) {
                }

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

                if (currentIndex == 3) {
                    form.find('#submitForm').css('display', 'block');

                    $('#submitForm').on('click', function (e) {
                        form.validate().settings.ignore = ":disabled";
                        return form.valid();
                    });
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
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=CdaForm@3'); ?>');
            } else {
                return false;
            }
        });


        $("#CdaForm").find('.onlyNumber').on('keydown', function (e) {
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

        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY'
        });

        $('.datepickerfuture').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY'
        });
        $('#land_use_category_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_api_url}}/get-list";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "land_type_id";//dynamic id for callback
            let element_name = "land_type_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getLandUseList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })
        // Get Land Use List


        // Get Land Use Details List
        $("#land_use_category_id").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            var landCategoryvalue = $('#land_use_category_id').val();
            var landCategory = landCategoryvalue.split("@")[0];

            if (landCategoryvalue) {
                $.ajax({
                    type: "GET",
                    url: "<?php echo url('/cda-form/land-use-details-list'); ?>" + '/' + landCategory,
                    data: {},
                    success: function (response) {
                        var option = '';
                        if (response.responseCode == 1) {
                            var i = 0;
                            $.each(response.data, function (id, value) {
                                option += '<input type="checkbox" class="land_use_sub_cat" name="land_use_sub_cat_id[' + i + ']" value="' + id + '"> ' + value + '  ';
                                i++;
                            });
                        }
                        $("#land_use_sub").html(option);
                        $(self).next().hide();
                    }
                });
            } else {
                $("#land_use_sub").html('');
                $(self).next().hide();
            }

        });

        // Get City Corporation List

        $('#city_corporation_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_api_url}}/get-list";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "city_corp_id";//dynamic id for callback
            let element_name = "city_corp_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getCityList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })


        $('#thana_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_api_url}}/get-list";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "thana_id";//dynamic id for callback
            let element_name = "thana_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getThanaList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        $("#thana_id").on("change", function () {
            var self = $(this)
            $(self).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#mouza_id").html('<option value="">Please Wait...</option>')
            var thana = $('#thana_id').val()
            var thana_id = thana.split("@")[0]
            if (thana_id) {
                let e = $(this);
                let api_url = "{{$cda_api_url}}/get-mouzalist";
                let selected_value = ''; // for callback
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "mouza_id";
                let element_id = "mouza_id";//dynamic id for callback
                let element_name = "mouza_name";//dynamic name for callback
                let element_calling_id = "thana_id";//dynamic name for callback
                let data = '{"thana_id": ' + thana_id + ' }';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

                apiCallPost(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#mouza_id").html('<option value="">Select Thana First</option>')
                $(self).next().hide()
            }

        })


        // Get Block List
        $('#block_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_api_url}}/get-list";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "block_id";//dynamic id for callback
            let element_name = "block_no";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getBlockList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#seat_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_api_url}}/get-list";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "sit_id";//dynamic id for callback
            let element_name = "sit_no";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getSitList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })

        // Get Ward List
        $('#ward_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_api_url}}/get-list";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "word_id";//dynamic id for callback
            let element_name = "word_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getWardList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })


        // Get Sector List
        $('#sector_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_api_url}}/get-list";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "sector_id";//dynamic id for callback
            let element_name = "sector_no";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '{"name":"getSectorList" }';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallPost(e, options, apiHeaders, callbackResponse, arrays);

        })
        $("#sector_id").one("click", function () {
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url('/cda-form/sector-list'); ?>",
                data: {},
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $(self).html(option);
                    $(self).next().hide();
                }
            });
        });

    });

    // Get Thana List


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
            var action = "{{url('/cda-form/upload-document')}}";
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
                        ' <a href="javascript:void(0)" onclick="EmptyFile(' + doc_id
                        + ', ' + isRequired + ')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                    //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field != '') {
                        $("#" + id).removeClass('required');
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }
    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id]) {
        var option = '<option value="">Select One</option>';
        if (response.status === 200) {
            $.each(response.data.resonse.result, function (key, row) {
                let id = (element_calling_id === '' || element_calling_id == null) ? (row[element_id] + '@' + row[element_name]) : (row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name]);
                let value = row[element_name]
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + calling_id).html(option);
        $("#" + calling_id).parent().find('.loading_data').hide();
    }

    function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]) {
        $("#" + dependant_select_id).select2('destroy')
        var option = '<option value="">Select One</option>';
        if (response.status === 200) {
            $.each(response.data.resonse.result.data, function (key, row) {
                let id = (element_calling_id === '' || element_calling_id == null) ? (row[element_id] + '@' + row[element_name]) : (row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name]);
                let value = row[element_name]
                option += '<option value="' + id + '">' + value + '</option>'
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + dependant_select_id).select2()
        $("#" + calling_id).next().hide()
    }

    $(document).on('click', '.document_type', function () {
        var doc_type = $(this).val();
        var app_id = $("#app_id").val();
        var _token = "{{ csrf_token() }}";

        var attachment_key = "cda_";
        if (doc_type == 1) {
            attachment_key += "mp";
        } else if (doc_type == 2) {
            attachment_key += "pw";
        } else if (doc_type == 3) {
            attachment_key += "ap";
        } else {
            alert('Please Select one type of Land');
        }

        $.ajax({
            type: "POST",
            url: '/cda-form/getDocList',
            dataType: "json",
            data: {_token: _token, attachment_key: attachment_key, app_id: app_id},
            success: function (result) {
                if (result.html != undefined) {
                    $('#docListDiv').html(result.html);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //console.log(errorThrown);
                alert('Unknown error occured. Please, try again after reload');
            },
        });
    });


</script>