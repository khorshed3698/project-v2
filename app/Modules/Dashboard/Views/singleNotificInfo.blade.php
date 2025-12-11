@extends('layouts.admin')
@section('content')

    <style>
        .panel-head {
            background: #4B8DF8;
            padding: 2px 10px;
            border-radius: 0;
            box-sizing: border-box;
            border: 1px solid #4B8DF8;
            border-bottom: 0;
        }
    </style>


    @if(Auth::user()->user_type == '1x101')
        <!-- <select class="js-example-basic-single" id="allUserNotific" name="state">
                  <option value="AL">Alabama</option>
                  <option value="AL">Alabama</option>
                  <option value="AL">Alabama</option>
                  <option value="AL">Alabama</option>
                  <option value="AL">Alabama</option>

                  <option value="WY">Wyoming</option>
                </select> -->

    @endif

    <?php



    if(Request::segment(1) == 'single-notification'){  ?>
    <div class="col-lg-12">
        <div class="panel panel-page">
            <div class="panel-heading panel-head">
                <h4 id=""><i class="fa fa-bell" aria-hidden="true"></i> <strong
                            style="color: white">Notification</strong></h4>
            </div>
            <div class="panel-body" style="background: #ffffff;border: 1px solid #4B8DF8; border-top: 0;">

                <div class="panel panel-default">
                    <div class="panel-heading" style="padding: 5px">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6"><span class="pull-left"> <h4><b>Title:</b>  {{ $singleNotificInfo->email_subject }}</h4></span>
                                </div>
                                <div class="col-md-6"><span
                                            class="pull-right"> <h4>Date : {{ $singleNotificInfo->created_at }}</h4></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <p>{!! $singleNotificInfo->email_content !!}</p>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <?php
    }else{ ?>

    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 id=""><i class="fa fa-bell" aria-hidden="true"></i> <strong>All notification</strong></h4>
            </div>
            <div class="panel-body">
                @if (count($notificationsAll) > 0)
                    <?php $i = 0; ?>
                    <div class="panel-group" id="accordion">
                        @foreach($notificationsAll as $notific)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4>
                                        <a data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse{{$notific->id}}" style="color: #000">
                                            <div class="pull-left">Title:</b> {{ $notific->email_subject }}</div>
                                            <div class="pull-right">Date
                                                : {{ date('d-M-Y h:i:s A', strtotime($notific->created_at)) }}</div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{$notific->id}}"
                                     class="panel-collapse collapse @if($i == 0) in @endif">
                                    <div class="panel-body">
                                        {!! $notific->email_content !!}
                                    </div>
                                </div>
                            </div>
                            <?php $i++; ?>
                        @endforeach
                    </div>
                @else
                    <h4 class="text-center">You have no notification</h4>
                @endif
            </div>
        </div>
    </div>
    <?php
    }

    ?>
    <!-- <script>
     $(document).ready(function() {
        $('#allUserNotific').select2();
    });
</script> -->

    @include('navigation.footer')
@endsection

@section('footer-script')
@endsection


