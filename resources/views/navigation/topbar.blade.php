<?php
$user_desk_ids = \App\Libraries\CommonFunction::getUserDeskIds();

$accessible_process = [];
if (\Illuminate\Support\Facades\Session::has('accessible_process')) {
    $accessible_process = \Illuminate\Support\Facades\Session::get('accessible_process');
}

?>
<style>
        .search-box2 {
        position: relative;
    }

    .search-box2 input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    #searchResults {
        position: absolute;
        background-color: #f9f9f9;
        width: 100%;
        border: 1px solid #ddd;
        z-index: 9999;
        border-radius: 4px;
        padding: 10px;
        box-sizing: border-box;
        display: none;
        max-height: 200px;
        overflow-y: auto;
    }

    /* Show the dropdown menu when the user clicks on the search field */
    .search-box2 input[type="text"]:focus+#searchResults {
        display: block;
    }

    #searchResults .search-item {
        padding: 11px 0;
        cursor: pointer;
    }

    #searchResults .search-item p {
        margin: 0px
    }

    #searchResults .group-header {
        padding: 7px 0px;
    }

    #searchResults .search-item:hover {
        background-color: #b7b7b755;
    }

    #searchResults::-webkit-scrollbar {
        width: 10px;
        /* Width of the scrollbar */
    }

    /* Track */
    #searchResults::-webkit-scrollbar-track {
        background: #f1f1f1;
        /* Color of the track */
    }

    /* Handle */
    #searchResults::-webkit-scrollbar-thumb {
        background: #888;
        /* Color of the scrollbar handle */
        border-radius: 5px;
        /* Rounded corners */
    }

    /* Handle on hover */
    #searchResults::-webkit-scrollbar-thumb:hover {
        background: #555;
        /* Change color on hover */
    }

    .search-container2 {
        position: relative;
    }

    .search-box2 {
        position: relative;
    }
    .navbar-top-links li a {
        padding: 5px;
        min-height: 0px;
    }

    .dropdown-alerts .divider {
        height: 1px;
        width: 100%;
        margin: 0px 0;
        overflow: hidden;
        background-color: #9D9FA2
    }

    .dropdown-height {
        height: 300px;
    }
     .navbar-header{
        position: relative;
    }
    #MainNav{
        position: absolute;
        bottom: -101%;
        left: 0;
        height: 100%;
    }
    #MainNav #side-menu{
        background-color: white;
    }

    .navbar-top-links .dropdown-alerts li a div {
        white-space: normal;
        padding: 12px;

    }

    .navbar-top-links .dropdown-alerts li a {
        padding: 10px 20px;
        min-height: 0;
    }

    .dropdown-alerts>li:first-child {
        overflow: hidden;
    }

    .check {
        height: 225px;
        overflow-y: scroll;
    }

    #test li a {
        height: 33px;
    }

    .title {
        margin-left: 0px;
    }
    .switch_button{
        font-size: 12px;
    }
    .switch-icon{
        transition: all 0.5s;
    }
    
    .switch_button:focus,.switch_button:active,.switch_button:visited, .switch_button:hover {
        outline: none;
        text-decoration: none;
    }
    .company-container:hover .switch-icon{
        rotate: 90deg;
    }

    .company-name:hover {
        text-decoration: none;
    }

    .navbar-top-links.navbar-right {
        display: flex;
        align-items: center;
    }

    .addButtonConatiner {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgb(28, 96, 171);
    }

    .d-none {
        display: none !important;
    }

    .d-block {
        display: block;
    }

    .dropdown-toggle {
        border: 1px solid #DFE1E5;
        border-radius: 7px;
    }
    .company-container{
        margin-top: 5px; 
        text-align: start;
    }

    @media screen and (max-width: 767px) {
        .title {
            margin: 10px 5px;
            font-size: 16px !important;
        }
        .company-container{
            text-align: center;
            margin-top: 0px;
            margin-bottom: 5px;
        }
        .navbar-header{
            position: static !important;
        }
        #MainNav{
            position: static;
        }
    }

    /* Top bar Bida Services */
    .dropdown-submenu{
        position: relative;
    }
    .dropdown-submenu .caret{
        -webkit-transform: rotate(-90deg); 
        transform: rotate(-90deg);
    }
    .dropdown-submenu > .dropdown-menu {
        top:0; 
        right:100%; 
        margin-top:-6px; 
        margin-left:-1px;
    }

    .dropdown-submenu.open > a:after{
        border-left-color:#fff;
    }
    .dropdown-submenu.open > .dropdown-menu, .dropdown-submenu.open > .dropdown-menu {
        display: block;
    }
    .dropdown-submenu .dropdown-menu{
        margin-bottom: 8px;
    }
    .navbar-default .navbar-nav .open .dropdown-menu .dropdown-submenu ul{
        background-color: #f6f6f6;
    }
    .navbar-inverse .navbar-nav .open .dropdown-menu .dropdown-submenu ul{
        background-color:#333;
    }
    .navbar .navbar-nav .open .dropdown-submenu .dropdown-menu > li > a{
        padding-left: 30px;
    }
    @media screen and (min-width:992px){
        .dropdown-submenu .dropdown-menu{
            margin-bottom: 2px;
        }
        .navbar .navbar-nav .open .dropdown-submenu .dropdown-menu > li > a{
            padding-left: 25px;
        }
        .navbar-default .navbar-nav .open .dropdown-menu .dropdown-submenu ul{
            background-color:#fff;
        }
        .navbar-inverse .navbar-nav .open .dropdown-menu .dropdown-submenu ul{
            background-color:#fff;
        }
    }
    .dropdown-submenu-left {
        position: relative;
    }

    .dropdown-submenu-left > .dropdown-menu {
        left: -100%;
        margin-top: 0;
        border-radius: 0;
        width: max-content;
    }
    .dropdown-submenu a, .dropdown-submenu a:focus, .dropdown-submenu a:hover {
        border: none;
        outline: none;
    }
    .dropdown-submenu , .dropdown-submenu:focus, .dropdown-submenu:hover {
        border: none;
        outline: none;
    }
    .d-null{
        display: none !important;
    }
    .mri-5{
        margin-right: 5px;
    }
    @media screen and (max-width:768px){

        .dropdown-submenu > .dropdown-menu {
            position: relative !important;
            top: 0;
            right: 0;
            margin: 0px;
            width: 100%;
            border: none;
            box-shadow: none;
        }
        .dropdown-submenu-left > .dropdown-menu{
            position: relative !important;
            top: 0;
            right: 0;
            left:0;
            margin: 0px;
            width: 100%;
            border: none;
            box-shadow: none;
        }
        .d-null{
            display: block !important;
        }
        .p-lg-2{
            padding-left: 24px !important;
        }
        .p-lg-3{
            padding-left: 35px !important;
        }
        #align-item-center{
            justify-content: center;
        }

    }
    @media screen and (max-width:450px){
        .navbar-top-links .dropdown-alerts{
            max-width: 250px !important;
        }
        
    }
    .caret{
        position: absolute;
        right: 5%;
        top: 50%;
        transform: translate(-50%, -50%);
        transition: all 0.5s;
    }


    /* Add this CSS to rotate the caret icon */
    .navbar-nav .dropdown-submenu-toggle b.caret {
        transition: transform 0.3s ease;
    }

    .rotate-icon {
        transform: rotate(-180deg) !important;
    }
