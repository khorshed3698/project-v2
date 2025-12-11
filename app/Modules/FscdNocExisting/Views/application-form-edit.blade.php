<?php
$accessMode = ACL::getAccsessRight('BfscdNocExiting');
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
    /*.electrical_station_div{*/
    /*    border-bottom: 1px solid #d7d1d1 !important;*/
    /*}*/
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
                        <h5><strong>Application For NOC Exiting Building</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'noc-exiting/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NOCexiting',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>
                    <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"
                           id="app_id"/>
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
                                            {!! Form::text('user_name', !empty($appData->user_name) ? $appData->user_name : '',['class' => 'form-control input-md','id'=>'user_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('user_mobile','User Mobile No ',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('user_mobile',!empty($appData->user_mobile) ? $appData->user_mobile : '',['class' => 'form-control input-md mobile_number_validation','id'=>'user_mobile']) !!}
                                            <span class="text-danger mobile_number_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('user_email','User Email ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('user_email',!empty($appData->user_email) ? $appData->user_email : '',['class' => 'form-control input-md email required','id'=>'user_email']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('user_address','User Address ',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('user_address',!empty($appData->user_address) ? $appData->user_address : '',['class' => 'form-control input-md','id'=>'user_address']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Details of the owner of the proposed Building / Institution</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('owner_name','Name ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('owner_name',!empty($appData->owner_name) ? $appData->owner_name : '',['class' => 'form-control input-md required','id'=>'owner_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('owner_phone','Phone no ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('owner_phone',!empty($appData->owner_phone) ? $appData->owner_phone : '',['class' => 'form-control input-md mobile_number_validation required','id'=>'owner_phone']) !!}
                                            <span class="text-danger mobile_number_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('owner_email','Owner Email ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('owner_email',!empty($appData->owner_email) ? $appData->owner_email : '',['class' => 'form-control input-md email required','id'=>'owner_email']) !!}
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
                                        {!! Form::label('owner_thana','Thana/Upozila',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('owner_thana',[],'',['class' => 'form-control input-md required','id'=>'owner_thana']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('owner_address','Address ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('owner_address',!empty($appData->owner_address) ? $appData->owner_address : '',['class' => 'form-control input-md required','id'=>'owner_address']) !!}
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
                                            @if(isset($appData->buliding_no_check) && $appData->buliding_no_check == 1 )
                                                {!! Form::text('buliding_no', '',['class' => 'form-control input-md onlyNumber','id'=>'buliding_no','readonly'=>'readonly']) !!}
                                            @else
                                                {!! Form::text('buliding_no',!empty($appData->buliding_no) ? $appData->buliding_no : '',['class' => 'form-control input-md onlyNumber','id'=>'buliding_no']) !!}
                                            @endif
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-5 col-md-1">
                                                <input type="checkbox" name="buliding_no_check" id="buliding_no_check" @if(isset($appData->buliding_no_check) && $appData->buliding_no_check == 1) checked="checked" @endif value="1" onclick="requriedField('buliding_no_check','buliding_no')">
                                            </div>
                                            {!! Form::label('buliding_no_check','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('road_no','Road No ',['class'=>'text-left col-md-5']) !!}
                                        <div class="col-md-7">
                                            @if(isset($appData->road_no_check) && $appData->road_no_check == 1 )
                                                {!! Form::text('road_no', '',['class' => 'form-control input-md onlyNumber','id'=>'road_no','readonly'=>'readonly']) !!}
                                            @else
                                                {!! Form::text('road_no',!empty($appData->road_no) ? $appData->road_no : '',['class' => 'form-control input-md','id'=>'road_no']) !!}
                                            @endif
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-5 col-md-1">
                                                <input type="checkbox" name="road_no_check" id="road_no_check" value="1" onclick="requriedField('road_no_check','road_no')">
                                            </div>
                                            {!! Form::label('road_no_check','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('council','City Corporation / Municipality / Union Parishad',['class'=>'text-left col-md-5 required-star']) !!}
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
                                            {!! Form::text('town_council',!empty($appData->town_council) ? $appData->town_council : '',['class' => 'form-control input-md','id'=>'town_council']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="union_council_div_id" hidden>
                                        {!! Form::label('union_council','Union Parishad ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('union_council', !empty($appData->union_council) ? $appData->union_council : '',['class' => 'form-control input-md','id'=>'union_council']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('proposed_building_address','Address ',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('proposed_building_address',!empty($appData->proposed_building_address) ? $appData->proposed_building_address : '',['class' => 'form-control input-md required','id'=>'proposed_building_address']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="row" style="background-color:#F7F7F7;padding: 25px 15px 10px 15px;margin: 2px 2px 5px 2px;">
                            <div class="col-md-8">
                                {!! Form::label('number_of_building','Number of buildings ',['class'=>'text-left col-md-5 required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('number_of_building',$appData->number_of_building,['class' => 'form-control input-md onlyNumber required','readonly','id'=>'number_of_building']) !!}
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

                        @if(!empty($appData->building_height))
                            <div id="templateImdFullPar">
                                <?php $inc = 0; ?>
                                @foreach($appData->building_height as $key => $value)

                                    <?php
                                    $gkey = ($key + 1);
                                    ?>
                                    <?php $templateImdFull = ($inc == 0 ? 'templateImdFull' : 'rowCount' . $inc); ?>
                                    <div class="panel panel-black templateImdFull" id="{{$templateImdFull}}">
                                        <div class="panel panel-success">
                                            <div class="panel-heading"><strong>Details of the proposed multi-storey Building</strong>
                                                <span class="pull-right topdata" style="{{($inc != 0)?'display:block;':'display:none;'}}">
                                                    <?php if ($inc == 0) { ?>
                                                    <a href="javascript:void(0)"
                                                       onclick="removeTableRow('moreInfoImd', 'templateImdFull');"
                                                       class="btn btn-xs btn-info topbutton">
                                                       <i class="fa fa-plus"> Remove Building </i>
                                                    </a>
                                                    <?php } else { ?>
                                                    <a href="javascript:void(0)"
                                                       onclick="removeTableRow('templateImdFullPar', 'rowCount<?php echo $inc; ?>')"
                                                       class="btn btn-xs btn-info addTableRows btn-danger"><i
                                                                class="fa fa-times"> Remove Building </i>
                                                    </a>
                                                    <?php } ?>
                                                 </span>
                                            </div>

                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        {!! Form::label('building_construction','Building construction class',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::select('building_construction['.$inc.']',[], '',['class' => 'form-control input-md building_construction required', 'data-value'=>$appData->building_construction[$key], 'id'=>"building_construction_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        {!! Form::label('building_use','Class of Building Use',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::select('building_use['.$inc.']',[],'',['class' => 'form-control input-md building_use required', 'data-value'=>$appData->building_construction[$key], 'id'=>"building_use_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        {!! Form::label('building_use_type','Type of building use',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::select('building_use_type['.$inc.']',[],'',['class' => 'form-control input-md building_use_type required', 'data-value'=>$appData->building_use_type[$key], 'id'=>"building_use_type_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        {!! Form::label('building_height','Building height (m) [If the height of the building is high]',['class'=>'text-left col-md-6']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('building_height['.$inc.']',!empty($appData->building_height[$key]) ? $appData->building_height[$key] : '',['class' => 'form-control input-md onlyNumber','id'=>"building_height_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        {!! Form::label('floor','Floor ',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::select('floor['.$inc.']',[],'',['class' => 'form-control input-md floor required', 'data-value'=>$appData->floor[$key], 'id'=>"floor_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        {!! Form::label('floor_number','Number of Floors  ',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('floor_number['.$inc.']',isset($appData->floor_number[$key]) ? $appData->floor_number[$key] : '',['class' => 'form-control input-md onlyNumber required','id'=>"floor_number_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        {!! Form::label('number_stairs','Number of Stairs',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('number_stairs['.$inc.']',isset($appData->number_stairs[$key]) ? $appData->number_stairs[$key]  : '',['class' => 'form-control input-md onlyNumber required','id'=>"number_stairs_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        {!! Form::label('number_of_basement','Number of Basement Floors ',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            <?php
                                                                $number_of_basement_check =  isset($appData->number_of_basement_check) ? json_decode(json_encode($appData->number_of_basement_check), true) : '';
                                                            ?>
                                                            @if(isset($number_of_basement_check[$key]) && $number_of_basement_check[$key] == 1)
                                                                {!! Form::text('number_of_basement['.$inc.']','',['class' => 'form-control input-md onlyNumber','id'=>"number_of_basement_$inc", 'readonly'=>'readonly']) !!}
                                                            @else
                                                                {!! Form::text('number_of_basement['.$inc.']',!empty($appData->number_of_basement[$key]) ? $appData->number_of_basement[$key] : '',['class' => 'form-control input-md onlyNumber required','id'=>"number_of_basement_$inc"]) !!}
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12 requriedBox">
                                                            <div class="col-md-offset-6 col-md-1">
                                                                <input type="checkbox" name="number_of_basement_check[{{$inc}}]" {{(isset($number_of_basement_check[$key]) && $number_of_basement_check[$key] == 1) ? 'checked' : ''}} id="number_of_basement_check_{{$inc}}" value="1" onclick="requriedField('number_of_basement_check_{{$inc}}','number_of_basement_{{$inc}}')">
                                                            </div>
                                                            {!! Form::label('number_of_basement_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        {!! Form::label('number_of_mezzanine','Number of mezzanine Floors ',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            <?php
                                                            $number_of_mezzanine_check =  isset($appData->number_of_mezzanine_check) ? json_decode(json_encode($appData->number_of_mezzanine_check), true) : '';
                                                            ?>
                                                            @if(isset($number_of_mezzanine_check[$key]) && $number_of_mezzanine_check[$key] == 1)
                                                                {!! Form::text('number_of_mezzanine['.$inc.']', '',['class' => 'form-control input-md onlyNumber','id'=>"number_of_mezzanine_$inc", 'readonly'=>'readonly']) !!}
                                                            @else
                                                                {!! Form::text('number_of_mezzanine['.$inc.']',isset($appData->number_of_mezzanine[$key]) ? $appData->number_of_mezzanine[$key] : '',['class' => 'form-control input-md onlyNumber required','id'=>"number_of_mezzanine_$inc"]) !!}
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12 requriedBox">
                                                            <div class="col-md-offset-6 col-md-1">
                                                                <input type="checkbox" name="number_of_mezzanine_check[{{$inc}}]" {{(isset($number_of_mezzanine_check[$key]) && $number_of_mezzanine_check[$key] == 1) ? 'checked' : ''}} id="number_of_mezzanine_check_{{$inc}}" value="1" onclick="requriedField('number_of_mezzanine_check_{{$inc}}','number_of_mezzanine_{{$inc}}')">
                                                            </div>
                                                            {!! Form::label('number_of_mezzanine_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        {!! Form::label('number_of_simi_basement','Number of Semi Basement Floors ',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            <?php
                                                                $number_of_simi_basement_check =  isset($appData->number_of_simi_basement_check) ? json_decode(json_encode($appData->number_of_simi_basement_check), true) : '';
                                                            ?>
                                                            @if(isset($number_of_simi_basement_check[$key]) && $number_of_simi_basement_check[$key] == 1)
                                                                 {!! Form::text('number_of_simi_basement['.$inc.']', '',['class' => 'form-control input-md onlyNumber','id'=>"number_of_simi_basement_$inc",'readonly'=>'readonly']) !!}
                                                            @else
                                                                 {!! Form::text('number_of_simi_basement['.$inc.']',isset($appData->number_of_simi_basement[$key]) ? $appData->number_of_simi_basement[$key] : '',['class' => 'form-control input-md onlyNumber required','id'=>"number_of_simi_basement_$inc"]) !!}
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12 requriedBox">
                                                            <div class="col-md-offset-6 col-md-1">
                                                                <input type="checkbox" name="number_of_simi_basement_check[{{$inc}}]" {{(isset($number_of_simi_basement_check[$key]) && $number_of_simi_basement_check[$key] == 1) ? 'checked' : ''}} id="number_of_simi_basement_check_{{$inc}}" value="1" onclick="requriedField('number_of_simi_basement_check_{{$inc}}','number_of_simi_basement_{{$inc}}')">
                                                            </div>
                                                            {!! Form::label('number_of_simi_basement_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        {!! Form::label('area_of_eash_floor','Area of Each Floor (sq. M.) ',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('area_of_eash_floor['.$inc.']',isset($appData->area_of_eash_floor[$key]) ? $appData->area_of_eash_floor[$key] : '',['class' => 'form-control input-md onlyNumber required','id'=>"area_of_eash_floor_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        {!! Form::label('size_of_each_basement','Size of each basement floor (sq. M.) ',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            <?php
                                                            $size_of_each_basement_check =  isset($appData->size_of_each_basement_check) ? json_decode(json_encode($appData->size_of_each_basement_check), true) : '';
                                                            ?>
                                                            @if( isset($size_of_each_basement_check[$key]) && $size_of_each_basement_check[$key] == 1)
                                                                {!! Form::text('size_of_each_basement['.$inc.']', '',['class' => 'form-control input-md onlyNumber','id'=>"size_of_each_basement_$inc",'readonly'=>'readonly']) !!}
                                                            @else
                                                                {!! Form::text('size_of_each_basement['.$inc.']',isset($appData->size_of_each_basement[$key]) ? $appData->size_of_each_basement[$key] : '',['class' => 'form-control input-md onlyNumber required','id'=>"size_of_each_basement_$inc"]) !!}
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12 requriedBox">
                                                            <div class="col-md-offset-6 col-md-1">
                                                                <input type="checkbox" name="size_of_each_basement_check[{{$inc}}]" {{( isset($size_of_each_basement_check[$key]) && $size_of_each_basement_check[$key] == 1) ? 'checked':''}} id="size_of_each_basement_check_{{$inc}}" value="1" onclick="requriedField('size_of_each_basement_check_{{$inc}}','size_of_each_basement_{{$inc}}')">
                                                            </div>
                                                            {!! Form::label('size_of_each_basement_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        {!! Form::label('vol_each_mezzainine','Volume of each mezzanine floor (sq. M.) ',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            <?php
                                                            $vol_each_mezzainine_check =  isset($appData->vol_each_mezzainine_check) ? json_decode(json_encode($appData->vol_each_mezzainine_check), true) : '';
                                                            ?>
                                                            @if(isset($vol_each_mezzainine_check[$key]) && $vol_each_mezzainine_check[$key] == 1)
                                                            {!! Form::text('vol_each_mezzainine['.$inc.']', '',['class' => 'form-control input-md onlyNumber','id'=>"vol_each_mezzainine_$inc",'readonly'=>'readonly']) !!}
                                                            @else
                                                                {!! Form::text('vol_each_mezzainine['.$inc.']',isset($appData->vol_each_mezzainine[$key]) ? $appData->vol_each_mezzainine[$key] : '',['class' => 'form-control input-md onlyNumber required','id'=>"vol_each_mezzainine_$inc"]) !!}
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12 requriedBox">
                                                            <div class="col-md-offset-6 col-md-1">
                                                                <input type="checkbox" name="vol_each_mezzainine_check[{{$inc}}]" id="vol_each_mezzainine_check_{{$inc}}" value="1" onclick="requriedField('vol_each_mezzainine_check_{{$inc}}','vol_each_mezzainine_{{$inc}}')" {{(isset($vol_each_mezzainine_check[$key]) && $vol_each_mezzainine_check[$key] == 1) ? 'checked':''}}>
                                                            </div>
                                                            {!! Form::label('vol_each_mezzainine_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        {!! Form::label('size_of_each_simi_basement','Size of each semi basement floor (sq. M.) ',['class'=>'text-left col-md-6  required-star']) !!}
                                                        <div class="col-md-6">
                                                            <?php
                                                            $size_of_each_simi_basement_check =  isset($appData->size_of_each_simi_basement_check) ? json_decode(json_encode($appData->size_of_each_simi_basement_check), true) : '';
                                                            ?>
                                                            @if(isset($size_of_each_simi_basement_check[$key]) && $size_of_each_simi_basement_check[$key] == 1 )
                                                            {!! Form::text('size_of_each_simi_basement['.$inc.']', '',['class' => 'form-control input-md onlyNumber','id'=>"size_of_each_simi_basement_$inc" ,'readonly'=>'readonly']) !!}
                                                            @else
                                                            {!! Form::text('size_of_each_simi_basement['.$inc.']',isset($appData->size_of_each_simi_basement[$key]) ? $appData->size_of_each_simi_basement[$key] : '',['class' => 'form-control input-md onlyNumber  required','id'=>"size_of_each_simi_basement_$inc" ]) !!}
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12 requriedBox">
                                                            <div class="col-md-offset-6 col-md-1">
                                                                <input type="checkbox" name="size_of_each_simi_basement_check[{{$inc}}]" id="size_of_each_simi_basement_check_{{$inc}}" value="1" {{(isset($size_of_each_simi_basement_check[$key]) && $size_of_each_simi_basement_check[$key] == 1 )? 'checked':''}} onclick="requriedField('size_of_each_simi_basement_check_{{$inc}}','size_of_each_simi_basement_{{$inc}}')">
                                                            </div>
                                                            {!! Form::label('size_of_each_simi_basement_check_0','Not Required',['class'=>'text-left col-md-4 requriedBox']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        {!! Form::label('total_floor_area','Total floor area (sq. M.)',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('total_floor_area['.$inc.']',isset($appData->total_floor_area[$key]) ? $appData->total_floor_area[$key] : '',['class' => 'form-control input-md onlyNumber required','id'=>"total_floor_area_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        {!! Form::label('electric_line','Whether there are electric high voltage lines on the proposed plot',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::select('electric_line['.$inc.']',[],'',['class' => 'form-control input-md electric_line required', 'data-value'=>$appData->electric_line[$key],'id'=>"electric_line_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        {!! Form::label('electric_line_distance','The distance of the high voltage line from the plot is horizontal and vertical distance',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('electric_line_distance['.$inc.']',isset($appData->electric_line_distance[$key]) ? $appData->electric_line_distance[$key]: '',['class' => 'form-control input-md required','id'=>"electric_line_distance_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        {!! Form::label('total_flats_number','Total number of residences / apartments and flats in case of residential building',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('total_flats_number['.$inc.']',isset($appData->total_flats_number[$key]) ? $appData->total_flats_number[$key] :'',['class' => 'form-control input-md number_0_to_9 onlyNumber required','id'=>"total_flats_number_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        {!! Form::label('description','A description of the floors to be used by each use class in the case of mixed class use',['class'=>'text-left col-md-6 required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::textarea('description['.$inc.']',isset($appData->description[$key]) ? $appData->description[$key] :'',['class' => 'form-control input-md required','id'=>"description_$inc"]) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $inc++; ?>
                            @endforeach
                            </div>
                        @else
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
                                                {!! Form::label('building_use_type','Type of building use',['class'=>'text-left col-md-6']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::select('building_use_type[0]',[],'',['class' => 'form-control input-md building_use_type','id'=>'building_use_type']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('building_height','Building height (m) [If the height of the building is high]',['class'=>'text-left col-md-6']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text('building_height[0]','',['class' => 'form-control input-md onlyNumber','id'=>'building_height']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                {!! Form::label('floor','Floor',['class'=>'text-left col-md-6 required-star']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::select('floor[0]',[],'',['class' => 'form-control input-md floor required','id'=>'floor']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('floor_number','Number of Floors  ',['class'=>'text-left col-md-6 required-star']) !!}
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
                                                {!! Form::label('number_of_basement','Number of Basement Floors',['class'=>'text-left col-md-6 required-star']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text('number_of_basement[0]','',['class' => 'form-control input-md onlyNumber','id'=>'number_of_basement_0']) !!}
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
                                                {!! Form::label('area_of_eash_floor','Area of Each Floor (sq. M.) ',['class'=>'text-left col-md-6']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text('area_of_eash_floor[0]','',['class' => 'form-control input-md onlyNumber','id'=>'area_of_eash_floor']) !!}
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

                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                {!! Form::label('total_floor_area','Total floor area (sq. M.) ',['class'=>'text-left col-md-6']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text('total_floor_area[0]','',['class' => 'form-control input-md onlyNumber','id'=>'total_floor_area']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('electric_line','Whether there are electric high voltage lines on the proposed plot ',['class'=>'text-left col-md-6']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::select('electric_line[0]',[],'',['class' => 'form-control input-md electric_line','id'=>'electric_line']) !!}
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
                                                {!! Form::label('total_flats_number','Total number of residences / apartments and flats in case of residential building',['class'=>'text-left col-md-6']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::text('total_flats_number[0]','',['class' => 'form-control input-md','id'=>'total_flats_number']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                {!! Form::label('description','A description of the floors to be used by each use class in the case of mixed class use',['class'=>'text-left col-md-6']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::textarea('description[0]',null,['class' => 'form-control input-md','id'=>'description']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                                            {!! Form::text('main_street_name',!empty($appData->main_street_name) ? $appData->main_street_name : '',['class' => 'form-control input-md required','id'=>'main_street_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('main_road_width','Width of main road adjacent to the plot (m)',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('main_road_width',!empty($appData->main_road_width) ? $appData->main_road_width : '',['class' => 'form-control input-md onlyNumber required','id'=>'main_road_width']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('length_connection','Length of plot connecting road (m)',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('length_connection',!empty($appData->length_connection) ? $appData->length_connection : '',['class' => 'form-control input-md required onlyNumber','id'=>'length_connection']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('plot_connecting','Plot connection road width (m.)',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('plot_connecting',!empty($appData->plot_connecting) ? $appData->plot_connecting : '',['class' => 'form-control input-md onlyNumber required','id'=>'plot_connecting']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('recommended_road','Recommended road length inside the plot (m)',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('recommended_road',!empty($appData->recommended_road) ? $appData->recommended_road : '',['class' => 'form-control input-md onlyNumber required','id'=>'recommended_road']) !!}
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
                                            {!! Form::textarea('north_side',!empty($appData->north_side) ? $appData->north_side : '',['class' => 'form-control input-md required','id'=>'north_side']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('south_side','Whats in the South ?',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('south_side',!empty($appData->south_side) ? $appData->south_side : '',['class' => 'form-control input-md required','id'=>'south_side']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('east_side','Whats in the East ?',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('east_side',!empty($appData->east_side) ? $appData->east_side : '',['class' => 'form-control input-md required','id'=>'east_side']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('west_side','Whats in the West ?',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('west_side',!empty($appData->west_side) ? $appData->west_side : '',['class' => 'form-control input-md required','id'=>'west_side']) !!}
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
                                            {!! Form::select('nearby_tahan',[], '',['class' => 'form-control input-md required','id'=>'nearby_tahan']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('nearby_fire_station','Fire station',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('nearby_fire_station',[], '',['class' => 'form-control input-md required','id'=>'nearby_fire_station']) !!}
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
                                            {!! Form::textarea('engineer_name',!empty($appData->engineer_name) ? $appData->engineer_name : '',['class' => 'form-control input-md required','id'=>'engineer_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('engineer_reg_no','Engineers registration number',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('engineer_reg_no',!empty($appData->engineer_reg_no) ? $appData->engineer_reg_no : '',['class' => 'form-control input-md required onlyNumber','id'=>'engineer_reg_no']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('architect_name','Name and address of the architect',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::textarea('architect_name',!empty($appData->architect_name) ? $appData->architect_name : '',['class' => 'form-control input-md required','id'=>'architect_name']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('architect_reg_no','Architects registration number',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('architect_reg_no',!empty($appData->architect_reg_no) ? $appData->architect_reg_no : '',['class' => 'form-control input-md required onlyNumber','id'=>'architect_reg_no']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="row electrical_station_div">
                                <div class="col-md-8">
                                    <div class="col-md-offset-1 col-md-1">
                                        <input type="checkbox" name="electrical_station" id="electrical_station" value="1" {{(!empty($appData->electrical_station)&&($appData->electrical_station == 1))? "checked":'' }}>
                                    </div>
                                    {!! Form::label('electrical_station','Is there an electrical substation ?',['class'=>'text-left col-md-8 floorSubStation']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary" id="eleSubstation" style="{{!empty($appData->electrical_station)&& ($appData->electrical_station == 1)?"display: block;":"display: none;"}}">
                            <div class="panel-heading"><strong>Electrical Substation Information</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('sub_station_location','Location of sub station',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('sub_station_location',!empty($appData->sub_station_location) ? $appData->sub_station_location : '',['class' => 'form-control input-md required','id'=>'sub_station_location']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('sub_station_room_size','Sub station room size',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('sub_station_room_size',!empty($appData->sub_station_room_size) ? $appData->sub_station_room_size : '',['class' => 'form-control input-md onlyNumber required','id'=>'sub_station_room_size']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('number_of_substation','Number of sub stations',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('number_of_substation',!empty($appData->number_of_substation) ? $appData->number_of_substation : '',['class' => 'form-control input-md number_0_to_9 onlyNumber required','id'=>'number_of_substation']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('floor_sub_station','Which floors is the electric sub station ?',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            @if(isset($appData->floor_sub_station_check) && $appData->floor_sub_station_check == 1 )
                                                {!! Form::text('floor_sub_station', '',['class' => 'form-control input-md','id'=>'floor_sub_station required','readonly'=>'readonly']) !!}
                                            @else
                                                {!! Form::text('floor_sub_station',!empty($appData->floor_sub_station) ? $appData->floor_sub_station : '',['class' => 'form-control input-md required','id'=>'floor_sub_station']) !!}
                                            @endif
                                        </div>
                                        <div class="col-md-12 requriedBox">
                                            <div class="col-md-offset-6 col-md-1">
                                                <input type="checkbox" name="floor_sub_station_check" id="floor_sub_station_check" @if(isset($appData->floor_sub_station_check) && $appData->floor_sub_station_check == 1)  checked="checked"  @endif value="1" onclick="requriedField('floor_sub_station_check','floor_sub_station')">
                                            </div>
                                            {!! Form::label('floor_sub_station_check','Downstairs',['class'=>'text-left col-md-4 requriedBox']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('electric_sub_station_kVA','How many kVA is the electric sub station',['class'=>'text-left col-md-5 required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('electric_sub_station_kVA',!empty($appData->electric_sub_station_kVA) ? $appData->electric_sub_station_kVA : '',['class' => 'form-control input-md required','id'=>'electric_sub_station_kVA']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('adequate_electtrical','Whether there is adequate ventilation in the interior of the electrical sub-station room',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('adequate_electtrical','yes',(isset($appData->adequate_electtrical)&&($appData->adequate_electtrical == 'yes') ? true : false) , ['class'=>'required ', 'id' => 'adequate_electtrical_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('adequate_electtrical', 'no',(isset($appData->adequate_electtrical)&&($appData->adequate_electtrical == 'no') ? true : false) , ['class'=>'required', 'id' => 'adequate_electtrical_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('safety_bestney','Whether the safety bestney is protected by a 4 feet high steel net around the transformer',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('safety_bestney','yes',(isset($appData->safety_bestney)&&($appData->safety_bestney == 'yes') ? true : false) , ['class'=>'required ', 'id' => 'safety_bestney_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('safety_bestney', 'no',(isset($appData->safety_bestney)&&($appData->safety_bestney == 'no') ? true : false) , ['class'=>'required', 'id' => 'safety_bestney_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('rain_likely','Whether flood / rain water is likely to enter inside the electrical substation',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('rain_likely','yes',(isset($appData->rain_likely)&&($appData->rain_likely == 'yes') ? true : false) , ['class'=>'required ', 'id' => 'rain_likely_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('rain_likely', 'no',(isset($appData->rain_likely)&&($appData->rain_likely == 'no') ? true : false) , ['class'=>'required', 'id' => 'rain_likely_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('properly_fire_rated','Whether the doors and walls of the electrical substation room are properly fire rated',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('properly_fire_rated','yes',(isset($appData->properly_fire_rated)&&($appData->properly_fire_rated == 'yes') ? true : false) , ['class'=>'required ', 'id' => 'properly_fire_ratedy_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('properly_fire_rated', 'no',(isset($appData->properly_fire_rated)&&($appData->properly_fire_rated == 'no') ? true : false) , ['class'=>'required', 'id' => 'properly_fire_rated_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('rubber_mats','Whether rubber mats have been properly installed inside the electrical substation room',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('rubber_mats','yes',(isset($appData->rubber_mats)&&($appData->rubber_mats == 'yes') ? true : false) , ['class'=>'required ', 'id' => 'rubber_mats_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('rubber_mats', 'no',(isset($appData->rubber_mats)&&($appData->rubber_mats == 'no') ? true : false) , ['class'=>'required', 'id' => 'rubber_mats_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('gas_fire_system','In case of installation of electrical substation inside the building “Inert Gas Fire Suppression System” Whether there are installation arrangements',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('gas_fire_system','yes',(isset($appData->gas_fire_system)&&($appData->gas_fire_system == 'yes') ? true : false) , ['class'=>'required ', 'id' => 'gas_fire_system_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('gas_fire_system', 'no',(isset($appData->gas_fire_system)&&($appData->gas_fire_system == 'no') ? true : false) , ['class'=>'required', 'id' => 'gas_fire_system_no']) !!}
                                                No</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('safety_legend','Whether the design of the electrical substation is displayed on a separate page with blowup / enlarge and safety legend',['class'=>'text-left col-md-12 required-star']) !!}
                                        <div class="col-md-12">
                                            <label class="radio-inline">{!! Form::radio('safety_legend','yes',(isset($appData->safety_legend)&&($appData->safety_legend == 'yes') ? true : false) , ['class'=>'required ', 'id' => 'safety_legend_yes']) !!}
                                                Yes</label>
                                            <label class="radio-inline">{!! Form::radio('safety_legend', 'no',(isset($appData->safety_legend)&&($appData->safety_legend == 'no') ? true : false) , ['class'=>'required', 'id' => 'safety_legend_no']) !!}
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
                                                           class="required col-md-1 text-left" style="width:3%;" @if(isset($appData->acceptTerms) && $appData->acceptTerms == 1) checked="checked" @endif>
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
                                                {!! Form::text('sfp_contact_name', !empty($appData->sfp_contact_name) ? $appData->sfp_contact_name : '',
                                                ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('sfp_contact_email', !empty($appData->sfp_contact_email) ? $appData->sfp_contact_email : '', ['class' =>
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
                                                {!! Form::text('sfp_contact_phone', !empty($appData->sfp_contact_phone) ? $appData->sfp_contact_phone : '', ['class' =>
                                                'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_address', !empty($appData->sfp_contact_address) ? $appData->sfp_contact_address : '', ['class' =>
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
                                                {!! Form::text('sfp_pay_amount', !empty($appData->sfp_pay_amount) ? $appData->sfp_pay_amount : '', ['class' => 'form-control input-md', 'readonly']) !!}
                                                {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_vat_on_pay_amount') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_vat_on_pay_amount','VAT on pay amount',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_vat_on_pay_amount', number_format($appData->sfp_vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
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
                                                {!! Form::text('sfp_total_amount', number_format($appData->sfp_pay_amount + $appData->sfp_vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
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
                            @if($appInfo->status_id == -1)
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
                            @else
                                <div class="pull-left">
                                    <span>&nbsp;</span>
                                </div>
                                <div class="pull-left" style="padding-left: 1em;">
                                    <button type="submit" id="submitForm" style="cursor: pointer;"
                                            class="btn btn-success btn-md" value="Submit" name="actionBtn">Resubmit
                                    </button>
                                </div>
                            @endif
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

@include('FscdNocExisting::noc-existing-scripts-edit')