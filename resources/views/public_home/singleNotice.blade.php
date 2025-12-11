@extends('layouts.front')

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
    .identity_hover, .identity_type{
        cursor: pointer;
    }
</style>

@section("content")
    <div class="row">
        <div class="col-md-10 col-md-offset-1" >
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h4><strong><i class="fa fa-bell"></i> Notice</strong></h4>
                    </div>
                    <div class="pull-right">
                        <a href="/" class="btn btn-info btn-md pull-right">Home</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <span class="pull-left">
                                    <h4>
                                        <strong>Title: </strong>
                                        {{$nData->heading}}
                                    </h4>
                                </span>
                            </div>
                            <div class="pull-right">
                                <span class="pull-right">
                                    <h4>
                                        <strong>Date :</strong> {{$nData->updated_at}}
                                    </h4>
                                </span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <strong style="font-size: 18px">Notice Details: </strong>
                            {!! $nData->details !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section ('footer-script')
@endsection