<?php
$accessMode = ACL::getAccsessRight('ExternalService');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

@extends('layouts.admin')
<style>
    #back-to-applicaiton {
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

    .full-page {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        background-color: rgb(255, 255, 255);
        z-index: 9000;
    }
</style>
@section('content')

    <div class="col-md-12">
        <h2 class="text-info">
            <a onclick="closeTab()" target="_blank" data-toggle="tooltip" data-placement="top"
               title="" class="button_back" data-original-title="Back To Applicant">
                <i class="fas fa-reply"></i>
            </a>
            <strong>Guideline For {{$serviceConfiguration->servicename}}
                of {{$serviceConfiguration->agencyname}}</strong></h2>
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-md-12" id="introduction">
                    <div id="back-to-applicaiton">
                        <div class="msgtost2">
                            {{--                            <div class="tooltip-demo pull-right" style="padding: 5px">--}}
                            {{--                                <a onclick="closeTab()" target="_blank" data-toggle="tooltip" data-placement="top"--}}
                            {{--                                   title="" class="btn btn-default help-button" data-original-title="Help">--}}
                            {{--                                    <i class="fas fa-hand-point-left"></i> Back To Application--}}
                            {{--                                </a>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                    <a style="display: none" class="btn btn-danger btn-sm" id="introductionClose">Minimize</a>
                    <?php
                    $isUrl = substr($serviceConfiguration->guideline, 0, 4);
                    ?>
                    @if( $isUrl== 'http')
                        {{--                        <iframe src="{{ $serviceConfiguration->introduction }}" width="100%" height="100vh"></iframe>--}}
                        <object id="myObject" data="{{$serviceConfiguration->guideline}}" width="100%"
                                height="100vh"></object>
                    @else
                        <blockquote class="note_description"><h4
                                    style="font-style: italic;">{{$serviceConfiguration->guideline}}</h4>
                        </blockquote>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @if($serviceConfiguration->videotuitorial)
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6"><h3 class="pull-left" style="font-size: 20px; ">Tutorial</h3></div>
                        <div class="col-md-6"><a class="btn btn-info pull-right" id="tutorialFullScreen">Full Screen</a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel-group" id="tutorial">
                        <a style="display: none" class="btn btn-danger btn-sm" id="tutorialClose">Minimize</a>
                        <div class="embed-responsive embed-responsive-16by9 thumbnail">
                            <div class="embed-responsive embed-responsive-16by9">
                                <object width="100%" height="360px">
                                    <param name="movie" value="{{$serviceConfiguration->videotuitorial}}"></param>
                                    <param name="allowFullScreen" value="true"></param>
                                    <param name="allowscriptaccess" value="always"></param>
                                    <embed src="{{$serviceConfiguration->videotuitorial}}"
                                           allowscriptaccess="always" allowfullscreen="true"
                                           width="640"
                                           height="360"></embed>
                                </object>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($serviceConfiguration->formpreview)
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6"><h3 class="pull-left" style="font-size: 20px; ">Application View</h3>
                        </div>
                        <div class="col-md-6"><a class="btn btn-info pull-right" id="appPreviewFullScreen">Full
                                Screen</a></div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="panel-group" id="appPreview">
                        <a style="display: none" class="btn btn-danger btn-sm" id="appPreviewClose">Minimize</a>
                        <object style="display: block; margin: 0 auto;" width="100%" height="600px"
                                type="application/pdf"
                                data="<?php echo $serviceConfiguration->formpreview ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH"></object>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
@section('footer-script')
    <script>
        function closeTab() {
            window.close();
        }

        $(document).on('click', '#tutorialFullScreen', function (e) {
            $("#tutorial").addClass('full-page');
            $("#tutorial").find('object').css('height', '100vh')
            $("#tutorialClose").show();
        });
        $(document).on('click', '#tutorialClose', function (e) {
            $("#tutorial").removeClass('full-page');
            $("#tutorial").find('object').css('height', '360px')
            $("#tutorialClose").hide();
        });
        $(document).on('click', '#appPreviewFullScreen', function (e) {
            $("#appPreview").addClass('full-page');
            $("#appPreview").find('object').css('height', '100vh')
            $("#appPreviewClose").show();
        });
        $(document).on('click', '#appPreviewClose', function (e) {
            $("#appPreview").removeClass('full-page');
            $("#appPreview").find('object').css('height', '600px')
            $("#appPreviewClose").hide();
        });
    </script>
@endsection