</style>
@if (Auth::user()->user_type != '5x505')
<div class="navbar-header nav-toggle">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
</div>
@endif
<!-- /.navbar-header -->
@if (Auth::user()->user_type == '5x505')
    <div class="navbar-header nav-logo" style="padding: 0 25px;">
@else
    <div class="navbar-header nav-logo">
@endif

        {{--    {!! Html::image(Session::get('logo'), 'logo', array( 'width' => 40,'height' => 40,'style'=>' margin: 5px 0 0 10px !important;border-radius:50%' ))!!} --}}
        <a href="{{ url('dashboard') }}">
            {{-- {!! Html::image(Session::get('logo'), 'logo', array( 'height' => 45,'style'=>' margin: 5px 0 0 10px !important;' ))!!} --}}

            {!! Html::image(Session::get('logo'), 'logo', [
                'height' => 45,
                'style' => 'margin: 5px 0 0 10px !important;',
                'onerror' => "this.onerror=null;this.src='" . asset('/assets/images/photo_default.png') . "';",
            ]) !!}

        </a>
        {{--    {!!  Html::image('assets/images/govt_logo.png','Logo',['width'=>50,'style'=>'margin: 0px 0 0 10px !important;']) !!} --}}

    </div>
    <div class="navbar-header header-caption" style="line-height: 20px; padding-top: 5px;">
        @if (Auth::user()->user_type != '5x505')
            <strong class="title" style="text-align:start;">{{ Session::get('title') }} </strong>
        @endif

        {{--    @if (env('project_mode')) --}}
        {{--        <strong style="color:red;">{{env('project_mode')}}</strong> --}}
        {{--    @endif --}}

        @if (Auth::user()->user_type == '5x505')
            {{-- <strong class="title">{{ \App\Libraries\CommonFunction::getCompanyNameById(Auth::user()->working_company_id) }}</strong> --}}

            <a href="{{ url('basic-information/form-bida', Encryption::encodeId('EUBS')) }}{{ !empty($appInfo) && $appInfo->is_existing_for_bida == 1 ? '/' . Encryption::encodeId(Auth::user()->company_ids) : '' }}" class="company-name">
                <strong class="title" tyle="color: #004c99;">{{ \App\Libraries\CommonFunction::getCompanyNameById(Auth::user()->working_company_id) }}</strong>
            </a>
            @if(Auth::user()->user_status == 'active' && Auth::user()->is_approved == 1)
                <div class="company-container">
                    <img alt="switch company"
                    src="{{ url('assets/fonts_svg/rotate.svg') }}"
                    width="20" class="switch-icon">
                    <a class="switch_button {{ Request::is('company-association/switch-company') ? 'active' : '' }}"
                    href="{{ url('company-association/switch-company') }}">
                    Switch Company
                    </a>
                </div>
            @endif
            
        @endif
    </div>

    @if (Auth::user()->user_type == '5x505')
        <ul class="nav navbar-top-links navbar-right" style="padding: 0px 25px; flex-wrap: wrap; justify-content: center;">
    @else
        <ul class="nav navbar-top-links navbar-right" id="align-item-center">
    @endif

            @if (Auth::user()->user_type != '6x606')
                <li class="dropdown" style="line-height: 5px; margin:5px;" id="checkClass">
                    <a class="dropdown-toggle" data-toggle="" href="#" id="dropdownSearchBox" aria-expanded="false" style="text-align: center;">
                        <i id="dropdown_search_icon" class="fas fa-search" style="color: rgb(55,73,87); font-size: 20px"></i>
                        {{-- <small><span>&nbsp;</span></small> --}}
                    </a>
                    <div class="dropdown-menu dropdown-alerts search_div in" aria-labelledby="dropdownSearchBox">
                        {!! Form::open([
                            'url' => '/process/list',
                            'method' => 'POST',
                            'class' => '',
                            'id' => 'global-search',
                            'role' => 'form',
                        ]) !!}
                        <li class="row">
                            <div class="input-group input-group-lg search-box2">
                                <input type="text" name="search_by_keyword" required class="form-control"
                                    placeholder="{{ Auth::user()->user_type == '5x505' ? 'Tracking Number' : Auth::user()->user_type == '10x112' ? 'Search Menu' : 'Track. ID or Company Name' }}"
                                    id="searchField">
                                <span class="input-group-btn">
                                    <button id="global-search-submit" class="btn btn-primary" type="submit"
                                        style="background-color: #EBF0FE; border-color: #ccc; color: rgba(0,0,0,0.8);">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </span>
                            </div>
                            <div id="searchResults">
                            </div>
                            <div class="clearfix"></div>
                        </li>
                        {!! Form::close() !!}
                    </div>
                </li>
            @endif

            <?php
            $currentUrl = request()->path();
            ?>

            @if (Auth::user()->user_type == '5x505' && $currentUrl != 'dashboard')
                <li class="nav-item" style="line-height: 5px; margin:5px;" title="DashBoard">
                    <a class="dropdown-toggle" href="{{ url('dashboard') }}" role="button">
                        <i class="fa fa-home" style="color: rgb(55,73,87); font-size: 20px"></i>
                    </a>
                </li>
            @endif
            
            <li class="nav-item" style="line-height: 5px; margin:5px;" title="Full Screen">
                <a class="dropdown-toggle" data-widget="fullscreen" href="#" role="button" id="fullscreen-toggle">
                    <i class="fas fa-expand-arrows-alt" style="color: rgb(55,73,87); font-size: 20px"></i>
                    {{-- <small><span>&nbsp;</span></small> --}}
                </a>
            </li>

            {{-- <li class="dropdown" style="line-height: 5px">
                <a class="dropdown-toggle active" data-toggle="dropdown" href="#" id="notificShow" aria-expanded="false" style="padding: 14px 15px !important">
                    <div id="nCount"></div>
                    <i class="fa fa-bell fa-fw" style="color: #FFBB00; font-size: 20px"></i>
                    <div></div>
                    <small><span>&nbsp;</span></small>
                </a>
                <ul class="dropdown-menu dropdown-height dropdown-alerts in" id="notific">
                    <div style="height: 30px;" id="notificHead">
                        <strong class="pull-left" style="margin-left: 13px; margin-top: 10px;">Notification</strong>
                        <!-- <a href="###"><b class="pull-right" id="markNotificAsRead" style="margin-right: 23px;">Mark All as Read</b></a>  -->
                        <small><span>&nbsp;</span></small>
                    </div>
                    <div>
                        <li class="divider"></li>
                    </div>

                </ul>
            </li> --}}


            {{-- <li class="dropdown"> --}}
            {{-- <a class="dropdown-toggle change_url" title="" data-toggle="dropdown" href="#" style="color: #333 !important;"> --}}
            {{-- <div style="height: 7px;"></div> --}}
            {{-- <i class="fa fa-language fa-fw"></i> {!! App::getLocale()=='en'?'English':''!!} <i --}}
            {{-- class="fa fa-caret-down"></i> --}}
            {{-- <div></div> --}}
            {{-- <small><span>&nbsp;</span></small> --}}
            {{-- </a> --}}
            {{-- <ul class="dropdown-menu dropdown-tasks language"> --}}
            {{-- <li> --}}
            {{-- <a href="{{ url('language/en') }}"> --}}
            {{-- <div> --}}
            {{-- <i class="fa fa-language" aria-hidden="true"></i> </i> English --}}
            {{-- </div> --}}
            {{-- </a> --}}
            {{-- </li> --}}

            {{-- <li> --}}
            {{-- <a href="{{ url('language/bn') }}"> --}}
            {{-- <div> --}}
            {{-- <i class="fa fa-bold fa-fw"></i> বাংলা --}}
            {{-- </div> --}}
            {{-- </a> --}}
            {{-- </li> --}}

            {{-- </ul> --}}
            {{-- </li> --}}

            @if ($currentUrl != 'dashboard/apply-service' && Auth::user()->user_type == '5x505' && Auth::user()->user_status == 'active' && Auth::user()->is_approved == 1 )
                <li class="nav-item" style="line-height: 5px;" id="addButton">
                    <a class="addButtonMain" href="{{ url('/dashboard/apply-service') }}">
                        <div class="addButtonConatiner">
                            <i id="" class="fas fa-plus" style="color: #fff; font-size: 20px"></i>
                        </div>
                    </a>
                </li>
            @endif

            <li class="dropdown" style="margin:5px;">
                <a class="dropdown-toggle change_url new" title="" data-toggle="dropdown" href="#"
                    style="padding: 10px 5px !important;width: 230px; display: flex">
                    {{-- <img src="{{ \App\Libraries\UtilFunction::userProfileUrl(Auth::user()->user_pic, 'users/upload/') }}" class=" img-circle" alt="" id="user_sisgnature " width="32px" height="32px" style="float: left; outline: #707070 solid 2px;"/> --}}
                    <img src="{{ url('users/upload/' . Auth::user()->user_pic) }}" class=" img-circle" id="user_sisgnature " alt="user_pic"
                            width="32px" height="32px" style="float: left; outline: #707070 solid 2px;" onerror="this.src=`{{ asset('/assets/images/default_profile.jpg') }}`"  />

                    <div class="flex-column" style="margin-left: 10px">
                        <h6 style="margin-top: 0; margin-bottom: 3px; font-size: 14px; color: #5B5B5B">
                            {!! CommonFunction::getUserFullName() !!} <i class="fa fa-angle-down  pull-right" style="color: #484848"></i>
                        </h6>
                        <small class="text-muted notify-count">
                            Last login: {{ Session::get('last_login_time') }}
                            <span><i class="fas fa-external-link-square-alt change_url " id="dd" aria-hidden="true" title="Access Log"></i></span>
                        </small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-user" id="dropdown-responsive">
                    @if (Auth::user()->user_type == '5x505' && Auth::user()->user_status == 'active' && Auth::user()->is_approved == 1 && count($accessible_process) > 0)
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#" class="dropdown-submenu-toggle">
                                <i class="fa fa-globe mri-5" aria-hidden="true"></i> BIDA Services <b class="caret"></b>

                            </a>
                            <ul class="dropdown-menu">
                                @if (in_array(102, $accessible_process) || in_array(12, $accessible_process))
                                    <li class="divider d-null"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-submenu-toggle p-lg-2">
                                            <img alt="bida-registration" src="{{ url('assets/fonts_svg/registration.svg') }}" width="20"> 
                                            BIDA Registration <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu sub-menus">
                                            <li class="divider d-null"></li>
                                            @if (in_array(102, $accessible_process))
                                                <li><a href="{{ url('/bida-registration/list/' . \App\Libraries\Encryption::encodeId(102)) }}" class="p-lg-3">
                                                    <img alt="bida registration new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20"> New</a>
                                                </li>
                                            @endif
                                            @if (in_array(12, $accessible_process))
                                                <li class="divider"></li>
                                                <li><a href="{{ url('/bida-registration-amendment/list/' . \App\Libraries\Encryption::encodeId(12)) }}" class="p-lg-3">
                                                    <img alt="bida registration amendment" src="{{ url('assets/fonts_svg/application_amendment.svg') }}" width="20"> Amendment</a>
                                                </li>
                                                <li class="divider d-null"></li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                {{-- BIDA Registration menu end --}}

                                {{-- Office Permission menu start --}}
                                @if (in_array(6, $accessible_process) || in_array(7, $accessible_process) || in_array(8, $accessible_process) || in_array(9, $accessible_process))
                                    <li class="divider d-null"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-submenu-toggle p-lg-2">
                                            <img alt="Office Permission" src="{{ url('assets/fonts_svg/office_permission.svg') }}" width="20"> 
                                            Office Permission <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu sub-menus">
                                            <li class="divider d-null"></li>
                                            @if (in_array(6, $accessible_process))
                                                <li>
                                                    <a href="{{ url('/office-permission-new/list/' . \App\Libraries\Encryption::encodeId(6)) }}" class="p-lg-3">
                                                        <img alt="office permission new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20"> New
                                                    </a>
                                                </li>
                                                <li class="divider d-null"></li>
                                            @endif
                                            @if (in_array(7, $accessible_process))
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="{{ url('/office-permission-extension/list/' . \App\Libraries\Encryption::encodeId(7)) }}" class="p-lg-3">
                                                        <img alt="office permission extension" src="{{ url('assets/fonts_svg/application_extension.svg') }}" width="20"> Extension
                                                    </a>
                                                </li>
                                                <li class="divider d-null"></li>
                                            @endif
                                            @if (in_array(8, $accessible_process))
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="{{ url('/office-permission-amendment/list/' . \App\Libraries\Encryption::encodeId(8)) }}" class="p-lg-3">
                                                        <img alt="office permission amendment" src="{{ url('assets/fonts_svg/application_amendment.svg') }}" width="20"> Amendment
                                                    </a>
                                                </li>
                                                <li class="divider d-null"></li>
                                            @endif
                                            @if (in_array(9, $accessible_process))
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="{{ url('/office-permission-cancellation/list/' . \App\Libraries\Encryption::encodeId(9)) }}" class="p-lg-3">
                                                        <img alt="office permission cancellation" src="{{ url('assets/fonts_svg/application_cancellation.svg') }}" width="20"> Cancellation
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                {{-- Project Office menu start --}}
                                @if (in_array(22, $accessible_process) || in_array(23, $accessible_process) || in_array(24, $accessible_process) || in_array(25, $accessible_process))
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-submenu-toggle p-lg-2">
                                            <img alt="Project Office" src="{{ url('assets/fonts_svg/office_permission.svg') }}" width="20"> 
                                            Project Office <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu sub-menus">
                                            <li class="divider d-null"></li>
                                            @if (in_array(22, $accessible_process))
                                                <li>
                                                    <a href="{{ url('/project-office-new/list/' . \App\Libraries\Encryption::encodeId(22)) }}" class="p-lg-3">
                                                        <img alt="Project Office new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20"> New
                                                    </a>
                                                </li>
                                                <li class="divider d-null"></li>
                                            @endif
                                            {{-- @if (in_array(23, $accessible_process))
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="{{ url('/project-office-extension/list/' . \App\Libraries\Encryption::encodeId(23)) }}" class="p-lg-3">
                                                        <img alt="Project Office extension" src="{{ url('assets/fonts_svg/application_extension.svg') }}" width="20"> Extension
                                                    </a>
                                                </li>
                                                <li class="divider d-null"></li>
                                            @endif
                                            @if (in_array(24, $accessible_process))
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="{{ url('/project-office-amendment/list/' . \App\Libraries\Encryption::encodeId(24)) }}" class="p-lg-3">
                                                        <img alt="Project Office amendment" src="{{ url('assets/fonts_svg/application_amendment.svg') }}" width="20"> Amendment
                                                    </a>
                                                </li>
                                                <li class="divider d-null"></li>
                                            @endif
                                            @if (in_array(25, $accessible_process))
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="{{ url('/project-office-cancellation/list/' . \App\Libraries\Encryption::encodeId(25)) }}" class="p-lg-3">
                                                        <img alt="Project Office cancellation" src="{{ url('assets/fonts_svg/application_cancellation.svg') }}" width="20"> Cancellation
                                                    </a>
                                                </li>
                                            @endif --}}
                                        </ul>
                                    </li>
                                @endif

                                {{-- Vip Lounge menu start --}}
                                @if (in_array(17, $accessible_process))
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-submenu-toggle p-lg-2"><img alt="vip lounge"
                                            src="{{ url('assets/fonts_svg/visa_recommendation.svg') }}" width="20"> VIP/CIP Lounge <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu sub-menus">
                                            <li class="divider d-null"></li>
                                            <li>
                                                <a href="{{ url('/vip-lounge/list/' . \App\Libraries\Encryption::encodeId(17)) }}" class="p-lg-3">
                                                    <img alt="vip lounge new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20"> New
                                                </a>
                                            </li>
                                            <li class="divider d-null"></li>
                                        </ul>
                                    </li>
                                @endif
                                {{-- Vip Lounge menu End --}}

                                {{-- Visa Recommendation menu start --}}
                                @if (in_array(1, $accessible_process) || in_array(10, $accessible_process))
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-submenu-toggle p-lg-2"><img alt="visa recommendation"
                                            src="{{ url('assets/fonts_svg/visa_recommendation.svg') }}"
                                            width="20"> Visa Recommendation <b class="caret"></b></a>
                                        <ul class="dropdown-menu sub-menus">
                                            <li class="divider d-null"></li>
                                            @if (in_array(1, $accessible_process))
                                                <li>
                                                    <a href="{{ url('/visa-recommendation/list/' . \App\Libraries\Encryption::encodeId(1)) }}" class="p-lg-3">
                                                        <img alt="visa recommendation new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20"> New
                                                    </a>
                                                </li>
                                                <li class="divider"></li>
                                            @endif
                                            @if (in_array(10, $accessible_process))
                                                <li>
                                                    <a href="{{ url('/visa-recommendation-amendment/list/' . \App\Libraries\Encryption::encodeId(10)) }}" class="p-lg-3">
                                                        <img alt="visa recommendation amendment" src="{{ url('assets/fonts_svg/application_amendment.svg') }}" width="20"> Amendment
                                                    </a>
                                                </li>
                                                <li class="divider d-null"></li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                {{-- Visa Recommendation menu end --}}

                                {{-- Waiver Condition 7 & 8 menu start --}}
                                @if (in_array(19, $accessible_process) || in_array(20, $accessible_process))
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-submenu-toggle p-lg-2"><img alt="waiver"
                                            src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20"> Waiver <b class="caret"></b></a>
                                        <ul class="dropdown-menu sub-menus">
                                            <li class="divider d-null"></li>
                                            @if (in_array(19, $accessible_process))
                                                <li>
                                                    <a href="{{ url('/waiver-condition-7/list/' . \App\Libraries\Encryption::encodeId(19)) }}" class="p-lg-3">
                                                        <img alt="waiver condition7" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20"> Condition No 7
                                                    </a>
                                                </li>
                                                <li class="divider d-null"></li>
                                            @endif
                                            @if (in_array(20, $accessible_process))
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="{{ url('/waiver-condition-8/list/' . \App\Libraries\Encryption::encodeId(20)) }}" class="p-lg-3">
                                                        <img alt="waiver condition8" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20"> Condition No 8
                                                    </a>
                                                </li>
                                                <li class="divider d-null"></li>
                                            @endif
                                        </ul>
                                    </li>
                                    <li class="divider d-null"></li>
                                @endif
                                {{-- Waiver Condition 7 & 8 menu end --}}

                                {{-- Import Permission menu start --}}
                                @if (in_array(21, $accessible_process))
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-submenu-toggle p-lg-2">
                                            <img alt="import permission" src="{{ url('assets/fonts_svg/application_amendment.svg') }}" width="20"> Import Permission <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu sub-menus">
                                            <li class="divider d-null"></li>
                                            <li>
                                                <a href="{{ url('/import-permission/list/' . \App\Libraries\Encryption::encodeId(21)) }}" class="p-lg-3">
                                                    <img alt="import permission new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20"> New
                                                </a>
                                            </li>
                                            <li class="divider d-null"></li>
                                        </ul>
                                    </li>
                                @endif

                                {{-- Import Permission menu end --}}

                                {{-- Work Permit menu start --}}
                                @if (in_array(2, $accessible_process) || in_array(3, $accessible_process) || in_array(4, $accessible_process) || in_array(5, $accessible_process))
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-submenu-toggle p-lg-2">
                                            <img alt="work permit" src="{{ url('assets/fonts_svg/work_permit.svg') }}" width="20"> Work Permit <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu sub-menus">
                                            <li class="divider d-null"></li>
                                            <li>
                                                <a href="{{ url('/work-permit-new/list/' . \App\Libraries\Encryption::encodeId(2)) }}" class="p-lg-3">
                                                    <img alt="work permit new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20"> New
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="{{ url('/work-permit-extension/list/' . \App\Libraries\Encryption::encodeId(3)) }}" class="p-lg-3" >
                                                    <img alt="work permit extension" src="{{ url('assets/fonts_svg/application_extension.svg') }}" width="20"> Extension
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="{{ url('/work-permit-amendment/list/' . \App\Libraries\Encryption::encodeId(4)) }}" class="p-lg-3">
                                                    <img alt="work permit amendment" src="{{ url('assets/fonts_svg/application_amendment.svg') }}" width="20"> Amendment
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="{{ url('/work-permit-cancellation/list/' . \App\Libraries\Encryption::encodeId(5)) }}" class="p-lg-3" >
                                                    <img alt="work permit cancellation" src="{{ url('assets/fonts_svg/application_cancellation.svg') }}" width="20"> Cancellation
                                                </a>
                                            </li>
                                            <li class="divider d-null"></li>
                                        </ul>
                                    </li>
                                @endif
                                {{-- Remittance Menu Start --}}
                                @if (in_array(11, $accessible_process))
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-submenu-toggle p-lg-2"><img alt="remittance" src="{{ url('assets/fonts_svg/ramittance.svg') }}" width="20"> Remittance <b class="caret"></b></a>
                                        <ul class="dropdown-menu sub-menus">
                                            <li class="divider d-null"></li>
                                            <li>
                                                <a href="{{ url('/remittance-new/list/' . \App\Libraries\Encryption::encodeId(11)) }}" class="p-lg-3">
                                                    <img alt="remittance new" src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                    width="20"> New</a>
                                            </li>
                                            <li class="divider d-null"></li>
                                        </ul>
                                    </li>
                                    <li class="divider"></li>
                                @endif
                                {{-- Remittance Menu end --}}


                                {{-- IRC recommendation Menu Start --}}
                                @if (in_array(13, $accessible_process) ||
                                in_array(14, $accessible_process) ||
                                in_array(15, $accessible_process) ||
                                in_array(16, $accessible_process))
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#" class="dropdown-submenu-toggle p-lg-2"><img alt="irc recommendation"
                                            src="{{ url('assets/fonts_svg/1st_adhoc.svg') }}" width="20"> IRC Recommendation <b class="caret"></b></a>
                                        <ul class="dropdown-menu sub-menus">
                                            <li class="divider d-null"></li>
                                            <li>
                                                <a href="{{ url('/irc-recommendation-new/list/' . Encryption::encodeId(13)) }}" class="p-lg-3">
                                                    <img alt="1st Adhoc" src="{{ url('assets/fonts_svg/1st_adhoc.svg') }}" width="20"> 1st Adhoc</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="{{ url('/irc-recommendation-second-adhoc/list/' . Encryption::encodeId(14)) }}" class="p-lg-3">
                                                    <img alt="2nd Adhoc" src="{{ url('assets/fonts_svg/2nd_adhoc.svg') }}" width="20"> 2nd Adhoc
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="{{ url('/irc-recommendation-third-adhoc/list/' . Encryption::encodeId(15)) }}" class="p-lg-3">
                                                    <img alt="3rd Adhoc" src="{{ url('assets/fonts_svg/3rd_adhoc.svg') }}" width="20"> 3rd Adhoc
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="{{ url('/irc-recommendation-regular/list/' . Encryption::encodeId(16)) }}" class="p-lg-3">
                                                    <img alt="Regular" src="{{ url('assets/fonts_svg/3rd_adhoc.svg') }}" width="20"> Regular</a>
                                            </li>
                                            <li class="divider d-null"></li>
                                        </ul>
                                    </li>
                                @endif
                                <li class="divider d-null"></li>
                            </ul>
                        </li>
                        <li class="divider"></li>
                    @endif

                    @if (Auth::user()->user_type == '1x101' || Auth::user()->user_type == '2x202' || Auth::user()->delegate_to_user_id == 0)
                        <li>
                            <a href="{{ url('users/profileinfo') }}"><i class="fa fa-user fa-fw"></i> My Profile</a>
                        </li>
                        <li class="divider"></li>
                    @endif

                    @if(Auth::user()->user_status == 'active' && Auth::user()->is_approved == 1)
                        @if (in_array(Auth::user()->user_type, ['1x101', '1x102', '2x202', '5x505']))
                            <li>
                                <a class="nav-link {{ Request::is('company-association/list') ? 'active' : '' }}"
                                    href="{{ url('/company-association/list') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Company association" src="{{ url('assets/fonts_svg/company_association.svg') }}">
                                    </div>
                                    Company association
                                </a>
                            </li>
                            <li class="divider"></li>
                        @endif

                        @if (Auth::user()->user_type == '5x505')
                            <li>
                                <a class="nav-link {{ Request::is('company-association/switch-company') ? 'active' : '' }}"
                                    href="{{ url('company-association/switch-company') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Switch Company" src="{{ url('assets/fonts_svg/application_amendment.svg') }}">
                                    </div>
                                    Switch Company
                                </a>
                            </li>
                            <li class="divider"></li>
                        @endif

                        @if (in_array(Auth::user()->user_type, ['1x101', '5x505']) || (Auth::user()->user_type == '4x404' && !in_array(20, $user_desk_ids)))
                            <li>
                                <a class="nav-link {{ Request::is('users/*') ? 'active' : '' }}"
                                    href="{{ url('/users/lists') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Users" src="{{ url('assets/fonts_svg/users.svg') }}">
                                    </div>
                                    Users
                                </a>
                            </li>
                            <li class="divider"></li>
                        @endif
                    @endif

                    <li>
                        <a class="nav-link {{ Request::is('users/*') ? 'active' : '' }}" href="/notification-all">
                            <div class="nav-link-icon">
                                {{-- <i class="fa fa-bell" style="color: #FFBB00; font-size: 18px"></i> --}}
                                <i class="fa fa-bell" style="font-size: 18px"></i>
                            </div>
                            Notification
                        </a>
                    </li>
                    <li class="divider"></li>
                    {{-- @if (Auth::user()->user_type == '5x505' && Auth::user()->user_status == 'active' && Auth::user()->is_approved == 1)
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#" class="dropdown-submenu-toggle">
                                <i class="fas fa-book-reader  mri-5"></i> Bida Training <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="divider d-null"></li>
                                <li class="dropdown-submenu">
                                    <a href="{{ url('training/upcoming-course') }}" class="p-lg-2">
                                        <i class="fas fa-chalkboard-teacher  mri-5"></i> 
                                        Upcoming Course 
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li class="dropdown-submenu">
                                    <a href="{{ url('training/purchase-course') }}" class="p-lg-2">
                                        <i class="fas fa-shopping-basket  mri-5"></i> 
                                        Purchsed Course 
                                    </a>
                                </li>
                                <li class="divider d-null"></li>
                            </ul>
                        </li>
                        <li class="divider"></li>
                    @endif --}}

                    @if (Auth::user()->social_login == 2)
                        <li>
                            <a href="{{ url('osspid/logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ url('logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </li>
                    @endif
                </ul>
            </li>
    </ul>
