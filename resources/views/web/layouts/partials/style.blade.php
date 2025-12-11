<!-- Fonts -->
{{-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700&display=swap" rel="stylesheet"> --}}

<link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/fonts/fonts.css')}}">
<!-- Plugins -->
<link rel="stylesheet" href="{{asset('assets/landingV2/assets/plugins/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/landingV2/assets/plugins/animate/animate.min.css')}}" media="print" onload="this.media='all'">
<link rel="stylesheet" href="{{ asset("assets/plugins/toastr.min.css") }}" media="print" onload="this.media='all'" />
<!-- Page-specific styles -->
@stack('pluginStyles') <!-- This is where all pushed styles will be included -->

<!-- Common -->
<link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/web.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/bida-common.css')}}">
<link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/bida-header.css')}}">
<link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/bida-footer.css')}}"> --}}
{{-- <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/home/bida-public-service.css')}}"> --}}

@stack('customStyles')