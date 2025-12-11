<?php
$accessMode = ACL::getAccsessRight('BfscdNocProposed');
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
    .btn-group-xs>.btn, .btn-xs {
        padding: 4px 8px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
        margin-top: -3px;
    }
    .panel-heading {
        padding: 10px 5px !important;
    }
    .panel-success>.panel-heading {
        color: #fff;
        background-color: #0c720c;
        border-color: #9ca198;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
    }
    .requriedBox{
        font-weight: bold;
    }
    .requriedBox{
        margin-bottom: -5px;
    }
    .guide {
        background-color: #f5f5f5;
        padding: 10px 20px;
    }
    .attested > ul > li{
        list-style:none;
    }
    .wizard > .steps .number {
        font-size: 1.2em;
    }
    .floorSubStation {
        font-size: 18px;
        font-weight: bold;
        color: #787474;
    }
    .floor_sub_station_div {
        border-top: 1px solid #c7c2c2;
        border-bottom: 1px solid #d7d1d1;
        background-color: #f9f9f9;
        margin: 0 10px;
        padding: 10px 2px 2px 2px;
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
    textarea {
        height: 60px !important;
    }

    ul.no-bullets {
        list-style-type: none;
        margin: 0;
        padding: 0;
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
                        <h5><strong>Application For NOC Proposed Building</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'noc-proposed/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NOCproposed',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>

                    <h3 class="text-center stepHeader"> Registration</h3>
                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Safety Firm</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-9">
                                        {!! Form::label('safety_firm','Safety Firm ',['class'=>'text-left col-md-4']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('safety_firm',[],'',['class' => 'form-control input-md','id'=>'safety_firm']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Name and Address of the Applicant</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('user_name','User Name ',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('user_name', \App\Libraries\CommonFunction::getUserFullName(),['class' => 'form-control input-md','id'=>'user_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('user_mobile','User Mobile No :',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('user_mobile',Auth::user()->user_phone,['class' => 'form-control input-md mobile_number_validation','id'=>'user_mobile']) !!}
                                            <span class="text-danger mobile_number_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('user_email','User Email ',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('user_email',Auth::user()->user_email,['class' => 'form-control input-md email','id'=>'user_email']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('user_address','User Address ',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('user_address',Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''),['class' => 'form-control input-md','id'=>'user_address']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Details of the owner of the proposed building / institution</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('owner_name','Name ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('owner_name','',['class' => 'form-control input-md required','id'=>'owner_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('owner_phone','Phone no ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('owner_phone','',['class' => 'form-control input-md mobile_number_validation required','id'=>'owner_phone']) !!}
                                            <span class="text-danger mobile_number_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('owner_email','Owner Email ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('owner_email','',['class' => 'form-control input-md email required','id'=>'owner_email']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('owner_division','Division ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('owner_division',[],'',['class' => 'form-control input-md required','id'=>'owner_division']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('owner_district','District ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('owner_district',[],'',['class' => 'form-control input-md required','id'=>'owner_district']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('owner_thana','Thana/Upozila ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('owner_thana',[],'',['class' => 'form-control input-md required','id'=>'owner_thana']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('owner_address','Address ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('owner_address',null,['class' => 'form-control input-md required','id'=>'owner_address']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </fieldset>
                    <h3 class="text-center stepHeader"> Plot Location & Details</h3>
                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Plot Location</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('proposed_building_division','Division of the Proposed Building',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('proposed_building_division',[],'',['class' => 'form-control input-md required','id'=>'proposed_building_division']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('proposed_building_district','District of the proposed building',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('proposed_building_district',[],'',['class' => 'form-control input-md required','id'=>'proposed_building_district']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('proposed_building_thana','Thana/Upozila of the proposed building',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('proposed_building_thana',[],'',['class' => 'form-control input-md required','id'=>'proposed_building_thana']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('buliding_no','Building No ',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('buliding_no','',['class' => 'form-control input-md onlyNumber','id'=>'buliding_no']) !!}
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-5 col-md-1">
                                                <input type="checkbox" name="buliding_no_check" id="buliding_no_check" value="1" onclick="requriedField('buliding_no_check','buliding_no')">
                                            </div>
                                            {!! Form::label('buliding_no_check','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('road_no','Road No ',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('road_no','',['class' => 'form-control input-md ','id'=>'road_no']) !!}
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-5 col-md-1">
                                                <input type="checkbox" name="road_no_check" id="road_no_check" value="1" onclick="requriedField('road_no_check','road_no')">
                                            </div>
                                            {!! Form::label('road_no_check','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('council','City Corporation / Municipality / Union Parishad ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('council',[],'',['class' => 'form-control input-md required','id'=>'council']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6" id="city_corporation_div_id" hidden>
                                        {!! Form::label('city_corporation','City Corporation',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('city_corporation',[],'',['class' => 'form-control input-md','id'=>'city_corporation']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="town_council_div_id" hidden>
                                        {!! Form::label('town_council','Municipality',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('town_council','',['class' => 'form-control input-md','id'=>'town_council']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="union_council_div_id" hidden>
                                        {!! Form::label('union_council','Union Parishad ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('union_council','',['class' => 'form-control input-md','id'=>'union_council']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('proposed_building_address','Address ',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('proposed_building_address',null,['class' => 'form-control input-md','id'=>'proposed_building_address']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="row" style="background-color:#F7F7F7;padding: 25px 15px 10px 15px;margin: 2px 2px 5px 2px;">
                            <div class="col-md-8">
                                {!! Form::label('number_of_building','Number of buildings',['class'=>'text-left col-md-5 required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('number_of_building',1,['class' => 'form-control input-md onlyNumber required','readonly','id'=>'number_of_building']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span class="pull-right">
                                    <a href="javascript:void(0)"
                                       onclick="addBuildingRow('moreInfoImd', 'templateImdFull');"
                                       class="btn btn-xs btn-info addTableRows">
                                        Add Building
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="panel panel-black templateImdFull" id="templateImdFull">
                        <div class="panel panel-success">
                            <div class="panel-heading"><strong>Details of the proposed multi-storey Building</strong>
                                <span class="pull-right topdata" style="display: none;">
                                    <a href="javascript:void(0)"
                                       onclick="removeTableRow('moreInfoImd', 'templateImdFull');"
                                       class="btn btn-xs btn-info topbutton">
                                       <i class="fa fa-plus"> Remove Building </i>
                                    </a>
                                </span>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('building_construction','Building construction class',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('building_construction[0]',[],'',['class' => 'form-control input-md building_construction required','id'=>'building_construction']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('building_use','Class of Building Use',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('building_use[0]',[],'',['class' => 'form-control input-md building_use required','id'=>'building_use']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('building_use_type','Type of building use',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('building_use_type[0]',[],'',['class' => 'form-control input-md building_use_type required','id'=>'building_use_type']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('building_height','Building height (m) [If the height of the building is high]',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('building_height[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'building_height']) !!}
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('floor','Floor ',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('floor[0]',[],'',['class' => 'form-control floor input-md required','id'=>'floor']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('floor_number','Number of Floors',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('floor_number[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'floor_number']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('number_stairs','Number of Stairs',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('number_stairs[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'number_stairs']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('number_of_basement','Number of Basement Floors ',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('number_of_basement[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'number_of_basement_0']) !!}
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-6 col-md-1">
                                                <input type="checkbox" name="number_of_basement_check[0]" id="number_of_basement_check_0" value="1" onclick="requriedField('number_of_basement_check_0','number_of_basement_0')">
                                            </div>
                                            {!! Form::label('number_of_basement_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('number_of_mezzanine','Number of mezzanine Floors ',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('number_of_mezzanine[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'number_of_mezzanine_0']) !!}
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-6 col-md-1">
                                                <input type="checkbox" name="number_of_mezzanine_check[0]" id="number_of_mezzanine_check_0" value="1" onclick="requriedField('number_of_mezzanine_check_0','number_of_mezzanine_0')">
                                            </div>
                                            {!! Form::label('number_of_mezzanine_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('number_of_simi_basement','Number of Semi Basement Floors ',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('number_of_simi_basement[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'number_of_simi_basement_0']) !!}
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-6 col-md-1">
                                                <input type="checkbox" name="number_of_simi_basement_check[0]" id="number_of_simi_basement_check_0" value="1" onclick="requriedField('number_of_simi_basement_check_0','number_of_simi_basement_0')">
                                            </div>
                                            {!! Form::label('number_of_simi_basement_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('area_of_eash_floor','Area of Each Floor (sq. M.) ',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('area_of_eash_floor[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'area_of_eash_floor']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('size_of_each_basement','Size of each basement floor (sq. M.) ',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('size_of_each_basement[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'size_of_each_basement_0']) !!}
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-6 col-md-1">
                                                <input type="checkbox" name="size_of_each_basement_check[0]" id="size_of_each_basement_check_0" value="1" onclick="requriedField('size_of_each_basement_check_0','size_of_each_basement_0')">
                                            </div>
                                            {!! Form::label('size_of_each_basement_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('vol_each_mezzainine','Volume of each mezzanine floor (sq. M.) ',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('vol_each_mezzainine[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'vol_each_mezzainine_0']) !!}
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-6 col-md-1">
                                                <input type="checkbox" name="vol_each_mezzainine_check[0]" id="vol_each_mezzainine_check_0" value="1" onclick="requriedField('vol_each_mezzainine_check_0','vol_each_mezzainine_0')">
                                            </div>
                                            {!! Form::label('vol_each_mezzainine_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('size_of_each_simi_basement','Size of each semi basement floor (sq. M.) ',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('size_of_each_simi_basement[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'size_of_each_simi_basement_0']) !!}
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-6 col-md-1">
                                                <input type="checkbox" name="size_of_each_simi_basement_check[0]" id="size_of_each_simi_basement_check_0" value="1" onclick="requriedField('size_of_each_simi_basement_check_0','size_of_each_simi_basement_0')">
                                            </div>
                                            {!! Form::label('size_of_each_simi_basement_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>


{{--                                    <div class="col-md-6">--}}
{{--                                        {!! Form::label('size_of_each_simi_basement','Size of each semi basement floor (sq. M.) ',['class'=>'text-left col-md-6 required-star']) !!}--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::text('size_of_each_simi_basement[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'size_of_each_simi_basement_0']) !!}--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-12 requriedBox">--}}
{{--                                            <div class="col-md-offset-6 col-md-1">--}}
{{--                                                <input type="checkbox" name="size_of_each_simi_basement_check[0]" id="size_of_each_simi_basement_check_0" value="1" onclick="requriedField('size_of_each_simi_basement_check_0','size_of_each_simi_basement_0')">--}}
{{--                                            </div>--}}
{{--                                            {!! Form::label('size_of_each_simi_basement_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('total_floor_area','Total floor area (sq. M.)',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('total_floor_area[0]','',['class' => 'form-control input-md onlyNumber required','id'=>'total_floor_area']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('electric_line','Whether there are electric high voltage lines on the proposed plot ',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('electric_line[0]',[],'',['class' => 'form-control input-md electric_line required','id'=>'electric_line']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('electric_line_distance','The distance of the high voltage line from the plot is horizontal and vertical distance',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('electric_line_distance[0]','',['class' => 'form-control input-md required','id'=>'electric_line_distance']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('total_flats_number','Total number of residences / apartments and flats in case of residential building',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::text('total_flats_number[0]','',['class' => 'form-control input-md number_0_to_9 onlyNumber required','id'=>'total_flats_number']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('description','A description of the floors to be used by each use class in the case of mixed class use',['class'=>'text-left col-md-6 required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::textarea('description[0]',null,['class' => 'form-control input-md required','id'=>'description']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        </div>
                        <div id="moreInfoImd" class="clear"></div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Others Adjacent/Nearby </h3>
                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Description of the road adjacent to the plot</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('main_street_name','Name of the main street adjacent to the plot',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('main_street_name','',['class' => 'form-control input-md required','id'=>'main_street_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('main_road_width','Width of main road adjacent to the plot (m)',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('main_road_width','',['class' => 'form-control input-md onlyNumber required','id'=>'main_road_width']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('length_connection','Length of plot connecting road (m)',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('length_connection','',['class' => 'form-control input-md onlyNumber required','id'=>'length_connection']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('plot_connecting','Plot connection road width (m.)',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('plot_connecting','',['class' => 'form-control input-md onlyNumber required','id'=>'plot_connecting']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('recommended_road','Recommended road length inside the plot (m)',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('recommended_road','',['class' => 'form-control input-md onlyNumber required','id'=>'recommended_road']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Description of the four sides of the land or plot</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('north_side','Whats in the North ?',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('north_side','',['class' => 'form-control input-md required','id'=>'north_side']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('south_side','Whats in the South ?',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('south_side','',['class' => 'form-control input-md required','id'=>'south_side']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('east_side','Whats in the East ?',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('east_side','',['class' => 'form-control input-md required','id'=>'east_side']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('west_side','Whats in the West ?',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('west_side','',['class' => 'form-control input-md required','id'=>'west_side']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Nearby Fire station</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('nearby_division','Division',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('nearby_division',[],'',['class' => 'form-control input-md required','id'=>'nearby_division']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('nearby_district','District',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('nearby_district',[],'',['class' => 'form-control input-md required','id'=>'nearby_district']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('nearby_tahan','Thana',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('nearby_tahan',[],'',['class' => 'form-control input-md required','id'=>'nearby_tahan']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('nearby_fire_station','Fire station',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('nearby_fire_station',[],'',['class' => 'form-control input-md required','id'=>'nearby_fire_station']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Details of those involved in the construction work</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('engineer_name','Name and address of the engineer',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('engineer_name','',['class' => 'form-control input-md required','id'=>'engineer_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('engineer_reg_no','Engineers registration number',['class'=>'text-left col-md-5  required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('engineer_reg_no','',['class' => 'form-control input-md required onlyNumber','id'=>'engineer_reg_no']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('architect_name','Name and address of the architect',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('architect_name','',['class' => 'form-control input-md required','id'=>'architect_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('architect_reg_no','Architects registration number',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('architect_reg_no','',['class' => 'form-control input-md required onlyNumber','id'=>'architect_reg_no']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class=" row electrical_station_div">
                                <div class="col-md-8 ">
                                    <div class="col-md-offset-1 col-md-1">
                                        <input type="checkbox" name="electrical_station" id="electrical_station" value="1">
                                    </div>
                                    {!! Form::label('electrical_station','Is there an electrical substation ?',['class'=>'text-left col-md-8 floorSubStation']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary" id="eleSubstation">
                            <div class="panel-heading"><strong>Electrical Substation Information</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('sub_station_location','Location of sub station',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('sub_station_location','',['class' => 'form-control input-md required','id'=>'sub_station_location']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('sub_station_room_size','Sub station room size',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('sub_station_room_size','',['class' => 'form-control input-md  onlyNumber required','id'=>'sub_station_room_size']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('number_of_substation','Number of sub stations',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('number_of_substation','',['class' => 'form-control input-md number_0_to_9 onlyNumber required','id'=>'number_of_substation']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('floor_sub_station','Which floors is the electric sub station ?',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('floor_sub_station','',['class' => 'form-control input-md required','id'=>'floor_sub_station']) !!}
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-6 col-md-1">
                                                <input type="checkbox" name="floor_sub_station_check" id="floor_sub_station_check" value="1" onclick="requriedField('floor_sub_station_check','floor_sub_station')">
                                            </div>
                                            {!! Form::label('floor_sub_station_check','Downstairs',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('electric_sub_station_kVA','How many kVA is the electric sub station',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('electric_sub_station_kVA','',['class' => 'form-control input-md required','id'=>'electric_sub_station_kVA']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('adequate_electtrical','Whether there is adequate ventilation in the interior of the electrical sub-station room',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('adequate_electtrical','yes','' , ['class'=>'required ', 'id' => 'adequate_electtrical_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('adequate_electtrical', 'no','' , ['class'=>'required', 'id' => 'adequate_electtrical_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('safety_bestney','Whether the safety bestney is protected by a 4 feet high steel net around the transformer',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('safety_bestney','yes','' , ['class'=>'required ', 'id' => 'safety_bestney_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('safety_bestney', 'no','' , ['class'=>'required', 'id' => 'safety_bestney_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('rain_likely','Whether flood / rain water is likely to enter inside the electrical substation',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('rain_likely','yes','' , ['class'=>'required ', 'id' => 'rain_likely_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('rain_likely', 'no','' , ['class'=>'required', 'id' => 'rain_likely_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('properly_fire_rated','Whether the doors and walls of the electrical substation room are properly fire rated',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('properly_fire_rated','yes','' , ['class'=>'required ', 'id' => 'properly_fire_ratedy_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('properly_fire_rated', 'no','' , ['class'=>'required', 'id' => 'properly_fire_rated_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('rubber_mats','Whether rubber mats have been properly installed inside the electrical substation room',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('rubber_mats','yes','' , ['class'=>'required ', 'id' => 'rubber_mats_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('rubber_mats', 'no','' , ['class'=>'required', 'id' => 'rubber_mats_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('gas_fire_system','In case of installation of electrical substation inside the building “Inert Gas Fire Suppression System” Whether there are installation arrangements',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('gas_fire_system','yes','' , ['class'=>'required ', 'id' => 'gas_fire_system_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('gas_fire_system', 'no','' , ['class'=>'required', 'id' => 'gas_fire_system_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('safety_legend','Whether the design of the electrical substation is displayed on a separate page with blowup / enlarge and safety legend',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('safety_legend','yes','' , ['class'=>'required', 'id' => 'safety_legend_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('safety_legend', 'no','' , ['class'=>'required', 'id' => 'safety_legend_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                    </fieldset>

                    <h3 class="text-center stepHeader">Application Guide & Submit</h3>
                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Application Guide</strong></div>
                            <div class="panel-body guide">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <h4>The following documents must be submitted to the office of the head of the inspection committee</h4><br>
                                    </div>
                                    <div class="col-md-12 attested">
                                        <ul class="no-bullets">
                                            <li>(A) Photocopy of the owner's lease or purchase or heba or other documents ............................................ 02 Copy</li>
                                            <li>(B) Attested photocopy of documents and permits if the land or plot is allotted by the government ........ 02 Copy</li>
                                            <li>(C) Attested photocopy of land rent update receipt..................... ...................................................................  02 Copy</li>
                                            <li>(D) Attested photocopy of power of attorney.................... ...............................................................................  02 Copy</li>
                                            <li>(E) Site plan ........................................................................................................................................................  02 Copy</li>
                                            <li>(F) Layout plan ....................................................................................................................................................  02 Copy</li>
                                            <li>(G) Fire Safety Plan: Floor based design (A-2 / A-3 size)................................................................................... 04 Copy</li>
                                            <li>(H) Original copy of the certificate of fee paid (subject to effect).</li>
                                        </ul><br>

                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 form-group {{$errors->has('acceptTerms') ? 'has-error' : ''}}">
                                                <div style="margin-left: 20px">
                                                    <input id="acceptTerms-2" name="acceptTerms" type="checkbox"
                                                           class="required col-md-1 text-left" style="width:3%;">
                                                    <label for="acceptTerms-2" class="col-md-11 text-left ">
                                                        I / we are certifying that, The above information has been duly fulfilled in accordance with fire prevention and extinguishing rules and regulations and the information provided to the best of my / our knowledge is correct. Once the application is approved, the Director General or the authorized officer may revoke the clearance due to any misinformation or any other inconsistency or any need of the Government. Moreover, we will be obliged to provide any other information or documents under these laws and rules.</label>

                                                    <div class="clearfix"></div>
                                                    {!! $errors->first('acceptTerms','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
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
                                                Vat/ tax and service charge is an approximate amount, it may vary based on the
                                                Sonali Bank system.
                                            </div>

                                            <div class="alert alert-info" role="alert">
                                                <div class="alert alert-info" role="alert">
                                                    Please check your mailbox for an email from ""VAT Online Services"" within 5
                                                    minutes for further instructions
                                                </div>
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
@include('FscdNocProposed::noc-proposed-scripts-add')
