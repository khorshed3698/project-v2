@extends('layouts.admin')
@section('page_heading')
    {!! $pageTitle !!}
@endsection
@section('content')
    <div class="col-sm-12">
        @include('message.message')
    </div>

    @if(Auth::user()->is_approved != 1 or Auth::user()->is_approved !=true)
        @include('message.un-approved')
    @else


        @include('Dashboard::training_course_url_redirection')


        <div class="col-md-12">

            @if(isset($user_multiple_company) == 1 && Auth::user()->user_type == '5x505')
                @include('Dashboard::working-company-modal')
            @else
                @include('Dashboard::dashboard')
            @endif

        </div>
    @endif

    @include('navigation.footer')
@endsection

@section('footer-script')
@endsection
