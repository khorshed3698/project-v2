<?php
$accessMode = ACL::getAccsessRight('ExternalService');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

@extends('layouts.admin')
<style>
    #back-to-applicaiton{
        /* height: 30px; */
        /* width: 100%; */
        /*width: auto;*/
        position: fixed;
        bottom: 50px;
        left: 50px;
        z-index: 999;
    }
    .button_back {
        width: auto;
        height: auto;
        color: white;
        background-color: #7C42EF;
        border-radius: 10px;
        border: 2px solid #7C42EF;
        padding: 0 8px;
        margin-right: 15px;
        font-size: 24px;
    }

    #myObject {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
</style>
@section('content')

    <div class="col-md-12">
        <h2 class="text-info"><a  onclick="closeTab()" target="_blank" data-toggle="tooltip" data-placement="top" title="" class="button_back" data-original-title="Back To Application">
                <i class="fas fa-reply"></i>
            </a><strong>Introduction of {{$serviceConfiguration->servicename}}
                of {{$serviceConfiguration->agencyname}}</strong></h2>
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-md-12" id="introduction">
                    <div id="back-to-applicaiton">
                        <div class="msgtost2">
{{--                            <div class="tooltip-demo pull-right" style="padding: 5px">--}}
{{--                                <a  onclick="closeTab()" target="_blank" data-toggle="tooltip" data-placement="top" title="" class="btn btn-default help-button" data-original-title="Help">--}}
{{--                                    <i class="fas fa-hand-point-left"></i> Back To Application--}}
{{--                                </a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                    <a style="display: none" class="btn btn-danger btn-sm" id="introductionClose">Minimize</a>
                    <?php
                    $isUrl = substr($serviceConfiguration->introduction,0, 4);
                    ?>
                    @if( $isUrl== 'http')
                        {{--                        <iframe src="{{ $serviceConfiguration->introduction }}" width="100%" height="100vh"></iframe>--}}
                        <object id="myObject" data="{{$serviceConfiguration->introduction}}" width="100%"
                                height="100vh"></object>
                    @else
                        <blockquote class="note_description"><h4
                                    style="font-style: italic;">{{$serviceConfiguration->introduction}}</h4>
                        </blockquote>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer-script')
    <script>
        function closeTab() {
            window.close();
        }
    </script>
@endsection
