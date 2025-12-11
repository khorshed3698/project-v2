@extends('web.layouts.app')

@push('customStyles')
    <!-- Inner Page -->
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/pages/inner-page.css')}}">
@endpush

@section('content')

<section class="bida-page-breadcrumb">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Necessary Resources</li>
            </ol>
        </nav>
    </div>
</section>
<section class="inner-page-content bida-section">
    <div class="container">
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
        <div id="sub_agency_div" class="float-end mb-5">
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
    </div>
</section>

@endsection

{{-- Page Style & Script--}}
@push('styles')
    <!-- Home Page -->
@endpush
