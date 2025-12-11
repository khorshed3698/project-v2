@extends('layouts.admin')

@section('header-resources')
    <style>
        .list-group-item {
            background: rgba(245, 245, 250, 1) !important;
            color: #000;
        }
    </style>
@endsection

@section('content')
    @include('partials.messages')

    @if(Auth::user()->is_approved != 1 or Auth::user()->is_approved !=true)
        @include('message.un-approved')
    @else
        <div class="row">
            <div class="col-md-12">
                @include('Training::dashboard.training-dashboard-box')
            </div>
        </div>
    @endif
@endsection

@section('footer-script')
    <script>

    </script>
@endsection

