<?php
$accessMode = ACL::getAccsessRight('DCCI_COS');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}

?>
<style>
    .panel-heading {
        padding: 2px 5px;
        overflow: hidden;
    }

    table {
        counter-reset: section;
    }

    .count:before {
        counter-increment: section;
        content: counter(section);
    }

    .row > .col-md-5,
    .row > .col-md-7,
    .row > .col-md-3,
    .row > .col-md-9,
    .row > .col-md-12 > strong:first-child {
        padding-bottom: 5px;
        display: block;
    }

    legend.scheduler-border {
        font-weight: normal !important;
    }

    .table {
        margin: 0;
    }

    .table > tbody > tr > td,
    .table > tbody > tr > th,
    .table > tfoot > tr > td,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > thead > tr > th {
        padding: 5px;
    }

    .mb5 {
        margin-bottom: 5px;
    }

    .mb0 {
        margin-bottom: 0;
    }
    .v_label{
        font-size:15px;
        font-weight:700;
    }
</style>
<section class="content" id="applicationForm">

    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <strong style="line-height: 30px;">
                        Application For Country of Origin Service (DCCI)
                    </strong>
                </div>
                <div class="pull-right">

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>
                </div>

            </div>

            <div class="panel-body">

                <ol class="breadcrumb">
                    <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                    <li><strong>DCCI Tracking no. : </strong>{{$appInfo->dcci_cos_tracking_no}}</li>
                    <li class="highttext"><strong> Date of Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    <li><strong>Current Desk
                            :</strong>
                        {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}
                    </li>
                    <li><strong>DCCI Member : </strong>
                        {{$appInfo->dcci_member}}</li>
                    @if(!empty($pdf->origin_certificate))
                        <li style="margin-top:5px;" > <a href="{{ url('uploads/'.$pdf->origin_certificate) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf" aria-hidden="true"></i> Applicant Copy</a></li>
                    @endif
                    @if(!empty($certificate->cosignor_certificate))
                        <li style="margin-top:5px;" > <a href="{{ url('uploads/'.$certificate->cosignor_certificate) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-file-archive" aria-hidden="true"> </i> Cosignor certificate</a></li>
                    @endif
                </ol>

                {{--Payment information--}}
                @include('SonaliPaymentStackHolder::payment-information')


                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Consignor Information</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Company/Firm Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->cosignor_company_name) ? $appData->cosignor_company_name:''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="col-md-4 col-xs-12">
                                        <span class="v_label">Company Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-8">
                                        {{!empty($appData->road_company) ? $appData->road_company:''}},
                                        {{!empty($appData->post_office_company) ? explode('@',$appData->post_office_company)[2]:''}}
                                        ,
                                        {{!empty($appData->thana_company) ? explode('@',$appData->thana_company)[2]:''}}
                                        ,
                                        {{!empty($appData->district_company) ? explode('@',$appData->district_company)[2]:''}}
                                        ,
                                        {{!empty($appData->division_company) ? explode('@',$appData->division_company)[2]:''}}
                                        ,
                                        {{!empty($appData->country_company) ? explode('@',$appData->country_company)[1]:''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="col-md-4 col-xs-12">
                                        <span class="v_label">Factory Address </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-8">
                                        {{!empty($appData->road_factory) ? $appData->road_factory :''}},
                                        {{!empty($appData->post_office_factory) ? explode('@',$appData->post_office_factory)[2]:''}}
                                        ,
                                        {{!empty($appData->thana_factory) ? explode('@',$appData->thana_factory)[2]:''}}
                                        ,
                                        {{!empty($appData->district_factory) ? explode('@',$appData->district_factory)[2]:''}}
                                        ,
                                        {{!empty($appData->division_factory) ? explode('@',$appData->division_factory)[2]:''}}
                                        ,
                                        {{!empty($appData->country_company) ? explode('@',$appData->country_company)[1]:''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-6 col-xs-5">
                                        <span class="v_label">Signature Image</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        <a target="_blank" class="btn btn-xs btn-primary"
                                           href="{{URL::to('/uploads/'.$appData->validate_field_signature)}}"
                                           title="{{$appData->validate_field_signature}}">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-6 col-xs-5">
                                        <span class="v_label">Seal Image</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        @if(!empty($appData->validate_field_seal))
                                            <a target="_blank" class="btn btn-xs btn-primary"
                                               href="{{URL::to('/uploads/'.$appData->validate_field_seal)}}"
                                               title="{{$appData->validate_field_seal}}">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                Open File
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Consignee Information</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Company/Firm Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->consignee_company_name) ? $appData->consignee_company_name:''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Company Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->company_address) ? $appData->company_address:''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Destination Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->destination_address) ? $appData->destination_address:''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Particular of Transport(Optional Declaration)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->particuler_transport) ? $appData->particuler_transport:''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Currency</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->currency) ? explode('@',$appData->currency)[1]:''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Goods Information</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover ">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Marks</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">H.S Code</th>
                                        <th class="text-center">Weight (E.g.-1kg)</th>
                                        <th class="text-center">Value</th>
                                    </tr>
                                    </thead>
                                    <tbody id="goodDescription">
                                    @if(count($appData->marks) > 0)
                                        @foreach($appData->marks as $key => $value)
                                            <tr id="rowCountGoodDescription_{{$key}}" data-number="{{$key}}">
                                                <td class="col-md-1 col-xs-3 text-center count"></td>
                                                <td class="col-md-2 col-xs-3 text-center">{{!empty($appData->marks[$key]) ? $appData->marks[$key] :''}}</td>
                                                <td class="col-md-2 col-xs-3 text-center">{{!empty($appData->quantity[$key]) ? $appData->quantity[$key] :''}}</td>
                                                <td class="col-md-2 col-xs-3 text-center">{{!empty($appData->description[$key]) ? $appData->description[$key] :''}}</td>
                                                <td class="col-md-2 col-xs-3 text-center">{{!empty($appData->hscode[$key]) ? $appData->hscode[$key] :''}}</td>
                                                <td class="col-md-2 col-xs-3 text-center">{{!empty($appData->weight[$key]) ? $appData->weight[$key] :''}}</td>
                                                <td class="col-md-2 col-xs-3 text-center">{{!empty($appData->value[$key]) ? $appData->value[$key] :''}}</td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="rowCountGoodDescription_0" data-number="0">
                                            <td class="col-md-2 col-xs-3">{!! Form::text('marks[]',null,['class' =>'form-control input-md ','id'=>'fiscalYear_0']) !!}</td>
                                            <td class="col-md-2 col-xs-3">{!! Form::text('quantity[]',null,['class' =>'form-control input-md ','id'=>'quantity_0']) !!}</td>
                                            <td class="col-md-2 col-xs-3">{!! Form::textarea('description[]',null,['class' =>'form-control input-md ','size'=>'5x1','id'=>'description_0']) !!}</td>
                                            <td class="col-md-2 col-xs-3">{!! Form::select('hscode[]',['1'=>'12','2'=>'21'],null,['class' =>'form-control input-md ','id'=>'hscode_0']) !!}</td>
                                            <td class="col-md-2 col-xs-3">{!! Form::text('weight[]',null,['class' =>'form-control input-md ','id'=>'weight_0']) !!}</td>
                                            <td class="col-md-2 col-xs-3">{!! Form::text('value[]',null,['class' =>'form-control input-md ','id'=>'value_0']) !!}</td>
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

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Document Information</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class=" col-md-12">
                                    <table class="table table-bordered table-hover" id="loadDetails">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Document Name</th>
                                            <th>Number</th>
                                            <th>Date</th>
                                            <th>File</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($document as $key => $value)
                                            <tr>
                                                <td>{{$key+1}} .</td>
                                                <td>{{$value->doc_name}}</td>
                                                <td>{{$value->doc_number}}</td>
                                                <td>{{$value->date}}</td>
                                                <td>
                                                    <a target="_blank" class="btn btn-xs btn-primary"
                                                       href="{{URL::to('/uploads/'.$value->doc_path)}}"
                                                       title="Other File {{$key+1}}">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                        Open File
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                        @if(!empty($appData->validate_field_otherFile))
                            <div class="well-sm">
                                <h4>Other Documents</h4>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-hover" id="loadDetails">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>File</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($appData->validate_field_otherFile as $key => $value)
                                                <tr>
                                                    <td>{{$key+1}} .</td>
                                                    <td>Other File {{$key+1}}</td>
                                                    <td>
                                                        <a target="_blank" class="btn btn-xs btn-primary"
                                                           href="{{URL::to('/uploads/'.$value)}}"
                                                           title="Other File {{$key+1}}">
                                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                            Open File
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="panel panel-info">
                        <div class="panel-heading" style="padding-bottom: 4px;">
                            <strong>Print Quantity</strong>
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
                                        <td class="text-center">
                                          {{!empty($appData->print_count_original) ? $appData->print_count_original : '1'}}
                                        </td>
                                        <td class="text-center">
                                            {{!empty($appData->per_original_price) ? $appData->per_original_price : ''}}
                                        </td>
                                        <td class="text-center">
                                            {{!empty($appData->original_total) ? $appData->original_total : ''}}
                                         </td>
                                    </tr>

                                    <tr>
                                        <td>Certificate of Origin (Copy)</td>
                                        <td class="text-center">
                                            {{!empty($appData->print_count_copy) ? $appData->print_count_copy : '0'}}
                                        </td>
                                        <td class="text-center">
                                           {{!empty($appData->per_copy_price) ? $appData->per_copy_price : ''}}
                                        </td>
                                        <td class="text-center">
                                            {{!empty($appData->copy_total) ? $appData->copy_total : ''}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <label class="pull-right">Total Amount</label>
                                        </td>
                                        <td class="text-center">
                                            {{!empty($appData->total_amount) ? $appData->total_amount : ''}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


            </div>
        </div>
    </div>
</section>
