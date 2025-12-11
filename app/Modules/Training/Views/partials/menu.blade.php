<?php
$trainingDirector = Auth::user()->desk_training_ids;
//1 = director
//2 = Coordinator
?>
@if($user_type == '1x101')

{{--    <li class="treeview {{ (Request::is('training-configure/*') ? 'active' : '') }}">--}}
{{--        <a href="#"><i class="fa fa-wrench"></i>--}}
{{--            <span>Training configure</span>--}}
{{--            <span class="fa arrow"></span>--}}
{{--            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>--}}
{{--        </a>--}}
{{--        <ul class="treeview-menu">--}}
{{--            <li class="{{ ((Request::is('training-configure/project') || Request::is('training-configure/project/*')) ? 'active' : '') }}">--}}
{{--                <a href="{{ url ('/training-configure/project') }}"><i class="fa  fa-hand-o-right"></i>--}}
{{--                    {!! trans('Training::messages.project') !!}--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="{{ ((Request::is('training-configure/vendor') || Request::is('training-configure/vendor/*')) ? 'active' : '') }}">--}}
{{--                <a href="{{ url ('/training-configure/vendor') }}"><i class="fa  fa-hand-o-right"></i>--}}
{{--                    {!! trans('Training::messages.vendor') !!}--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </li>--}}

<li class="treeview {{ (Request::is('training/*') ? 'active' : '') }}">
    <a href="#"><i class="far fa-file-powerpoint" style="padding-right: 10px;"></i>
        <span>Training</span>
        <span class="fa arrow"></span>
        <span class="pull-right-container"></span>
    </a>
    <ul class="nav nav-second-level">
        <li class="{{ (Request::is('training/dashboard')  || Request::is('training/dashboard') ? 'active' : '') }}">
            <a href="{{ url ('/training/dashboard') }}"><i class="fas fa-tachometer-alt" style="padding-right: 10px;"></i>
                <span>প্রশিক্ষণ ড্যাশবোর্ড</span>
            </a>
        </li>
        <li class="{{ (Request::is('training/category-list')  || Request::is('training/category-list/*') ? 'active' : '') }}">
            <a href="{{ url ('/training/category-list') }}"><i class="fab fa-stack-overflow" style="padding-right: 10px;"></i>
                <span>প্রশিক্ষণ ক্যাটাগরি</span>
            </a>
        </li>
        <li class="{{ (Request::is('training/course/admin-list')  || Request::is('training/course/admin-list/*') ? 'active' : '') }}">
            <a href="{{ url ('/training/course/admin-list') }}"><i class="fa fa-book" style="padding-right: 10px;"></i>
                <span>কোর্স সংযুক্তি</span>
            </a>
        </li>
        <li class="{{ (Request::is('training/center/list')  || Request::is('training/center/list/*') ? 'active' : '') }}">
            <a href="{{ url ('/training/center/list') }}"><i class="fa fa-align-center" style="padding-right: 10px;"></i>
                <span>প্রশিক্ষণ কেন্দ্র</span>
            </a>
        </li>
    </ul>
</li>
@endif

{{-- admin user --}}

@if($trainingDirector == 1)
    <li class="treeview {{ (Request::is('training/*') ? 'active' : '') }}">
        <a href="#"><i class="fa fa-file-powerpoint-o  fa-fw"></i>
            <span>Training</span>
            <span class="fa arrow"></span>
            <span class="pull-right-container"></span>
        </a>
        <ul class="nav nav-second-level">
            <li class="{{ (Request::is('training/dashboard')  || Request::is('training/dashboard') ? 'active' : '') }}">
                <a href="{{ url ('/training/dashboard') }}"><i class="fas fa-tachometer-alt" style="padding-right: 10px;"></i>
                    প্রশিক্ষণ ড্যাশবোর্ড
                </a>
            </li>
            <li class="{{ (Request::is('training/schedule')  || Request::is('training/schedule/*') ? 'active' : '') }}">
                <a href="{{ url ('/training/schedule') }}"><i class="far fa-calendar" style="padding-right: 10px;"></i>
                    {!! trans('Training::messages.training_schedule') !!}
                </a>
            </li>
        </ul>
    </li>
@endif


{{-- coodinator user --}}
@if($trainingDirector == 2)

    <li class="treeview {{ (Request::is('training/*') ? 'active' : '') }}">
        <a href="#"><i class="fa fa-file-powerpoint-o  fa-fw"></i>
            <span>Training</span>
            <span class="fa arrow"></span>
            <span class="pull-right-container"></span>
        </a>
        <ul class="nav nav-second-level">

            <li class="{{ (Request::is('training/dashboard') || Request::is('training/dashboard') ? 'active' : '') }}">
                <a href="{{ url ('/training/dashboard') }}"><i class="fas fa-tachometer-alt" style="padding-right: 10px;"></i>
                    প্রশিক্ষণ ড্যাশবোর্ড
                </a>
            </li>

