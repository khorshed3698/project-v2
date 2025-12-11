<?php
$accessMode = ACL::getAccsessRight('CdaOc');
if (!ACL::isAllowed($accessMode, '-E-')) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>

    .wizard > .content, .wizard, .tabcontrol {
        overflow: visible;
    }

    .form-group {
        margin-bottom: 5px;
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

    label.checkbox-inline {
        margin-top: 0;
        margin-left: 10px;
    }


    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

        .siteDivLR {
            margin-top: 12px;
            margin-right: 8px;
        }

        .siteDivFB {
            margin-top: 12px;
            margin-right: 8px;
        }

        .pull-right {
            float: none !important;
        }

        .pull-left {
            float: none !important;
        }

        .text-right {
            text-align: left !important;
        }
    }

    @media screen and (min-width: 350px) {

        .siteDivLR {
            margin-top: 12px;
        }

        .siteDivFB {
            margin-top: 12px;
        }

    }


    .signature-pad {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        font-size: 10px;
        width: 100%;
        height: 100%;
        max-width: 700px;
        max-height: 460px;
        border: 1px solid #e8e8e8;
        background-color: #fff;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.27), 0 0 40px rgba(0, 0, 0, 0.08) inset;
        border-radius: 4px;
        padding: 16px;
    }
    .signature-pad--body {
        position: relative;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        border: 1px solid #f4f4f4;
    }
    .signature-pad--body
    canvas {
        width: 100%!important;
        height: 100%!important;
        display: block;   /* this is IMPORTANT! */
        border-radius: 4px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.02) inset;
    }
    #signatureViewerDiv img{
        width: 100%;
    }
    
    .signature-pad--footer {
        color: #C3C3C3;
        text-align: center;
        font-size: 1.2em;
        margin-top: 8px;
    }
    .signature-pad--actions {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        margin-top: 8px;
    }
</style>

<div class="col-md-12">
    @include('message.message')
