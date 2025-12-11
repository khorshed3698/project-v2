@extends('layouts.plane')

@section('body')
<div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0; border-bottom: 1px solid #bfe4f0;background: url('/assets/images/top_bg2.jpg') no-repeat scroll 0 0; ">
            @include ('navigation.topbar')
        </nav>

    <div id="page-wrapper" style="border: none !important;margin: 0 !important;">
        <div class="row">
            <br/>
            @yield('content')
        </div>
    </div>
</div>
@stop

