<?php
$accessMode = ACL::getAccsessRight('VATReg');
?>
@extends('layouts.admin')
@section('content')
    <section class="content">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <strong>Shortfall Messages For Tracking No: {{$tracking_no}}</strong>
                            <div class="pull-right">
                                {{--                                <a class="btn btn-sm btn-success"  href="/process/new-connection-bpdb/view/{{\App\Libraries\Encryption::encodeId($appInfo->id)}}/{{\App\Libraries\Encryption::encodeId($appInfo->process_type_id)}}" role="button" aria-expanded="false" aria-controls="collapseExample">--}}
                                {{--                                    View Application--}}
                                {{--                                </a>--}}

                            </div>
                        </div>
                        <div class="panel-body">
                            <?php
                            $response = json_decode($shortfallData->response);
                            $items = $response->data->response->items;
                            $i = 1;
                            ?>

                            @foreach ($items as $value)
                                <p>({{$i}}) {{ $value->MSGTX}}</p>
                                <?php $i++;?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-script')

@endsection