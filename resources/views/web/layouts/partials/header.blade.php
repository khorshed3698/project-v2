<?php
$redirect_url = App\Libraries\CommonFunction::redirectToKeycloak();
?>
@include('public_home.top-notice')
<header class="header-main">
    <div class="header-top deskView">
        <div class="container">
            <div class="header-top-content">
                <div class="htop-left">
                    <div class="htop-links">
                        <a href="{{ url('/') }}" class="icon-link-item ps-0">
                            <span class="link-text">One Stop Service</span>
                        </a>
                        <a href="tel:{{ config('app.support_contact_mobile') }}" class="icon-link-item" aria-label="Call Us">
                            <span class="link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M15.391 11.2737L11.9253 9.78839C11.7773 9.7253 11.6127 9.712 11.4565 9.75051C11.3002 9.78902 11.1607 9.87724 11.0589 10.0019L9.52409 11.8771C7.11537 10.7414 5.17691 8.80293 4.04122 6.39421L5.91639 4.85941C6.0413 4.75781 6.12971 4.61829 6.16824 4.46196C6.20676 4.30562 6.19331 4.14099 6.1299 3.99299L4.64462 0.527323C4.57503 0.367781 4.45195 0.23752 4.29661 0.159002C4.14127 0.0804839 3.9634 0.0586298 3.79367 0.0972082L0.575549 0.839852C0.41191 0.877639 0.265911 0.969777 0.161381 1.10123C0.056851 1.23268 -3.76957e-05 1.39567 1.874e-08 1.56362C1.874e-08 9.50062 6.43315 15.9214 14.3578 15.9214C14.5258 15.9215 14.6888 15.8646 14.8204 15.7601C14.9519 15.6556 15.044 15.5095 15.0818 15.3458L15.8245 12.1277C15.8628 11.9572 15.8405 11.7786 15.7614 11.6227C15.6823 11.4668 15.5513 11.3434 15.391 11.2737Z" fill="white"/>
                                </svg>
                            </span>
                            <span class="link-text">{{ config('app.support_contact_mobile') }}</span>
                        </a>
                        <a href="mailto:{{ config('app.support_contact_email') }}" class="icon-link-item" aria-label="Mail Us">
                            <span class="link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="13" viewBox="0 0 17 13" fill="none">
                                    <path d="M16.3243 4.16159C16.4436 4.06523 16.6211 4.15537 16.6211 4.30768V10.6642C16.6211 11.4879 15.9632 12.1562 15.1524 12.1562H2.42377C1.61293 12.1562 0.955078 11.4879 0.955078 10.6642V4.31079C0.955078 4.15537 1.12948 4.06834 1.25188 4.16469C1.93726 4.70554 2.84602 5.39249 5.96698 7.69576C6.61259 8.17445 7.70187 9.18155 8.78809 9.17533C9.88042 9.18466 10.9911 8.1558 11.6123 7.69576C14.7332 5.39249 15.6389 4.70244 16.3243 4.16159ZM8.78809 8.17756C9.49795 8.18999 10.5199 7.26992 11.034 6.89071C15.0943 3.89738 15.4033 3.63628 16.3396 2.89028C16.5171 2.7504 16.6211 2.53282 16.6211 2.3028V1.71222C16.6211 0.888507 15.9632 0.220215 15.1524 0.220215H2.42377C1.61293 0.220215 0.955078 0.888507 0.955078 1.71222V2.3028C0.955078 2.53282 1.05911 2.74729 1.23658 2.89028C2.17287 3.63317 2.4819 3.89738 6.54222 6.89071C7.05626 7.26992 8.07822 8.18999 8.78809 8.17756Z" fill="white"/>
                                </svg>
                            </span>
                            <span class="link-text">{{ config('app.support_contact_email') }}</span>
                        </a>
                    </div>
                </div>

                <div class="htop-right">
                    <div class="htop-links">
                        <a href="{{ url('/#ossPContactSection') }}" class="link-text smoothScroll">Need Help?</a>
                        <a href="{{ route('service_tracking') }}" class="icon-link-item">
                          <span class="link-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <g clip-path="url(#clip0_16_998)">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.4998 2C9.14436 2.00012 7.80863 2.32436 6.60402 2.94569C5.39941 3.56702 4.36086 4.46742 3.575 5.57175C2.78914 6.67609 2.27878 7.95235 2.08647 9.29404C1.89417 10.6357 2.02551 12.004 2.46954 13.2846C2.91357 14.5652 3.65741 15.7211 4.639 16.6557C5.62059 17.5904 6.81147 18.2768 8.11228 18.6576C9.41309 19.0384 10.7861 19.1026 12.1168 18.8449C13.4475 18.5872 14.6972 18.015 15.7618 17.176L19.4138 20.828C19.6024 21.0102 19.855 21.111 20.1172 21.1087C20.3794 21.1064 20.6302 21.0012 20.8156 20.8158C21.001 20.6304 21.1062 20.3796 21.1084 20.1174C21.1107 19.8552 21.0099 19.6026 20.8278 19.414L17.1758 15.762C18.1638 14.5086 18.7789 13.0024 18.9509 11.4157C19.1228 9.82905 18.8446 8.22602 18.148 6.79009C17.4514 5.35417 16.3646 4.14336 15.0121 3.29623C13.6595 2.44911 12.0957 1.99989 10.4998 2ZM3.99977 10.5C3.99977 8.77609 4.68458 7.12279 5.90357 5.90381C7.12256 4.68482 8.77586 4 10.4998 4C12.2237 4 13.877 4.68482 15.096 5.90381C16.3149 7.12279 16.9998 8.77609 16.9998 10.5C16.9998 12.2239 16.3149 13.8772 15.096 15.0962C13.877 16.3152 12.2237 17 10.4998 17C8.77586 17 7.12256 16.3152 5.90357 15.0962C4.68458 13.8772 3.99977 12.2239 3.99977 10.5Z" fill="white"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_16_998">
                                        <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                          </span>
                            <span class="link-text">Service Tracking</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-menu">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="site-logo" href="{{ url('/') }}">
                    <img class="logo-icon" src="{{ asset(Session::get('logo')) }}" alt="Logo BIDA OSS" width="155" onerror="this.onerror=null;this.src='{{asset('/assets/images/photo_default.webp')}}';">
                </a>

                <div class="flex-btn-group">
                    @if(!Request::is('signup/*'))
                        <a href="#" role="button" class="nav-btn btn btn-red res-login-btn" data-redirect="{{ $redirect_url }}" onclick="handleLogin(event)">Login /Registration</a>
                    @endif
                    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link smoothScroll" href="{{ url('/#bidaAboutSec') }}">About BIDA</a></li>
                        <li class="nav-item"><a class="nav-link smoothScroll" href="{{ url('/#bidaHowItWorksSec') }}">How OSS Works?</a></li>
                        @if(!Request::is('signup/*'))
                            <li class="nav-item deskView">
                                <div class="flex-btn-group">
                                    <a href="#" role="button" class="nav-btn btn btn-red" data-redirect="{{ $redirect_url }}" onclick="handleLogin(event)">Login /Registration</a>
                                </div>
                            </li>
                        @endif
                    </ul>

                    <div class="header-top resView">
                        <div class="header-top-content">
                            <div class="htop-left">
                                <div class="htop-links">
                                    <a href="{{ url('/') }}" class="icon-link-item ps-0">
                                        <span class="link-text">One Stop Service</span>
                                    </a>
                                    <a href="#" class="icon-link-item">
                                        <span class="link-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M15.391 11.2737L11.9253 9.78839C11.7773 9.7253 11.6127 9.712 11.4565 9.75051C11.3002 9.78902 11.1607 9.87724 11.0589 10.0019L9.52409 11.8771C7.11537 10.7414 5.17691 8.80293 4.04122 6.39421L5.91639 4.85941C6.0413 4.75781 6.12971 4.61829 6.16824 4.46196C6.20676 4.30562 6.19331 4.14099 6.1299 3.99299L4.64462 0.527323C4.57503 0.367781 4.45195 0.23752 4.29661 0.159002C4.14127 0.0804839 3.9634 0.0586298 3.79367 0.0972082L0.575549 0.839852C0.41191 0.877639 0.265911 0.969777 0.161381 1.10123C0.056851 1.23268 -3.76957e-05 1.39567 1.874e-08 1.56362C1.874e-08 9.50062 6.43315 15.9214 14.3578 15.9214C14.5258 15.9215 14.6888 15.8646 14.8204 15.7601C14.9519 15.6556 15.044 15.5095 15.0818 15.3458L15.8245 12.1277C15.8628 11.9572 15.8405 11.7786 15.7614 11.6227C15.6823 11.4668 15.5513 11.3434 15.391 11.2737Z" fill="white"/>
                                            </svg>
                                        </span>
                                        <span class="link-text">{{ config('app.support_contact_mobile') }}</span>
                                    </a>
                                    <a href="#" class="icon-link-item">
                                        <span class="link-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="13" viewBox="0 0 17 13" fill="none">
                                                <path d="M16.3243 4.16159C16.4436 4.06523 16.6211 4.15537 16.6211 4.30768V10.6642C16.6211 11.4879 15.9632 12.1562 15.1524 12.1562H2.42377C1.61293 12.1562 0.955078 11.4879 0.955078 10.6642V4.31079C0.955078 4.15537 1.12948 4.06834 1.25188 4.16469C1.93726 4.70554 2.84602 5.39249 5.96698 7.69576C6.61259 8.17445 7.70187 9.18155 8.78809 9.17533C9.88042 9.18466 10.9911 8.1558 11.6123 7.69576C14.7332 5.39249 15.6389 4.70244 16.3243 4.16159ZM8.78809 8.17756C9.49795 8.18999 10.5199 7.26992 11.034 6.89071C15.0943 3.89738 15.4033 3.63628 16.3396 2.89028C16.5171 2.7504 16.6211 2.53282 16.6211 2.3028V1.71222C16.6211 0.888507 15.9632 0.220215 15.1524 0.220215H2.42377C1.61293 0.220215 0.955078 0.888507 0.955078 1.71222V2.3028C0.955078 2.53282 1.05911 2.74729 1.23658 2.89028C2.17287 3.63317 2.4819 3.89738 6.54222 6.89071C7.05626 7.26992 8.07822 8.18999 8.78809 8.17756Z" fill="white"/>
                                            </svg>
                                        </span>
                                        <span class="link-text">{{ config('app.support_contact_email') }}</span>
                                    </a>
                                </div>
                            </div>

                            <div class="htop-right">
                                <div class="htop-links">
                                    <a href="#" class="link-text">Need Help?</a>
                                    <a href="{{ route('service_tracking') }}" class="icon-link-item">
                                      <span class="link-icon">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                            <g clip-path="url(#clip0_16_998)">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.4998 2C9.14436 2.00012 7.80863 2.32436 6.60402 2.94569C5.39941 3.56702 4.36086 4.46742 3.575 5.57175C2.78914 6.67609 2.27878 7.95235 2.08647 9.29404C1.89417 10.6357 2.02551 12.004 2.46954 13.2846C2.91357 14.5652 3.65741 15.7211 4.639 16.6557C5.62059 17.5904 6.81147 18.2768 8.11228 18.6576C9.41309 19.0384 10.7861 19.1026 12.1168 18.8449C13.4475 18.5872 14.6972 18.015 15.7618 17.176L19.4138 20.828C19.6024 21.0102 19.855 21.111 20.1172 21.1087C20.3794 21.1064 20.6302 21.0012 20.8156 20.8158C21.001 20.6304 21.1062 20.3796 21.1084 20.1174C21.1107 19.8552 21.0099 19.6026 20.8278 19.414L17.1758 15.762C18.1638 14.5086 18.7789 13.0024 18.9509 11.4157C19.1228 9.82905 18.8446 8.22602 18.148 6.79009C17.4514 5.35417 16.3646 4.14336 15.0121 3.29623C13.6595 2.44911 12.0957 1.99989 10.4998 2ZM3.99977 10.5C3.99977 8.77609 4.68458 7.12279 5.90357 5.90381C7.12256 4.68482 8.77586 4 10.4998 4C12.2237 4 13.877 4.68482 15.096 5.90381C16.3149 7.12279 16.9998 8.77609 16.9998 10.5C16.9998 12.2239 16.3149 13.8772 15.096 15.0962C13.877 16.3152 12.2237 17 10.4998 17C8.77586 17 7.12256 16.3152 5.90357 15.0962C4.68458 13.8772 3.99977 12.2239 3.99977 10.5Z" fill="white"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_16_998">
                                                    <rect width="24" height="24" fill="white"/>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                      </span>
                                        <span class="link-text">Service Tracking</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>