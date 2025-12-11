@extends('web.layouts.app')

@push('customStyles')
    {{--Inner Page--}}
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/pages/inner-page.css')}}">
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/home/bida-accordian.css')}}">
@endpush

@section('content')

    <section class="bida-page-breadcrumb">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('web.home') }}">Home</a></li>
                    <li class="breadcrumb-item">Available Online Services</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="inner-page-content bida-section">
        <div class="container">
            <div class="page-content">
                <div class="bida-accordian-sec">
                    <div id="bidaServiceAccordian" class="bida-accordian">

                        @foreach($availableServices as $supperName => $Services)
                                <?php $supperNameCount++ ?>

                            <div class="bida-acd-item">
                                <div class="accordian-head" data-bs-toggle="collapse" data-bs-target="#supperName-{{ $supperNameCount }}" aria-expanded="true" aria-controls="supperName-{{ $supperNameCount }}">
                                    <span class="accordian-indicator"><span class="icon-plus"></span></span>
                                    <div class="bida-acd-title">
                                        <h3>{{ $supperName }}</h3>
                                    </div>
                                </div>

                                <div id="supperName-{{ $supperNameCount }}" class="collapse show">
                                    <div class="bida-acd-content">
                                        <div id="supperNameInner-{{ $subNameCount }}" class="accordian-content">
                                            @foreach($Services as $subName => $Service)
                                                    <?php $subNameCount++ ?>
                                                <div class="bida-2nd-lavel-acd-item bida-acd-item">
                                                    <div class="accordian-head collapsed" data-bs-toggle="collapse" data-bs-target="#subName-{{ $subNameCount }}" aria-expanded="true" aria-controls="subName-{{ $subNameCount }}">
                                                        <span class="accordian-indicator"><span class="icon-plus"></span></span>
                                                        <div class="bida-acd-title">
                                                            <h3>{{ $subName }}</h3>
                                                        </div>
                                                    </div>
                                                    <div id="subName-{{ $subNameCount }}" class="collapse" data-bs-parent="#supperNameInner-{{ $subNameCount }}">
                                                        <div class="bida-acd-content">
                                                            {!! $Service['description'] !!}
                                                        </div>
                                                        <div id="sub_agency_div" class="float-end pb-3 pe-4">
                                                            <label class="radio-inline">Is this article helpful?</label>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($Service['sd_id']) }}" onclick="isHelpFulArticle('yes', this.value, 1)">
                                                                Yes
                                                            </label>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($Service['sd_id']) }}" onclick="isHelpFulArticle('no', this.value, 1)">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('customScripts')
    <script>
        document.addEventListener('shown.bs.collapse', function (e) {
            // Prevent default behavior (not necessary in most cases for this event)
            e.preventDefault();

            // Get the closest accordion item (your "bida-acd-item")
            let panel = e.target.closest('.bida-acd-item');

            // Smooth scroll to the panel if it exists
            if (panel) {
                panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    </script>
@endpush