<?php
$accessMode = ACL::getAccsessRight('LimaFactoryLayout');
if (!ACL::isAllowed($accessMode, '-A-')) {
    die('You have no access right! Please contact with system admin if you have any query.[ML-1101]');
}
?>
<style>
    .form-group {
        margin-bottom: 5px;
    }

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .photo_size {
        max-height: 150px;
        max-width: 200px;
        float: right;
    }

    .padding-l-r {
        padding: 0px 20px;
    }

    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-switcher/css/switcher.css") }}"/>
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
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="">Application for Factory Layout Approval</h4>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'licence-applications/lima-factory-layout/store','method' => 'post', 'class' =>'form-horizontal', 'id' => 'NewApplication',
                            'enctype' =>'multipart/form-data', 'files' => 'true')) !!}

                        <input type="hidden" name="selected_file" id="selected_file"/>
                        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                        <input type="hidden" name="isRequired" id="isRequired"/>

                        <!-- start -:- Industry Classification/Type -->
                        <div class="panel panel-info">
                            {{--                            <div class="panel-heading">--}}
                            {{--                                <strong>Industry Classification/Type</strong>--}}
                            {{--                            </div>--}}
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('industry_id') ? 'has-error': ''}}">
                                            {!! Form::label('industry_id','Classification/Industrial Sector',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('industry_id', [], '', ['class' =>'form-control input-md required', 'id'=> 'industry_id']) !!}
                                                {!! $errors->first('industry_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Industry Classification/Type -->

                        <!-- start -:- Basic information of the factory -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Factory’s Basic Information</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('factory_name_en') ? 'has-error': ''}}">
                                            {!! Form::label('factory_name_en', 'Full Name of the Factory (in English)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('factory_name_en','', ['class' => 'form-control input-md required','placeholder'=>'Full Name of the Factory (in English)', 'id' => 'factory_name']) !!}
                                                {!! $errors->first('factory_name_en','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('factory_name_bn') ? 'has-error': ''}}">
                                            {!! Form::label('factory_name_bn', 'Full Name of the Factory (in Bengali)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('factory_name_bn', '', ['class' => 'form-control input-md required','placeholder'=>'Full Name of the Factory (in Bengali)', 'id' => 'factory_name_bn']) !!}
                                                {!! $errors->first('factory_name_bn','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('factory_head_office_address') ? 'has-error': ''}}">
                                            {!! Form::label('factory_head_office_address','Factory Head Office Address',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('factory_head_office_address','',['class'=>'form-control input-md required','id'=>'factory_head_office_address', 'placeholder'=>'Factory Head Office Address','maxlength' => 254, 'rows' => 2, 'cols' => 50]) !!}
                                                {!! $errors->first('factory_head_office_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('correspondence_address_with_factory') ? 'has-error': ''}}">
                                            {!! Form::label('correspondence_address_with_factory','Postal Address to the Factory',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('correspondence_address_with_factory','',['class'=>'form-control input-md required','id'=>'correspondence_address_with_factory', 'placeholder'=>'Postal Address to the Factory','maxlength' => 254, 'rows' => 2, 'cols' => 50]) !!}
                                                {!! $errors->first('correspondence_address_with_factory','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- start -:- Basic information of the factory -->

                        <!-- start -:- Identification of the owner -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Owner’s Information</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('owner_name') ? 'has-error': ''}}">
                                            {!! Form::label('owner_name', 'Name of the Owner/Managing Director',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('owner_name', '', ['class' => 'form-control input-md required','placeholder'=>'Name of the Owner/Managing Director', 'id' => 'owner_name']) !!}
                                                {!! $errors->first('owner_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('owner_present_address') ? 'has-error': ''}}">
                                            {!! Form::label('owner_present_address', 'Factory Owner’s Present Address',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('owner_present_address','',['class'=>'form-control input-md required','id'=>'owner_present_address', 'placeholder'=>'Factory Owner’s Present Address','maxlength' => 254, 'rows' => 3, 'cols' => 50]) !!}
                                                {!! $errors->first('owner_present_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('owner_permanent_address') ? 'has-error': ''}}">
                                            {!! Form::label('owner_permanent_address', 'Factory Owner’s Permanent Address',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::textarea('owner_permanent_address','',['class'=>'form-control input-md required','id'=>'owner_permanent_address', 'placeholder'=>'Factory Owner’s Permanent Address','maxlength' => 254, 'rows' => 3, 'cols' => 50]) !!}
                                                {!! $errors->first('owner_permanent_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Identification of the owner -->


                        <!-- start -:- Factory location -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Factory location</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('division_id') ? 'has-error': ''}}">
                                            {!! Form::label('division_id','Division',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('division_id', [], '', ['class' =>'form-control input-md required', 'id'=> 'division_id']) !!}
                                                {!! $errors->first('division_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('district_id') ? 'has-error': ''}}">
                                            {!! Form::label('district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('district_id', [], '', ['class' =>'form-control input-md required', 'id'=> 'district_id']) !!}
                                                {!! $errors->first('district_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('upazilla_id') ? 'has-error': ''}}">
                                            {!! Form::label('upazilla_id','Upazila/Thana',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('upazilla_id', [], '', ['class' =>'form-control input-md required', 'id'=> 'upazilla_id']) !!}
                                                {!! $errors->first('upazilla_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('post_office') ? 'has-error': ''}}">
                                            {!! Form::label('post_office','Post Office',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('post_office', [], '', ['class' =>'form-control input-md required', 'id'=> 'post_office']) !!}
                                                {!! $errors->first('post_office','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('road_no_en') ? 'has-error': ''}}">
                                            {!! Form::label('road_no_en', 'Road no. (in English)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('road_no_en', '', ['class' => 'form-control input-md required','placeholder'=>'Factory Road No.', 'id' => 'road_no_en']) !!}
                                                {!! $errors->first('road_no_en','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('road_no_bn') ? 'has-error': ''}}">
                                            {!! Form::label('road_no_bn', 'Road no. (in Bengali)',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('road_no_bn', '', ['class' => 'form-control input-md','placeholder'=>'Factory Road No. (in Bengali)', 'id' => 'road_no_bn']) !!}
                                                {!! $errors->first('road_no_bn','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('holding_name_en') ? 'has-error': ''}}">
                                            {!! Form::label('holding_name', 'House/Holding/Village/ Mahalla (in English)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('holding_name_en', '', ['class' => 'form-control input-md required','placeholder'=>'Name of House/Holding/Village/Mahalla', 'id' => 'holding_name_en']) !!}
                                                {!! $errors->first('holding_name_en','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('holding_name_bn') ? 'has-error': ''}}">
                                            {!! Form::label('holding_name_bn', 'House/Holding/Village/ Mahalla (in Bengali)',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('holding_name_bn', '', ['class' => 'form-control input-md','placeholder'=>'Name of House/Holding/Village/Mahalla (in Bengali)', 'id' => 'holding_name_bn']) !!}
                                                {!! $errors->first('holding_name_bn','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('nearest_railway_steamer_launch') ? 'has-error': ''}}">
                                            {!! Form::label('nearest_railway_steamer_launch', 'Nearest Railway Station/Steamer Ghat/Launch Ghat',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('nearest_railway_steamer_launch', '', ['class' => 'form-control input-md required','placeholder'=>'Name of Nearby Rail Station/SteamerGhaat/LaunchGhaat', 'id' => 'nearest_railway_steamer_launch']) !!}
                                                {!! $errors->first('nearest_railway_steamer_launch','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('nearest_bus_stop') ? 'has-error': ''}}">
                                            {!! Form::label('nearest_bus_stop', 'Nearby Bus Stoppage',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('nearest_bus_stop', '', ['class' => 'form-control input-md','placeholder'=>'Name of Nearby Bus Stoppage', 'id' => 'nearest_bus_stop']) !!}
                                                {!! $errors->first('nearest_bus_stop','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- start -:- Factory location -->


                        <!-- start -:- Building renovation information -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Building’s Approval Information</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        {{--                                        <h4>Local Authority approved details of the layout, their inhibition in that--}}
                                        {{--                                            holding and quantity - in which building the institute is located/ the rent--}}
                                        {{--                                            portion of the building.</h4>--}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('notify_authority_name') ? 'has-error': ''}}">
                                            {!! Form::label('notify_authority_name', 'Name of the Approval Authority',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('notify_authority_name', '', ['class' => 'form-control input-md', 'placeholder'=>'Name of the Approval Authority', 'id' => 'notify_authority_name']) !!}
                                                {!! $errors->first('notify_authority_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('layout_load_bearing_capacity') ? 'has-error': ''}}">
                                            {!! Form::label('layout_load_bearing_capacity', 'Load Bearing capacity according to the Structural Design (PSF)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('layout_load_bearing_capacity', '', ['class' => 'form-control input-md', 'placeholder'=>'Load Bearing capacity according to the Structural Design (PSF)', 'id' => 'layout_load_bearing_capacity']) !!}
                                                {!! $errors->first('layout_load_bearing_capacity','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('building_plan_approval_date') ? 'has-error': ''}}">
                                            {!! Form::label('building_plan_approval_date', 'Plan Approval Date',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                <div class="datepicker input-group date"
                                                     data-date-format="dd-mm-yyyy">
                                                    {!! Form::text('building_plan_approval_date', '', ['class'=>'form-control input-md date', 'id' => 'building_plan_approval_date', 'placeholder'=>'e.g., 21-02-1952']) !!}
                                                    <span class="input-group-addon"><span
                                                                class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('building_plan_approval_date','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('nearest_railway_steamer_launch') ? 'has-error': ''}}">
                                            {!! Form::label('building_plan_reference_no', 'Reference No.',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('building_plan_reference_no','',['class'=>'form-control input-md required','id'=>'building_plan_reference_no', 'placeholder'=>'Reference No. of Building']) !!}
                                                {!! $errors->first('building_plan_reference_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Building renovation information -->


                        <!-- start -:- Information regarding work environment -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Information about the Working Environment</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table" cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Types of Machines</th>
                                                    <th>Number</th>
                                                    <th>Location</th>
                                                    <th>Load</th>
                                                    <th>
                                                        #
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody id="factoryEnvironmentTable">
                                                <tr id="factoryEnvironmentTableRow_0" data-number="0">
                                                    <td>
                                                        {!! Form::text('factory_machine_type[]', null, ['class' => 'form-control input-md', 'placeholder'=>'Types of Machines', 'id' => 'factory_machine_type_0']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('factory_machine_measurement[]', null, ['class' => 'form-control input-md', 'placeholder'=>'Number of Machine', 'id' => 'factory_machine_measurement_0']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('factory_machine_location[]', null, ['class' => 'form-control input-md', 'placeholder'=>'Location of Machine', 'id' => 'factory_machine_location_0']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('factory_machine_amount[]', null, ['class' => 'form-control input-md', 'placeholder'=>'Load Amount of Machine', 'id' => 'factory_machine_amount_0']) !!}
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0);"
                                                           class="btn btn-xs btn-primary addTableRows"
                                                           onclick="addrowDife('factoryEnvironmentTable', 'factoryEnvironmentTableRow_0');"><i
                                                                    class="fa fa-plus"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Information regarding work environment -->


                        <!-- start -:- Attachment -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Attachments</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
{{--                                        <h4>Choose the Type of Attachment, select whether it’s hard copy or not. If you--}}
{{--                                            have the soft copy, please hit Browse button, choose the file and hit the--}}
{{--                                            Add Attachment button to attach the file. Accepted File Types: png, gif,--}}
{{--                                            jpeg, jpg, pdf, doc, docx, xls, xlsx, dwg. Maximum upload size: 100mb.</h4>--}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="docListDiv">
                                            @include('LimaLayoutPlan::documents')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Attachment -->


                        <!-- start -:- Service Fee Payment Details -->
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Service Fee Payment</strong>
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
                                <div class="form-group">
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
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 padding-l-r">
                                            <div class="alert alert-danger" role="alert">
                                                Vat or tax and service charge is an approximate amount, it may vary
                                                based
                                                on the Sonali Bank system.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 padding-l-r">
                                            <div class="checkbox">
                                                <label>
                                                    {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms',
                                                    'class'=>'required')) !!}
                                                    All the details and information provided in this form are true and
                                                    complete.
                                                    I am aware that any untrue or incomplete statement may result in
                                                    delay
                                                    in BIN issuance and I may be subjected to full penal action under
                                                    the Value
                                                    Added Tax and Supplementary Duty Act, 2012 or any other applicable
                                                    Act Prevailing at present.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -:- Service Fee Payment Details -->

                        <!-- start -:- Save As Draft & Payment & Submit Button -->
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="pull-left">
                                    <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn">Save as Draft
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 button_last">
                                <div class="pull-right" style="padding-left: 1em;">
                                    <button type="submit" id="submitForm" style="cursor: pointer;"
                                            class="btn btn-success btn-md" value="Submit" name="actionBtn">Payment &amp;
                                        Submit
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- end -:- Save As Draft & Payment & Submit Button -->
                        {!! Form::close() !!}
                    </div><!-- .panel-body (form panel body) -->
                </div><!-- .panel .panel-primary (form panel)-->
            </div><!-- .box-body -->
        </div><!-- #inputForm -->
    </div><!-- .col-md-12 -->

    <!-- start -:- Modal -->
    <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"></div>
        </div>
    </div>
    <!-- end -:- Modal -->
</section>

@include('partials.image-resize.image-upload')
{{--@include('partials.profile-capture')--}}
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-switcher/js/jquery.switcher.min.js') }}"></script>

<script type="text/javascript">
    /*$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });*/
    var yyyy = today.getFullYear();
    $("#NewApplication").validate();
    $(document).ready(function () {
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 100),
            ignoreReadonly: true
        });
        $('.year_picker').datetimepicker({
            viewMode: 'years',
            format: 'YYYY',
            ignoreReadonly: true
        });
        // Load Document On Page Load.
        getDoc();
    });// end -:- document ready()
    $('#nid_number').on('blur', function (e) {
        var nid = $('#nid_number').val().length;
        if (nid == 10 || nid == 13 || nid == 17) {
            $('#nid_number').removeClass('error')
        } else {
            $('#nid_number').addClass('error')
            // $('#nid_number').val('')
        }
    });

    function imagePreview(input) {
        if (input.files && input.files[0]) {
            var calling_id = input.id;
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#photo_viewer_" + calling_id).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }


    $(document).ready(function () {
        //var switcherEl = $('#checkbox_testing').switcher();
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/mutation-land/get-refresh-token';
            //$('#license_type_id').keydown();
            $('#industry_id').keydown();
            //$('#electricity_type').keydown();
            //$('.factory_organization_id').keydown();
            //$('.factory_building_structure_type').keydown();
            $('#division_id').keydown();
        });
        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: "OSS_BIDA"
            },
            {
                key: "agent-id",
                value: "{{ config('stakeholder.agent_id') }}"
            },
        ];
        $('#license_type_id').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$ml_service_url}}license-type";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "nameEn";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });
        $('#industry_id').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$ml_service_url}}industrial-sector";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "nameEn";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });
        $('#electricity_type').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$ml_service_url}}electricity-type";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "nameEn";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });
        $('.factory_organization_id').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ml_service_url}}/membership-organization";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "nameEn"; //dynamic name for callback
            var data = null;
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });
        $('.factory_building_structure_type').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ml_service_url}}/building-structure-type";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "nameEn"; //dynamic name for callback
            var data = null;
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });
        $('#division_id').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$ml_service_url}}division";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });
        $("#division_id").on("change", function (el) {
            let e = $(this);
            let key = el.which;
            if (typeof key !== "undefined") {
                return false
            }
            $(e).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#district_id").html('<option value="">Please Wait...</option>');
            var division = $('#division_id').val();
            var division_id = division.split("@")[0];
            if (division_id) {
                let e = $(this);
                let api_url = "{{$ml_service_url}}district/" + division_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "district_id";
                let element_id = "id";//dynamic id for callback
                let element_name = "name";//dynamic name for callback
                let element_calling_id = "division_id";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback
                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);
            } else {
                $("#district_id").html('<option value="">Select Division First</option>');
                $(e).next().hide()
            }
        });
        $("#district_id").on("change", function (el) {
            let e = $(this);
            let key = el.which;
            if (typeof key !== "undefined") {
                return false
            }
            $(e).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#upazilla_id").html('<option value="">Please Wait...</option>');
            var district = $('#district_id').val();
            var district_id = district.split("@")[0];
            if (district_id) {
                let e = $(this);
                let api_url = "{{$ml_service_url}}thana/" + district_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "upazilla_id";
                let element_id = "id";//dynamic id for callback
                let element_name = "name_en";//dynamic name for callback
                let element_calling_id = "district_id";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback
                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);
            } else {
                $("#upazilla_id").html('<option value="">Select District First</option>');
                $(e).next().hide()
            }
        });
        $("#upazilla_id").on("change", function (el) {
            let e = $(this);
            let key = el.which;
            if (typeof key !== "undefined") {
                return false
            }
            $(e).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#post_office").html('<option value="">Please Wait...</option>');
            var district = $('#district_id').val();
            var district_id = district.split("@")[0];
            var upazilla = $('#upazilla_id').val();
            var upazilla_id = upazilla.split("@")[0];
            if (upazilla_id) {
                let e = $(this);
                let api_url = "{{$ml_service_url}}post-office/" + district_id + "/thana/" + upazilla_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "post_office";
                let element_id = "id";//dynamic id for callback
                let element_name = "name_en";//dynamic name for callback
                let element_calling_id = "upazilla_id";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback
                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);
            } else {
                $("#post_office").html('<option value="">Select Upazilla First</option>');
                $(e).next().hide()
            }
        });

        function callbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
            var option = '<option value="">Select One</option>';
            if (response.responseCode === 200) {
                $.each(response.data, function (key, row) {
                    let id = row[element_id] + '@' + row[element_name];
                    let value = row[element_name];
                    option += '<option value="' + id + '">' + value + '</option>';
                });
            }
            $("#" + calling_id).html(option);
            $("#" + calling_id).next().hide()
        }// end -:- callbackResponse()
        function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]) {
            var option = '<option value="">Select One</option>';
            if (response.responseCode === 200) {
                $.each(response.data, function (key, row) {
                    let id = (element_calling_id === '' || element_calling_id == null) ? (row[element_id] + '@' + row[element_name]) : (row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name]);
                    let value = row[element_name];
                    option += '<option value="' + id + '">' + value + '</option>'
                })
            } else {
                console.log(response.status)
            }
            $("#" + dependant_select_id).html(option);
            $("#" + calling_id).next().hide()
        }// end -:- dependantCallbackResponse()
    });// end -:- Document Ready
    function addrowDife(tableID, templateRow) {
        var x = document.getElementById(templateRow).cloneNode(true);
        console.log(x);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length;
        //alert(rowCount)
        if (rowCount < 8) {

            var rowCo = rowCount + 1;
            var idText = tableID + 'Row_' + rowCount;

            x.id = idText;
            $("#" + tableID).append(x);
            var bid = idText.split("_").pop();
            //get input elements
            var attrInput = $("#" + tableID).find('#' + idText).find('input');
            for (var i = 0; i < attrInput.length; i++) {
                var nameAtt = attrInput[i].name;
                var inputId = attrInput[i].id;

                var lastNum = parseInt(inputId.match(/\d+$/)[0]); // extract the last digits from the string and convert them to a number

                var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name

                //var ret = inputId.split('_')[0];
                var ret = inputId.replace('_' + lastNum, '');
                var repTextId = ret + '_' + rowCo;
                attrInput[i].id = repTextId;
                attrInput[i].name = repText;
            }
            attrInput.val(''); //value reset

            var attrSel = $("#" + tableID).find('#' + idText).find('select');
            for (var i = 0; i < attrSel.length; i++) {
                var nameAtt = attrSel[i].name;
                var inputId = attrSel[i].id;
                var lastNum = parseInt(inputId.match(/\d+$/)[0]); // extract the last digits from the string and convert them to a number
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
                //var ret = inputId.split('_')[0];
                var ret = inputId.replace('_' + lastNum, '');
                var repTextId = ret + '_' + rowCo;
                attrSel[i].id = repTextId;
                attrSel[i].name = repText;
            }
            attrSel.val(''); //value reset


            //$('.m_currency ').prop('selectedIndex', 102);
            //Class change by btn-danger to btn-primary
            $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
            $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
            // alert(rowCount);

            //alert(floor[rowCount])
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

            $("#CdaOcForm").find('.enbnNumber').on('keydown', function (e) {
                var enbn = $(this).val();
                var reg = /^([0-9]|[০-৯])/;
                if (enbn) {
                    if (reg.test(enbn)) {
                        $(this).removeClass('error');
                        return true;
                    } else {
                        $(this).addClass('error');
                        return false;
                    }
                }
            })

        }
    }

    function openManagingAuthorityModal() {
        // $('#largeModal').modal({backdrop: 'static', keyboard: false});
        $.ajax({
            type: 'post',
            url: "{{ url('licence-applications/lima-factory-layout/ajax/managing-authority-modal') }}",
            data: {
                _token: $('input[name="_token"]').val(),
            },
            success: function (response) {
                if (response.responseCode == 1) {
                    $('#largeModal .modal-content').html(response.html);
                    $('#largeModal').modal('show');
                } else {
                    $('#largeModal').modal('hide');
                    alert(response.message);
                }
                console.log(response);
            },
            error: function (response) {
                alert(response.message);
                console.log(response);
            }
        });
    }// end -:- openManagingAuthorityModal()
    function managementAuthorityFormSubmit(btn) {
        var formData = new FormData(document.querySelector('#management-authority-information-form'));
        $.ajax({
            type: 'POST',
            url: "{{ url('licence-applications/lima-factory-layout/ajax/managing-authority-form') }}",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
            },
            success: function (response) {
                if (response.responseCode == 1) {
                    //console.log(response.data.factory_owners_name);
                    let content = '';
                    content += '<tr>';

                    content += '<td>' + response.data.residency_type + '<input type="hidden" name="residency_type[]" value="' + response.data.residency_type + '"/></td>';
                    content += '<td>' + response.data.factory_owners_name + '<input type="hidden" name="factory_owners_name[]" value="' + response.data.factory_owners_name + '"/></td>';
                    content += '<td>' + response.data.factory_cc_owner_designation_id + '<input type="hidden" name="factory_cc_owner_designation_id[]" value="' + response.data.factory_cc_owner_designation_id + '"/></td>';
                    content += '<td>' + response.data.factory_owners_father + '<input type="hidden" name="factory_owners_father[]" value="' + response.data.factory_owners_father + '"/></td>';
                    content += '<td>' + response.data.factory_owners_mother + '<input type="hidden" name="factory_owners_mother[]" value="' + response.data.factory_owners_mother + '"/></td>';
                    content += '<td>' + response.data.factory_owners_phone + '<input type="hidden" name="factory_owners_phone[]" value="' + response.data.factory_owners_phone + '"/></td>';
                    content += '<td>' + response.data.factory_owners_address + '<input type="hidden" name="factory_owners_address[]" value="' + response.data.factory_owners_address + '"/></td>';
                    content += '<td><a href="javascript:void(0);" class="btn btn-xs btn-warning">Open</a></td>';
                    content += '<td>' + response.data.factory_owners_nid + '<input type="hidden" name="factory_owners_nid[]" value="' + response.data.factory_owners_nid + '"/></td>';
                    content += '<td><a href="javascript:void(0);" class="btn btn-xs btn-warning">Open</a></td>';
                    content += '<td>' + response.data.factory_owners_passport + '<input type="hidden" name="factory_owners_passport[]" value="' + response.data.factory_owners_passport + '"/></td>';
                    content += '<td><button class="btn btn-xs btn-danger" onclick="removeHtmlTableRow(this)"><i class="fa fa-times"></i></button></td>';

                    content += '</tr>';
                    $('#managementAuthorityInformationTable').append(content);

                    $('#largeModal').modal('hide');
                } else {
                    alert(response.message);
                }
            },
            error: function (response) {
                alert('Error is occored ! ' + response.message);
            },
            complete: function () {
            }
        }); // end -:- Ajax
    }// end -:- managementAuthorityFormSubmit()
    function removeHtmlTableRow(tableRow) {
        $(tableRow).closest('tr').remove();
    }// end -:- removeHtmlTableRow()
</script>
