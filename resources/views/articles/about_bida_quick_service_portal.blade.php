@extends('layouts.front')

@section('content')
    @include('articles.top-navbar')
    <div class="row">
        <div class="col-md-8">
            <div class="box-div">
                <h3>About quick service portal</h3>
                @if(!empty($contents))
                    {!! $contents !!}
                @else
                    <p class="text-center">No content found.</p>
                @endif

                <div class="pull-right">
                    <label class="radio-inline">Is this article helpful?</label>
                    <label class="radio-inline">
                        <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('yes', '', 5)">
                        Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('no', '', 5)">
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