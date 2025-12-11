<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<html lang="en" class="no-js">

<head>
    <meta charset="utf-8"/>
    <title>@yield('title', config('app.project_name'))</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>

    <meta
            name="keywords"
            content="BIDA Registration, Company Registration (RJSC), Office Permission, Outward Remittance Approval,
            Tax Identification Number, Visa Recommendation, Work Permit">
    <meta
            name="description"
            content="One Stop Service is an online platform integrating relevant Government agencies for providing efficient and transparent
            services to domestic and foreign investors">

    <link rel="shortcut icon" type="image/png" href="{{ asset("assets/images/favicon1.ico") }}"/>

    <link rel="stylesheet" type="text/css" href="{{asset('vendor/bootstrap-3.4.1/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/bootstrap-datetimepicker.css") }}"/>
    <link rel="stylesheet" href="{{ asset("assets/fontawesome/css/all.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("assets/plugins/toastr.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("build/css/intlTelInput_v16.0.8.css") }}"/>
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/custom_v004.min.css") }}"/>

    <!-- Common -->
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/bida-common.css')}}">
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/bida-header-old.css')}}">
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/bida-footer-old.css')}}">

    <!-- Inner Page -->
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/pages/inner-page.css')}}">

    @yield('style')
</head>
<body>
@include('public_home.style')

<div class="main-content">

    @include('web.layouts.partials.header')

    @yield('body')

    @include('web.layouts.partials.footer')

</div>

<!-- jQuery -->
{{--<script src="{{ asset("assets/scripts/jquery.min.js") }}" type="text/javascript"></script>--}}
<script src="{{ asset("assets/scripts/jquery_v3.5.1.min.js") }}" type="text/javascript"></script>
<script src="{{asset('vendor/bootstrap-3.4.1/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{ asset("assets/scripts/moment.js") }}" type="text/javascript"></script>
{{--<script async src="{{ asset("vendor/moment_v2.26.0.min.js") }}" type="text/javascript"></script>--}}
<script src="{{ asset("assets/scripts/bootstrap-datetimepicker_v001.min.js") }}"></script>
<script src="{{ asset("assets/scripts/jquery.validate.js") }}"></script>

<script async src="{{ asset("assets/plugins/toastr.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("assets/scripts/custom_v07.min.js") }}"></script>

<script type="text/javascript">
    var base_url = '{{url()}}';
</script>

{{-- Social widget and feedback system --}}
<div id="batworld"></div>
<script type='text/javascript'>
    window.ba_sw_id = '{{ config('app.SOCIAL_WIDGET_ID') }}';
    let social_widget_site_url = '{{ config('app.SOCIAL_WIDGET_SITE_URL') }}';
    setTimeout(() => {
        const s1 = document.createElement('script');
        s1.setAttribute('src', social_widget_site_url);
        document.body.appendChild(s1);
    }, 500);
</script>


{{--<script src="{{ asset("assets/scripts/image-processing.js") }}"></script>--}}
@yield('footer-script')
</body>

</html>