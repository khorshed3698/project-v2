<?php
$user_type = Auth::user()->user_type;
$type = explode('x', $user_type);
$Segment=Request::segment(3);
?>

<div class="navbar-default sidebar sidebar-color" role="navigation" id="MainNav">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="{{ (Request::is('/dashboard') ? 'active' : '') }}">
                <a href="{{ url ('/dashboard') }}"><i class="fa fa-dashboard fa-fw"></i>
                    {!!trans('messages.dashboard')!!}
                </a>
            </li>

            {{--@if(Auth::user()->is_approved == 0 && Auth::user()->social_login == 1 && Auth::user()->user_type == '5x505' && Auth::user()->user_status == 'active')--}}
                {{--<li class="{{ (Request::is('/basic-information') ? 'active' : '') }}">--}}
                    {{--<a class="@if (Request::is('basic-information/*') || Request::is('process/basic-information/*'))  active @endif"--}}
                       {{--href="{{ url ('/basic-information/list/'.\App\Libraries\Encryption::encodeId(100)) }}">--}}
                        {{--<i class="fa fa-check-square fa-fw"></i> {{trans('messages.basic-information')}}--}}
                    {{--</a>--}}
                {{--</li>--}}
            {{--@endif--}}


            {{-- if user is active and approved--}}
            @if(Auth::user()->user_status == 'active' && (Auth::user()->is_approved == 1 or Auth::user()->is_approved == true))
                @if(Auth::user()->is_approved == 1 && (! (Auth::user()->first_login == 0 AND in_array($type[0], [5,6,13]))))
                    @if($type[0] != 4)
                        {{--<li class="{{ (Request::is('/meeting-form') ? 'active' : '') }}">--}}
                        {{--<a class="@if(Request::is('meeting-form/list/*') || Request::is('meeting-form/view/*') || Request::is('meeting-form/edit') || Request::is('meeting-form/add') )  active @endif"--}}
                        {{--href="{{ url ('/meeting-form/list/'.\App\Libraries\Encryption::encodeId(10)) }}">--}}
                        {{--<i class="fa  fa-adjust fa-fw" aria-hidden="true"></i> {{trans('messages.meeting_form')}}--}}
                        {{--</a>--}}
                        {{--</li>--}}
                    @endif
                    @if(in_array($type[0], [1,4,5]))
                        <li class="{{ (Request::is('/basic-information') ? 'active' : '') }}">
                            <a class="@if (Request::is('basic-information/*') || Request::is('process/basic-information/*'))  active @endif"
                               href="{{ url ('/basic-information/list/'.\App\Libraries\Encryption::encodeId(100)) }}">
                                <i class="fa fa-check-square fa-fw"></i> {{trans('messages.basic-information')}}
                            </a>
                        </li>
                    @endif
                    @if(($type[0] == 5 && \App\Libraries\CommonFunction::checkEligibility() == 1) || in_array($type[0], [1,4]))
                        <li class="{{ (Request::is('/visa-recommendation') ? 'active' : '') }}">
                            <a class="@if (Request::is('visa-recommendation/*') || Request::is('process/visa-recommendation/*'))  active @endif"
                               href="{{ url ('/visa-recommendation/list/'.\App\Libraries\Encryption::encodeId(1)) }}">
                                <i class="fa fa-check-square fa-fw"></i> {{trans('messages.visa-recommendation')}}
                            </a>
                        </li>
                        <li class="{{ (Request::is('/work-permit') ? 'active' : '') }}">
                            <a class="@if (Request::is('work-permit/*') || Request::is('process/work-permit/*'))  active @endif"
                               href="{{ url ('/work-permit/list/'.\App\Libraries\Encryption::encodeId(2)) }}">
                                <i class="fa fa-check-square fa-fw"></i> {{trans('messages.work-permit')}}
                            </a>
                        </li>

                        {{--<li class="{{ (Request::is('export-permit/*') ? 'active' : '') }}">--}}
                            {{--<a class="@if (Request::is('export-permit/*') || Request::is('process/export-permit/*'))  active @endif"--}}
                               {{--href="{{ url ('export-permit/list/'.\App\Libraries\Encryption::encodeId(6)) }}">--}}
                                {{--<i class="fa fa-cloud-upload fa-fw"></i> Export Permit--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        {{--<li class="{{ (Request::is('/import-permit') ? 'active' : '') }}">--}}
                            {{--<a class="@if (Request::is('import-permit/*') || Request::is('process/import-permit/*'))  active @endif"--}}
                               {{--href="{{ url ('/import-permit/list/'.\App\Libraries\Encryption::encodeId(7)) }}">--}}
                                {{--<i class="fa fa-check-square fa-fw"></i> {{trans('messages.import-permit')}}--}}
                            {{--</a>--}}
                        {{--</li>--}}

                        <li class="{{ (Request::is('/liaison-representative') ? 'active' : '') }}">
                            <a class="@if (Request::is('liaison-representative/*') || Request::is('process/liaison-representative/*'))  active @endif"
                               href="{{ url ('/liaison-representative/list/'.\App\Libraries\Encryption::encodeId(5)) }}">
                                <i class="fa fa-building fa-fw"></i> {{trans('messages.liaison-office')}}
                            </a>
                        </li>


                        <li class="{{ (Request::is('/foreign-borrowing') ? 'active' : '') }}">
                            <a class="@if (Request::is('foreign-borrowing/*') || Request::is('process/foreign-borrowing/*'))  active @endif"
                               href="{{ url ('/foreign-borrowing/list/'.\App\Libraries\Encryption::encodeId(3)) }}">
                                <i class="fa fa-building fa-fw"></i> {{trans('messages.foreign-borrowing')}}
                            </a>
                        </li>
                    @endif
                @endif

                @if(Auth::user()->is_approved == 1 && (!(Auth::user()->first_login == 0 AND (in_array($type[0], [5,6])))))
                    <li>
                        <a class="@if (Request::is('reports') || Request::is('reports/*')) active @endif" href="{{ url ('/reports ')}}">
                            <i class="fa fa-book fa-fw"></i> {!! trans('messages.report') !!}
                        </a>
                    </li>
                    {{--<li>--}}
                        {{--<a class="@if (Request::is('company-association/*')) active @endif" href="{{ url ('/company-association/list')}}">--}}
                            {{--<i class="fa fa-area-chart fa-fw"></i> {!! trans('messages.company_association') !!}--}}
                        {{--</a>--}}
                    {{--</li>--}}

                    {{--<li>--}}
                    {{--<a class="@if (Request::is('csv-upload/list') || Request::is('csv-upload/list/*')) active @endif" href="{{ url ('/csv-upload/list ')}}">--}}
                    {{--<i class="fa fa-align-justify fa-fw"></i> {!! trans('messages.csv_up_down') !!}--}}
                    {{--</a>--}}

                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a class="@if (Request::is('board-meting') || Request::is('board-meting/new-board-meting')  || Request::is('board-meting/list') || Request::is('board-meting/agenda/edit/*') || Request::is('board-meting/agenda/list/*') || Request::is('board-meting/agenda/create-new-agenda/*') ||  Request::is('board-meting/agenda/process/*') ) active @endif" href="{{ url ('/board-meting/lists') }}">--}}
                    {{--<i class="fa fa-users fa-fw"></i> {!!trans('messages.board_meting')!!}--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    @if($type[0] ==1 or $type[0]=='14') {{-- For System Admin and Programmer --}}
                    <li>
                        <a class="@if (Request::is('users/*')) active @endif" href="{{ url ('/users/lists') }}">
                            <i class="fa fa-users fa-fw"></i> {!!trans('messages.users')!!}
                        </a>
                    </li>
                    @endif

                    @if($type[0] ==1) {{-- For System Admin --}}
                    <li class="{{ (Request::is('settings/*') ? 'active' : '') }}">
                        <a href="{{ url ('/settings') }}"><i class="fa fa-gear fa-fw"></i>
                            <!--Settings--> {!!trans('messages.settings')!!}
                            <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="@if(Request::is('settings/area-list') || Request::is('settings/create-area') || Request::is('settings/edit-area/*')) active @endif" href="{{ url ('/settings/area-list') }}">
                                    <i class="fa fa-map-marker fa-fw"></i> {!!trans('messages.area')!!}
                                </a>
                            </li>
                            <li class="{{ (Request::is('/faq/faq-cat') ? 'active' : '') }}">
                                <a href="{{ url ('/faq/faq-cat') }}">
                                    <!--FAQ--><i class="fa fa-list-alt fa-fw" aria-hidden="true"></i>  {!!trans('messages.faq')!!}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/document') || Request::is('settings/create-document') || Request::is('settings/edit-document/*')) active @endif"
                                   href="{{ url ('/settings/document') }}">
                                    <i class="fa fa-file-text fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.document') !!}</span>
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/bank-list') || Request::is('settings/create-bank')  || Request::is('settings/edit-bank/*')  || Request::is('settings/view-bank/*')) active @endif" href="{{ url ('/settings/bank-list') }}">
                                    <i class="fa fa-bank  fa-fw"></i> {!! trans('messages.bank') !!}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/branch-list') || Request::is('settings/create-branch') || Request::is('settings/view-branch/*')) active @endif" href="{{ url ('/settings/branch-list') }}">
                                    <i class="fa fa-bank  fa-fw"></i> {!! trans('messages.bank_branch') !!}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/notice') || Request::is('settings/create-notice') || Request::is('settings/edit-notice/*')) active @endif" href="{{ url ('/settings/notice') }}">
                                    <i class="fa fa-list-alt fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.notice') !!}</span>
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/security') || Request::is('settings/edit-security/*')) active @endif" href="{{ url ('/settings/security') }}" href="{{ url ('/settings/security') }}">
                                    <i class="fa fa-key fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.security_profile') !!}</span>
                                </a>
                            </li>
                            <li>
                                {{--<a class="@if(Request::is('settings/company-info') )    active @endif" href="{{ url ('/settings/company-info') }}">--}}
                                <a class="@if(Request::is('settings/company-info') || Request::is('settings/company-info') || Request::is('settings/create-company')) active @endif" href="{{ url ('/settings/company-info') }}">
                                    <i class="fa fa-envelope  fa-fw"></i> {!! trans('messages.company_info') !!}
                                </a>
                            </li>

                            <li>
                                <a class="@if(Request::is('settings/stakeholder') || Request::is('settings/create-stakeholder') || Request::is('settings/edit-stakeholder/*')) active @endif" href="{{ url ('/settings/stakeholder') }}">
                                    <i class="fa fa-stack-exchange fa-fw"></i> {!! trans('messages.stakeholder') !!}
                                </a>
                            </li>

                            <li>
                                <a class="@if(Request::is('settings/holiday') || Request::is('settings/create-holiday') || Request::is('settings/edit-holiday/*')) active @endif" href="{{ url ('/settings/holiday') }}">
                                    <i class="fa fa-stack-exchange fa-fw"></i> {!! trans('messages.holiday') !!}
                                </a>
                            </li>

                            <li>
                                <a class="@if(Request::is('settings/process-category') || Request::is('settings/create-process-category') || Request::is('settings/edit-process-category/*')) active @endif" href="{{ url ('/settings/process-category') }}">
                                    <i class="fa fa-stack-exchange fa-fw"></i> {!! trans('messages.process_category') !!}
                                </a>
                            </li>

                            <li>
                                <a class="@if(Request::is('settings/currency') || Request::is('settings/create-currency') || Request::is('settings/edit-currency/*')) active @endif" href="{{ url ('/settings/currency') }}">
                                    <i class="fa fa-money  fa-fw"></i> {!! trans('messages.currency') !!}
                                </a>
                            </li>

                            <li>
                                {{--<a class="@if(Request::is('settings/park-info') OR Request::is('settings/create-park-info') OR Request::is('settings/edit-park-info/*')) active @endif" href="{{ url ('/settings/park-info') }}">--}}
                                {{--<i class="fa fa-tree fa-fw" aria-hidden="true"></i> <span> {!! trans('messages.park_info') !!}</span>--}}
                                {{--</a>--}}
                            </li>


                            <li>
                                <a class="@if(Request::is('settings/whats-new') OR Request::is('settings/create-whats_new') OR Request::is('settings/edit-whats-new/*')) active @endif" href="{{ url ('/settings/whats-new') }}">
                                    <i class="fa fa-barcode  fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.whats_new') !!}</span>
                                </a>
                            </li>
                            <li>
                                <a class="@if(Request::is('settings/user-manual') OR Request::is('settings/create-user-manual') OR Request::is('settings/edit-user-manual/*')) active @endif" href="{{ url ('/settings/user-manual') }}">
                                    <i class="fa fa-book  fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.user_manual') !!}</span>
                                </a>
                            </li>

                            <li>
                                <a class="@if(Request::is('settings/home-page-slider') OR Request::is('settings/create-home-page-slider') OR Request::is('settings/edit-home-page-slider/*')) active @endif" href="{{ url ('/settings/home-page-slider') }}">
                                    <i class="fa fa-file-image-o fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.home_page_slider') !!}</span>
                                </a>
                            </li>
                            {{--<li>--}}
                            {{--<a class="@if(Request::is('settings/high-commission') || Request::is('settings/create-high-commission') ||--}}
                            {{--Request::is('settings/edit-high-commission/*')) active @endif" href="{{ url ('/settings/high-commission') }}">--}}
                            {{--<i class="fa fa-building fa-fw" aria-hidden="true"></i> <span>High Commission</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a class="@if(Request::is('settings/hs-codes') || Request::is('settings/create-hs-code') ||--}}
                            {{--Request::is('settings/edit-hs-code/*')) active @endif" href="{{ url ('/settings/hs-codes') }}">--}}
                            {{--<i class="fa fa-codepen fa-fw" aria-hidden="true"></i> <span>HS Code</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a class="@if(Request::is('settings/indus-cat') || Request::is('settings/create-indus-cat') ||--}}
                            {{--Request::is('settings/edit-indus-cat/*')) active @endif" href="{{ url ('/settings/indus-cat') }}">--}}
                            {{--<i class="fa fa-indent" aria-hidden="true"></i> <span> {!! trans('messages.industrial_category') !!}</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a class="@if(Request::is('settings/ports') || Request::is('settings/create-port') || Request::is('settings/edit-port/*')) active @endif" href="{{ url ('/settings/ports') }}">--}}
                            {{--<i class="fa fa-support fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.port') !!}</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a class="@if(Request::is('settings/features') || Request::is('settings/features/*')) active @endif" href="{{ url ('/settings/features') }}">--}}
                            {{--<i class="fa fa-user fa-fw"></i> {!! trans('messages.features') !!}--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            <li>
                                <a class="@if(Request::is('settings/service-info') || Request::is('settings/create-service-info-details')  || Request::is('settings/service-info/*')) active @endif" href="{{ url ('/settings/service-info') }}">
                                    <i class="fa fa-user fa-fw"></i> {!! trans('messages.service_info') !!}
                                </a>
                            </li>
                            {{--<li>--}}
                            {{--<a class="@if(Request::is('settings/user-types') || Request::is('settings/edit-user-type/*')) active @endif" href="{{ url ('/settings/user-type') }}">--}}
                            {{--<i class="fa fa-user fa-fw"></i> {!! trans('messages.user_type') !!}--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            <li>
                                <a class="@if(Request::is('settings/edit-logo')) active @endif" href="{{ url ('/settings/edit-logo') }}">
                                    <i class="fa fa-list-alt fa-fw" aria-hidden="true"></i> <span>{!! trans('messages.title_logo') !!}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                @endif


                {{--@if(Auth::user()->is_approved == 1)--}}
                {{--<li class="{{ (Request::is('/exam/*') ? 'active' : '') }}">--}}
                {{--<a href="{{ url ('/exam') }}"><i class="fa fa-graduation-cap"></i>--}}
                {{--{!!trans('messages.exam')!!}--}}
                {{--<span class="fa arrow"></span></a>--}}
                {{--<ul class="nav nav-second-level">--}}
                {{--@if($type[0] == 9)   --}}{{-- Only exam controller user will get these menus  --}}
                {{--<li class="{{ (Request::is('/exam/question-bank/*') ? 'active' : '') }}">--}}
                {{--<a href="{{ url ('/exam/question-bank/list') }}"><i class="fa fa-question-circle"></i>--}}
                {{--{!!trans('messages.question_bank')!!}--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li class="{{ (Request::is('/exam/schedule/*') ? 'active' : '') }}">--}}
                {{--<a href="{{ url ('/exam/schedule/list') }}"><i class="fa fa-list-alt"></i>--}}
                {{--{!!trans('messages.scheduling')!!}--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li class="{{ (Request::is('/exam/result/*') ? 'active' : '') }}">--}}
                {{--<a href="{{ url ('/exam/result/list') }}"><i class="fa fa-file-text"></i>--}}
                {{--{!!trans('messages.result_process')!!}--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--@endif--}}

                {{--@if($type[0] != 9) --}}{{-- Only exam controller user will not get this menu --}}
                {{--<li class="{{ (Request::is('/exam/exam-list/*') ? 'active' : '') }}">--}}
                {{--<a href="{{ url ('/exam/exam-list/list') }}"><i class="fa fa-laptop"></i>--}}
                {{--{!!trans('messages.exam_list')!!}--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--@endif--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--@endif--}}
            @endif
        </ul>


        <div id="1">
            <div class="circular-sb" id="msgtost" style="display: none;">
                <p id="">
                <p id="feature_text"></p>
                <button class="btn btn-success feedbackbtn" value="ok" id="yesbtn" style="margin-left: 30px;">OK</button>
                </p>

                <input type="hidden" value="1" id="msg1">
                <div class="circle1"></div>
                <div class="circle2"></div>
            </div>
        </div>

        {{--Last Activities--}}
        <div class="panel panel-default msgtost">
            <fieldset class="scheduler-border" style="padding: 0px">
                <legend class="scheduler-border" style="color: gray;margin-bottom:3px;">Last Activities</legend>
                <div class="control-group">
                    <?php
                    $lastAction = \App\Libraries\CommonFunction::lastAction();
                    ?>

                    <small style="color: grey;margin: 0px;padding: 0px;">
                        @foreach($lastAction as $actionInfo)
                            <ul style="padding: 0px 15px;"><li><a href="{{url('users/profileinfo#tab_7')}}">@if($actionInfo !=null) {{$actionInfo->action}}  &nbsp;&nbsp;{{date("d-M-y h:i:a", strtotime($actionInfo->updated_at))}}  @endif </a></li></ul>
                        @endforeach
                    </small>
                    <a href="{{url('/users/profileinfo#tab_7')}}" class="pull-right btn btn-link " style="color: #286090">More <i class="fa fa-arrow-right"></i></a>
                </div>
            </fieldset>
        </div>


    <!-- <script>
    $(document).ready(function(){
        feedbackmessage();
        function feedbackmessage(){
            $.ajax({
                url: '{{ url("settings/fMsgShow") }}',
                type: "get",
                data: {},
                success: function(data){
                    if(data.id == 1){
                        $("#msgtost").css('display','block');
                        // $("#feature_text").html('<h5>'+data.feature_text+'</h5>');
                        $("#feature_text").html(data.feature_text);
                    }
                    
                    
                }
            })
        }
        $(".feedbackbtn").click(function(){

            var featurId = $("#msg1").val();
            var loc = window.location;
            var value = $(this).val();
             $("#msgtost").remove();            
            $.ajax({
                url: '{{ url("settings/feedback") }}',
                type: "get",
                data: {value:value,featurId:featurId},
                success: function(data){
                }
            })
        })
    })
</script> -->


        {{--Powered by section--}}
        <div class="panel panel-default">
            <div class="panel-header text-center">
                <br/>Powered by<br/><br/>
                {!!  Html::image('assets/images/business_automation.png','Business Automation logo',['width'=>'75']) !!}<br/><br/>
                {{--<br/>Supported by <br/><br/>--}}
                <div class="">
                </div>
            </div>
        </div>

        {{--Developed By Section--}}
        {{--<div class="panel-body" style="border: 1px solid rosybrown">--}}
            {{--<small>Developed By <a href="http://ocpl.com.bd/">OCPL</a>.</small>--}}
        {{--</div>--}}



    </div><!-- /.sidebar-collapse -->
</div><!-- /.navbar-static-side -->

