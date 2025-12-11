<?php
$accessMode = ACL::getAccsessRight('DOE');
?>
@extends('layouts.admin')
@section('content')
    <style>
        fieldset
        {
            border: 1px solid #ddd !important;
            margin: 0;
            xmin-width: 0;
            padding: 10px;
            position: relative;
            border-radius:4px;
            background-color:#f5f5f5;
            padding-left:10px!important;
        }

        legend
        {
            font-size:14px;
            font-weight:bold;
            margin-bottom: 0px;
            width: 35%;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px 5px 5px 10px;
            background-color: #f5f5f5;
        }
    </style>
    <section class="content">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-primary">

                        <div class="panel-heading clearfix">
                            <strong>Information changed by DOE for tracking No: {{ $appInfo->tracking_no  }}
                            </strong>
                            <div class="pull-right">
                                    <a class="btn btn-sm btn-info"  href="/process/licence-applications/doe/view/{{\App\Libraries\Encryption::encodeId($appInfo->id)}}/{{\App\Libraries\Encryption::encodeId($appInfo->process_type_id)}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                        View Application
                                    </a>
                            </div>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(array('url' => '/doe/store-comment','enctype'=>'multipart/form-data','method' => 'post','id' => 'DOEFormResubmit','role'=>'form')) !!}
                            <input type="hidden" name="app_id_resubmit" value="{{ Encryption::encodeId($appInfo->id) }}" id="app_id_resubmit"/>

                            <fieldset  class="border p-2">
                                @if($changes_json == null || $changes_json =='[]' )
                                      No Change
                                 @else
                                <legend>JSON Format:</legend>
                                {{$changes_json}}
                                 @endif

                            </fieldset>
                            {!! Form::close() !!}
                            <br><br>
                            @if(count($changes)>0)
                            <div class="panel panel-success">
                                <div class="panel-heading"><strong>Changed details</strong></div>
                                <div class="panel-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Label</th>
                                                <th>Old</th>
                                                <th>New</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($changes as $key=>$value)
                                                <tr>
                                                    <td>{{$value->field_name}}</td>
                                                    <td>{{$value->old}}</td>
                                                    <td>{{$value->new}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>


                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-script')
    <script>
        $(function () {
            var _token = $('input[name="_token"]').val();
            $("#DOEFormResubmit").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
@endsection