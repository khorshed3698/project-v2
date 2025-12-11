<div class="card text-bg-success mb-3" id="ipa_agency">
    <div class="card-header">
        Investment Promotion Agency (IPA)
    </div>
    <div class="card-body" style="padding: 0">
        <div class="accordion" id="accordionFlushExample">
            <?php $ra = 1; ?>
            @forelse($regulatory_agencies->where('agency_type', 'ipa')->sortBy('order') as $key => $regulatory_agency)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ipa_{{ $key }}" aria-expanded="false" aria-controls="ipa_{{ $key }}">
                            @if(!empty($regulatory_agency->name))
                                {{ $regulatory_agency->name }}
                            @endif
                        </button>
                    </h2>
                    <div id="ipa_{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            @if(!empty($regulatory_agency->description))
                                {!!  $regulatory_agency->description !!}
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
                            @if((!empty($regulatory_agency->contact_name)) || (!empty($regulatory_agency->designation)) || (!empty($regulatory_agency->mobile)) || (!empty($regulatory_agency->phone)) || (!empty($regulatory_agency->email)))
                                <p class="text-primary mb-1"><strong>Contact Information :</strong></p>

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
                            <p style="font-size: 11px; margin: 5px 0 0 0;">
                                <i>Last updated: {{ Carbon\Carbon::parse($regulatory_agency->updated_at)->diffForHumans() }}</i>
                            </p>
                            {{--regulatory agency IPA details start--}}

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
                                <p><a href="{{ $regulatory_agency->url }}" target="_blank" rel="noopener" class="link-underline-primary">Learn more</a></p>
                            @endif
                        </div>
                    </div>
                </div>

            @empty
                <p>No IPA found.</p>
            @endforelse
        </div>
    </div>
</div>


<div class="card text-bg-success mb-3" id="clpia_agency">
    <div class="card-header">
        Certificate/ License/ Permit Issuing Agency (CLPIA)
    </div>
    <div class="card-body" style="padding: 0">
        <div class="accordion" id="accordionClpia">
            @forelse($regulatory_agencies->where('agency_type', 'clp')->sortBy('order') as $key => $regulatory_agency)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#clpia_{{ $key }}" aria-expanded="false" aria-controls="clpia_{{ $key }}">
                            @if(!empty($regulatory_agency->name))
                                {{ $regulatory_agency->name }}
                            @endif
                        </button>
                    </h2>
                    <div id="clpia_{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#accordionClpia">
                        <div class="accordion-body">
                            @if(!empty($regulatory_agency->description))
                                {!! $regulatory_agency->description !!}
                            @endif
                            <p style="font-size: 11px; margin: 5px 0 0 0;">
                                <i>Last updated: {{ Carbon\Carbon::parse($regulatory_agency->updated_at)->diffForHumans() }}</i>
                            </p>
                            {{--regulatory agency IPA details end--}}

                            @if (!empty($regulatory_agency->url))
                                <p><a href="{{ $regulatory_agency->url }}" target="_blank" rel="noopener" class="link-underline-primary">Learn more</a></p>
                            @endif
                        </div>
                    </div>
                </div>

            @empty
                <p>No CLPIA found.</p>
            @endforelse
        </div>
    </div>
</div>



<div class="card text-bg-success mb-3" id="utility_agency">
    <div class="card-header">
        Utility Service Provider
    </div>
    <div class="card-body" style="padding: 0">
        <div class="accordion" id="accordionUtility">
            @forelse($regulatory_agencies->where('agency_type', 'utility')->sortBy('order') as $key => $regulatory_agency)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#utility_{{ $key }}" aria-expanded="false" aria-controls="utility_{{ $key }}">
                            @if(!empty($regulatory_agency->name))
                                {{ $regulatory_agency->name }}
                            @endif
                        </button>
                    </h2>
                    <div id="utility_{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#accordionUtility">
                        <div class="accordion-body">
                            @if(!empty($regulatory_agency->description))
                                {!! $regulatory_agency->description !!}
                            @endif
                            <p style="font-size: 11px; margin: 5px 0 0 0;">
                                <i>Last updated: {{ Carbon\Carbon::parse($regulatory_agency->updated_at)->diffForHumans() }}</i>
                            </p>
                            {{--regulatory agency IPA details end--}}

                            @if (!empty($regulatory_agency->url))
                                <p><a href="{{ $regulatory_agency->url }}" target="_blank" rel="noopener" class="link-underline-primary">Learn more</a></p>
                            @endif
                        </div>
                    </div>
                </div>

            @empty
                <p>No CLPIA found.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    $('.panel-collapse').on('shown.bs.collapse', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $panel = $(this).closest('.panel');
        $('html,body').animate({
            scrollTop: $panel.offset().top
        }, 'slow');
    });
</script>