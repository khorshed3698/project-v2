@extends('layouts.front')

@section('style')
    <style>
        thead > tr > th {
            padding: 10px !important;
        }
        .logo_green {
            background-color: rgba(0, 104, 72, 0.8) !important;;
            color: #fff;
            border-color: #006848 !important;
        }
    </style>
@endsection

@section('content')
    @include('articles.top-navbar')
    <div class="row">
        <div class="col-md-8">
            <div class="box-div">
                <h3>Necessary Resources</h3>
                <div class="panel panel-default">
                    <table class="table table-hover table-bordered table-striped" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr class="success">
                            <th class="logo_green">Document name</th>
                            <th class="logo_green">Link</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse ($user_manuals as $user_manual)
                            <tr>
                                <td>{{ $user_manual->typeName }}</td>
                                <td class="text-center">
                                    @if(file_exists($user_manual->pdfFile))
                                    <a href="{{ url($user_manual->pdfFile) }}" target="_blank" rel="noopener" class="btn btn-success btn-xs">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                        Download
                                    </a>
                                        @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
                                    No content found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pull-right">
                    <label class="radio-inline">Is this article helpful?</label>
                    <label class="radio-inline">
                        <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('yes', '', 8)">
                        Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('no', '', 8)">
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