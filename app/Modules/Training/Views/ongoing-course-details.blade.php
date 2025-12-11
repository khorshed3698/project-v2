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
        }

        .help_widget:hover {
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.167647058823529);
        }

        .help_widget_header img {
            width: 90%;
            margin-top: 15px;
            border-radius: 10px;
            height: auto;
            padding-top: 0 !important;
        }

        .help_widget_content {
            padding: 0 15px;
        }

        .help_widget_content h3 {
            font-weight: 600;
        }

        .help_widget_content p {
            font-size: 16px;
        }

        .modal-dialog-centered {
            margin-top: 10%;
        }

        .statusBtn {
            border-radius: 30px;
            font-size: 18px;
            width: 70%;
            pointer-events: none;
        }

        .statusBtn1 {
            border-radius: 30px;
            font-size: 18px;
            width: 70%;

        }

        label, span {
            font-size: 16px;
        }

    </style>
@endsection

@section('content')
    @include('partials.messages')

    <div class="row">
        <div class="col-md-4 col-xs-12 col-sm-4">
            <div class="help_widget">
                <div class="help_widget_header text-center">
                    <img alt='...' src="{{ asset('/uploads/training/'.$courseDetails->course_image) }}"
                         onerror="this.src=`{{asset('/assets/images/no-image.png')}}`"/>
                </div>
                <div class="help_widget_content text-left">
                    <h3 title="{{ !empty($courseDetails->master_tracking_no)?$courseDetails->master_tracking_no:'' }}">{{ mb_substr($courseDetails->course_title, 0, 45, 'UTF-8') }}</h3>

                    <div class="row" style="padding: 5px 15px">

                        <button class="btn btn-warning btn-xs" style="border-radius: 50%; font-size: 8px"><i
                                    class="fa fa-calendar"></i></button>
                        <span>{{ trans('Training::messages.class_start') }}:</span><span
                                class="input_ban"> {{date("d-m-Y", strtotime($courseDetails->course_duration_start))}} </span>
                    </div>
                    <div class="row" style="padding: 5px 15px">
                        <button class="btn btn-danger btn-xs" style="border-radius: 50%; font-size: 8px"><i
                                    class="fa fa-calendar"></i></button>
                        <span>{{ trans('Training::messages.reg_ends') }}:</span> <span
                                class="input_ban">{{date("d-m-Y", strtotime($courseDetails->enroll_deadline))}}</span>
                    </div>

                    {{--                   <br>--}}
                    {{--                    <span>{{trans('Training::messages.coordinator_name')}} : {{$courseDetails->coordinator_name}}</span><br>--}}
                    {{--                    <span>{{trans('Training::messages.speaker_name')}} : {{$courseDetails->speaker_name}}</span>--}}

                    <div class="text-center" style="font-size: 18px">
                        <b> <span>{{ trans('Training::messages.price') }} :</span><span class="input_ban"
                                                                                        style="color: #00a157;"> {{$courseDetails->course_fee}}</span>
                            <span style="color: #00a157;">টাকা</span> </b>
                        <b> <span>{{ trans('Training::messages.service_fee') }} :</span><span class="input_ban"
                                                                                              style="color: #00a157;"> {{$fixedServiceFeeAmount}}</span>
                            <span style="color: #00a157;">টাকা</span> </b>
                    </div>

                    <div class="text-center">
                        <br>
                        @if($courseDetails->course_status == 'Applied' || $courseDetails->course_status == 'Shortlisted')
                            <button class="btn btn-info statusBtn"><b>Pending</b></button>
                        @elseif($courseDetails->course_status == 'Confirmed' && $courseDetails->course_fee == null)
                            <button class="btn btn-success statusBtn">Ongoing</button>
                        @elseif($courseDetails->course_status == 'Confirmed' && $courseDetails->course_fee != null)
                            @if($courseDetails->course_status == 'Confirmed' && $courseDetails->is_pay == 1)
                                <button class="btn btn-success statusBtn">Paid</button><br><br>
                                <a href="/spg/payment-voucher/{{\App\Libraries\Encryption::encodeId($paymentInfo->id)}}"
                                   class="btn btn-info statusBtn1">Download Voucher</a>
                            @else
                                <button class="btn btn-success statusBtn">{{ trans('Training::messages.payment') }}</button>
                                {{-- counter payment status--}}
                                @if(!empty($paymentInfo) && $paymentInfo->payment_status == 3)
                                    <br><br>
                                    <a href="/spg/counter-payment-check/{{ Encryption::encodeId($paymentInfo->id)}}/{{Encryption::encodeId(0)}}"
                                       class="btn btn-danger btn-sm">
                                        <strong> Cancel payment request</strong>
                                    </a>
                                    <a href="/spg/counter-payment-check/{{ Encryption::encodeId($paymentInfo->id)}}/{{Encryption::encodeId(1)}}"
                                       class="btn btn-primary btn-sm">
                                        <strong> Confirm payment request</strong>
                                    </a>

                                @endif
                            @endif
                        @elseif($courseDetails->course_status == 'Declined')
                            <button class="btn btn-danger statusBtn">Declined</button>
                        @endif
                    </div>
                    <br>
                </div>

            </div>
        </div>
        <div class="col-md-8">
            <div class="help_widget" style="padding: 10px 0px 10px 25px; text-align: left">
                <h3 style="color: #00a65a">{{ trans('Training::messages.necessity') }}</h3>
                {!! $courseDetails->requirements !!}
                <br>
                <h3 style="color: #00a65a">{{ trans('Training::messages.details') }}</h3>
                {!! $courseDetails->description !!}
            </div>

        </div>
    </div>

    <div class="help_widget" style="padding: 5px 15px">
        <h3 style="color: #00a157;">{{ trans('Training::messages.training_schedule') }}</h3>

        <div class="table-responsive">
            <table class="table">
                <thead>
                <th class="text-center">{{ trans('Training::messages.day') }}</th>
                <th class="text-center">{{ trans('Training::messages.time') }}</th>
                <th class="text-center"> {{ trans('Training::messages.place') }}</th>
                <th class="text-center">{{ trans('Training::messages.batch_name') }}</th>
                <th class="text-center">{{ trans('Training::messages.action') }}</th>
                </thead>
                <tbody>
                <tr class="text-center">
                    <td>{{$courseDetails->session_days}}</td>
                    <td>{{date("h:i a", strtotime($courseDetails->session_start_time))}}
                        - {{date("h:i a", strtotime($courseDetails->session_end_time))}}</td>
                    <td>{{$courseDetails->venue}}</td>
                    <td>{{$courseDetails->batch_name}}</td>
                    <td>
                        @if($courseDetails->course_status == 'Confirmed' && $courseDetails->fees_type == 'paid')
                            @if($courseDetails->course_status == 'Confirmed' && $courseDetails->is_pay == 1)
                                <button class="btn btn-success" disabled>Paid</button>
                            @else
                                @if(!empty($paymentInfo) && $paymentInfo->payment_status == 3)
                                    <label class="label label-warning"> Counter payment process</label>
                                @else
                                    <a href="/training/payment/{{\App\Libraries\Encryption::encodeId($courseDetails->id)}}"
                                       class="btn btn-success btn-sm">Pay Now</a>
                                @endif
                            @endif
                        @else
                            <button class="btn btn-info" disabled>Free</button>
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12 col-xs-12">
        <a href="/" class="btn btn-default">{{ trans('Training::messages.close') }}</a>&nbsp;&nbsp;&nbsp;
    </div>

@stop

@section('footer-script')
@endsection
