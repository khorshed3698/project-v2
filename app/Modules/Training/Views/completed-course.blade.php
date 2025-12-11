<?php

use Illuminate\Support\Facades\Auth;

$userType = Auth::user()->user_type;
?>
@extends('layouts.admin')

@section('header-resources')

    <style>
        .help_widget {
            height: auto;
            background: inherit;
            background-color: rgba(255, 255, 255, 1);
            border: none;
            border-radius: 10px;
            box-shadow: 0px 0px 13px rgba(0, 0, 0, 0.117647058823529);
            position: relative;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        .help_widget:hover {
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.167647058823529);
        }

        .help_widget_header {
            padding: 10px;
        }

        .help_widget_header img {
            width: 100%;
            height: 180px;
            border-radius: 10px;
            padding-top: 0 !important;
            background-position: center;
            background-size: cover;
        }

        .help_widget_content {
            padding: 0 15px;
        }

        .help_widget_content h3 {
            font-weight: 600;
            overflow: hidden;
            white-space: normal;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            text-overflow: ellipsis;
            height: 3em;
            line-height: 1.5em;
            font-size: 20px;
        }

        .help_widget_content p {
            font-size: 16px;
        }

        .footerElement {
            padding: 5px 10px;
            width: 100%;
            position: absolute;
            bottom: 0;
        }
        .tooltip {
        }
        .tooltip-inner {
            background-color: #27A25A;
        }
        .help_widget_certificate{
            padding: 0 0 0 15px;
        }
        .help_widget_payment{
            padding: 0 0 0 15px;
        }
        .padding_top_15{
            padding-top: 15px;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">

            @foreach($completedCourse as $row)
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                    <div class="help_widget">
                        <div class="help_widget_header text-center">
                            <img alt='...' src="{{ asset('/uploads/training/'.$row->course_thumbnail_path) }}"
                                 onerror="this.src=`{{asset('/assets/images/no-image.png')}}`"/>
                        </div>
                        <div class="row" style="padding: 5px 15px">
                            <div class="col-md-12 text-left">
                                <button class="btn btn-success btn-xs" style="border-radius: 50%; font-size: 8px"><i
                                            class="fa fa-calendar"></i></button>
                                <span style="font-size: 11px"> {{ trans('Training::messages.duration') }}:</span><span
                                        style="font-size: 12px" class="input_ban"> {{date("d/m/y", strtotime($row->course_duration_start))}} - {{date("d/m/y", strtotime($row->course_duration_end))}}</span>
                            </div>
                            <div class="col-md-12 text-left">
                                <button class="btn btn-success btn-xs" style="border-radius: 50%; font-size: 8px"><i
                                            class="fa fa-calendar"></i></button>
                                <span style="font-size: 11px"> {{ trans('Training::messages.total_hours') }}:</span>
                                <span class="input_ban" style="font-size: 12px">{{$row->total_hours}}</span>
                            </div>
                        </div>
                        <div class="help_widget_content text-left">
                            <h3 data-toggle="tooltip" data-placement="top"
                            title="{{ $row->course_title}}">{{ $row->course_title }}</h3>
                            <span style="font-size: 16px" class="text-danger">রেজিস্ট্রেশন শেষ </span>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
                                @if($userType == '10x112' || $userType == '5x505')
                                    <div class="help_widget_certificate">
                                        <a href="{{url('training/certificate-generate/' . \App\Libraries\Encryption::encodeId($row->id))}}"
                                           target="_blank">
                                            <button class="btn btn-success">Download Certificate &nbsp;<i
                                                        class="fa fa-download"></i>
                                            </button>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left padding_top_15">
                                @if($userType == '10x112' || $userType == '5x505')
                                    @if($row->fees_type == 'paid')
                                    <div class="help_widget_payment">
                                        <a href="{{url('/training/course-payment/course-details/' . \App\Libraries\Encryption::encodeId($row->id))}}"
                                           target="_blank">
                                            <button class="btn btn-success">Payment &nbsp;<i
                                                        class="fa fa-money"></i>
                                            </button>
                                        </a>
                                    </div>
                                    @else
                                        <div class="help_widget_payment">
                                            <a href="javascript:void(0);">
                                                <button class="btn btn-info" disabled>Free &nbsp;<i
                                                            class="fa fa-money"></i>
                                                </button>
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        {{--                        <div class="row footerElement">--}}
                        {{--                            <div class="text-center">--}}
                        {{--                                <a href="/training/course-details/{{\App\Libraries\Encryption::encodeId($row->id)}}" class="btn btn-success btn-sm"--}}
                        {{--                                   style="font-size: 13px">{{ trans('Training::messages.open') }}</a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>
                </div>
            @endforeach

        </div><!-- /.col-lg-12 -->
        <div class="col-md-offset-5">
            {!! $completedCourse->links() !!}
        </div>
    </div>


@endsection <!--content section-->

@section('footer-script')
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection <!--- footer-script--->

