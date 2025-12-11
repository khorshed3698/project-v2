<?php

use App\Libraries\Encryption;
use Illuminate\Support\Facades\Auth;

$user_type = Auth::user()->user_type;
$desk_training_ids = Auth::user()->desk_training_ids;
?>
@extends('layouts.admin')

@section('style')
    <style>
        .help_widget {
            height: 470px;
            background: inherit;
            background-color: rgba(255, 255, 255, 1);
            border: none;
            border-radius: 10px;
            position: relative;
            margin: 20px 0px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.167647058823529);
        }

        .help_widget:hover {
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.167647058823529);
        }

        .help_widget_header {
            padding: 10px;
        }

        .help_widget_header img {
            width: 100%;
            height: 25vh;
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
            font-size: 14px;
        }

        .footerElement {
            padding: 5px 10px;
            width: 100%;
            /*position: absolute;*/
            /*bottom: 0;*/
        }

        .footer_element_left {
            position: absolute;
            bottom: 5px;
            left: 10px;
        }

        .footer_element_right {
            position: absolute;
            bottom: 5px;
            right: 10px;
        }

        .tooltip {}

        .tooltip-inner {
            background-color: #27A25A;
        }
    </style>
@endsection

@section('content')
    @include('partials.messages')
    <div class="row">
        <div class="col-lg-12">
            @if($course->count() > 0)
                @foreach ($course as $row)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                        <div class="help_widget">
                            <div class="help_widget_header">
                                <img alt="{{ $row->course->course_title }}"
                                    src="{{ asset('/uploads/training/course/' . $row->course_thumbnail_path) }}"
                                    onerror="this.src=`{{ asset('/assets/images/no-image.png') }}`"
                                    title="{{ $row->course->course_title }}" />
                            </div>
                            {{-- <div class="help_widget_header"
                                style="background-image: url('{{ asset('/uploads/training/course/' . $row->course_thumbnail_path) }}');
                                        background-size: cover;
                                        background-position: center;
                                        width: 100%; 
                                        height: 170px;"
                                title="{{ $row->course->course_title }}"
                                onerror="this.style.backgroundImage=`url({{ asset('/assets/images/no-image.png') }})`">
                            </div> --}}
                            <div class="row" style="padding: 5px 15px">
                                <span class="col-md-12 text-left">
                                    <button class="btn btn-success btn-xs" style="border-radius: 50%; font-size: 8px"><i
                                            class="fa fa-calendar"></i></button>
                                    <span class="input_ban" style="font-size: 12px">Duration:
                                        {{ date('d F Y', strtotime($row->course_duration_start)) }} -
                                        {{ date('d F Y', strtotime($row->course_duration_end)) }}</span>
                                </span>
                                <span class="col-md-12 text-left">
                                    <button class="btn btn-success btn-xs" style="border-radius: 50%; font-size: 8px"><i
                                            class="fa fa-calendar"></i></button>
                                    <span style="font-size: 10px">Total Hour:</span>
                                    <span class="input_ban" style="font-size: 10px">{{ $row->total_hours }}</span>
                                </span><!--./col-md-5-->

                            </div>
                            <div class="help_widget_content text-left">
                                <h3>{{ $row->course->course_title }}</h3>
                                
                                <span style="font-size: 16px">{{ mb_substr($row->venue, 0, 30, 'UTF-8') }}</span>
                                <br>
                                <?php
                                $enroll_deadline = strtotime($row->enroll_deadline);
                                $current_date = strtotime(date('Y-m-d'));
                                ?>
                                @if($enroll_deadline >= $current_date)
                                    <span style="color: #811B8C">Registration End:</span>
                                    <span class="" style="color: #811B8C">
                                        {{ date('d M', $enroll_deadline) }}</span>
                                @else
                                    <span class="text-danger">Registration Closed</span>
                                @endif
                                    
                                <div class="row footerElement">
                                    <div class="pull-left footer_element_left">
                                        {{-- <p style="font-size: 16px; color: #00a157;">
                                            @if ($row->amount == 0)
                                                <b class="input_ban" style="font-size:16px">Free</b>
                                            @else
                                                <b class="input_ban" style="font-size:16px"><span>Course Fee :</span>{{ round($row->amount) }}
                                                </b> <b style="font-size:16px">Taka</b>
                                            @endif
                                        </p> --}}
                                        
                                    </div>
                                    <div class="pull-right footer_element_right">
                                        <a href="{{ url('training/course-details/' . \App\Libraries\Encryption::encodeId($row->id)) }}"
                                            class="btn btn-success btn-sm"
                                            style="font-size: 13px">{{ $row->enroll_deadline >= date('Y-m-d') ? 'Details' : 'Open' }}
                                            <i class="fa fa-arrow-right"></i> </a>
                                    </div>
                                </div><!--./footerElement-->
                            </div>

                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-lg-12">
                    <div class="alert alert-info">
                        <strong>Info!</strong> No upcoming course available.
                    </div>
                </div>
            @endif

        </div><!-- /.col-lg-12 -->
    </div><!--./row-->
    <div class="row">
        <div class="col-md-12 text-center">
            {{-- {!! $course->links() !!} --}}
        </div><!--./col-md-offset-5-->
    </div><!--./row-->
@endsection <!--content section-->

@section('footer-script')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection <!--- footer-script--->