</div>
<div class="col-md-12">
    <div class="panel panel-primary" id="inputForm">
        <div class="panel-heading">
            <h5><strong>বসবাস বা ব্যবহার সনদপত্রের আবেদন ফরম (Occupancy Certificate) - সম্পূর্ন সমাপ্ত</strong></h5>
        </div>

        {!! Form::open(array('url' => 'cda-oc/store','method' => 'post', 'class' => 'form-horizontal', 'id' => 'CdaOcForm',
        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($app_info->id) }}" id="app_id"/>
        <input type="hidden" name="selected_file" id="selected_file"/>
        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
        <input type="hidden" name="isRequired" id="isRequired"/>

        <h3 class="text-center stepHeader">Details Information</h3>
        <fieldset>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            {!! Form::label('BuildingClassId','অকুপেন্সির ধরন',['class'=>'text-left col-md-5 required-star']) !!}
                            {!! Form::select('BuildingClassId', [], '', ['placeholder' => 'Select One','class' => 'form-control required', 'id'=>'BuildingClassId']) !!}
                        </div>

                        <div class="col-md-8" id="buildingSubClassList">

                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <label class="checkbox-inline required-star">
                                {!! Form::checkbox('IsApprovedRA',1, !empty($app_data->IsApprovedRA) ? ($app_data->IsApprovedRA == 1 ? true : false) : false, array('id'=>'is_residential_area', 'class'=>'required')) !!}
                                অনুমোদিত আবাসিক এলাকা কিনা?
                            </label>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6 {{$errors->has('ApplicationDate') ? 'has-error': ''}}">
                            {!! Form::label('ApplicationDate','আবেদনের তারিখ',['class'=>'col-md-5 required-star']) !!}
                            <div class=" col-md-7">
                                <div class="datepicker input-group date"
                                     data-date-format="dd-mm-yyyy">
                                    {!! Form::text('ApplicationDate',  !empty($app_data->ApplicationDate) ? $app_data->ApplicationDate:'', ['class'=>'form-control input-md required', 'id' => 'ApplicationDate', 'placeholder'=>'Pick from datepicker']) !!}
                                    <span class="input-group-addon"><span
                                                class="fa fa-calendar"></span></span>
                                </div>
                                {!! $errors->first('ApplicationDate','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('PermitNo','নির্মাণ অনুমোদন নাম্বার',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::text('PermitNo', $app_data->PermitNo,['class' => 'form-control onlyNumber engOnly input-md required','id'=>'PermitNo']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6 {{$errors->has('ConstructionCompletedDate') ? 'has-error': ''}}">
                            {!! Form::label('ConstructionCompletedDate','কাজ সমাপ্তের তারিখ',['class'=>'col-md-5 required-star']) !!}
                            <div class=" col-md-7">
                                <div class="datepicker input-group date"
                                     data-date-format="dd-mm-yyyy">
                                    {!! Form::text('ConstructionCompletedDate', !empty($app_data->ConstructionCompletedDate) ? $app_data->ConstructionCompletedDate:'', ['class'=>'form-control input-md required', 'id' => 'ConstructionCompletedDate', 'placeholder'=>'Pick from datepicker']) !!}
                                    <span class="input-group-addon"><span
                                                class="fa fa-calendar"></span></span>
                                </div>
                                {!! $errors->first('ConstructionCompletedDate','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('CityCorporationId','সিটি কর্পোরেশন/পৌরসভা/গ্রাম/মহল্লা',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('CityCorporationId', $city_corporation, $app_data->CityCorporationId, ['class' => 'form-control required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('BS','বি. এস. নং',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::text('BS', $app_data->BS,['class' => 'form-control required onlyNumber engOnly input-md','id'=>'BS']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('RS','আর. এস. নং',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::text('RS', $app_data->RS,['class' => 'form-control required onlyNumber engOnly input-md','id'=>'RS']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('ThanaId','থানার নাম',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('ThanaId', [], '', ['placeholder' => 'Select One','class' => 'form-control required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('MoujaId','মৌজার নাম',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('MoujaId', [], '', ['placeholder' => 'Select One','class' => 'form-control required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('BlockId','ব্লক নং',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('BlockId', [], '', ['placeholder' => 'Select One','class' => 'form-control required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('SeatId','সিট নং',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('SeatId', [], '', ['placeholder' => 'Select One','class' => 'form-control required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('WardId','ওয়ার্ড নং',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('WardId', [], '', ['placeholder' => 'Select One','class' => 'form-control required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('SectorId','সেক্টর নং',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::select('SectorId', [], '', ['placeholder' => 'Select One','class' => 'form-control required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('RoadName','রাস্তার নাম',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::text('RoadName', !empty($app_data->RoadName) ? $app_data->RoadName : '',['class' => 'form-control required input-md','id'=>'RoadName']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('PlotArea','বাহুর মাপ সহ জমি/প্লটের পরিমাণ',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::text('PlotArea', !empty($app_data->PlotArea) ? $app_data->PlotArea : '',['class' => 'form-control required onlyNumber engOnly input-md','id'=>'PlotArea']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('PlotDesc','জমি/প্লট এ বিদ্যমান বাড়ি/কাঠামোর বিবরণ',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                {!! Form::textarea('PlotDesc', $app_data->PlotDesc,['class' => 'form-control input-md required','id'=>'PlotDesc']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('signature','স্বাক্ষর যুক্ত করুন',['class'=>'text-left col-md-5 required-star']) !!}
                            <div class="col-md-7" id="signature_pad_div">
                                <div id="signature-pad" class="signature-pad">
                                    <div class="signature-pad--body">
                                        <canvas id="canvas"></canvas>
                                    </div>
                                    <div class="signature-pad--footer">
                                        <div class="description">Sign above</div>
                                        <div class="signature-pad--actions">
                                            <div>
                                                <button type="button" class="button clear btn btn-primary btn-xs" data-action="clear">Clear</button>
                                            </div>
                                            <div>
                                                <button type="button" class="button save btn btn-primary btn-xs" data-action="save">Save</button>
                                            </div>
                                            <div>
                                                <button type="button" class="button save btn btn-primary btn-xs" data-action="download">Download</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input required type="hidden" data-rule-signature="true" name="Signature" id="Signature" value="{{ $app_data->Signature }}">
                                </div>
                            </div>
                            <div class="col-md-7" id="existing_signature">
                                <div id="signatureViewerDiv" class="fixed-image">
                                    <img class="img-thumbnail img-signature" id="signatureViewer" src="{{ $app_data->Signature }}" alt=" Signature">
                                </div>
                            </div>

                            <div class="pull-right">
                                <button id="getSigPad" class="btn btn-info btn-sm" type="button">স্বাক্ষর পরিবর্তন করুন</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <h3 class="text-center stepHeader">Declaration</h3>
        <fieldset>
            <div class="panel panel-info">
                <div class="panel-heading" style="padding-bottom: 4px;">
                    <strong>আংশিক সমাপ্তের ব্যবহারের ধরণ</strong>
                </div>
                <div class="panel-body">
                    <div class="col-md-12 col-xs-12">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="text-center required-star">তলার ধরণ</th>
                                <th class="text-center required-star">ব্যবহারের ধরণ</th>
                                <th class="text-center required-star">ব্যবহার</th>
                                <th class="text-center required-star">আংশিক(বর্গমিটার)</th>
                                <th class="text-center required-star">পূর্ণ সমাপ্ত(বর্গমিটার)</th>
                                <th class="text-center">মোট ক্ষেত্রফল</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody id="coveredAreaDetails">
                            @if(count($app_data->FloorTotalArea) > 0)
                                <?php $inc = 0; ?>
                                @foreach($app_data->FloorTotalArea as $key => $value)
                                    <?php $lkey = ($key + 1); ?>
                                    <tr id="coveredAreaDetailsRow_{{$inc}}">
                                        <td width="20%"> {!! Form::select("FloorTypeId[$inc]",[], !empty($app_data->FloorTypeId[$key]) ? $app_data->FloorTypeId[$key]:'',['class'=>'form-control FloorTypeId_1 required', 'data-value'=>$app_data->FloorTypeId[$key], 'id'=>'FloorTypeId_'.$lkey]) !!}</td>
                                        <td width="20%">{!! Form::select("FloorUseId[$inc]",[], !empty($app_data->FloorUseId[$key]) ? $app_data->FloorUseId[$key]:'',['class' => 'form-control FloorUseId required', 'data-value'=>$app_data->FloorUseId[$key], 'id'=>'usage_'.$lkey ]) !!}</td>
                                        <td>{!! Form::text("FloorUse[$inc]",!empty($app_data->FloorUse[$key]) ? $app_data->FloorUse[$key]:'',['class' => 'form-control required FloorUse', 'id'=>'FloorUse'.$lkey]) !!}</td>
                                        <td>{!! Form::text("PartialCompletion[$inc]",!empty($app_data->PartialCompletion[$key]) ? $app_data->PartialCompletion[$key]:'',['class' => 'form-control required onlyNumber engOnly PartialCompletion', 'id'=>'PartialCompletion'.$lkey, 'onkeyup'=>"calculateTotalArea.call(this)" ]) !!}</td>
                                        <td>{!! Form::text("FullCompletion[$inc]",!empty($app_data->FullCompletion[$key]) ? $app_data->FullCompletion[$key]:'',['class' => 'form-control  required onlyNumber engOnly FullCompletion', 'id'=>'FullCompletion'.$lkey,'onkeyup'=>"calculateTotalArea.call(this)"]) !!}</td>
                                        <td>{!! Form::text("FloorTotalArea[$inc]",!empty($app_data->FloorTotalArea[$key]) ? $app_data->FloorTotalArea[$key]:'',['readonly'=>'readonly','class' => 'form-control FloorTotalArea enbnNumber required', 'id'=>'FloorTotalArea_'.$lkey]) !!}</td>

                                        <td style="vertical-align: middle; text-align: center">
                                            <?php if ($inc == 0) { ?>
                                            <a class="btn btn-sm btn-primary addTableRows"
                                               title="Add more"
                                               onclick="addTableRowCDA('coveredAreaDetails', 'coveredAreaDetailsRow_0');">
                                                <i class="fa fa-plus"></i></a>

                                            <?php } else { ?>
                                            <a href="javascript:void(0);"
                                               class="btn btn-sm btn-danger removeRow"
                                               onclick="removeTableRow('coveredAreaDetails', 'coveredAreaDetailsRow_{{$inc}}');">
                                                <i class="fa fa-times" aria-hidden="true"></i></a>
                                            <?php } ?>
                                        </td>
                                    </tr>

                                    <?php $inc++; ?>
                                @endforeach
                            @else
                                <tr id="coveredAreaDetailsRow_0">
                                    <td width="20%"> {!! Form::select('FloorTypeId[]',[],'',['class'=>'form-control required FloorTypeId_1','id'=>'FloorTypeId_1']) !!}</td>
                                    <td width="20%">{!! Form::select('FloorUseId[]',[],'',['class' => 'form-control required FloorUseId', 'id'=>'FloorUseId']) !!}</td>
                                    <td>{!! Form::text('PartialCompletion[]','',['class' => 'form-control onlyNumber required engOnly PartialCompletion', 'id'=>'PartialCompletion', 'onkeyup'=>"calculateTotalArea.call(this)"]) !!}</td>
                                    <td>{!! Form::text('FullCompletion[]','',['class' => 'form-control onlyNumber required engOnly FullCompletion', 'id'=>'FullCompletion','onkeyup'=>"calculateTotalArea.call(this)"]) !!}</td>
                                    <td>{!! Form::text('FloorTotalArea[]','',['readonly'=>'readonly','class' => 'form-control FloorTotalArea enbnNumber required', 'id'=>'FloorTotalArea_1']) !!}</td>
                                    <td style="vertical-align: middle; text-align: center">
                                        <a class="btn btn-sm btn-primary addTableRows"
                                           title="Add more LOAD DETAILS"
                                           onclick="addTableRowCDA('coveredAreaDetails', 'coveredAreaDetailsRow_0');">
                                            <i class="fa fa-plus"></i></a>
                                    </td>
                                </tr>
                            @endif
                            </tbody>

                            <tr>
                                <td colspan="4" class="text-right">
                                    ভবনের মেঝের মোট ক্ষেত্রফল
                                </td>
                                <td>
                                    {!! Form::text('building_FloorTotalArea_area', $app_data->building_FloorTotalArea_area,['readonly'=>'readonly', 'class' => 'form-control input-md','id'=>'building_FloorTotalArea_area']) !!}
                                    {!! $errors->first('building_FloorTotalArea_area','<span class="help-block">:message</span>') !!}
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </fieldset>

        {{--Attachments--}}
        <h3 class="text-center stepHeader">Attachments</h3>
        <fieldset>
            <div class="row">
                <div class="col-md-12">
                    <div id="showDocumentDiv"></div>
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
                            <div class="col-md-6 {{$errors->has('UserName') ? 'has-error': ''}}">
                                {!! Form::label('UserName','Contact name',['class'=>'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('UserName', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md', 'readonly'=>'readonly',]) !!}
                                    {!! $errors->first('UserName','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('Email') ? 'has-error': ''}}">
                                {!! Form::label('Email','Contact email',['class'=>'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    {!! Form::email('Email', Auth::user()->user_email, ['class' => 'form-control input-md email', 'readonly'=>'readonly',]) !!}
                                    {!! $errors->first('Email','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('MobileNo') ? 'has-error': ''}}">
                                {!! Form::label('MobileNo','Contact phone',['class'=>'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('MobileNo', Auth::user()->user_phone, ['class' => 'form-control input-md', 'readonly'=>'readonly',]) !!}
                                    {!! $errors->first('MobileNo','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('Address') ? 'has-error': ''}}">
                                {!! Form::label('Address','Contact address',['class'=>'col-md-5 text-left']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('Address', Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' => 'form-control input-md', 'readonly'=>'readonly',]) !!}
                                    {!! $errors->first('Address','<span class="help-block">:message</span>') !!}
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

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>

    $(document).ready(function () {

        var form = $("#CdaOcForm").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // Always allow previous action even if the current form is not valid!

                return true;
                if (newIndex == 1) {
                    // to validate the land use sub check boxes
                    if($('#signatureViewerDiv img').attr('src') == '' && $("#Signature").val() == '' ) {
                        swal({type: 'error', text: "আপনার স্বাক্ষরটি পুনরায় প্রদান করুন।"});
                        return false;
                    }

                }
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

        {{----end step js---}}
        $("#CdaOcForm").validate({
            rules: {
                field: {
                    required: true,
                    email: true,

                }
            }
        });

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            form.validate().settings.ignore = ":disabled,:hidden";
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=CdaOcForm@3'); ?>');
            } else {
                return false;
            }
        });

        $(document).on('blur', '.mobile', function () {
            var mobile = $(this).val()
            if (mobile) {
                if (mobile.length !== 11) {
                    $(this).addClass('error');
                    return false;
                } else {
                    $(this).removeClass('error');
                    return true;
                }
            }
        });

        $(document).on('blur', '.enbnNumber', function () {
            var enbn = $(this).val();
            var reg = /^([0-9]|[০-৯])/;
            if (enbn) {
                if (reg.test(enbn)) {
                    $(this).removeClass('error');
                    return true;
                } else {
                    $(this).addClass('error')
                    return false;
                }
            }
        });


        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100),
            ignoreReadonly: true
        });

        $(document).on('keydown', '.mobile', function () {
            var mobile = $(this).val();
            var reg = /^01/;
            if (mobile) {
                if (mobile.length === 2) {
                    if (reg.test(mobile)) {
                        $(this).removeClass('error');
                        return true;
                    } else {
                        $(this).addClass('error')
                        $(this).val('')
                        return false;
                    }
                }
            }


        });

        $(document).on('blur', '#applicant_nid_no', function () {
            var nid_val = $('#applicant_nid_no').val()
            var nid = nid_val.length
            if (nid_val) {
                if (nid == 10 || nid == 13 || nid == 17) {
                    $('#applicant_nid_no').removeClass('error')
                } else {
                    $('#applicant_nid_no').addClass('error')
                }
            }
        })

        $(document).on('blur', '#applicant_tin_no', function () {
            var tin_val = $('#applicant_tin_no').val()
            var tin = tin_val.length
            if (tin_val) {
                if (tin < 15) {
                    $('#applicant_tin_no').removeClass('error')
                } else {
                    $('#applicant_tin_no').addClass('error')
                }
            }
        })


        $("#CdaOcForm").find('.onlyNumber').on('keydown', function (e) {
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

    })

    function calculateTotalArea() {
        var parentTd = $(this).parent();
        var parentRow = parentTd.parent();

        var PartialCompletion = parentRow.find('.PartialCompletion').val();
        var FullCompletion = parentRow.find('.FullCompletion').val();

        if (PartialCompletion != null && PartialCompletion != 0 && FullCompletion != null && FullCompletion != 0){
            var totalFloor = parseFloat(PartialCompletion) + parseFloat(FullCompletion);
            parentRow.find('.FloorTotalArea').val(totalFloor.toFixed(2));
        }else if(FullCompletion != null && FullCompletion != 0) {
            parentRow.find('.FloorTotalArea').val(parseFloat(FullCompletion).toFixed(2));
        }else{
            parentRow.find('.FloorTotalArea').val(parseFloat(PartialCompletion).toFixed(2));
        }
        calculateBuildingTotalFloorArea('FloorTotalArea', 'building_FloorTotalArea_area')
    }

    function calculateBuildingTotalFloorArea(className, totalShowFieldId) {
        var total = 0.00;
        $("." + className).each(function () {
            total = total + (this.value ? parseFloat(this.value) : 0.00);
        })
        $("#" + totalShowFieldId).val(total.toFixed(2));
    }

    $(document).ready(function () {

        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/cda-oc/get-refresh-token';

            $('#BuildingClassId').keydown()
            $('#BlockId').keydown()
            $('#SeatId').keydown()
            $('#WardId').keydown()
            $('#SectorId').keydown()
            $('.FloorTypeId_1').keydown()
            $('.FloorUseId').keydown()
            $('#ThanaId').keydown()
            getDoc();

        });

        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: "{{ $client_id }}"
            },
            {
                key: "agent-id",
                value: "{{ $agent_id }}"
            },
        ]

        $('#BuildingClassId').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/buildingClasses";
            let selected_value = '{{!empty($app_data->BuildingClassId) ? $app_data->BuildingClassId:''}}'; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "BuildingClassId";//dynamic id for callback
            let element_name = "BuildingClassName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });

        $("#BuildingClassId").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            var BuildingClassId = $('#BuildingClassId').val()
            var BuildingClassId = BuildingClassId.split("@")[0]
            if (BuildingClassId) {
                let api_url = "{{$service_url}}/info/buildingSubClasses/" + BuildingClassId;
                let selected_value = '{{!empty($app_data->buildingSubClassList) ? json_encode($app_data->buildingSubClassList):''}}'; // for callback
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "buildingSubClassList";
                let element_id = "BuildingSubClassId";//dynamic id for callback
                let element_name = "BuildingSubClassName";//dynamic name for callback
                let element_calling_id = "BuildingClassId";//dynamic name for callback
                let element_details = "UseType";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, element_details, dependant_select_id]; // for callback
                apiCallGet(e, options, apiHeaders, checkboxCallbackResponse, arrays);
            }
        });

        $('#ThanaId').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/thana";
            let selected_value = '{{!empty($app_data->ThanaId) ? $app_data->ThanaId:''}}'; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "ThanaId";//dynamic id for callback
            let element_name = "ThanaName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $("#ThanaId").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#MoujaId").html('<option value="">Please Wait...</option>')
            var thana = $('#ThanaId').val()
            var thana_id = thana.split("@")[0]
            if (thana_id) {
                let e = $(this);
                let api_url = "{{$service_url}}/info/mouja/" + thana_id;
                let selected_value = '{{!empty($app_data->MoujaId) ? $app_data->MoujaId:''}}';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "MoujaId";
                let element_id = "MoujaId";//dynamic id for callback
                let element_name = "MoujaName";//dynamic name for callback
                let element_calling_id = "ThanaId";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback
                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);
            } else {
                $("#MoujaId").html('<option value="">Select Thana First</option>')
                $(e).next().hide()
            }

        })

        $('#BlockId').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/blocks";
            let selected_value = '{{!empty($app_data->BlockId) ? $app_data->BlockId:''}}'; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "BlockId";//dynamic id for callback
            let element_name = "BlockName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#SeatId').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/seats";
            let selected_value = '{{!empty($app_data->SeatId) ? $app_data->SeatId:''}}'; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "SeatId";//dynamic id for callback
            let element_name = "SeatNo";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#WardId').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/wards";
            let selected_value = '{{!empty($app_data->WardId) ? $app_data->WardId:''}}'; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "WardId";//dynamic id for callback
            let element_name = "WardName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#SectorId').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/sector";
            let selected_value = '{{!empty($app_data->SectorId) ? $app_data->SectorId:''}}'; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "SectorId";//dynamic id for callback
            let element_name = "SectorName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('.FloorTypeId_1').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/floorType";
            let selected_value = $(this).attr("data-value"); // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "FloorTypeId";//dynamic id for callback
            let element_name = "FloorTypeName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('.FloorUseId').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/floorUseType";
            let selected_value = $(this).attr("data-value"); // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "FloorUseId";//dynamic id for callback
            let element_name = "FloorUseType";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        function getDoc() {
            let _token = $('input[name="_token"]').val()
            let app_id = '{{isset($app_info->id) ? $app_info->id : ''}}'
            $.ajax({
                type: "POST",
                url: '/cda-oc/get-dynamic-doc',
                dataType: "json",
                data: {
                    _token: _token,
                    appId: app_id
                },
                success: function (result) {
                    $("#showDocumentDiv").html(result.data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#showDocumentDiv").html('');
                },
            })
        }

    })
    function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
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
        $("#" + dependant_select_id).html(option)
        $("#" + calling_id).next().hide()
    }

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
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
        $("#" + calling_id).html(option)
        $("#" + calling_id).trigger('change')
        $("#" + calling_id).next().hide()
    }

    function checkboxCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, element_details, dependant_select_id]) {
        var option = '';
        if (response.responseCode === 200) {
            var dataArr = [];
            if (selected_value != '') {
                $.each(JSON.parse(selected_value.replace(/&quot;/g, '"')), function (key, row) {
                    var data = row.split('@')[0];
                    dataArr.push(data)
                })
            }

            $.each(response.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_calling_id] + '@' + row[element_details] + '@' + row[element_name];
                let value = row[element_name] + ':' + row[element_details];
                if (dataArr.includes(id.split('@')[0])) {
                    option += '<label class="checkbox-inline">';
                    option += '<input type="checkbox" checked="checked" class="buildingSubClassList" name="buildingSubClassList[' + key + ']" value="' + id + '"> ' + value;
                    option += '</label>';
                } else {
                    option += '<label class="checkbox-inline">';
                    option += '<input type="checkbox" class="buildingSubClassList" name="buildingSubClassList[' + key + ']" value="' + id + '"> ' + value;
                    option += '</label>';
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + calling_id).next().hide()
    }

    // Add table Row script
    function addTableRowCDA(tableID, templateRow) {
        // alert(templateRow)
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
            var bid = idText.split("_").pop()
            //get input elements
            var attrInput = $("#" + tableID).find('#' + idText).find('input');
            for (var i = 0; i < attrInput.length; i++) {
                var nameAtt = attrInput[i].name;
                var inputId = attrInput[i].id;
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
                var ret = inputId.split('_')[0];
                var repTextId = ret + '_' + rowCo;
                attrInput[i].id = repTextId;
                attrInput[i].name = repText;
            }
            attrInput.val(''); //value reset
            //$('.m_currency ').prop('selectedIndex', 102);
            //Class change by btn-danger to btn-primary
            $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                .attr('onclick', 'removeTableRowCustom("' + tableID + '","' + idText + '")');
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
                        $(this).addClass('error')
                        return false;
                    }
                }
            })

        }
    }

    // Remove Table row script
    function removeTableRowCustom(tableID, removeNum) {
        //alert(removeNum)
        $('#' + tableID).find('#' + removeNum).remove();
        var index = 0
        var rowCo = 0
        $('#' + tableID + ' tr').each(function () {
                var trId = $(this).attr("id")
                var id = trId.split("_").pop()
                var trName = trId.split("_").shift()

                var attrInput = $("#" + tableID).find('#' + trId).find('input');
                for (var i = 0; i < attrInput.length; i++) {
                    var inputId = attrInput[i].id
                    var ret = inputId.split('_')[0]
                    var repTextId = ret + '_' + rowCo
                    attrInput[i].id = repTextId
                }
                var rowCount = $('#' + tableID).find('tr').length;

                var ret = trId.replace('_' + id, '');
                var repTextId = ret + '_' + rowCo;
                $(this).removeAttr("id")
                $(this).attr("id", repTextId)
                $(this).removeAttr("data-number")
                $(this).attr("data-number", rowCo)
                if (rowCo != 0) {
                    $(this).find('.addTableRows').removeAttr('onclick');
                    $(this).find('.addTableRows').attr('onclick', 'removeTableRowCustom("' + tableID + '","' + trName + '_' + rowCo + '")');
                }
                index++;
                rowCo++;
            }
        )
    }

    //Doc and image upload section
    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            $("#validate_field_" + abc).val = '';
            $('#' + abc).val = ''
            var isReq = $('#' + abc).attr('data-required')
            if (isReq == 'required') {
                $('#' + abc).addClass('required error')
            }
            document.getElementById(abc).value = ''
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
            $('#span_' + abc).show()
            let img = $('#old_image_' + abc).val()
            let old_img = $('#old_image_' + abc).attr('data-img')
            $('#validate_field_' + abc).val(img)
            if (!old_img) {
                $('#photo_viewer_' + abc).attr('src', '{{(url('assets/images/no-image.png'))}}')
            } else {
                $('#photo_viewer_' + abc).attr('src', old_img)
            }


        } else {
            return false;
        }
    });

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

    function uploadDocument(targets, id, vField, isRequired) {
        let file_id = $("#"+id);
        let file_label = $("#label_"+id);
        let file_element = document.getElementById(id);
        let file = file_element.files;
        let file_types = ['application/pdf','image/jpeg'];
        if (file && file[0]) {
            if(jQuery.inArray(file[0].type, file_types) === -1) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'The file format is not valid! Please upload in pdf format.'
                });
                file_element.value = '';
                return false;
            }

            let file_size = parseFloat((file[0].size) / (1024 * 1024)).toFixed(1); //MB Calculation
            if (!(file_size <= 2)) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Max file size 2MB. You have uploaded ' + file_size + 'MB'
                });
                file_element.value = '';
                return false;
            }
        }

        let input_file = file_id.val();
        if (input_file === '') {
            file_id.html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if (file_label.length) {
                file_label.remove();
            }
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";

            let action = "{{ url('/cda-oc/upload-document') }}";
            $("#" + targets).html('Uploading....');

            let file_data = file_id.prop('files')[0];
            let form_data = new FormData();
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
                    let fileNameArr = input_file.split("\\");
                    let l = fileNameArr.length;
                    if (file_label.length) {
                        file_label.remove();
                    }
                    let newInput = $('<label class="saved_file_' + id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" class="filedelete" docid="' + id + '" ><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                    file_id.after(newInput);
                    if (response.includes("Error") === false) {
                        $('#' + id).removeClass('required');
                        $('#span_' + id).hide();
                    }

                    document.getElementById(id).value = '';
                    let validate_field = $('#' + vField).val();
                    if (validate_field === '') {
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }
</script>

<script src="{{ asset("vendor/signature_pad/js/signature_pad.umd.js") }}"></script>
<script>
    $(document).ready(function () {
        
        const wrapper = document.getElementById("signature-pad");
        const clearButton = wrapper.querySelector("[data-action=clear]");
        const downloadButton = wrapper.querySelector("[data-action=download]");
        const saveButton = wrapper.querySelector("[data-action=save]");
        const canvas = wrapper.querySelector("canvas");
        const signaturePad = new SignaturePad(canvas, {
            // It's Necessary to use an opaque color when saving image as JPEG;
            // this option can be omitted if only saving as PNG or SVG
            backgroundColor: 'rgb(255, 255, 255)'
        });

        // Adjust canvas coordinate space taking into account pixel ratio,
        // to make it look crisp on mobile devices.
        // This also causes canvas to be cleared.
        function resizeCanvas() {
            // When zoomed out to less than 100%, for some very strange reason,
            // some browsers report devicePixelRatio as less than 1
            // and only part of the canvas is cleared then.
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);

            // This part causes the canvas to be cleared
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);

            // This library does not listen for canvas changes, so after the canvas is automatically
            // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
            // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
            // that the state of this library is consistent with visual state of the canvas, you
            // have to clear it manually.
            //signaturePad.clear();

            // If you want to keep the drawing on resize instead of clearing it you can reset the data.
            signaturePad.fromData(signaturePad.toData());
        }

        // On mobile devices it might make more sense to listen to orientation change,
        // rather than window resize events.
        window.onresize = resizeCanvas;
        resizeCanvas();

        function download(dataURL, filename) {
            const blob = dataURLToBlob(dataURL);
            const url = window.URL.createObjectURL(blob);

            const a = document.createElement("a");
            a.style = "display: none";
            a.href = url;
            a.download = filename;

            document.body.appendChild(a);
            a.click();

            window.URL.revokeObjectURL(url);
        }

        // One could simply use Canvas#toBlob method instead, but it's just to show
        // that it can be done using result of SignaturePad#toDataURL.
        function dataURLToBlob(dataURL) {
            // Code taken from https://github.com/ebidel/filer.js
            const parts = dataURL.split(';base64,');
            const contentType = parts[0].split(":")[1];
            const raw = window.atob(parts[1]);
            const rawLength = raw.length;
            const uInt8Array = new Uint8Array(rawLength);

            for (let i = 0; i < rawLength; ++i) {
                uInt8Array[i] = raw.charCodeAt(i);
            }

            return new Blob([uInt8Array], { type: contentType });
        }

        clearButton.addEventListener("click", () => {
            signaturePad.clear();
        });

        downloadButton.addEventListener("click", () => {
            if (signaturePad.isEmpty()) {
                alert("Please provide a signature first.");
            } else {
                const dataURL = signaturePad.toDataURL("image/jpeg");
                $("#Signature").val(dataURL);
                download(dataURL, "signature.jpg");
            }
        });
        saveButton.addEventListener("click", () => {
            if (signaturePad.isEmpty()) {
                alert("Please provide a signature first.");
                return false;
            } else {
                const dataURL = signaturePad.toDataURL("image/jpeg");
                $("#Signature").val(dataURL);
            }
        });

        var signature = $('#signatureViewerDiv img').attr('src');
        if(signature){
            $('#existing_signature').show();
            $('#signature_pad_div').hide();

        }else{
            $('#existing_signature').hide();
            $('#signature_pad_div').show();
        }
        
        $('#getSigPad').click(function () {
            $("#signature_pad_div").show();
            $("#existing_signature").hide();
            $('#signatureViewerDiv img').attr('src', '');
            $("#getSigPad").hide();
        });
    });
</script>