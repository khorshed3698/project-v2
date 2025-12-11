<?php
$user_type = Auth::user()->user_type;
$type = explode('x', $user_type);
$Segment = Request::segment(3);
$user_desk_ids = \App\Libraries\CommonFunction::getUserDeskIds();

$is_eligibility = 0;
if ($user_type == '5x505') {
    $is_eligibility = \App\Libraries\CommonFunction::checkEligibility();
}

$accessible_process = [];
if (\Illuminate\Support\Facades\Session::has('accessible_process')) {
    $accessible_process = \Illuminate\Support\Facades\Session::get('accessible_process');
}
?>

<div class="navbar-default sidebar sidebar-color" role="navigation" id="MainNav" style="margin-top:66px;">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="{{ (Request::is('/dashboard') ? 'active' : '') }}">
                <a href="{{ url ('/dashboard') }}" class="nav-link">
                    <div class="nav-link-icon">
                        <img alt="Dashboard" src="{{url('assets/fonts_svg/dashboard.svg')}}">
                    </div>
                    Dashboard
                </a>
            </li>

            {{-- if user is active and approved--}}
            @if(Auth::user()->user_status == 'active' && Auth::user()->is_approved == 1)

                {{--                @if(($type[0] == 5 && $is_eligibility) || in_array($type[0], [1,2,4,9]))--}}
                @if($type[0] == 5 && $is_eligibility)

                    <li class="{{ (Request::is('/dashboard/new-application') ? 'active' : '') }}">
                        <a href="{{ url ('/dashboard/new-application') }}" class="nav-link">
                            <div class="nav-link-icon">
                                <img alt="Dashboard" src="{{url('assets/fonts_svg/application_new.svg')}}">
                            </div>
                            New application
                        </a>
                    </li>

                    {{-- <li class="{{ (( Request::is('licence-applications/*')  || Request::is('process/licence-applications/*')) ? 'active' : '')  }}">

                        <a class="nav-link @if (Request::is('licence-applications/*') || Request::is('process/licence-applications/*')) active @endif"
                           href="{{ url ('licence-applications/app-home') }}">
                            <div class="nav-link-icon">
                                <img alt="Business Licence" src="{{url('assets/fonts_svg/business_licence.svg')}}">
                            </div>
                            Business Licence
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>



                        <ul class="nav nav-second-level">
                            <li>
                                <a class="nav-link @if (Request::is('licence-applications/*') || Request::is('process/licence-applications/*'))  active @endif"
                                   href="{{URL::to('licence-applications/individual-licence')}}">
                                    <div class="nav-link-icon">
                                        <img alt="Individual Licence" src="{{url('assets/fonts_svg/individual_licence.svg')}}">
                                    </div>
                                    Individual Licence
                                </a>
                            </li>

                        </ul>
                    </li> --}}
                @endif


                {{-- BIDA Registration menu start --}}
                @if(in_array(102, $accessible_process) || in_array(12, $accessible_process))
                    <li class="{{ ((
                        Request::is('bida-registration/*') || Request::is('process/bida-registration/*') ||
                        Request::is('bida-registration-amendment/*') || Request::is('process/bida-registration-amendment/*')
                    ) ? 'active' : '')  }}">
                        <a class="nav-link" href="{{ url ('/bida-registration') }}">
                            <div class="nav-link-icon">
                                <img alt="bida-registration" src="{{url('assets/fonts_svg/registration.svg')}}">
                            </div>
                            BIDA Registration
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>
                        <ul class="nav nav-second-level">
                            @if(in_array(102, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('bida-registration/*') || Request::is('process/bida-registration/*'))  active @endif"
                                       href="{{ url ('/bida-registration/list/'.\App\Libraries\Encryption::encodeId(102)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="bida registration new" src="{{url('assets/fonts_svg/application_new.svg')}}">
                                        </div>
                                        New
                                    </a>

                                </li>
                            @endif
                            @if(in_array(12, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('bida-registration-amendment/*') || Request::is('process/bida-registration-amendment/*'))  active @endif"
                                       href="{{ url ('/bida-registration-amendment/list/'.\App\Libraries\Encryption::encodeId(12)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="bida registration amendment" src="{{url('assets/fonts_svg/application_amendment.svg')}}">
                                        </div>
                                        Amendment
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                {{-- BIDA Registration menu end --}}

                {{-- Office Permission menu start --}}
                @if(in_array(6, $accessible_process) || in_array(7, $accessible_process) || in_array(8, $accessible_process) || in_array(9, $accessible_process))
                    <li class="{{ ((
                            Request::is('office-permission/*') || Request::is('process/office-permission/*') ||
                            Request::is('office-permission-new/*') || Request::is('process/office-permission-new/*') ||
                            Request::is('office-permission-extension/*') || Request::is('process/office-permission-extension/*') ||
                            Request::is('office-permission-amendment/*') || Request::is('process/office-permission-amendment/*') ||
                            Request::is('office-permission-cancellation/*') || Request::is('process/office-permission-cancellation/*')
                        ) ? 'active' : '')  }}">
                        <a class="nav-link" href="{{ url ('/office-permission') }}">
                            <div class="nav-link-icon">
                                <img alt="Office Permission" src="{{url('assets/fonts_svg/office_permission.svg')}}">
                            </div>
                            Office Permission
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>
                        <ul class="nav nav-second-level">
                            @if(in_array(6, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('office-permission-new/*') || Request::is('process/office-permission-new/*'))  active @endif"
                                       href="{{ url ('/office-permission-new/list/'.\App\Libraries\Encryption::encodeId(6)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="office permission new" src="{{url('assets/fonts_svg/application_new.svg')}}">
                                        </div>
                                        New
                                    </a>
                                </li>
                            @endif
                            @if(in_array(7, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('office-permission-extension/*') || Request::is('process/office-permission-extension/*'))  active @endif"
                                       href="{{ url ('/office-permission-extension/list/'.\App\Libraries\Encryption::encodeId(7)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="office permission extension" src="{{url('assets/fonts_svg/application_extension.svg')}}">
                                        </div>
                                        Extension
                                    </a>
                                </li>
                            @endif

                            @if(in_array(8, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('office-permission-amendment/*') || Request::is('process/office-permission-amendment/*'))  active @endif"
                                       href="{{ url ('/office-permission-amendment/list/'.\App\Libraries\Encryption::encodeId(8)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="office permission amendment" src="{{url('assets/fonts_svg/application_amendment.svg')}}">
                                        </div>
                                        Amendment
                                    </a>
                                </li>
                            @endif

                            @if(in_array(9, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('office-permission-cancellation/*') || Request::is('process/office-permission-cancellation/*'))  active @endif"
                                       href="{{ url ('/office-permission-cancellation/list/'.\App\Libraries\Encryption::encodeId(9)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="office permission cancellation" src="{{url('assets/fonts_svg/application_cancellation.svg')}}">
                                        </div> Cancellation
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                {{-- Office Permission menu End --}}

                {{-- Project Office menu start --}}
                @if(in_array(22, $accessible_process) || in_array(23, $accessible_process) || in_array(24, $accessible_process) || in_array(25, $accessible_process))
                    <li class="{{ ((
                            Request::is('project-office/*') || Request::is('process/project-office/*') ||
                            Request::is('project-office-new/*') || Request::is('process/project-office-new/*') ||
                            Request::is('project-office-extension/*') || Request::is('process/project-office-extension/*') ||
                            Request::is('project-office-amendment/*') || Request::is('process/project-office-amendment/*') ||
                            Request::is('project-office-cancellation/*') || Request::is('process/project-office-cancellation/*')
                        ) ? 'active' : '')  }}">
                        <a class="nav-link" href="{{ url ('/project-office') }}">
                            <div class="nav-link-icon">
                                <img alt="Project Office" src="{{url('assets/fonts_svg/office_permission.svg')}}">
                            </div>
                            Project Office
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>
                        <ul class="nav nav-second-level">
                            @if(in_array(22, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('project-office-new/*') || Request::is('process/project-office-new/*'))  active @endif"
                                       href="{{ url ('/project-office-new/list/'.\App\Libraries\Encryption::encodeId(22)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="Project Office new" src="{{url('assets/fonts_svg/application_new.svg')}}">
                                        </div>
                                        New
                                    </a>
                                </li>
                            @endif
                            {{-- @if(in_array(23, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('project-office-extension/*') || Request::is('process/project-office-extension/*'))  active @endif"
                                       href="{{ url ('/project-office-extension/list/'.\App\Libraries\Encryption::encodeId(23)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="Project Office extension" src="{{url('assets/fonts_svg/application_extension.svg')}}">
                                        </div>
                                        Extension
                                    </a>
                                </li>
                            @endif

                            @if(in_array(24, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('project-office-amendment/*') || Request::is('process/project-office-amendment/*'))  active @endif"
                                       href="{{ url ('/project-office-amendment/list/'.\App\Libraries\Encryption::encodeId(24)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="Project Office amendment" src="{{url('assets/fonts_svg/application_amendment.svg')}}">
                                        </div>
                                        Amendment
                                    </a>
                                </li>
                            @endif

                            @if(in_array(25, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('project-office-cancellation/*') || Request::is('process/project-office-cancellation/*'))  active @endif"
                                       href="{{ url ('/project-office-cancellation/list/'.\App\Libraries\Encryption::encodeId(25)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="Project Office cancellation" src="{{url('assets/fonts_svg/application_cancellation.svg')}}">
                                        </div> Cancellation
                                    </a>
                                </li>
                            @endif --}}
                        </ul>
                    </li>
                @endif
                {{-- Project Office menu End --}}

                {{-- Vip Lounge menu start --}}
                @if(in_array(17, $accessible_process))
                    <li class="{{ ((
                            Request::is('vip-lounge/*') || Request::is('process/vip-lounge/*')
                        ) ? 'active' : '')  }}">
                        <a class="nav-link" href="{{ url ('/vip-lounge') }}">
                            <div class="nav-link-icon">
                                <img alt="vip lounge" src="{{url('assets/fonts_svg/visa_recommendation.svg')}}">
                            </div>
                            VIP/CIP Lounge
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="nav-link @if (Request::is('vip-lounge/*') || Request::is('process/vip-lounge/*'))  active @endif"
                                   href="{{ url ('/vip-lounge/list/'.\App\Libraries\Encryption::encodeId(17)) }}">
                                    <div class="nav-link-icon">
                                        <img alt="vip lounge new" src="{{url('assets/fonts_svg/application_new.svg')}}">
                                    </div> New
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- Vip Lounge menu End --}}

                {{-- Visa Recommendation menu start --}}
                @if(in_array(1, $accessible_process) || in_array(10, $accessible_process))
                    <li class="{{ ((
                            Request::is('visa-recommendation/*') || Request::is('process/visa-recommendation/*') ||
                            Request::is('visa-recommendation-amendment/*') || Request::is('process/visa-recommendation-amendment/*')
                        ) ? 'active' : '')  }}">
                        <a class="nav-link" href="{{ url ('/visa-recommendation') }}">
                            <div class="nav-link-icon">
                                <img alt="Visa Recommendation" src="{{url('assets/fonts_svg/visa_recommendation.svg')}}">
                            </div>
                            Visa Recommendation
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>
                        <ul class="nav nav-second-level">
                            @if(in_array(1, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('visa-recommendation/*') || Request::is('process/visa-recommendation/*'))  active @endif"
                                       href="{{ url ('/visa-recommendation/list/'.\App\Libraries\Encryption::encodeId(1)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="visa recommendation new" src="{{url('assets/fonts_svg/application_new.svg')}}">
                                        </div> New
                                    </a>
                                </li>
                            @endif

                            @if(in_array(10, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('visa-recommendation-amendment/*') || Request::is('process/visa-recommendation-amendment/*'))  active @endif"
                                       href="{{ url ('/visa-recommendation-amendment/list/'.\App\Libraries\Encryption::encodeId(10)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="visa recommendation amendment" src="{{url('assets/fonts_svg/application_amendment.svg')}}">
                                        </div> Amendment
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                {{-- Visa Recommendation menu End --}}

                {{-- Waiver Condition 7 & 8 menu start --}}
                @if(in_array(19, $accessible_process) || in_array(20, $accessible_process))
                    <li class="{{ ((
                            Request::is('waiver-condition-7/*') || Request::is('process/waiver-condition-7/*') ||
                            Request::is('waiver-condition-8/*') || Request::is('process/waiver-condition-8/*')
                        ) ? 'active' : '')  }}">
                        <a class="nav-link" href="{{ url ('/waiver-condition') }}">
                            <div class="nav-link-icon">
                                <img alt="waiver condition 7" src="{{url('assets/fonts_svg/application_new.svg')}}">
                            </div>
                            Waiver
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>
                        <ul class="nav nav-second-level">
                            @if(in_array(19, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('waiver-condition-7/*') || Request::is('process/waiver-condition-7/*'))  active @endif"
                                       href="{{ url ('/waiver-condition-7/list/'.\App\Libraries\Encryption::encodeId(19)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="waiver condition 7" src="{{url('assets/fonts_svg/application_new.svg')}}">
                                        </div> Condition No 7
                                    </a>
                                </li>
                            @endif

                            @if(in_array(20, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('waiver-condition-8/*') || Request::is('process/waiver-condition-8/*'))  active @endif"
                                       href="{{ url ('/waiver-condition-8/list/'.\App\Libraries\Encryption::encodeId(20)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="waiver condition 8" src="{{url('assets/fonts_svg/application_new.svg')}}">
                                        </div> Condition No 8
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                {{-- Waiver Condition 7 & 8 menu End --}}

                {{-- Import Permission menu start --}}
                @if(in_array(21, $accessible_process))
                    <li class="{{ ((
                            Request::is('import-permission/*') || Request::is('process/import-permission/*')
                        ) ? 'active' : '')  }}">
                        <a class="nav-link" href="{{ url ('/import-permission') }}">
                            <div class="nav-link-icon">
                                <img alt="Import Permission" src="{{url('assets/fonts_svg/application_amendment.svg')}}">
                            </div>
                            Import Permission
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="nav-link @if (Request::is('import-permission/*') || Request::is('process/import-permission/*'))  active @endif"
                                   href="{{ url ('/import-permission/list/'.\App\Libraries\Encryption::encodeId(21)) }}">
                                    <div class="nav-link-icon">
                                        <img alt="waiver condition 7" src="{{url('assets/fonts_svg/application_new.svg')}}">
                                    </div> New
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- Work Permit menu start --}}
                @if(in_array(2, $accessible_process) || in_array(3, $accessible_process) || in_array(4, $accessible_process) || in_array(5, $accessible_process))
                    <li class="{{ ((
                            Request::is('work-permit/*') || Request::is('process/work-permit/*') ||
                            Request::is('work-permit-new/*') || Request::is('process/work-permit-new/*') ||
                            Request::is('work-permit-extension/*') || Request::is('process/work-permit-extension/*') ||
                            Request::is('work-permit-amendment/*') || Request::is('process/work-permit-amendment/*') ||
                            Request::is('work-permit-cancellation/*') || Request::is('process/work-permit-cancellation/*')
                        ) ? 'active' : '')  }}">
                        <a class="nav-link" href="{{ url ('/work-permit') }}">

                            <div class="nav-link-icon">
                                <img alt="Work Permit" src="{{url('assets/fonts_svg/work_permit.svg')}}">
                            </div>
                            Work Permit
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>

                        <ul class="nav nav-second-level">
                            @if(in_array(2, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('work-permit-new/*') || Request::is('process/work-permit-new/*'))  active @endif"
                                       href="{{ url ('/work-permit-new/list/'.\App\Libraries\Encryption::encodeId(2)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="work permit new" src="{{url('assets/fonts_svg/application_new.svg')}}">
                                        </div>
                                        New
                                    </a>
                                </li>
                            @endif
                            @if(in_array(3, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('work-permit-extension/*') || Request::is('process/work-permit-extension/*'))  active @endif"
                                       href="{{ url ('/work-permit-extension/list/'.\App\Libraries\Encryption::encodeId(3)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="work permit extension" src="{{url('assets/fonts_svg/application_extension.svg')}}">
                                        </div>
                                        Extension
                                    </a>
                                </li>
                            @endif

                            @if(in_array(4, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('work-permit-amendment/*') || Request::is('process/work-permit-amendment/*'))  active @endif"
                                       href="{{ url ('/work-permit-amendment/list/'.\App\Libraries\Encryption::encodeId(4)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="work permit amendment" src="{{url('assets/fonts_svg/application_amendment.svg')}}">
                                        </div>
                                        Amendment
                                    </a>
                                </li>
                            @endif
                            @if(in_array(5, $accessible_process))
                                <li>
                                    <a class="nav-link @if (Request::is('work-permit-cancellation/*') || Request::is('process/work-permit-cancellation/*'))  active @endif"
                                       href="{{ url ('/work-permit-cancellation/list/'.\App\Libraries\Encryption::encodeId(5)) }}">
                                        <div class="nav-link-icon">
                                            <img alt="work permit cancellation" src="{{url('assets/fonts_svg/application_cancellation.svg')}}">
                                        </div>
                                        Cancellation
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                {{-- Work Permit menu end --}}

                {{-- Security Clearance Menu start --}}
                @if(in_array($type[0], [1,2]) || ($type[0] == 4 && !in_array(20, $user_desk_ids) &&checkUserDeskNone()))
                    <li>
                        <a class="@if (Request::is('security-clearance/*')) active @endif nav-link"
                           href="{{ url ('security-clearance/list')}}">
                            <div class="nav-link-icon">
                                <img alt="security clearance" src="{{url('assets/fonts_svg/security_clearance.svg')}}">
                            </div>
                            Security Clearance
                        </a>
                    </li>

                @endif
                {{-- Security Clearance Menu end --}}

                {{-- Remittance Menu Start --}}
                @if(in_array(11, $accessible_process))
                    <li class="{{ ((
                            Request::is('remittance/*') || Request::is('process/remittance/*') ||
                            Request::is('remittance-new/*') || Request::is('process/remittance-new/*')) ? 'active' : '')  }}">
                        <a class="nav-link" href="{{ url ('/remittance') }}">
                            <div class="nav-link-icon">
                                <img alt="remittance approval" src="{{url('assets/fonts_svg/ramittance_approval.svg')}}">
                            </div>
                            Remittance Approval
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>

                        <ul class="nav nav-second-level">
                            <li>
                                <a class="nav-link @if (Request::is('remittance-new/*') || Request::is('process/remittance-new/*'))  active @endif"
                                   href="{{ url ('/remittance-new/list/'.\App\Libraries\Encryption::encodeId(11)) }}">
                                    <div class="nav-link-icon">
                                        <img alt="Remittance" src="{{url('assets/fonts_svg/ramittance.svg')}}">
                                    </div>
                                    Remittance
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- Remittance Menu End --}}

                {{-- IRC recommendation Menu Start --}}
                @if(in_array(13, $accessible_process) || in_array(14, $accessible_process) || in_array(15, $accessible_process) || in_array(16, $accessible_process))
                    <li class="{{ ((
                            Request::is('irc-recommendation-new/*') || Request::is('process/irc-recommendation-new/*') ||
                            Request::is('irc-recommendation-second-adhoc/*') || Request::is('process/irc-recommendation-second-adhoc/*') ||
                            Request::is('irc-recommendation-third-adhoc/*') || Request::is('process/irc-recommendation-third-adhoc/*') ||
                            Request::is('irc-recommendation-regular/*') || Request::is('process/irc-recommendation-regular/*')
                            ) ? 'active' : '')  }}">
                        <a href="{{ url ('/irc-recommendation') }}" class="nav-link">
                            <div class="nav-link-icon">
                                <img alt="Work Permit" src="{{url('assets/fonts_svg/irc_recomandation.svg')}}">
                            </div>
                            IRC Recommendation
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>

                        <ul class="nav nav-second-level">
                            <li>
                                <a class="nav-link @if (Request::is('irc-recommendation-new/*') || Request::is('process/irc-recommendation-new/*'))  active @endif"
                                   href="{{ url ('/irc-recommendation-new/list/'.Encryption::encodeId(13)) }}">
                                    <div class="nav-link-icon">
                                        <img alt="1st Adhoc" src="{{url('assets/fonts_svg/1st_adhoc.svg')}}">
                                    </div>
                                    1st Adhoc
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if (Request::is('irc-recommendation-second-adhoc/*') || Request::is('process/irc-recommendation-second-adhoc/*'))  active @endif"
                                   href="{{ url ('/irc-recommendation-second-adhoc/list/'.Encryption::encodeId(14)) }}">
                                    <div class="nav-link-icon">
                                        <img alt="2nd Adhoc" src="{{url('assets/fonts_svg/2nd_adhoc.svg')}}">
                                    </div>
                                    2nd Adhoc
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if (Request::is('irc-recommendation-third-adhoc/*') || Request::is('process/irc-recommendation-third-adhoc/*'))  active @endif"
                                   href="{{ url ('/irc-recommendation-third-adhoc/list/'.Encryption::encodeId(15)) }}">
                                    <div class="nav-link-icon">
                                        <img alt="3rd Adhoc" src="{{url('assets/fonts_svg/3rd_adhoc.svg')}}">
                                    </div>
                                    3rd Adhoc
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if (Request::is('irc-recommendation-regular/*') || Request::is('process/irc-recommendation-regular/*'))  active @endif"
                                   href="{{ url ('/irc-recommendation-regular/list/'.Encryption::encodeId(16)) }}">
                                    <div class="nav-link-icon">
                                        <img alt="Regular" src="{{url('assets/fonts_svg/3rd_adhoc.svg')}}">
                                    </div>
                                    Regular
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- IRC recommendation Menu End --}}

                {{-- Start Payment and IPN Menu For System Admin, IT help desk and Programmer --}}
                @if(in_array($type[0], [1,2,14]))
                    <li class="{{ (Request::is('/spg') ? 'active' : '') }}">
                        <a class="nav-link" href="{{ url ('/spg') }}">
                            <div class="nav-link-icon">
                                <img alt="Payment" src="{{url('assets/fonts_svg/payment.svg')}}">
                            </div>
                            Payment
                        </a>
                    </li>
                    <li class="{{ (Request::is('/spg/stack-holder') ? 'active' : '') }}">
                        <a class="nav-link" href="{{ url ('/spg/stack-holder') }}">
                            <div class="nav-link-icon">
                                <img alt="payment stakeholder" src="{{url('assets/fonts_svg/payment_stakholder.svg')}}">
                            </div>Payment Stakeholder
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ (Request::is('/ipn') ? 'active' : '') }}" href="{{ url ('/ipn') }}">
                            <div class="nav-link-icon">
                                <img alt="ipn" src="{{url('assets/fonts_svg/ipn.svg')}}">
                            </div>
                            IPN
                        </a>
                    </li>
                @endif
                {{-- End Payment and IPN Menu For System Admin, IT help desk and Programmer --}}

                {{-- Report Menu start --}}
                @if(in_array($type[0], [1,2,3,8,14,15]) || ($type[0] == 4 && checkUserDeskNone()))
                    <li>
                        <a class="nav-link @if (Request::is('reports') || Request::is('reports/*')) active @endif"
                           href="{{ url ('/reports ')}}">
                            <div class="nav-link-icon">
                                <img alt="Report" src="{{url('assets/fonts_svg/report.svg')}}">
                            </div>
                            Report
                        </a>
                    </li>
                @endif
                {{-- Report Menu end --}}

                {{-- Board Meeting Menu start --}}
                @if(in_array($type[0], [1,13]) || ($type[0] == 4 && !in_array(20, $user_desk_ids) &&checkUserDeskNone()) || in_array(6, $user_desk_ids))
                    <li class="{{ ((Request::is('board-meting/*') ) ? 'active' : '')  }}">
                        <a class="nav-link" href="{{ url ('/board-meting') }}">
                            <div class="nav-link-icon">
                                <img alt="Meeting" src="{{url('assets/fonts_svg/meeting.svg')}}">
                            </div>
                            Meeting
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>

                        <ul class="nav nav-second-level">
                            <li>
                                <a class="nav-link @if (Request::is('board-meting/lists') || Request::is('board-meting/agenda/list/*'))  active @endif"
                                   href="{{ url ('board-meting/lists') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Open Meeting" src="{{url('assets/fonts_svg/open_meeting.svg')}}">
                                    </div>Open Meeting
                                </a>
                            </li>

                            <li>
                                <a class="nav-link @if (Request::is('board-meting/new-board-meting') || Request::is('board-meting/committee/member-edit/*')|| Request::is('board-meting/committee/*'))  active @endif"
                                   href="{{ url ('board-meting/new-board-meting') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Create Meeting" src="{{url('assets/fonts_svg/create_meeting.svg')}}">
                                    </div>
                                    Create Meeting
                                </a>
                            </li>
                        </ul>
                    </li>

                @endif
                {{-- Board Meeting Menu end --}}

                {{-- Start Company Association Menu for System Admin, Programmer and Applicant --}}
                @if(in_array($type[0], [1,2,5]))
                    <li>
                        <a class="nav-link {{ (Request::is('company-association/list')  ? 'active' : '') }}"
                           href="{{ url ('/company-association/list') }}">
                            <div class="nav-link-icon">
                                <img alt="Company association" src="{{url('assets/fonts_svg/company_association.svg')}}">
                            </div>
                            Company association
                        </a>
                    </li>
                @endif

                {{-- @if(in_array($type[0], [5]))
                    <li>
                        <a class="nav-link {{ (Request::is('company-association/switch-company')  ? 'active' : '') }}"
                           href="{{ url ('company-association/switch-company') }}">
                            <div class="nav-link-icon">
                                <img alt="Switch Company" src="{{url('assets/fonts_svg/switch_company.svg')}}">
                            </div>
                            Switch Company
                        </a>
                    </li>
                @endif --}}

                {{-- Start Company Association Menu for System Admin, Programmer and Applicant --}}


                {{-- Start Users Menu for System Admin and Programmer --}}
                {{-- @if(in_array($type[0], [1,5]) || ($type[0] == 4 && !in_array(20, $user_desk_ids)))
                    <li>
                        <a class="nav-link {{ (Request::is('users/*') ? 'active' : '') }}" href="{{ url ('/users/lists') }}">
                            <div class="nav-link-icon">
                                <img alt="Users" src="{{url('assets/fonts_svg/users.svg')}}">
                            </div>
                            Users
                        </a>
                    </li>
                @endif --}}
                {{--                @if(in_array($type[0], [1,5]) || ($type[0] == 4 && !in_array(20, $user_desk_ids) &&checkUserDeskNone()))--}}
                {{--                    <li>--}}
                {{--                        <a class="nav-link {{ (Request::is('external-test/*') ? 'active' : '') }}" href="{{ url ('/external-test/list') }}">--}}
                {{--                            <div class="nav-link-icon">--}}
                {{--                                <img alt="Users" src="{{url('assets/fonts_svg/work_permit.svg')}}">--}}
                {{--                            </div>--}}
                {{--                            External Test--}}
                {{--                        </a>--}}
                {{--                    </li>--}}
                {{--                @endif--}}

                @include('Training::sidebar_menu')

                {{-- Start Users Menu for System Admin and Programmer --}}

                {{-- Support Menu start --}}
                @if(in_array($type[0], [2]))
                    <li class="{{ (Request::is('settings/*') ? 'active' : '') }}">
                        <a class="nav-link" href="{{ url ('/settings') }}">
                            <div class="nav-link-icon">
                                <img alt="Support" src="{{url('assets/fonts_svg/support.svg')}}">
                            </div>
                            Support
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="nav-link @if(Request::is('settings/area-list') || Request::is('settings/create-area') || Request::is('settings/edit-area/*')) active @endif"
                                   href="{{ url ('/settings/area-list') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Area" src="{{url('assets/fonts_svg/area.svg')}}">
                                    </div>
                                    Area
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if(Request::is('settings/bank-list') || Request::is('settings/create-bank')  || Request::is('settings/edit-bank/*')  || Request::is('settings/view-bank/*')) active @endif"
                                   href="{{ url ('/settings/bank-list') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Bank" src="{{url('assets/fonts_svg/bank.svg')}}">
                                    </div>
                                    Bank
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if(Request::is('settings/branch-list') || Request::is('settings/create-branch') || Request::is('settings/view-branch/*')) active @endif"
                                   href="{{ url ('/settings/branch-list') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Bank Branch" src="{{url('assets/fonts_svg/bank_branch.svg')}}">
                                    </div>
                                    Bank Branch
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if(Request::is('settings/currency') || Request::is('settings/create-currency') || Request::is('settings/edit-currency/*')) active @endif"
                                   href="{{ url ('/settings/currency') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Currency" src="{{url('assets/fonts_svg/currency.svg')}}">
                                    </div>
                                    Currency
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if(Request::is('settings/company-info') || Request::is('settings/company-info') || Request::is('settings/create-company')) active @endif"
                                   href="{{ url ('/settings/company-info') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Company Info" src="{{url('assets/fonts_svg/company_informarion.svg')}}">
                                    </div>
                                    Company Info
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if(Request::is('settings/get-change-basic-info-list') || Request::is('settings/change-basic-info-view/*')) active @endif"
                                   href="{{ url ('/settings/get-change-basic-info-list') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Change Company Info" src="{{url('assets/fonts_svg/company_informarion.svg')}}">
                                    </div>
                                    Change Company Info
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if(Request::is('settings/email-sms-queue') || Request::is('settings/email-sms-queue/*')) active @endif"
                                   href="{{ url ('settings/email-sms-queue') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Email & SMS Queue" src="{{url('assets/fonts_svg/email_&_sms_queue.svg')}}">
                                    </div>
                                    Email & SMS Queue
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if(Request::is('settings/forcefully-data-update')) active @endif"
                                   href="{{ url ('settings/forcefully-data-update') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Forcefully data update" src="{{url('assets/fonts_svg/forcefully_data_update.svg')}}">
                                    </div>
                                    Forcefully data update
                                </a>
                            </li>
                            {{--                            <li>--}}
                            {{--                                <a class="nav-link {{ (Request::is('log/*') ? 'active' : '') }}" href="{{ url ('/log') }}">--}}
                            {{--                                    <div class="nav-link-icon">--}}
                            {{--                                        <img alt="Log Report" src="{{url('assets/fonts_svg/report.svg')}}">--}}
                            {{--                                    </div>--}}
                            {{--                                    Log Report--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                            {{--                            <li>--}}
                            {{--                                <a class="nav-link @if(Request::is('support/nid-list') || Request::is('support/nid-list/*')) active @endif"--}}
                            {{--                                   href="{{ url ('support/nid-list') }}">--}}
                            {{--                                    <div class="nav-link-icon">--}}
                            {{--                                        <img alt="NID List" src="{{url('assets/fonts_svg/nid_list.svg')}}">--}}
                            {{--                                    </div>--}}
                            {{--                                    NID List--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            <li>
                                <a class="nav-link @if(Request::is('settings/notice') || Request::is('settings/create-notice') || Request::is('settings/edit-notice/*')) active @endif"
                                   href="{{ url ('/settings/notice') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Notice" src="{{url('assets/fonts_svg/notice.svg')}}">
                                    </div>
                                    Notice
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if(Request::is('settings/pdf-print-requests')) active @endif"
                                   href="{{ url ('/settings/pdf-print-requests') }}">
                                    <div class="nav-link-icon">
                                        <img alt="PDF print requests" src="{{url('assets/fonts_svg/pdf_print_requests.svg')}}">
                                    </div>
                                    PDF print requests
                                </a>
                            </li>
                            <li>
                                <a class="nav-link @if(Request::is('settings/home-page/contact') OR Request::is('settings/home-page/contact/*')) active @endif"
                                   href="{{ url ('/settings/home-page/contact') }}">
                                    <div class="nav-link-icon">
                                        <img alt="Home page contact" src="{{url('assets/fonts_svg/service_info.svg')}}">
                                    </div>
                                    Home page contact
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- Support Menu end --}}


                {{-- Settings Menu start --}}
                @if(in_array($type[0], [1]) || ($type[0] == 4 && !in_array(20, $user_desk_ids) &&checkUserDeskNone()))
                    <li class="{{ (Request::is('settings/*') ? 'active' : '') }}">
                        <a class="nav-link" href="{{ url ('/settings') }}">
                            <div class="nav-link-icon">
                                <img alt="Settings" src="{{url('assets/fonts_svg/settings.svg')}}">
                            </div>
                            Settings
                            <div class="nav-link-arrow">
                                <span class="fa arrow"></span>
                            </div>
                        </a>
                        <ul class="nav nav-second-level">
                            @if($type[0] == 1)
                                <li>
                                    <a class="nav-link @if(Request::is('settings/airport/*')) active @endif"
                                       href="{{ url ('/settings/airport/list') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Airport" src="{{url('assets/fonts_svg/airport.svg')}}">
                                        </div>
                                        Airport
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/area-list') || Request::is('settings/create-area') || Request::is('settings/edit-area/*')) active @endif"
                                       href="{{ url ('/settings/area-list') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Area" src="{{url('assets/fonts_svg/area.svg')}}">
                                        </div>
                                        Area
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/bank-list') || Request::is('settings/create-bank')  || Request::is('settings/edit-bank/*')  || Request::is('settings/view-bank/*')) active @endif"
                                       href="{{ url ('/settings/bank-list') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Bank" src="{{url('assets/fonts_svg/bank.svg')}}">
                                        </div>
                                        Bank
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/branch-list') || Request::is('settings/create-branch') || Request::is('settings/view-branch/*')) active @endif"
                                       href="{{ url ('/settings/branch-list') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Bank Branch" src="{{url('assets/fonts_svg/bank_branch.svg')}}">
                                        </div>
                                        Bank Branch
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/company-info') || Request::is('settings/company-info') || Request::is('settings/create-company')) active @endif"
                                       href="{{ url ('/settings/company-info') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Company Info" src="{{url('assets/fonts_svg/company_informarion.svg')}}">
                                        </div>
                                        Company Info
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/get-change-basic-info-list') || Request::is('settings/change-basic-info-view/*')) active @endif"
                                       href="{{ url ('/settings/get-change-basic-info-list') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Change Company Info" src="{{url('assets/fonts_svg/company_informarion.svg')}}">
                                        </div>
                                        Change Company Info
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/currency') || Request::is('settings/create-currency') || Request::is('settings/edit-currency/*')) active @endif"
                                       href="{{ url ('/settings/currency') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Currency" src="{{url('assets/fonts_svg/currency.svg')}}">
                                        </div>
                                        Currency
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/document') || Request::is('settings/create-document') || Request::is('settings/edit-document/*')) active @endif"
                                       href="{{ url ('/settings/document') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Document" src="{{url('assets/fonts_svg/document.svg')}}">
                                        </div>
                                        Document
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/email-sms-queue') || Request::is('settings/email-sms-queue/*')) active @endif"
                                       href="{{ url ('settings/email-sms-queue') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Email & SMS Queue" src="{{url('assets/fonts_svg/email_&_sms_queue.svg')}}">
                                        </div>
                                        Email & SMS Queue
                                    </a>
                                </li>
                                <li class="{{ (Request::is('/faq/faq-cat') ? 'active' : '') }}">
                                    <a class="nav-link" href="{{ url ('/faq/faq-cat') }}">
                                        <div class="nav-link-icon">
                                            <img alt="FAQ" src="{{url('assets/fonts_svg/faq.svg')}}">
                                        </div>
                                        FAQ
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/forcefully-data-update')) active @endif"
                                       href="{{ url ('settings/forcefully-data-update') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Forcefully data update" src="{{url('assets/fonts_svg/forcefully_data_update.svg')}}">
                                        </div>
                                        Forcefully data update
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/high-commission') || Request::is('settings/create-high-commission') || Request::is('settings/edit-high-commission/*')) active @endif"
                                       href="{{ url ('/settings/high-commission') }}">
                                        <div class="nav-link-icon">
                                            <img alt="High Commission" src="{{url('assets/fonts_svg/high_commission.svg')}}">
                                        </div>
                                        High Commission
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/holiday') || Request::is('settings/create-holiday') || Request::is('settings/edit-holiday/*')) active @endif"
                                       href="{{ url ('/settings/holiday') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Holiday" src="{{url('assets/fonts_svg/holiday.svg')}}">
                                        </div>
                                        Holiday
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/home-page-slider') OR Request::is('settings/create-home-page-slider') OR Request::is('settings/edit-home-page-slider/*')) active @endif"
                                       href="{{ url ('/settings/home-page-slider') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Home page slider" src="{{url('assets/fonts_svg/home_page_slider.svg')}}">
                                        </div>
                                        Home page slider
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/home-page/contact') OR Request::is('settings/home-page/contact/*')) active @endif"
                                       href="{{ url ('/settings/home-page/contact') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Home page contact" src="{{url('assets/fonts_svg/service_info.svg')}}">
                                        </div>
                                        Home page contact
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/notice') || Request::is('settings/create-notice') || Request::is('settings/edit-notice/*')) active @endif"
                                       href="{{ url ('/settings/notice') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Notice" src="{{url('assets/fonts_svg/notice.svg')}}">
                                        </div>
                                        Notice
                                    </a>
                                </li>
                                {{--                                <li>--}}
                                {{--                                    <a class="nav-link {{ (Request::is('log/*') ? 'active' : '') }}" href="{{ url ('/log') }}">--}}
                                {{--                                        <div class="nav-link-icon">--}}
                                {{--                                            <img alt="Log Report" src="{{url('assets/fonts_svg/report.svg')}}">--}}
                                {{--                                        </div>--}}
                                {{--                                        Log Report--}}
                                {{--                                    </a>--}}
                                {{--                                </li>--}}
                                @if($user_type != '1x102')
                                <li>
                                    <a class="nav-link @if(Request::is('settings/maintenance-mode')) active @endif"
                                       href="{{ url ('settings/maintenance-mode') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Operational mode" src="{{url('assets/fonts_svg/operational_mode.svg')}}">
                                        </div>
                                        Operational mode
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/payment-configuration') OR Request::is('settings/create-payment-configuration') OR Request::is('settings/edit-payment-configuration/*')) active @endif"
                                       href="{{ url ('/settings/payment-configuration') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Payment Configuration" src="{{url('assets/fonts_svg/payment_configration.svg')}}">
                                        </div>
                                        Payment Configuration
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/stakeholder-payment-configuration') OR Request::is('settings/create-stakeholder-payment-configuration') OR Request::is('settings/edit-stakeholder-payment-configuration/*')) active @endif"
                                       href="{{ url ('/settings/stakeholder-payment-configuration') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Payment Configuration Stakeholder" src="{{url('assets/fonts_svg/payment_configration_stakholder.svg')}}">
                                        </div>
                                        Payment Configuration Stakeholder
                                    </a>
                                </li>
                                @endif
                                <li>
                                    <a class="nav-link @if(Request::is('settings/regulatory-agency') || Request::is('settings/create-regulatory-agency') || Request::is('settings/edit-regulatory-agency/*')) active @endif"
                                       href="{{ url ('/settings/regulatory-agency') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Regulatory Agencies" src="{{url('assets/fonts_svg/regulatory_agencies.svg')}}">
                                        </div>
                                        Regulatory Agencies
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/regulatory-agency-details') || Request::is('settings/create-regulatory-agency-details') || Request::is('settings/edit-regulatory-agency-details/*')) active @endif"
                                       href="{{ url ('/settings/regulatory-agency-details') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Regulatory Agencies Details" src="{{url('assets/fonts_svg/regulatory_agencies_details.svg')}}">
                                        </div>
                                        Regulatory Agencies Details
                                    </a>
                                </li>
                                @if($user_type != '1x102')
                                <li>
                                    <a class="nav-link @if(Request::is('settings/security') || Request::is('settings/edit-security/*')) active @endif"
                                       href="{{ url ('/settings/security') }}" href="{{ url ('/settings/security') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Security profile" src="{{url('assets/fonts_svg/security_profile.svg')}}">
                                        </div>
                                        Security profile
                                    </a>
                                </li>
                                @endif
                                <li>
                                    <a class="nav-link @if(Request::is('settings/service-info') || Request::is('settings/create-service-info-details')  || Request::is('settings/service-info/*')) active @endif"
                                       href="{{ url ('/settings/service-info') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Service info" src="{{url('assets/fonts_svg/service_info.svg')}}">
                                        </div>
                                        Service info
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/stakeholder') || Request::is('settings/create-stakeholder') || Request::is('settings/edit-stakeholder/*')) active @endif"
                                       href="{{ url ('/settings/stakeholder') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Stakeholder" src="{{url('assets/fonts_svg/stakeholder.svg')}}">
                                        </div>
                                        Stakeholder
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/edit-logo')) active @endif"
                                       href="{{ url ('/settings/edit-logo') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Title & Logo" src="{{url('assets/fonts_svg/title_&_logo.svg')}}">
                                        </div>
                                        Title & Logo
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/user-manual') OR Request::is('settings/create-user-manual') OR Request::is('settings/edit-user-manual/*')) active @endif"
                                       href="{{ url ('/settings/user-manual') }}">
                                        <div class="nav-link-icon">
                                            <img alt="Necessary Resources" src="{{url('assets/fonts_svg/necessary_resources.svg')}}">
                                        </div>
                                        Necessary Resources
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link @if(Request::is('settings/whats-new') OR Request::is('settings/create-whats_new') OR Request::is('settings/edit-whats-new/*')) active @endif"
                                       href="{{ url ('/settings/whats-new') }}">
                                        <div class="nav-link-icon">
                                            <img alt="What's new" src="{{url('assets/fonts_svg/whats_new.svg')}}">
                                        </div>
                                        What's new
                                    </a>
                                </li>
                                {{-- //////////Dashboard Slider Start////////// --}}
                                <li>
                                    <a class="nav-link @if(Request::is('settings/dashboard-slider') OR Request::is('settings/create-dashboard-slider') OR Request::is('settings/edit-dashboard-slider/*')) active @endif" href="{{ url ('/settings/dashboard-slider') }}">
                                        <div class="nav-link-icon">
                                            <img alt="DashBoard Slider" src="{{url('assets/fonts_svg/home_page_slider.svg')}}">
                                        </div>
                                        {!! trans('messages.dashboard_slider') !!}
                                    </a>
                                </li>

                                {{-- //////////Dashboard Slider End////////// --}}
                            @endif
                            <li>
                                <a class="nav-link @if(Request::is('settings/pdf-print-requests')) active @endif"
                                   href="{{ url ('/settings/pdf-print-requests') }}">
                                    <div class="nav-link-icon">
                                        <img alt="PDF print requests" src="{{url('assets/fonts_svg/pdf_print_requests.svg')}}">
                                    </div>
                                    PDF print requests
                                </a>
                            </li>
                            @if($user_type != '1x102')
                            <li>
                                <a class="nav-link @if(Request::is('settings/app-rollback') || Request::is('settings/app-rollback-open')) active @endif"
                                   href="{{ url ('/settings/app-rollback') }}">
                                    <div class="nav-link-icon">
                                        <img alt="App Rollback" src="{{url('assets/fonts_svg/app_rollback.svg')}}">
                                    </div>
                                    App Rollback
                                </a>
                            </li>
                            @endif
                            <li>
                                <a class="nav-link @if(Request::is('settings/external-service-list') || Request::is('settings/external-service-list')) active @endif"
                                   href="{{ url ('/settings/external-service-list') }}">
                                    <div class="nav-link-icon">
                                        <img alt="..." src="{{url('assets/fonts_svg/app_rollback.svg')}}">
                                    </div>
                                    External Service List
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- Settings Menu end --}}
                {{--                @if($type[0] == 4 && !in_array(20, $user_desk_ids))--}}
                {{--                    @if(\App\Libraries\CommonFunction::isEserve() == 1)--}}
                {{--                        <li>--}}
                {{--                            <a href="{{ \App\Libraries\CommonFunction::generateLink() }}" target="_blank" rel="noopener">--}}
                {{--                                <i class="fas fa-external-link-square-alt"></i> E-Serve--}}
                {{--                            </a>--}}
                {{--                        </li>--}}
                {{--                    @endif--}}
                {{--                @endif--}}
            @endif
        </ul>


        <div id="1">
            <div class="circular-sb" id="msgtost" style="display: none;">
                <p id="feature_text"></p>
                <button class="btn btn-success feedbackbtn" value="ok" id="yesbtn" style="margin-left: 30px;">OK
                </button>
                <input type="hidden" value="1" id="msg1">
                <div class="circle1"></div>
                <div class="circle2"></div>
            </div>
        </div>


        {{--Powered by & Developed by section--}}
        {{-- <div class="panel" style="padding: 10px 30px;">
            <p style="margin: 0; padding: 0; font-size: 13px; font-style: italic;">{{trans('messages.manage_by')}}</p>
            <a href="https://www.ba-systems.com" target="_blank" rel="noopener">
                <img style="background: none;" alt="Business Automation Ltd." src="{{url('assets/images/business_automation_ltd.png')}}">
            </a>
            <div class="clearfix"></div>
        </div> --}}


    </div><!-- /.sidebar-collapse -->
</div><!-- /.navbar-static-side -->

