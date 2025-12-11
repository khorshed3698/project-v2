@extends('layouts.admin')

@section('content')

    @include('partials.datatable-css')
    <?php $accessMode = TrACL::getAccsessRight('TrainingNotification');
    if (!TrACL::isAllowed($accessMode, 'A')) die('no access right!');
    ?>
    <div class="col-lg-12">

        @include('partials.messages')

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left" style="padding-top: 7px">
                    <b> <i class="fa fa-list"></i> {!! trans('Training::messages.notification') !!} </b>
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-lg-12">

                    <div class="col-md-8 col-md-offset-2">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-5" for="">{{trans('Training::messages.course_name')}}</label>
                                <span class="col-md-7">: {{ $notificationInfo->course_title }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-5" for="">{{trans('Training::messages.batch_name')}}</label>
                                <span class="col-md-7">: {{ $notificationInfo->batch_name }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-5" for="">{{trans('Training::messages.subject')}}</label>
                                <span class="col-md-7">: {{ $notificationInfo->subject }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-5" for="">{{trans('Training::messages.description')}}</label>
                                <span class="col-md-7">: {{ $notificationInfo->description }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-5" for="">{{trans('Training::messages.notify')}}</label>
                                <span class="col-md-7">:
                                    @if($notificationInfo->notify_via_sms == 1 && $notificationInfo->notify_via_email == 0)
                                        SMS
                                    @elseif($notificationInfo->notify_via_sms == 1 && $notificationInfo->notify_via_email == 1)
                                        SMS, Email
                                    @elseif($notificationInfo->notify_via_sms == 0 && $notificationInfo->notify_via_email == 1)
                                        Email
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row text-center">
                    <a href="{{ url('/training/notification') }}">
                        {!! Form::button(trans('Training::messages.close'), array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <br>
            </div><!-- /.box -->
        </div>
    </div>

@endsection


@section('footer-script')

@endsection <!--- footer script--->
