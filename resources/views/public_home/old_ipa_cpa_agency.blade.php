<div class="panel panel-success" id="ipa_agency">
    <div class="panel-heading"><h5><strong>Investment Promotion Agency (IPA)</strong></h5></div>
    <div class="panel-body" style="padding: 0">
        <table class="table table-hover table-bordered table-striped" style="margin-bottom: 0" aria-label="Detailed Report Data Table">
            <thead>
                <tr>
                    <th aria-hidden="true"></th>
                </tr>
            </thead>            
            <tbody>
            <?php $ra = 1; ?>
            @forelse($regulatory_agencies->where('agency_type', 'ipa')->sortBy('order') as $key => $regulatory_agency)
                <tr>
                    <td>
                        <a class="down_up_arrow" style="cursor:pointer;" data-toggle="collapse"
                           data-target="#ipa_<?php echo $key; ?>">
                            <strong>
                                @if(!empty($regulatory_agency->name))
                                    {{ $regulatory_agency->name }}
                                @endif
                            </strong>
                        </a>

                        <div id="ipa_<?php echo $key; ?>" class="collapse">
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

                                        <div class="panel panel-info">
                                            <div style="padding: 10px 15px;" class="panel-heading" role="tab"
                                                 id="headingIPA_<?php echo $agency_service_ids[$key]; ?>">
                                                <h4 class="panel-title">
                                                    <a style="font-size: 14px;" class="down_up_arrow" role="button"
                                                       onclick="loadSubAgencyDetails('{{ \App\Libraries\Encryption::encodeId($agency_service_ids[$key])}}', '{{ $service_name_slug }}')"
                                                       data-toggle="collapse" data-parent="#{{$accordion_id}}"
                                                       href="#collapseIPA_<?php echo $agency_service_ids[$key]; ?>"
                                                       aria-controls="collapseIPA_<?php echo $agency_service_ids[$key]; ?>">
                                                        {{ $service_name }}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseIPA_<?php echo $agency_service_ids[$key]; ?>"
                                                 class="panel-collapse collapse"
                                                 role="tabpanel"
                                                 aria-labelledby="headingIPA_<?php echo $agency_service_ids[$key]; ?>"
                                                 aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="text-center" id="{{$service_name_slug}}_preloading">
                                                        <br/>
                                                        <br/>
                                                        <i class="fa fa-spinner fa-pulse fa-4x"></i>
                                                        <br/>
                                                        <br/>
                                                    </div>
                                                    <div id="{{$service_name_slug}}_content">

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
                                <a style="display: block; margin-top: 5px;" href="{{ $regulatory_agency->url }}"
                                   target="_blank" rel="noopener">Learn more</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No IPA found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-success" style="margin-bottom: 0" id="">
    <div class="panel-heading"><h5><strong>Certificate/ License/ Permit Issuing Agency (CLPIA)</strong></h5></div>
    <div class="panel-body" style="padding: 0">
        <table class="table table-hover table-bordered table-striped" style="margin-bottom: 0" id="cap_agency" aria-label="Detailed Report Data Table">
            <thead>
                <tr>
                    <th aria-hidden="true"></th>
                </tr>
            </thead>            
            <tbody>
            @forelse($regulatory_agencies->where('agency_type', 'clp')->sortBy('order') as $key => $regulatory_agency)
                <tr>
                    <td>
                        <a class="down_up_arrow" style="cursor:pointer;" data-toggle="collapse"
                           data-target="#clpia_<?php echo $key; ?>">
                            <strong>
                                @if(!empty($regulatory_agency->name))
                                    {{ $regulatory_agency->name }}
                                @endif
                            </strong>
                        </a>

                        <div id="clpia_<?php echo $key; ?>" class="collapse">
                            @if(!empty($regulatory_agency->description))
                                {!! $regulatory_agency->description !!}
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
                                <p style="font-size: 11px; margin: 5px 0 0 0;">
                                    <i>Last updated: {{ Carbon\Carbon::parse($regulatory_agency->updated_at)->diffForHumans() }}</i>
                                </p>
                            {{--regulatory agency IPA details end--}}
                            {{--                            regulatory agency CLP details start--}}
                            @if(!empty($agency_service_names))
                                <?php
                                $flag_clp = 0;
                                $accordion_id = "accordionCLP_" . $regulatory_agency->id;
                                ?>
                                <div style="margin-bottom: 0px;" class="panel-group" id="{{$accordion_id}}"
                                     role="tablist"
                                     aria-multiselectable="true">
                                    @foreach($agency_service_names as $key => $service_name)
                                        <?php $service_name_slug = str_slug($service_name);?>
                                        @if($flag_clp < 1 )
                                            <p class="text-info"><strong>Available Services:</strong></p>
                                        @endif

                                        <div class="panel panel-info">
                                            <div style="padding: 10px 15px;" class="panel-heading" role="tab"
                                                 id="headingCLP_<?php echo $agency_service_ids[$key]; ?>">
                                                <h4 class="panel-title">
                                                    <a style="font-size: 14px;" class="down_up_arrow" role="button"
                                                       onclick="loadSubAgencyDetails('{{ \App\Libraries\Encryption::encodeId($agency_service_ids[$key])}}', '{{ $service_name_slug }}')"
                                                       data-toggle="collapse" data-parent="#{{$accordion_id}}"
                                                       href="#collapseCLP_<?php echo $agency_service_ids[$key]; ?>"
                                                       aria-controls="collapseCLP_<?php echo $agency_service_ids[$key]; ?>">
                                                        {{ $service_name }}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseCLP_<?php echo $agency_service_ids[$key]; ?>"
                                                 class="panel-collapse collapse"
                                                 role="tabpanel"
                                                 aria-labelledby="headingCLP_<?php echo $agency_service_ids[$key]; ?>"
                                                 aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="text-center" id="{{$service_name_slug}}_preloading">
                                                        <br/>
                                                        <br/>
                                                        <i class="fa fa-spinner fa-pulse fa-4x"></i>
                                                        <br/>
                                                        <br/>
                                                    </div>
                                                    <div id="{{$service_name_slug}}_content"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $flag_clp++; ?>
                                    @endforeach
                                </div>
                            @endif
                            {{--                            regulatory agency CLP details end--}}

                            @if (!empty($regulatory_agency->url))
                                <a style="display: block; margin-top: 5px;" href="{{ $regulatory_agency->url }}"
                                   target="_blank" rel="noopener">Learn more</a>
                            @endif
                        </div>
                    </td>
                </tr>
                <?php $ra++; ?>
            @empty
                <tr>
                    <td colspan="2">No CLPIA found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-success" style="margin-top: 20px" id="">
    <div class="panel-heading"><h5><strong>Utility Service Provider</strong></h5></div>
    <div class="panel-body" style="padding: 0">
        <table class="table table-hover table-bordered table-striped" style="margin-bottom: 0" id="cap_agency" aria-label="Detailed Report Data Table">
            <thead>
                <tr>
                    <th aria-hidden="true"></th>
                </tr>
            </thead>            
            <tbody>
            @forelse($regulatory_agencies->where('agency_type', 'utility')->sortBy('order') as $key => $regulatory_agency)
                <tr>
                    <td>
                        <a class="down_up_arrow" style="cursor:pointer;" data-toggle="collapse"
                           data-target="#clpia_<?php echo $key; ?>">
                            <strong>
                                @if(!empty($regulatory_agency->name))
                                    {{ $regulatory_agency->name }}
                                @endif
                            </strong>
                        </a>

                        <div id="clpia_<?php echo $key; ?>" class="collapse">
                            @if(!empty($regulatory_agency->description))
                                {!! $regulatory_agency->description !!}
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
                            <p style="font-size: 11px; margin: 5px 0 0 0;">
                                <i>Last updated: {{ Carbon\Carbon::parse($regulatory_agency->updated_at)->diffForHumans() }}</i>
                            </p>
                            {{--regulatory agency IPA details end--}}
                            {{--                            regulatory agency CLP details start--}}
                            @if(!empty($agency_service_names))
                                <?php
                                $flag_clp = 0;
                                $accordion_id = "accordionCLP_" . $regulatory_agency->id;
                                ?>
                                <div style="margin-bottom: 0px;" class="panel-group" id="{{$accordion_id}}"
                                     role="tablist"
                                     aria-multiselectable="true">
                                    @foreach($agency_service_names as $key => $service_name)
                                        <?php $service_name_slug = str_slug($service_name);?>
                                        @if($flag_clp < 1 )
                                            <p class="text-info"><strong>Available Services:</strong></p>
                                        @endif

                                        <div class="panel panel-info">
                                            <div style="padding: 10px 15px;" class="panel-heading" role="tab"
                                                 id="headingCLP_<?php echo $agency_service_ids[$key]; ?>">
                                                <h4 class="panel-title">
                                                    <a style="font-size: 14px;" class="down_up_arrow" role="button"
                                                       onclick="loadSubAgencyDetails('{{ \App\Libraries\Encryption::encodeId($agency_service_ids[$key])}}', '{{ $service_name_slug }}')"
                                                       data-toggle="collapse" data-parent="#{{$accordion_id}}"
                                                       href="#collapseCLP_<?php echo $agency_service_ids[$key]; ?>"
                                                       aria-controls="collapseCLP_<?php echo $agency_service_ids[$key]; ?>">
                                                        {{ $service_name }}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseCLP_<?php echo $agency_service_ids[$key]; ?>"
                                                 class="panel-collapse collapse"
                                                 role="tabpanel"
                                                 aria-labelledby="headingCLP_<?php echo $agency_service_ids[$key]; ?>"
                                                 aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    <div class="text-center" id="{{$service_name_slug}}_preloading">
                                                        <br/>
                                                        <br/>
                                                        <i class="fa fa-spinner fa-pulse fa-4x"></i>
                                                        <br/>
                                                        <br/>
                                                    </div>
                                                    <div id="{{$service_name_slug}}_content"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $flag_clp++; ?>
                                    @endforeach
                                </div>
                            @endif
                            {{--                            regulatory agency CLP details end--}}

                            @if (!empty($regulatory_agency->url))
                                <a style="display: block; margin-top: 5px;" href="{{ $regulatory_agency->url }}"
                                   target="_blank" rel="noopener">Learn more</a>
                            @endif
                        </div>
                    </td>
                </tr>
                <?php $ra++; ?>
            @empty
                <tr>
                    <td colspan="2">No CLPIA found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
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