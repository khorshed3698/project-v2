@extends('web.layouts.app')

@push('pluginStyles')
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/plugins/dataTables/datatables.min.css')}}" media="print" onload="this.media='all'">
@endpush

@push('customStyles')
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/home/home.min.css')}}">
@endpush

@section('content')

    {{--Home Slider--}}
    @include('Web::home.partials.slider')

    {{--Public Services--}}
    @include('Web::home.partials.public_services')

    {{-- How OSS Works? --}}
    @include('Web::home.partials.oss_works')

    {{-- bida-service-tab-sec --}}
    @include('Web::home.partials.bida_service')

    {{-- About BIDA --}}
    @include('Web::home.partials.about_bida')

    {{-- Contact Us --}}
    @include('Web::home.partials.contact_us')

@endsection

@push('pluginScripts')
    <script src="{{asset('assets/landingV2/assets/plugins/dataTables/datatables.min.js')}}" defer></script>
    <script src="{{ asset('assets/scripts/jquery.validate.min.js') }}"></script>
    <script src="{{ asset("assets/scripts/sweetalert2.all.min.js") }}" type="text/javascript" defer></script>
@endpush

{{-- Page Style & Script--}}
@push('customScripts')
    @include('Web::home.partials.public_service_js')
    @include('Web::home.partials.bida_service_js')
    @include('Web::home.partials.contact_us_js')
@endpush