{{--            <li class="{{ ((Request::is('course') or Request::is('course/*')) ? 'active' : '') }}">--}}
{{--                <a href="{{ url ('course/list')}}"><i class="fa fa-book"></i>--}}
{{--                    <span>{!! trans('Training::messages.add_course') !!}</span>--}}
{{--                </a>--}}
{{--            </li>--}}
            <li class="{{ ((Request::is('training-speaker') || Request::is('training-speaker/*')) ? 'active' : '') }}">
                <a href="{{ url ('/training-speaker') }}"><i class="fa      "></i>
                    প্রশিক্ষক সংযুক্তি
                </a>
            </li>

            <li class="{{ (Request::is('training/schedule')  || Request::is('training/schedule/*') ? 'active' : '') }}">
                <a href="{{ url ('/training/schedule') }}"><i class="far fa-calendar" style="padding-right: 10px;"></i>
                    {!! trans('Training::messages.training_schedule') !!}
                </a>
            </li>

            <li class="{{ (Request::is('training/attendance') || Request::is('training/attendance/*')  ? 'active' : '') }}">
                <a href="{{ url ('/training/attendance') }}"><i class="fa fa-users"></i>
                    {!! trans('Training::messages.attendance') !!}
                </a>
            </li>

            <li class="{{ (Request::is('training/evaluation') || Request::is('training/evaluation/*') ? 'active' : '') }}">
                <a href="{{ url ('/training/evaluation') }}"><i class="fa   fa-list"></i>
                    {!! trans('Training::messages.evaluation') !!}
                </a>
            </li>

            <li class="{{ (Request::is('training/notification') || Request::is('training/notification/*') ? 'active' : '') }}">
                <a href="{{ url ('/training/notification') }}"><i class="fa   fa-check-circle"></i>
                    {!! trans('Training::messages.notification') !!}
                </a>
            </li>

            <li class="{{ (Request::is('training/certificate') ? 'active' : '') }}">
                <a href="{{ url ('/training/certificate') }}"><i class="fa   fa-list"></i>
                    {!! trans('Training::messages.certificate') !!}
                </a>
            </li>

{{--            <li class="{{ (Request::is('training/internal-participate')  || Request::is('training/internal-participate/*') ? 'active' : '') }}">--}}
{{--                <a href="{{ url ('/training/internal-participate') }}"><i class="fa   fa-binoculars"></i>--}}
{{--                    {!! trans('Training::messages.internal_participate') !!}--}}
{{--                </a>--}}
{{--            </li>--}}
        </ul>
    </li>
    <li class="{{ (Request::is('reports*') ? 'active' : '') }}">
        <a href="{{ url ('/reports')}}"><i class="fa fa-book"></i>
            <span>{!! trans('Training::messages.report') !!}</span></a>
    </li>
@endif

{{-- tranne user --}}

@if( ($user_type == '4x404' && $trainingDirector == '') || $user_type == '10x112' || $user_type == '5x505' || $user_type == '7x707')
<li class="treeview {{ (Request::is('training/*') ? 'active' : '') }}">
    <a href="#"><i class="fa fa-file-powerpoint-o  fa-fw"></i>
        <span>{!! trans('Training::messages.training') !!}</span>
        <span class="fa arrow"></span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
    </a>
    <ul class="treeview-menu">
        @if($user_type == '5x505')
            <li class="{{ (Request::is('training/dashboard') || Request::is('training/dashboard') ? 'active' : '') }}">
                <a href="{{ url ('/training/dashboard') }}"><i class="fa fa-tachometer"></i>
                    {!!trans('Training::messages.training_dashboard')!!}
                </a>
            </li>
        @endif
        <li class="{{ (Request::is('training/upcoming-course') ? 'active' : '') }}">
            <a href="{{ url ('training/upcoming-course')}}"><i class="fa fa-book"></i>
                <span>{!!trans('Training::messages.upcoming_course')!!}</span>
            </a>
        </li>

        <li class="{{ (Request::is('training/ongoing-course') ? 'active' : '') }}">
            <a href="{{ url ('training/ongoing-course')}}"><i class="fa fa-book"></i>
                <span>{!!trans('Training::messages.ongoing_course')!!}</span>
            </a>
        </li>

        <li class="{{ (Request::is('training/completed-course') ? 'active' : '') }}">
            <a href="{{ url ('training/completed-course')}}"><i class="fa fa-book"></i>
                <span>{!!trans('Training::messages.closed_course')!!}</span>
            </a>
        </li>
    </ul>
</li>
    @if($user_type == '10x112' && Request::is('dashboard'))
{{--    <li class="">--}}
{{--        <a href="javascript:void(0);" onclick="openNidAndPassportModal()"><i class="fa fa-toggle-on"></i>--}}
{{--            <span>{!!trans('Training::messages.switch_user')!!}</span>--}}
{{--        </a>--}}
{{--    </li>--}}
    @endif
@endif

