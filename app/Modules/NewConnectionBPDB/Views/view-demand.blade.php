<?php
$accessMode = ACL::getAccsessRight('DOE');
?>
@extends('layouts.admin')
@section('content')
    <section class="content">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <strong>Demand Note For Tracking No: {{ $appInfo->tracking_no  }}</strong>
                            <div class="pull-right">
                                <a class="btn btn-sm btn-success"  href="/process/new-connection-bpdb/view/{{\App\Libraries\Encryption::encodeId($appInfo->id)}}/{{\App\Libraries\Encryption::encodeId($appInfo->process_type_id)}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    View Application
                                </a>
                            </div>
                        </div>
                        <div class="panel-body">
                            @if($appInfo->demand_rep !=null && $appInfo->demand_rep !='')
                            <div class="col-md-3">
                                <a class="btn btn-md btn-info"
                                   href="{{$appInfo->demand_rep}}"
                                   role="button">
                                    <i class="far fa-money-bill-alt"></i>
                                    Demand Note
                                </a>
                            </div>
                            @endif
                            @if($appInfo->meter_cost !=null && $appInfo->meter_cost !='')
                            <div class="col-md-3">
                                <a class="btn btn-md btn-info"
                                   href="{{$appInfo->meter_cost}}"
                                   role="button">
                                    <i class="far fa-money-bill-alt"></i>
                                    Meter Cost
                                </a>
                            </div>
                            @endif
                            @if($appInfo->estimate_rep !=null && $appInfo->estimate_rep !='')
                            <div class="col-md-3">
                                <a class="btn btn-md btn-info"
                                   href="{{$appInfo->estimate_rep}}"
                                   role="button">
                                    <i class="far fa-money-bill-alt"></i>
                                    Estimate Rep
                                </a>
                            </div>
                            @endif
                        </div>
                        @if($appInfo->demand_status ==1)
                        <div class="panel-footer">
                            <div class="pull-right">
                                <a class="btn btn-md btn-primary"
                                   href="/new-connection-bpdb/view/additional-payment/{{ Encryption::encodeId($appInfo->id)}}"
                                   role="button">
                                    <i class="far fa-money-bill-alt"></i>
                                    Demand Fee Pay
                                </a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-script')

@endsection