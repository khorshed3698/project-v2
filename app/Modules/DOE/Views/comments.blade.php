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
        #shortfall_comment_from_user {
            resize: vertical;
        }
    </style>
    <section class="content">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-primary">

                        <div class="panel-heading clearfix">

                            <strong>Comments For Tracking No: {{ $appInfo->tracking_no  }}
                            </strong>
                            <div class="pull-right">
                                @if(count($additionalPayment)>0)
                                <a class="btn btn-sm btn-info" role="button" data-toggle="collapse" href="#viewadditionalpayment" aria-expanded="false" aria-controls="collapseExample">
                                   View Additional Payment
                                </a>
                                @endif
                                @if(!in_array($appInfo->certificate_type_name ,['EIA_Approval','TOR_Approval','Zero_discharged_Approval']))
                                <a class="btn btn-sm btn-info" role="button" data-toggle="collapse" href="#paymentdiv" aria-expanded="false" aria-controls="collapseExample">
                                    Additional Payment
                                </a>
                                @endif
                                <a class="btn btn-sm btn-info"  href="/process/licence-applications/doe/view/{{\App\Libraries\Encryption::encodeId($appInfo->id)}}/{{\App\Libraries\Encryption::encodeId($appInfo->process_type_id)}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    View Application
                                </a>

                            </div>
                        </div>
                        <div class="panel-body">
                            <div id="paymentdiv"  class="collapse">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        {!! Form::open(array('url' => '/doe/additional-payment','enctype'=>'multipart/form-data','method' => 'post','id' => 'Doeadditionalpayment','role'=>'form')) !!}
                                        <input type="hidden" name="app_id" value="{{ Encryption::encodeId($appInfo->id) }}" id="app_id"/>
                                        <div class="row" style="margin: 0 auto;">
                                            <div class="col-md-6 {{$errors->has('payamount') ? 'has-error': ''}}">
                                                <div class="col-md-8">
                                                    {!! Form::text('payamount', null,
                                                    ['class' => 'form-control onlyNumber input-sm required','id'=>'payamount','placeholder'=>'Payment Amount']) !!}
                                                    {!! $errors->first('payamount','<span class="help-block">:message</span>') !!}
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="submit" class="btn btn-primary btn-sm" name="actionBtn" value="Submit Payment">
                                                </div>
                                            </div>
                                        </div>


                                        {!! Form::close() !!}
                                    </div>
                                </div>

                            </div>
                            @if(count($additionalPayment)>0)
                                    @include('DOE::payment-information')
                            @endif

                            {!! Form::open(array('url' => '/doe/store-comment','enctype'=>'multipart/form-data','method' => 'post','id' => 'DOEFormResubmit','role'=>'form')) !!}
                            <input type="hidden" name="app_id_resubmit" value="{{ Encryption::encodeId($appInfo->id) }}" id="app_id_resubmit"/>
                            @include('partials.messages')

                            <fieldset  class="border p-2">
                                <legend>Write your Comment here:</legend>
                                <div class="form-group clearfix">
                                    <div class="row">
                                        <div class="col-md-5 {{$errors->has('phone') ? 'has-error': ''}}">
                                            {!! Form::label('shortfall_comment_from_user','Comment:',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                @if  ($appInfo->status_id != 25 && $appInfo->status_id != 6)
                                                    {!! Form::textarea('shortfall_comment_from_user', '',
                                                         ['class'=>'form-control input-sm required', 'rows' => 2,'data-charcount-enable' => 'true', "data-charcount-maxlength" => "500", 'cols' => 60,'maxlength'=>"500",'id'=>'shortfall_comment_from_user']) !!}
                                                    {!! $errors->first('shortfall_comment_from_user','<span class="help-block">:message</span>') !!}
                                                @else
                                                    {!! Form::textarea('shortfall_comment_from_user', '',
                                                        ['class'=>'form-control input-sm required', 'rows' => 2, 'cols' => 60,'id'=>'shortfall_comment_from_user','readonly']) !!}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-5 {{$errors->has('shortfall_comment_file') ? 'has-error': ''}}">
                                            {!! Form::label('shortfall_comment_file',' Attached File:',['class'=>'col-md-5 text-left ']) !!}
                                            <div class="col-md-7">
                                                @if  ($appInfo->status_id != 25 && $appInfo->status_id !=6)
                                                    <input type="file" name="shortfall_comment_file" id="shortfall" onchange="uploadDocument('preview_shortfall', this.id, 'validate_field_shortfall',0)" >
                                                    <span style="color:#993333;">[N.B. Supported file extension is pdf. Example file.pdf]</span>
                                                @else
                                                     <input type="file" name="shortfall_comment_file" id="shortfall" onchange="uploadDocument('preview_shortfall', this.id, 'validate_field_shortfall',0)" disabled >

                                                 @endif
                                            </div>
                                        </div>
                                        @if  ($appInfo->status_id != 25 && $appInfo->status_id != 6)
                                        <div class="col-md-2">
                                            <button type="submit" id="resbumitform" style="cursor: pointer;"
                                                    class="btn btn-info btn-md"
                                                    value="submit" name="resubmitBtn"><i class="fa fa-paper-plane" aria-hidden="true"></i> send
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {!! Form::close() !!}
                            @if($appInfo->shortfall_comment_from_user !='' && $appInfo->shortfall_comment_from_user !=null )
                            <br><br>
                            <div class="panel panel-info">
                                <div class="panel-body">
                                    <label>Last Comment From User</label><br>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Comment</th>
                                            <th>Attachment</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$appInfo->shortfall_comment_from_user}}</td>
                                                <td>
                                                    @if($appInfo->shortfall_comment_file !='' && $appInfo->shortfall_comment_file !=null )
                                                        <a href="/uploads/{{$appInfo->shortfall_comment_file}}" class="btn btn-primary btn-sm">
                                                            View File
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                </div>
                            </div>
                            @endif

                            <br><br>
                            @if(count($comments)>0)
                            <div class="panel panel-success">
                                <div class="panel-heading"><strong>Comments History</strong></div>
                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-md-6">
                                            <strong>Comments from DOE office</strong>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Admin</th>
                                                    <th>Date Time</th>
                                                </tr>
                                            @foreach($adminComment as $key=>$value)
                                                <tr>
                                                    <td>{{$value->comment}}</td>
                                                    <td>{{$value->comment_date_time}}</td>
                                                </tr>
                                            @endforeach
                                            </table>
                                        </div>
{{--                                        {{dd($entComment)}}--}}

                                        <div class="col-md-6">
{{--                                            <strong>{{\Illuminate\Support\Facades\Auth::user()->user_full_name}} Section</strong>--}}
                                            <strong>Reply from user</strong>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>User</th>
                                                    <th>Date Time</th>
                                                    <th>Attachment</th>
                                                </tr>
                                            @foreach($entComment as $key2=>$value2)

                                                <tr>
                                                    <td>{{$value2->comment}}</td>
                                                    <td>{{$value2->comment_date_time}}</td>
                                                    <td>
{{--                                                        @if(count($value2->comment_attachments)>0)--}}
{{--                                                            @foreach($value2->comment_attachments as $fileKey =>$fileValue)--}}
{{--                                                               <a href="{{$fileValue->file_attach}}" class="btn btn-primary btn-sm">--}}
{{--                                                                   View File--}}
{{--                                                               </a>--}}

{{--                                                            @endforeach--}}
{{--                                                        @endif--}}
                                                        @if($value2->attachment !='' && $value2->attachment !=null )
                                                        <a href="{{$value2->attachment}}" class="btn btn-primary btn-sm">
                                                            View File
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>


                                            @endforeach
                                            </table>
                                        </div>

                                    </div>


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
    <script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" type="text/javascript"></script>
    <script>
        $(function () {
            var _token = $('input[name="_token"]').val();
            $("#DOEFormResubmit").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
        $(function () {
            $("#Doeadditionalpayment").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

    </script>
@endsection