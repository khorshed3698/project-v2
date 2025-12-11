<?php
$accessMode = ACL::getAccsessRight('DCCI_COS');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ url('assets/css/jquery-ui.css') }}">
<style>
    .wizard > .content,
    .wizard,
    .tabcontrol {
        overflow: visible;
    }

    .wizard > .steps > ul > li {
        width: 24% !important;
    }
    .wizard > .steps > ul > li:nth-child(1) {
        width: 28% !important;
    }

    .wizard > .steps .number {
        font-size: 1.2em;
    }

    .intl-tel-input .country-list {
        z-index: 5;
    }

    .form-group {
        margin-bottom: 5px;
    }

    .error ~ .select2-container--default .select2-selection--single {
        border: 1px solid red;
        border-radius: 4px;
    }

    .valid ~ .select2-container--default .select2-selection--single {
        border: 1px solid #aaa;
        border-radius: 4px;
    }

    .error ~ .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #f00;
    }

    .valid ~ .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
    }


    label {
        float: left !important;
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
                        <h5><strong>Application For Country of Origin Service (DCCI)</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'dcci-cos/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewApplication',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    @if($appInfo->status_id == 5)
                        <ol class="breadcrumb text-danger">
                            <li><strong>DCCI Tracking no. : </strong>{{$appInfo->dcci_cos_tracking_no}}</li>
                            <li><strong>Shortfall Message
                                    :</strong> {{ $shortfallmsg }}
                            </li>
                        </ol>
                    @endif
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>
                    <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"
                           id="app_id"/>
                    @if($appInfo->status_id == 5)
                        <input type="hidden" name="tracking_id" value="{{$appInfo->dcci_cos_tracking_no}}"
                               id="tracking_id"/>
                    @endif

                    <h3 class="text-center stepHeader">Country Of Origin Information</h3>
                    <fieldset>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Consignor Info</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            {!! Form::label('cosignor_company_name','Company / Firm Name:',['class'=>'col-md-3']) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('cosignor_company_name', !empty($appData->cosignor_company_name) ? $appData->cosignor_company_name : '',['class' => 'form-control input-md','readonly','placeholder'=>'Company Name']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>Company Address</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4 ">
                                                    {!! Form::label('country_company','Country :',['class'=>'col-md-5 col-xs-12 mt-sm-2']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::select('country_company',[],null,['class' =>'form-control input-md','id'=>'country_company','placeholder'=>'Select from here']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {!! Form::label('division_company','Division :',['class'=>'col-md-5 col-xs-12 sm-mt']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::select('division_company',[],null,['class' =>'form-control input-md','id'=>'division_company','placeholder'=>'Select country first']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {!! Form::label('district_company','District :',['class'=>'col-md-5 col-xs-12 sm-mt']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::select('district_company',[],null,['class' =>'form-control input-md ','id'=>'district_company','placeholder'=>'Select division first']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    {!! Form::label('thana_company','Thana/Upazila :',['class'=>'col-md-5 col-xs-12 sm-mt']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::select('thana_company',[],null,['class' =>'form-control input-md','id'=>'thana_company','placeholder'=>'Select district first']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {!! Form::label('post_office_company','Post Office :',['class'=>'col-md-5 col-xs-12 sm-mt']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::select('post_office_company',[],null,['class' =>'form-control input-md','id'=>'post_office_company','placeholder'=>'Select thana first']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {!! Form::label('road_company','Road/Area/House :',['class'=>'col-md-5 col-xs-12 sm-mt']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('road_company',!empty($appData->road_company) ? $appData->road_company : '',['class' =>'form-control input-md','id'=>'road_company']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class=" col-md-12" style="font-size: 17px; margin-bottom: 15px;">
                                        {!! Form::checkbox('same_as_mailing',1,!empty($appData->same_as_mailing) ? $appData->same_as_mailing : '',
                                        array('id'=>'same_as_mailing','class'=>'col-md-1'))
                                        !!}  {!! Form::label('same_as_mailing',' Same as Mailing Address',['class'=>'col-md-5 col-xs-12 mt-sm','style'=>'margin-left:-25px;']) !!}
                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>Factory Address</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    {!! Form::label('country_factory','Country :',['class'=>'col-md-5 col-xs-12 mt-sm']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::select('country_factory',[],null,['class' =>'form-control input-md','id'=>'country_factory','placeholder'=>'Select from here']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {!! Form::label('division_factory','Division :',['class'=>'col-md-5 col-xs-12 mt-sm']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::select('division_factory',[],null,['class' =>'form-control input-md','id'=>'division_factory','placeholder'=>'Select country first']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {!! Form::label('district_factory','District :',['class'=>'col-md-5 col-xs-12 mt-sm']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::select('district_factory',[],null,['class' =>'form-control input-md','id'=>'district_factory','placeholder'=>'Select division first']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    {!! Form::label('thana_factory','Thana/Upazila :',['class'=>'col-md-5 col-xs-12 mt-sm']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::select('thana_factory',[],null,['class' =>'form-control input-md','id'=>'thana_factory','placeholder'=>'Select district first']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {!! Form::label('post_office_factory','Post Office :',['class'=>'col-md-5 col-xs-12 mt-sm']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::select('post_office_factory',[],null,['class' =>'form-control input-md','id'=>'post_office_factory','placeholder'=>'Select thana first']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    {!! Form::label('road_factory','Road/Area/House :',['class'=>'col-md-5 col-xs-12 mt-sm']) !!}
                                                    <div class="col-md-7 col-xs-12">
                                                        {!! Form::text('road_factory',null,['class' =>'form-control input-md','id'=>'road_factory']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row col-md-12">

                                        <div class="col-md-6">
                                            {!! Form::label('signature','Signature Image :', ['class'=>'col-md-4 col-xs-12 required-star']) !!}
                                            <div class="col-md-8 col-xs-12">
                                                {!! Form::file('signature', ['class'=>!empty($appData->validate_field_signature) ? 'form-control input-md ' : 'form-control input-md required', 'id' => 'signature','flag'=>'img','data-height'=>'50','data-width'=>'50','onchange'=>"heightWidthImg(this)"]) !!}
                                                <input type="hidden" id="old_image_signature"
                                                       data-img="{{!empty($appData->validate_field_signature) ? URL::to('/uploads/'.$appData->validate_field_signature) : (url('assets/images/no-image.png'))}}"
                                                       value="{{!empty($appData->validate_field_signature) ? $appData->validate_field_signature : ''}}">
                                                <div id="preview_signature">
                                                    {!! Form::hidden('validate_field_signature',!empty($appData->validate_field_signature) ? $appData->validate_field_signature : '', ['class'=>'form-control input-md', 'id' => 'validate_field_signature','data-img'=>!empty($appData->validate_field_signature) ? URL::to('/uploads/'.$appData->validate_field_signature) : (url('assets/images/no-image.png'))]) !!}
                                                </div>

                                                <div class="col-md-4" style="position:relative;margin-top: 5px;">
                                                    <img id="photo_viewer_signature"
                                                         style="width:auto;height:80px;border:1px solid #ddd;padding:2px;"
                                                         src="{{!empty($appData->validate_field_signature) ? URL::to('/uploads/'.$appData->validate_field_signature) : (url('assets/images/no-image.png'))}}"
                                                         alt="signature">
                                                </div>
                                                @if(empty($appData->validate_field_signature))
                                                    <span id="span_signature"
                                                          style="font-size: 12px; font-weight: bold;color:#993333">Signature Image dimension should be in width=50px, height=50px and JPEG/PNG. This signature will be used in co certificate.</span>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('seal','Seal Image :', ['class'=>'col-md-4 col-xs-12 required-star']) !!}
                                            <div class="col-md-8 col-xs-12 ">
                                                {!! Form::file('seal', ['class'=>!empty($appData->validate_field_seal) ? 'form-control input-md' : 'form-control input-md required','flag'=>'img','data-height'=>'50','data-width'=>'100','id' => 'seal','onchange'=>"heightWidthImg(this)"]) !!}
                                                <input type="hidden" id="old_image_seal"
                                                       data-img="{{!empty($appData->validate_field_seal) ? URL::to('/uploads/'.$appData->validate_field_seal) : (url('assets/images/no-image.png'))}}"
                                                       value="{{!empty($appData->validate_field_seal) ? $appData->validate_field_seal : ''}}">
                                                <div id="preview_seal">
                                                    {!! Form::hidden('validate_field_seal',!empty($appData->validate_field_seal) ? $appData->validate_field_seal : '', ['class'=>'form-control input-md', 'id' => 'validate_field_seal','data-img'=>!empty($appData->validate_field_seal) ? URL::to('/uploads/'.$appData->validate_field_seal) : (url('assets/images/no-image.png'))]) !!}
                                                </div>
                                                <div class="col-md-4" style="position:relative;margin-top: 5px;">
                                                    <img id="photo_viewer_seal"
                                                         style="width:auto;height:80px;border:1px solid #ddd;padding:2px;"
                                                         src="{{!empty($appData->validate_field_seal) ? URL::to('/uploads/'.$appData->validate_field_seal) : (url('assets/images/no-image.png'))}}"
                                                         alt="seal">
                                                </div>
                                                @if(empty($appData->validate_field_seal))
                                                    <span id="span_seal"
                                                          style="font-size: 12px; font-weight: bold;color:#993333">Seal Image dimension should be in width=100px, height=50px and JPEG/PNG. This seal will be used in co certificate.</span>
                                                @endif

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Consignee Info</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row col-md-12">
                                        {!! Form::label('consignee_company_name','Company / Firm Name:',['class'=>'col-md-3']) !!}
                                        <div class="col-md-9">
                                            {!! Form::text('consignee_company_name',!empty($appData->consignee_company_name) ? $appData->consignee_company_name : '',['class' => 'form-control input-md','id'=>'consignee_company_name','placeholder'=>'Company or Firm name']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row col-md-12">
                                        {!! Form::label('company_address','Company Address:',['class'=>'col-md-3']) !!}
                                        <div class="col-md-9">
                                            {!! Form::textarea('company_address', !empty($appData->company_address) ? $appData->company_address : '',['class' => 'form-control input-md','id'=>'company_address','placeholder'=>'Company address','size' =>'5x2']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row col-md-12">
                                        {!! Form::label('destination_address','Destination Address :',['class'=>'col-md-3']) !!}
                                        <div class="col-md-9">
                                            {!! Form::textarea('destination_address', !empty($appData->destination_address) ? $appData->destination_address : '',['class' => 'form-control input-md','id'=>'destination_address','placeholder'=>'Destination Address','size' =>'5x2']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row col-md-12">
                                        {!! Form::label('particuler_transport','Particular of Transport(Optional Declaration):',['class'=>'col-md-3']) !!}
                                        <div class="col-md-9">
                                            {!! Form::textarea('particuler_transport', !empty($appData->particuler_transport) ? $appData->particuler_transport : '',['class' => 'form-control input-md','id'=>'particuler_transport','placeholder'=>'Particular of Transport','size' =>'5x4']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row col-md-12">
                                        {!! Form::label('currency','Currency:',['class'=>'col-md-3']) !!}
                                        <div class="col-md-4">
                                            {!! Form::select('currency',[], '',['class' => 'form-control input-md','id'=>'currency','placeholder'=>'select Currency']) !!}
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Description of Goods</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-hover ">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Marks</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Description</th>
                                                <th class="text-center">H.S Code</th>
                                                <th class="text-center">Weight (E.g.-1kg)</th>
                                                <th class="text-center">Value</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="goodDescription">
                                            @if(count($appData->marks) > 0)
                                                @foreach($appData->marks as $key => $value)
                                                    <tr id="rowCountGoodDescription_{{$key}}" data-number="{{$key}}">
                                                        <td class="col-md-2 col-xs-3">{!! Form::text('marks['.$key.']',!empty($appData->marks[$key]) ? $appData->marks[$key] :'',['class' =>'form-control input-md required','id'=>'fiscalYear_'.$key]) !!}</td>
                                                        <td class="col-md-2 col-xs-3">{!! Form::text('quantity['.$key.']',!empty($appData->quantity[$key]) ? $appData->quantity[$key] :'',['class' =>'form-control input-md required','id'=>'quantity_'.$key]) !!}</td>
                                                        <td class="col-md-2 col-xs-3">{!! Form::textarea('description['.$key.']',!empty($appData->description[$key]) ? $appData->description[$key] :'',['class' =>'form-control input-md required','size'=>'5x1','id'=>'description_'.$key]) !!}</td>
                                                        <td class="col-md-2 col-xs-3">
                                                            {!! Form::text('hscode['.$key.']',!empty($appData->hscode[$key]) ? $appData->hscode[$key] :'',['class' =>'form-control input-md hscodes required','id'=>'hscode_'.$key,'placeholder'=>'Type hscode name']) !!}
                                                            {!! Form::hidden('hscodeId['.$key.']',!empty($appData->hscodeId[$key]) ? $appData->hscodeId[$key] :'',['id'=>'hscodeId_'.$key,'class' =>'hscodeId']) !!}
                                                            <span class="empty-message"
                                                                  style="display: none;color:red;font-size:12px;">No result found</span>
                                                            <span class="min-msg"
                                                                  style="display: none;color:red;font-size:12px;">Type minimum 3 letter</span>
                                                            <span class="loader"
                                                                  style="display: none;font-size:12px;">Please wait...</span>
                                                        </td>
                                                        <td class="col-md-2 col-xs-3">{!! Form::text('weight['.$key.']',!empty($appData->weight[$key]) ? $appData->weight[$key] :'',['class' =>'form-control input-md required','id'=>'weight_'.$key]) !!}</td>
                                                        <td class="col-md-2 col-xs-3">{!! Form::text('value['.$key.']',!empty($appData->value[$key]) ? $appData->value[$key] :'',['class' =>'form-control input-md onlyNumber goodsValue required','id'=>'value_'.$key]) !!}</td>
                                                        <td style="vertical-align: middle; text-align: center">
                                                            <?php if ($key == 0) { ?>
                                                            <a class="btn btn-sm btn-primary addTableRows"
                                                               title="Add more business type"
                                                               onclick="addTableRowGD('goodDescription', 'rowCountGoodDescription_0');">
                                                                <i class="fa fa-plus"></i></a>
                                                            <?php } else { ?>
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-sm btn-danger removeRow"
                                                               onclick="removeTableRow('goodDescription','rowCountGoodDescription_{{$key}}');">
                                                                <i class="fa fa-times" aria-hidden="true"></i></a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr id="rowCountGoodDescription_0" data-number="0">
                                                    <td class="col-md-2 col-xs-3">{!! Form::text('marks[]',null,['class' =>'form-control input-md required','id'=>'fiscalYear_0']) !!}</td>
                                                    <td class="col-md-2 col-xs-3">{!! Form::text('quantity[]',null,['class' =>'form-control input-md required','id'=>'quantity_0']) !!}</td>
                                                    <td class="col-md-2 col-xs-3">{!! Form::textarea('description[]',null,['class' =>'form-control input-md required','size'=>'5x1','id'=>'description_0']) !!}</td>
                                                    <td class="col-md-2 col-xs-3">
                                                        {!! Form::text('hscode[]',null,['class' =>'form-control input-md hscodes required','id'=>'hscode_0','placeholder'=>'Type hscode name']) !!}
                                                        {!! Form::hidden('hscodeId[]',null,['class' =>'hscodeId','id'=>'hscodeId_0']) !!}
                                                        <span class="empty-message"
                                                              style="display: none;color:red;font-size:12px;">No result found</span>
                                                        <span class="min-msg"
                                                              style="display: none;color:red;font-size:12px;">Type minimum 3 letter</span>
                                                        <span class="loader"
                                                              style="display: none;font-size:12px;">Please wait...</span>
                                                    </td>
                                                    <td class="col-md-2 col-xs-3">{!! Form::text('weight[]',null,['class' =>'form-control input-md required','id'=>'weight_0']) !!}</td>
                                                    <td class="col-md-2 col-xs-3">{!! Form::text('value[]',null,['class' =>'form-control input-md onlyNumber goodsValue  required','id'=>'value_0']) !!}</td>
                                                    <td style="vertical-align: middle; text-align: center">
                                                        <a class="btn btn-sm btn-primary addTableRows"
                                                           title="Add more business type"
                                                           onclick="addTableRowGD('goodDescription', 'rowCountGoodDescription_0');">
                                                            <i class="fa fa-plus"></i></a>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Document Information</h3>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="docListDiv">
                                    @include('DCCICos::documents')
                                </div>

                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Print Quantity</h3>
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading" style="padding-bottom: 4px;">
                                <strong>Certificate Copy</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th class="text-center" width="30%">Type</th>
                                            <th class="text-center" width="25%">Quantity</th>
                                            <th class="text-center" width="20%">Fees</th>
                                            <th class="text-center" width="20%">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr>
                                            <td>Certificate of Origin (Original)</td>
                                            <td>
                                                {!! Form::number('print_count_original',!empty($appData->print_count_original) ? $appData->print_count_original : '1',['class' => 'form-control input-md onlyNumber','placeholder'=>'Number','id'=>'print_count_original']) !!}
                                            </td>
                                            <td>
                                                <input id='per_original_price' name="per_original_price"
                                                       class="form-control" value="" readonly>
                                            </td>
                                            <td>
                                                {!! Form::text('original_total',!empty($appData->original_total) ? $appData->original_total : '',['class' => 'form-control input-md onlyNumber','readonly','id'=>'original_total']) !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Certificate of Origin (Copy)</td>
                                            <td>
                                                {!! Form::number('print_count_copy',!empty($appData->print_count_copy) ? $appData->print_count_copy : '0',['class' => 'form-control input-md onlyNumber','placeholder'=>'Number','id'=>'print_count_copy']) !!}
                                            </td>
                                            <td>
                                                <input id='per_copy_price' name="per_copy_price" class="form-control"
                                                       value="" readonly>
                                            </td>
                                            <td>
                                                {!! Form::text('copy_total',!empty($appData->copy_total) ? $appData->copy_total : '',['class' => 'form-control input-md onlyNumber','readonly','id'=>'copy_total']) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <label class="pull-right">Total Amount</label>
                                            </td>
                                            <td>
                                                {!! Form::text('total_amount',!empty($appData->total_amount) ? $appData->total_amount : '',['class' => 'form-control input-md onlyNumber','readonly','id'=>'total_amount']) !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Declaration & Submit</h3>
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading" style="padding-bottom: 4px;">
                                <strong>DECLARATION</strong>
                            </div>
                            <div class="panel-body">
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
                                    @if($appInfo->status_id == 5)
                                        Resubmit
                                    @else
                                        Submit
                                    @endif
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

<script src="{{ asset("assets/scripts/jquery-ui-1.11.4.js") }}"></script>
<script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        var form = $("#NewApplication").show();
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

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            form.validate().settings.ignore = ":disabled,:hidden";
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=NewApplication@3'); ?>');
            } else {
                return false;
            }
        });

        {{----end step js---}}
        $("#NewApplication").validate({
            rules: {
                field: {
                    required: true,
                    email: true,

                }
            }
        });


        $('#print_count_original').on('input', function () {
            var val = $('#print_count_original').val();
            var per_price = $('#per_original_price').val();
            if (val != '') {
                if (val < 1 || val > 10) {
                    alert('minimum copy 1 and maximum copy 10')
                    $('#print_count_original').val('');
                } else {
                    var count = parseInt(val * per_price);
                    $('#original_total').val(count);
                    sum();
                }
            }
        })

        $('#print_count_copy').on('input', function () {
            var val = $('#print_count_copy').val();
            var per_price = $('#per_copy_price').val();
            if (val != '') {
                if (val < 0 || val > 10) {
                    alert('minimum copy 0 and maximum copy 10')
                    $('#print_count_copy').val('');
                } else {
                    var count = parseInt(val * per_price);
                    $('#copy_total').val(count);
                    sum();
                }
            }
        })

        $(document).on('input','.goodsValue', function () {
            var val = $(this).val();
            var floatNum = val.split('.')[1];
            var num = val.split('.')[0];

            if(num.length > 9){
                $(this).addClass('error')
            }else{
                $(this).removeClass('error')
            }
            if(floatNum){
                if(floatNum.length > 2){
                    $(this).addClass('error')
                }else{
                    $(this).removeClass('error')
                }
            }

        })

        function sum() {
            var original = $('#original_total').val();
            var copy = $('#copy_total').val();
            var total = parseInt(original) + parseInt(copy);
            $('#total_amount').val(parseInt(total))
        }


        $('#nid_number').on('blur', function (e) {
            var nid = $('#nid_number').val().length
            if (nid == 10 || nid == 13 || nid == 17) {
                $('#nid_number').removeClass('error')
            } else {
                $('#nid_number').addClass('error')
                $('#nid_number').val('')
            }
        })

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

        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        var user_mail = "{{$user_info}}"
        $(function () {
            token = "{{$token}}";
            tokenUrl = '/dcci-cos/get-refresh-token';

            $('#country_company').select2({
                minimumResultsForSearch: -1
            });
            $('#country_factory').select2({
                minimumResultsForSearch: -1
            });
            $('#currency').select2();

            $('#country_company').keydown();
            $('#country_factory').keydown();
            $('#currency').keydown();
        })

        //Comapny address api

        $('#country_company').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$bida_service_url}}/info/country";
            let selected_value = '{{ !empty($appData->country_company) ? $appData->country_company : ''}}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "id"; //dynamic id for callback
            let element_name = "name"; //dynamic name for callback
            let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            let apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json',
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA',
                },
                {
                    key: "user-email",
                    value: user_mail,
                }
            ];
            apiCallGet(e, options, apiHeaders, countryCallbackResponse, arrays);

        })

        $("#country_company").on("change", function () {
            let self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#division_company").html('<option value="">Please Wait...</option>');
            let country = $('#country_company').val();
            let countryId = country.split("@")[0];
            let selected_value = '{{ !empty($appData->division_company) ? $appData->division_company : ''}}';
            ;
            if (countryId) {
                let e = $(this);
                let api_url = "{{$bida_service_url}}/info/division/" + countryId;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "division_company"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let selected_element_id = "countryId";
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, selected_element_id];

                let apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                    {
                        key: "user-email",
                        value: user_mail,
                    }
                ];
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#division_company").html('<option value="">Select Country First</option>');
                $(self).next().hide();
            }

        })

        $("#division_company").on("change", function () {
            let self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#district_company").html('<option value="">Please Wait...</option>');
            let division = $('#division_company').val();
            let divisionId = division.split("@")[0];
            let selected_value = '{{ !empty($appData->district_company) ? $appData->district_company : ''}}';
            ;
            if (divisionId) {
                let e = $(this);
                let api_url = "{{$bida_service_url}}/info/district/" + divisionId;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "district_company"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let selected_element_id = "divisionId";
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, selected_element_id];

                let apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                    {
                        key: "user-email",
                        value: user_mail,
                    }
                ];
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#district_company").html('<option value="">Select division First</option>');
                $(self).next().hide();
            }

        })

        $("#district_company").on("change", function () {
            let self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#thana_company").html('<option value="">Please Wait...</option>');
            let district = $('#district_company').val();
            let districtId = district.split("@")[0];
            let selected_value = '{{ !empty($appData->thana_company) ? $appData->thana_company : ''}}';
            ;
            if (districtId) {
                let e = $(this);
                let api_url = "{{$bida_service_url}}/info/upazila/" + districtId;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "thana_company"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let selected_element_id = "districtId";
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, selected_element_id];

                let apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                    {
                        key: "user-email",
                        value: user_mail,
                    }
                ];
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#thana_company").html('<option value="">Select district First</option>');
                $(self).next().hide();
            }

        })

        $("#thana_company").on("change", function () {
            let self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#post_office_company").html('<option value="">Please Wait...</option>');
            let upazila = $('#thana_company').val();
            let upazilaId = upazila.split("@")[0];
            let selected_value = '{{ !empty($appData->post_office_company) ? $appData->post_office_company : ''}}';
            if (upazilaId) {
                let e = $(this);
                let api_url = "{{$bida_service_url}}/info/post-office/" + upazilaId;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "post_office_company"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let selected_element_id = "upazilaId";
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, selected_element_id];

                let apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                    {
                        key: "user-email",
                        value: user_mail,
                    }
                ];
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#post_office_company").html('<option value="">Select thana First</option>');
                $(self).next().hide();
            }

        })

        //Factory address api
        var document_onload_status = 1;

        $('#country_factory').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var checkBox = $('#same_as_mailing').is(':checked');
            let selected_value = ""; // for callback
            if (checkBox == true) {
                if (document_onload_status == 1) {
                    selected_value = '{{isset($appData->country_factory) ? $appData->country_factory : ''}}';
                } else {
                    selected_value = $("#country_factory").val(); // for callback
                }
            } else {
                selected_value = '{{isset($appData->country_factory) ? $appData->country_factory : ''}}'; // for callback
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$bida_service_url}}/info/country";
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "id"; //dynamic id for callback
            let element_name = "name"; //dynamic name for callback
            let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            let apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
                {
                    key: "user-email",
                    value: user_mail,
                }
            ];

            apiCallGet(e, options, apiHeaders, countryCallbackResponse, arrays);

        })

        $("#country_factory").on("change", function () {
            let self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#division_factory").html('<option value="">Please Wait...</option>');
            let country = $('#country_factory').val();
            let countryId = '';
            if (country !== null) {
                countryId = country.split("@")[0];
            }
            let selected_value = '';
            var checkBox = $('#same_as_mailing').is(':checked');
            if (checkBox == true) {
                if (document_onload_status == 1) {
                    selected_value = '{{isset($appData->division_company) ? $appData->division_company : ''}}';
                } else {
                    selected_value = $("#division_company").val(); // for callback
                }
            } else {
                selected_value = '{{isset($appData->division_company) ? $appData->division_company : ''}}';
            }
            if (countryId !== '') {
                let e = $(this);
                let api_url = "{{$bida_service_url}}/info/division/" + countryId;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "division_factory"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let selected_element_id = "countryId";
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, selected_element_id];

                let apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                    {
                        key: "user-email",
                        value: user_mail,
                    }
                ];
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#division_factory").html('<option value="">Select Country First</option>');
                $(self).next().hide();
            }

        })

        $("#division_factory").on("change", function () {
            let self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#district_factory").html('<option value="">Please Wait...</option>');
            let division = $('#division_factory').val();
            let divisionId = division.split("@")[0];
            let selected_value = '';
            var checkBox = $('#same_as_mailing').is(':checked');
            if (checkBox == true) {
                if (document_onload_status == 1) {
                    selected_value = '{{isset($appData->district_company) ? $appData->district_company : ''}}';
                } else {
                    selected_value = $("#district_company").val(); // for callback
                }
            } else {
                selected_value = '{{isset($appData->district_company) ? $appData->district_company : ''}}'; // for callback
            }
            if (divisionId) {
                let e = $(this);
                let api_url = "{{$bida_service_url}}/info/district/" + divisionId;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "district_factory"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let selected_element_id = "divisionId";
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, selected_element_id];

                let apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                    {
                        key: "user-email",
                        value: user_mail,
                    }
                ];
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#district_factory").html('<option value="">Select division First</option>');
                $(self).next().hide();
            }

        })

        $("#district_factory").on("change", function () {
            let self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#thana_factory").html('<option value="">Please Wait...</option>');
            let district = $('#district_factory').val();
            let districtId = district.split("@")[0];
            let selected_value = '';
            var checkBox = $('#same_as_mailing').is(':checked');
            if (checkBox == true) {
                if (document_onload_status == 1) {
                    selected_value = '{{isset($appData->thana_company) ? $appData->thana_company : ''}}';
                } else {
                    selected_value = $("#thana_company").val(); // for callback
                }
            } else {
                selected_value = '{{isset($appData->thana_company) ? $appData->thana_company : ''}}'; // for callback
            }
            if (districtId) {
                let e = $(this);
                let api_url = "{{$bida_service_url}}/info/upazila/" + districtId;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "thana_factory"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let selected_element_id = "districtId";
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, selected_element_id];

                let apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                    {
                        key: "user-email",
                        value: user_mail,
                    }
                ];
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#thana_factory").html('<option value="">Select district First</option>');
                $(self).next().hide();
            }

        })

        $("#thana_factory").on("change", function () {
            let self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#post_office_factory").html('<option value="">Please Wait...</option>');
            let upazila = $('#thana_factory').val();
            let upazilaId = upazila.split("@")[0];
            let selected_value = '';
            var checkBox = $('#same_as_mailing').is(':checked');
            if (checkBox == true) {
                if (document_onload_status == 1) {
                    selected_value = '{{isset($appData->post_office_company) ? $appData->post_office_company : ''}}';
                } else {
                    selected_value = $("#post_office_company").val(); // for callback
                }
            } else {
                selected_value = '{{isset($appData->post_office_company) ? $appData->post_office_company : ''}}'; // for callback
            }

            document_onload_status = 0;
            if (upazilaId) {
                let e = $(this);
                let api_url = "{{$bida_service_url}}/info/post-office/" + upazilaId;
                let calling_id = $(this).attr('id');
                let dependent_section_id = "post_office_factory"; // for callback
                let element_id = "id"; //dynamic id for callback
                let element_name = "name"; //dynamic name for callback
                let selected_element_id = "upazilaId";
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, selected_element_id];

                let apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                    {
                        key: "user-email",
                        value: user_mail,
                    }
                ];
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#post_office_factory").html('<option value="">Select thana First</option>');
                $(self).next().hide();
            }

        })

        @if(!empty($appData->road_factory))
        $("#road_factory").val('{{$appData->road_factory}}')
        @endif
        // Currency

        $('#currency').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$bida_service_url}}/info/currency";
            let selected_value = '{{isset($appData->currency) ? $appData->currency : ''}}';
            ; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "id"; //dynamic id for callback
            let element_name = "value"; //dynamic name for callback
            let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            let apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
                {
                    key: "user-email",
                    value: user_mail,
                }
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        var _token = $('input[name="_token"]').val();
        var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}';
        $.ajax({
            type: "POST",
            url: '/dcci-cos/get-dynamic-doc',
            dataType: "json",
            data: {
                _token: _token,
                appId: appId
            },
            success: function (result) {
                $("#showDocumentDiv").html(result.data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#showDocumentDiv").html('');
            },
        });

        $.ajax({
            type: "POST",
            url: '/dcci-cos/get-unit-price',
            dataType: "json",
            data: {
                _token: _token,
                appId: appId
            },
            success: function (result) {
                console.log(result)
                $("#per_original_price").val(result.data.original_unit_price);
                $("#per_copy_price").val(result.data.copy_unit_price);
                $('#print_count_original').trigger('input')
                $('#print_count_copy').trigger('input')
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#per_original_price").val('');
                $("#per_copy_price").val('');
            },
        });

        $(document).on('input', '.hscodes', function () {
            let selector = $(this).parent();
            let val_ln = $(this).val().length;
            selector.find('span.empty-message').hide();
            if (val_ln < 3 && val_ln !== 0) {
                selector.find('span.min-msg').show();
            } else {
                selector.find('span.min-msg').hide();
            }
            $(this).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url: '/dcci-cos/search-hsCode',
                        data: {
                            q: request.term,
                            _token: _token,
                            appId: appId
                        },
                        dataType: "json",
                        beforeSend: function () {
                            selector.find('span.loader').show();
                        },
                        success: function (data) {
                            response(data);
                            selector.find('span.loader').hide();
                        }
                    });
                },
                minLength: 3,
                response: function (event, ui) {
                    if (ui.content.length === 0) {
                        $(this).parent().find(".empty-message").show();
                        selector.find('span.min-msg').hide();
                    } else {
                        $(this).parent().find(".empty-message").hide();
                    }
                },
                focus: function (event, data) {
                    $(this).parent().find(".hscodes").val(data.item.code);
                    return false;
                },
                select: function (event, data) {
                    $(this).parent().find(".hscodes").val(data.item.code);
                    $(this).parent().find(".hscodeId").val(data.item.id);

                    return false;
                }
            })
                .data('ui-autocomplete')._renderItem = function (ul, item) {
                return $('<li class="list-group-item" style="width:250px;">')
                    .append('<span class="items">' + item.name + '</span>')
                    .appendTo(ul);
            };

        })
    });


    $(document).on('change', '#same_as_mailing', function () {
        let checkBox = $('#same_as_mailing').is(':checked');
        let country_company = $("#country_company").val()
        let division_company = $("#division_company").val()
        let district_company = $("#district_company").val()
        let thana_company = $("#thana_company").val()
        let post_office_company = $("#post_office_company").val()
        let road_company = $("#road_company").val()

        if (checkBox == true) {
            if (country_company !== '') {
                $("#country_factory").val(country_company)
                $("#division_factory").val(division_company)
                $("#district_factory").val(district_company)
                $("#thana_factory").val(thana_company)
                $("#post_office_factory").val(post_office_company)
                $("#road_factory").val(road_company)
                $("#country_factory").trigger('change')
                // $("#country_factory").select2("destroy").select2();
                disableOptionSelect('country_factory')
                disableOptionSelect('division_factory')
                disableOptionSelect('district_factory')
                disableOptionSelect('thana_factory')
                disableOptionSelect('post_office_factory')
            }
        } else {
            if (country_company !== '') {
                enableOptionSelect('country_factory')
                enableOptionSelect('division_factory')
                enableOptionSelect('district_factory')
                enableOptionSelect('thana_factory')
                enableOptionSelect('post_office_factory')
                $('#road_factory').val('');
            }
        }
    })

    function enableOptionSelect(id) {
        let el_id = $("#" + id);
        el_id.val('');
        $("#" + id + " option:not(:selected)").removeAttr('disabled')
        $("#" + id + ".select2-hidden-accessible").select2("destroy").select2();
        el_id.trigger('change')
    }

    function disableOptionSelect(id) {
        $("#" + id + " option:not(:selected)").attr('disabled', 'true')
    }

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_name];
                let value = row[element_name];

                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected ="selected" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option)
        if (selected_value !== '') {
            $("#" + calling_id).trigger('change')
        }
        $("#" + calling_id).next().hide()
    }

    function countryCallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_name];
                let value = row[element_name];

                if (id.split('@')[0] == '14') {
                    option += '<option selected ="selected" value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option)
        $("#" + calling_id).trigger('change')
        $("#" + calling_id).next().hide()
    }

    function dependantCallbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id, selected_element_id]) {
        $("#" + dependent_section_id).next().hide();
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = '';
                if (selected_element_id != '') {
                    id = row[element_id] + '@' + row[selected_element_id] + '@' + row[element_name];
                } else {
                    id = row[element_id] + '@' + row[element_name];
                }
                let value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id + " option[value]").remove();
        $("#" + dependent_section_id).append(option).trigger('change');
        $("#" + dependent_section_id).select2();
        $('.loading_data').hide();
        $("#" + calling_id).next().hide();
    }

    // Add table Row script
    function addTableRowGD(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var rowCount = $('#' + tableID).find('tr').length - 1;
        var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
        if (lastTr != '' && typeof lastTr !== "undefined") {
            rowCount = parseInt(lastTr) + 1;
        }
        //var rowCount = table.rows.length;
        //Increment id
        var rowCo = rowCount;
        var idText = 'rowCount' + tableID + '_' + rowCount;
        x.id = idText;
        $("#" + tableID).append(x);
        //get select box elements
        var attrSel = $("#" + tableID).find('#' + idText).find('select');
        //edited by ishrat to solve select box id auto increment related bug
        for (var i = 0; i < attrSel.length; i++) {

            var nameAtt = attrSel[i].name;
            var selectId = attrSel[i].id;
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
            var ret = selectId.split('_')[0];
            var repTextId = ret + '_' + rowCo;
            attrSel[i].id = repTextId;
            attrSel[i].name = repText;
        }
        attrSel.val(''); //value reset

        // end of  solving issue related select box id auto increment related bug by ishrat
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
            if ($('#' + inputId).hasClass('.hscodes')) {
                $('#' + repTextId).removeAttr('class');
                if ($('#' + repTextId).attr('type') == 'hidden') {
                    $('#' + repTextId).attr('class', 'hscodeId');
                } else {
                    $('#' + repTextId).attr('class', 'hscodes form-control input-md');
                }
            }
            $('#' + attrInput[i].id).removeClass('error');
        }
        attrInput.val(''); //value reset
        //edited by ishrat to solve textarea id auto increment related bug
        //get textarea elements
        var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
        for (var i = 0; i < attrTextarea.length; i++) {

            var nameAtt = attrTextarea[i].name;
            var selectId = attrTextarea[i].id;
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
            var ret = selectId.split('_')[0];
            var repTextId = ret + '_' + rowCo;
            attrTextarea[i].id = repTextId;
            attrTextarea[i].name = repText;
        }
        attrTextarea.val(''); //value reset
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


    } // end of addTableRowTraHis() function

    // Remove Table row script
    function removeTableRow(tableID, removeNum) {
        $('#' + tableID).find('#' + removeNum).remove();
        var index = 0
        var rowCo = 0
        $('#' + tableID + ' tr').each(function () {
                var trId = $(this).attr("id")
                var id = trId.split("_").pop();
                var trName = trId.split("_").shift();
                var nameIndex = id;

                var attrInput = $("#" + tableID).find('#' + trId).find('input');
                for (var i = 0; i < attrInput.length; i++) {
                    var nameAtt = attrInput[i].name;
                    var inputId = attrInput[i].id;
                    var repText = nameAtt.replace('[' + nameIndex + ']', '[' + index + ']'); //increment all array element name
                    var ret = inputId.replace('_' + id, '');
                    var repTextId = ret + '_' + rowCo;
                    attrInput[i].id = repTextId;
                    attrInput[i].name = repText;
                }


                var attrSel = $("#" + tableID).find('#' + trId).find('select');

                for (var i = 0; i < attrSel.length; i++) {
                    var nameAtt = attrSel[i].name;
                    var inputId = attrSel[i].id;
                    var repText = nameAtt.replace('[' + nameIndex + ']', '[' + index + ']'); //increment all array element name
                    // alert(nameIndex + ' ' + index)
                    var ret = inputId.replace('_' + id, '');
                    var repTextId = ret + '_' + rowCo;
                    attrSel[i].id = repTextId;
                    attrSel[i].name = repText;
                }

                var attrTextArea = $("#" + tableID).find('#' + trId).find('textarea');

                for (var i = 0; i < attrTextArea.length; i++) {
                    var nameAtt = attrTextArea[i].name;
                    var inputId = attrTextArea[i].id;
                    var repText = nameAtt.replace('[' + nameIndex + ']', '[' + index + ']'); //increment all array element name
                    // alert(nameIndex + ' ' + index)
                    var ret = inputId.replace('_' + id, '');
                    var repTextId = ret + '_' + rowCo;
                    attrTextArea[i].id = repTextId;
                    attrTextArea[i].name = repText;
                }
                var ret = trId.replace('_' + id, '');
                var repTextId = ret + '_' + rowCo;
                $(this).removeAttr("id")
                $(this).attr("id", repTextId)
                $(this).removeAttr("data-number")
                $(this).attr("data-number", rowCo)

                if (rowCo != 0) {
                    $(this).find('.addTableRows').removeAttr('onclick');
                    $(this).find('.addTableRows').attr('onclick', 'removeTableRow("' + tableID + '","' + trName + '_' + rowCo + '")');
                }
                index++;
                rowCo++;

            }
        )

    }

    /*document upload start*/

    function heightWidthImg(input) {
        var check = document.getElementById(input.id).getAttribute("flag")
        if (check == "img") {
            // return detect();
            var fileName = document.getElementById(input.id).files[0].name;
            var fileSize = document.getElementById(input.id).files[0].size;
            let _URL = window.URL || window.webkitURL;
            let file = document.getElementById(input.id).files[0];
            img = new Image();
            let objectUrl = _URL.createObjectURL(file);
            let imgHeight = $('#' + input.id).attr('data-height');
            let imgWidth = $('#' + input.id).attr('data-width');
            img.src = objectUrl;
            var extension = fileName.split('.').pop();

            if (extension !== "jpg" && extension !== "jpeg" && extension !== "png" && extension !== "PNG") {
                alert('file extension should be only jpg, jpeg and png');
                document.getElementById(input.id).value = "";
                return false;
            }
            if (fileSize >= 149999) {
                alert('File size cannot be over 150 KB');
                document.getElementById(input.id).value = "";
                return false;
            }
            img.onload = function () {
                // if (this.height != imgHeight || this.width != imgWidth) {
                //     alert('File height is ' + this.width + 'px and width is ' + this.height + 'px ; File height should be ' + imgHeight + " px and width should be " + imgWidth + "px");
                //     document.getElementById(input.id).value = "";
                //     _URL.revokeObjectURL(file);
                //     return false;
                // } else {
                    uploadDocument('preview_' + input.id, input.id, 'validate_field_' + input.id, 1);
                    imagePreview(input);
                    return true;
                // }
            }

        }


    }

    function uploadDocument(targets, id, vField, isRequired) {
        var check = document.getElementById(id).getAttribute("flag")
        if (check != "img") {
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop();
            if ((fileSize >= 1000000) || (extension !== "pdf")) {
                alert('File size cannot be over 1 MB and file extension should be only pdf');
                document.getElementById(id).value = "";
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
            if ($('#label_' + id).length)
                $('#label_' + id).remove();
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{URL::to('/dcci-cos/upload-document')}}";
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
                    if (response.includes("Error") == false) {
                        $('#' + id).removeClass('required');
                        $('#span_' + id).hide();
                    }

                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field == '') {
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            console.log(err)
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
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

    //Doc and image upload section
    $(document).on('click', '.filedelete', function () {
        var doc_id = $(this).attr('docid');
        doc_name_inc = doc_id.split('_')[1];
        doc_name_id = doc_id.split('_')[0];

        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            if (doc_name_inc) {
                doc = doc_name_id + '[' + doc_name_inc + ']';
                document.getElementById("validate_field_" + doc).value = '';
                $('#preview_' + doc_id).find('font').html('');
            } else {
                document.getElementById("validate_field_" + doc_id).value = '';
                $('.span_validate_field_' + doc_id).html('');
            }
            document.getElementById(doc_id).value = ''
            $('.saved_file_' + doc_id).html('');
            $('#span_' + doc_id).show()
            $('#photo_viewer_' + doc_id).attr('src', '{{(url('assets/images/no-image.png'))}}')
        } else {
            return false;
        }
    });

    $('#same_as_mailing').trigger('change')


</script>
