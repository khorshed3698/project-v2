@extends('layouts.front')

<style>
    .logo_green {
        background-color: rgba(0, 104, 72, 0.8) !important;
        color: #fff;
        border-color: #006848 !important;
    }
</style>

@section('content')
    @include('articles.top-navbar')
    <div class="row">
        <div class="col-md-8">
            <div class="box-div">
                @if(!empty($contents))
                    {!! $contents !!}
                @else
                    <p class="text-center">No content found.</p>
                @endif

                <div class="pull-right">
                    <label class="radio-inline">Is this article helpful?</label>
                    <label class="radio-inline">
                        <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('yes', '', 10)">
                        Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('no', '', 10)">
                        No
                    </label>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-4 hidden-sm hidden-xs">
            @include('public_home.login_panel')
        </div>
    </div>
@endsection