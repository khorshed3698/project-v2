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
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item">{{ isset($title) ? $title : null }}</li>
                </ol>
            </nav>
        </div>
    </section>
    <section class="inner-page-content bida-section">
        <div class="container">

            @foreach($regulatory_agencies as $key => $regulatory_agency)

            <div class="bida-accordian-sec">
                <div id="bidaServiceAccordian" class="bida-accordian">
                    <div class="bida-acd-item">
                        <div class="accordian-head collapsed" data-bs-toggle="collapse"
                            data-bs-target="#bidaAgencyAccordian-{{ $key }}" aria-expanded="false"
                            aria-controls="bidaAgencyAccordian-{{ $key }}">
                            <span class="accordian-indicator"><span class="icon-plus"></span></span>
                            <div class="bida-acd-title">
                                <h3>{{ isset($regulatory_agency->name) ? $regulatory_agency->name : null }}</h3>
                            </div>
                        </div>
                        <div id="bidaAgencyAccordian-{{ $key }}" class="collapse">
                            <div class="bida-acd-content">
                                {!! isset($regulatory_agency->description) ? $regulatory_agency->description : null !!}
                                @if((!empty($regulatory_agency->contact_name)) || (!empty($regulatory_agency->designation)) || (!empty($regulatory_agency->mobile)) || (!empty($regulatory_agency->phone)) || (!empty($regulatory_agency->email)))
                                    <p class="text-info"><strong>Contact Information :</strong></p>
                                    <ul class="list-unstyled">
                                        @if(!empty($regulatory_agency->contact_name))
                                            <li>Contact Name: {{ ucfirst($regulatory_agency->contact_name) }}</li>
                                        @endif
                                        @if(!empty($regulatory_agency->designation))
                                            <li>Designation: {{ $regulatory_agency->designation }}</li>
                                        @endif
                                        @if(!empty($regulatory_agency->mobile))
                                            <li>Mobile: {{ $regulatory_agency->mobile }}</li>
                                        @endif
                                        @if(!empty($regulatory_agency->phone))
                                            <li>Phone: {{ $regulatory_agency->phone}}</li>
                                        @endif
                                        @if(!empty($regulatory_agency->email))
                                            <li>Email: {{ $regulatory_agency->email}}</li>
                                        @endif
                                    </ul>
                                @endif
                                {{--regulatory agency IPA details start--}}
                                    <?php
                                    if (empty($regulatory_agency->regulatory_agencies_services)) {
                                        $agency_service_names = [];
                                        $agency_service_ids = [];
                                    } else {
                                        $agency_service_names = explode(',', $regulatory_agency->regulatory_agencies_services);
                                        $agency_service_ids = explode(',', $regulatory_agency->regulatory_agencies_details_ids);
                                    }
                                    ?>
                                {{--regulatory agency IPA details end--}}
                                <p style="font-size: 11px; margin: 5px 0 0 0;">
                                    <i>Last updated: {{ Carbon\Carbon::parse($regulatory_agency->updated_at)->diffForHumans() }}</i>
                                </p>
                                {{--regulatory agency IPA details start--}}
                                @if(!empty($agency_service_names))
                                <?php
                                $flag_ipa = 0;
                                $accordion_id = "accordionIPA_" . $regulatory_agency->id;
                                ?>
                                    <div style="margin-bottom: 0px;" class="panel-group" id="{{$accordion_id}}"
                                        role="tablist"
                                        aria-multiselectable="true">

                                        @foreach($agency_service_names as $key => $service_name)
                                                <?php $service_name_slug = str_slug($service_name);?>
                                            @if($flag_ipa < 1)
                                                <p class="text-info"><strong>Available Services:</strong></p>
                                            @endif
                                            
                                            <div class="accordion mb-2" id="accordionDetails_{{ $flag_ipa }}">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#clpia_{{ $flag_ipa }}"
                                                                aria-expanded="false" aria-controls="clpia_{{ $flag_ipa }}">
                                                            {{ $service_name }}
                                                        </button>
                                                    </h2>
                                                    <div id="clpia_{{ $flag_ipa }}"
                                                        class="accordion-collapse collapse"
                                                        data-bs-parent="#accordionDetails_{{ $flag_ipa }}">
                                                        <div class="accordion-body">
                                                            <!-- Details start -->
                                                            <?php
                                                                $regulatory_agency_details = \App\Modules\Settings\Models\RegulatoryAgencyDetails::find($agency_service_ids[$key]);
                                                            ?>
                                                            @if(!empty($regulatory_agency_details->method_of_recv_service))
                                                                <strong>Service Description and Procedure : </strong>
                                                                {!! $regulatory_agency_details->method_of_recv_service !!}
                                                            @endif

                                                            @if(!empty($regulatory_agency_details->who_get_service))
                                                                <strong>Who get services?</strong>
                                                                {!! $regulatory_agency_details->who_get_service !!}
                                                            @endif

                                                            @if(!empty($regulatory_agency_details->documents))
                                                                <strong>Required Documents: </strong>
                                                                {!! $regulatory_agency_details->documents !!}
                                                            @endif
                                                            @if(!empty($regulatory_agency_details->fees))
                                                                <div><strong>Fees: </strong>
                                                                {!! $regulatory_agency_details->fees !!}
                                                                </div>
                                                            @endif

                                                            <div class="row">
                                                                <div class="col-lg-8" style="font-size: 11px;">
                                                                    <i>Last updated: {{ Carbon\Carbon::parse($regulatory_agency_details->updated_at)->diffForHumans() }}</i>
                                                                </div>

                                                                <div class="col-lg-4 float-end">
                                                                    <div id="sub_agency_div">
                                                                        <label class="radio-inline">Is this article helpful?</label>
                                                                        <label class="radio-inline">
                                                                            <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($regulatory_agency_details->id) }}" onclick="isHelpFulArticle('yes', this.value, 2)">
                                                                            Yes
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                            <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($regulatory_agency_details->id) }}" onclick="isHelpFulArticle('no', this.value, 2)">
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            
                                                            <!-- Details end -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php $flag_ipa++; ?>
                                        @endforeach
                                    </div>
                                @endif
                                {{--regulatory agency IPA details end--}}
                                @if (!empty($regulatory_agency->url))
                                    <a style="display: block; margin-top: 5px;" href="{{ $regulatory_agency->url }}" target="_blank" rel="noopener">Learn more</a>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @endforeach

        </div>
    </section>
@endsection

{{-- Page Style & Script --}}
@push('styles')
    <!-- Home Page -->
@endpush