<script>
    // Close all submenus when clicking outside the menu
    $(document).on("click", function(e) {
        if (!$(e.target).closest('.dropdown-submenu').length) {
            $('.dropdown-submenu .dropdown-menu').hide();
        }
    });
    // Make Dropdown Submenus possible
    $('.dropdown-submenu a.dropdown-submenu-toggle').on("click", function(e) {
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
        $(this).find('b.caret').toggleClass('rotate-icon');

        var $submenu = $(this).next('.dropdown-menu');

        // Close all other open submenus
        $('.dropdown-submenu-toggle').not(this).each(function() {
            $(this).next('.dropdown-menu.sub-menus').hide();
        });
    });

    // Clear Submenu Dropdowns on hidden event
    $('#bs-navbar-collapse-1').on('hidden.bs.dropdown', function() {
        $('.navbar-nav .dropdown-submenu ul.dropdown-menu').removeAttr('style');
    });


    $("#global-search-submit").click(function(e) {

        var search_date = $("input[name=search_by_keyword]").val();

        if (search_date == '') {
            e.preventDefault();

            $("#dropdown_search_icon").removeClass('fa-times');
            $("#dropdown_search_icon").addClass('fa-search');
            $("#dropdownSearchBox").closest('li').removeClass('open');
            $("#dropdownSearchBox").attr('aria-expanded', 'false');
            localStorage.removeItem("dropdownSearchBox");
            e.preventDefault();
        } else {
            $("#global-search").submit();
        }
    });

    $(document).ready(function() {


        var checkSearchSession = localStorage.getItem("dropdownSearchBox");
        if (checkSearchSession) {
            $("#dropdown_search_icon").removeClass('fa-search');
            $("#dropdown_search_icon").addClass('fa-times');
            $("#dropdownSearchBox").closest('li').addClass('open');
            $("#dropdownSearchBox").attr('aria-expanded', 'true');
        }

        $("#dropdownSearchBox").click(function() {

            var getSearchSession = localStorage.getItem("dropdownSearchBox");
            if (getSearchSession) {
                $("#dropdown_search_icon").removeClass('fa-times');
                $("#dropdown_search_icon").addClass('fa-search');
                $(this).closest('li').removeClass('open');
                $(this).attr('aria-expanded', 'false');
                localStorage.removeItem("dropdownSearchBox");
            } else {
                localStorage.setItem("dropdownSearchBox", "open");
                $("#dropdown_search_icon").removeClass('fa-search');
                $("#dropdown_search_icon").addClass('fa-times');
                $(this).closest('li').addClass('open');
                $(this).attr('aria-expanded', 'true');
            }
        });


        $('.change_url').click(function() {
            var title = $(this).attr('title');
            if (title == 'Access Log') {
                $('#dropdown-responsive').addClass('hidden');
                document.location.href = '{{ '/users/profileinfo#tab_5' }}';
                return false;

            } else {
                $('#dropdown-responsive').removeClass('hidden');
            }
        })
    });
