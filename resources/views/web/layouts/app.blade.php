<?php echo
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>

        <!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.project_name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    {{--Open Graph Meta Tags--}}
    <meta property="og:title" content="Streamlined Investment with the BIDA One-Stop Service Platform">
    <meta property="og:description" content="The One-Stop Service (OSS) platform simplifies the investment process by integrating services from 35 government agencies, 12 banks, and 5 chamber associations, offering over 133 services. Investors can submit applications, track progress, and download essential documents all in one user-friendly portal.">
    <meta property="og:image" content="{{ asset('assets/landingV2/assets/frontend/images/home/bida-about-img.jpg') }}">
    <meta property="og:url" content="{{ route('web.home') }}">
    <meta property="og:type" content="profile">

    {{--Twitter Card Meta Tags--}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Streamlined Investment with the BIDA One-Stop Service Platform">
    <meta name="twitter:description" content="The One-Stop Service (OSS) platform simplifies the investment process by integrating services from 35 government agencies, 12 banks, and 5 chamber associations, offering over 133 services. Investors can submit applications, track progress, and download essential documents all in one user-friendly portal.">
    <meta name="twitter:image" content="{{ asset('assets/landingV2/assets/frontend/images/home/bida-about-img.jpg') }}">

    {{--Additional Meta Tags--}}
    <meta name="author" content="Bangladesh Investment Development Authority">
    <meta name="keywords" content="BIDA Registration, Company Registration (RJSC), Office Permission, Outward Remittance Approval, Tax Identification Number, Visa Recommendation, Work Permit">
    <meta name="description" content="One Stop Service is an online platform integrating relevant Government agencies for providing efficient and transparent services to domestic and foreign investors">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    {{-- <meta name="insightDB-token" content="{{ Cache::has('insightdb_api_token') ? Cache::get('insightdb_api_token') : '' }}"/> --}}

    <!-- Global Styles -->
    @include('web.layouts.partials.style')

</head>

<body>
<div class="main-content">

    @include('web.layouts.partials.header')


    @yield('content')

    {{--Footer Section--}}

    @include('web.layouts.partials.footer')

</div>

<!-- Global Script -->
@include('web.layouts.partials.script')
</body>
</html>