</script>


<script>
    $(document).ready(function() {

        //        notificationCount();

        function notificationCount() {
            $.ajax({
                url: '{{ url('/notifications/count') }}',
                type: "get",
                success: function(data) {

                    if (data.length != 0) {
                        $("#nCount").html('<span class="badge" style="background: #731504"> ' + data
                            .length + '</span>');
                    } else {
                        $("#nCount").html("");
                    }
                }
            })
        }
        // if ($('#checkClass').hasClass('open')) {
        //     $("#addButton").addClass("d-none");
        // }
        // //  notification();
        // $('#dropdownSearchBox').click(function() {
        //     $("#addButton").toggleClass("d-none");

        // })

        function notification() {
            $.ajax({
                url: '{{ url('/notifications/show') }}',
                type: "get",
                success: function(data) {
                    $("#notific").append('<div class="check" id="test"> </div>');
                    $.each(data, function(key, value) {
                        if (value.web_notification == 0) {
                            $("#test").append(
                                '<div style="height:56px;"><a href="/single-notification/' +
                                value.id + '" class="notiRead" id="' + value.id +
                                '" onclick ="readNotific(id)"><div><i class="fa fa-paper-plane fa-fw"></i><b> ' +
                                value.email_subject +
                                '</b><span class="pull-right text-muted small"> ' +
                                moment(value.created_at).fromNow() +
                                '</span></div></a></li></div><li class="divider"></li>');
                        } else {
                            $("#test").append(
                                '<div style="background: #D9DFDC;height:56px;"><a href="/single-notification/' +
                                value.id + '" id="' + value.id +
                                '" onclick ="readNotific(id)"><div><i class="fa fa-paper-plane fa-fw"></i> ' +
                                value.email_subject +
                                '<span class="pull-right text-muted small"> ' + moment(
                                    value.created_at).fromNow() +
                                ' </span></div></a></li></div> <li class="divider"></li>'
                            );
                        }
                    });
                    if (data == "") {
                        $("#notific").removeClass('dropdown-height');
                        $("#test").removeClass('check');
                    }
                    $("#notific").append(
                        '<li><a class="text-center; " href="/notification-all">  <strong> See All Notification </strong> <i class="fa fa-angle-right"></i></a> </li>'
                    )
                }
            })
        }


        $("#notificHead").click(function() {
            return false;
        });

    });

    function enterFullscreen() {
        document.documentElement.requestFullscreen();
        localStorage.setItem('fullscreenState', 'true');
    }

    function exitFullscreen() {
        document.exitFullscreen();
        localStorage.setItem('fullscreenState', 'false');
    }

    document.getElementById('fullscreen-toggle').addEventListener('click', function(event) {
        if (document.fullscreenElement) {
            exitFullscreen();
        } else {
            enterFullscreen();
        }
        event.preventDefault();
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('fullscreenState') === 'true') {
            enterFullscreen();
        }
    });

    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement) {
            localStorage.setItem('fullscreenState', 'false');
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('#searchField').on('input', function() {
            var term = $(this).val().trim();
            if (term.length >= 3) {
                $.ajax({
                    url: '{{ url('/search-data') }}',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        find: term
                    },
                    success: function(data) {
                        var groupedData = {};
                        $('#searchResults').empty();
                        if (Object.keys(data).length > 0) {
                            for (var groupName in data) {
                                if(groupName == 'tracking_no' && data['tracking_no'].length > 0){
                                    $('#searchResults').append('<div class="group-header"><b>Tracking No</b></div>');
                                }
                                else if(groupName == 'company' && data['company'].length > 0){
                                    $('#searchResults').append('<div class="group-header"><b>Company Name</b></div>');
                                }
                                else if(groupName == 'menu' && data['menu'].length > 0){
                                    $('#searchResults').append('<div class="group-header"><b>Menu</b></div>');
                                }
                                if(groupName != 'menu'){
                                    data[groupName].forEach(function(item) {
                                    $('#searchResults').append(
                                        '<div class="search-item"><p>' + item +
                                        '</p></div>');
                                    });
                                }
                                else{
                                    data[groupName].forEach(function(item) {
                                    $('#searchResults').append(
                                        '<div class="search-item" data-url="' +
                                        item.url + '"><p>' + item.name +
                                        '</p></div>');
                                    });
                                }
                                if(data['tracking_no'].length == 0 && data['company'].length == 0 && data['menu'].length == 0 ){
                                    $('#searchResults').html(
                                        '<div class="no-data">No data found</div>'
                                    );
                                }
                                
                            }
                            $('#searchResults').show();
                        } else {
                            $('#searchResults').html(
                                '<div class="no-data">No data found</div>'
                            );
                            $('#searchResults').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#searchResults').hide();
                        console.error('AJAX Error: ', error);
                        console.error('Status: ', status);
                        console.error('Response: ', xhr.responseText);

                        // Optional: Send error details to server for logging
                        $.ajax({
                            url: '{{ url('/log-error') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                error: error,
                                status: status,
                                response: xhr.responseText
                            },
                            success: function(response) {
                                console.log('Error logged successfully.');
                            },
                            error: function() {
                                console.error('Failed to log error on the server.');
                            }
                        });
                    }
                });
            } else {
                $('#searchResults').hide();
            }
        });

        $(document).on('click', '.search-item', function() {
            var url = $(this).data('url');
            if (url !== undefined) {
                var baseUrl = window.location.origin;
                var url2 = baseUrl + '/' + url;
                window.location = url2;
            } else {
                $('#searchField').val($(this).text());
                $('#global-search-submit').click();
            }
        });


        window.onclick = function(event) {
            var dropdown = document.getElementById("searchResults");
            if (event.target) {
                if (!event.target.matches('#searchResults') && !event.target.matches('.searchField')) {
                    var dropdowns = dropdown.getElementsByClassName("searchField");
                    $('#searchResults').hide();
                }
            } else {
                $('#searchResults').hide();
            }
        }
    });
</script>