    <div class="srv-data-info-box">
        @if (!empty($responseData['bida_oss_public_service_feedback']))
            @foreach ($responseData['bida_oss_public_service_feedback'] as $feedback)
                <div class="data-infobox-item">
                    <p>Organization</p>
                    <span class="info-num">{{ $feedback['Organization'] }}</span>
                </div>
                <div class="data-infobox-item">
                    <p>Service</p>
                    <span class="info-num">{{ $feedback['Service'] }}</span>
                </div>
                <div class="data-infobox-item">
                    <p>Number of Feedback</p>
                    <span class="info-num">{{ $feedback['Number of Feedback'] }}</span>
                </div>
                <div class="data-infobox-item">
                    <p>Feedback Ratings (out of 5)</p>
                    <span class="info-num">{{ $feedback['Feedback Ratting (out of 5)'] }}</span>
                </div>
            @endforeach
        @else
            <div class="data-infobox-item">
                <p>Organization</p>
                <span class="info-num">-</span>
            </div>
            <div class="data-infobox-item">
                <p>Service</p>
                <span class="info-num">-</span>
            </div>
            <div class="data-infobox-item">
                <p>Number of Feedback</p>
                <span class="info-num">-</span>
            </div>
            <div class="data-infobox-item">
                <p>Feedback Ratings (out of 5)</p>
                <span class="info-num">-</span>
            </div>
        @endif
    </div>
    
    <div class="srv-data-info-table">
        <div class="srv-data-table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-striped bidaSrvDataInfoTable">
                    <thead>
                        <tr>
                            <th>Entity</th>
                            <th>Service</th>
                            <th>Stipulated Delivery <br> Timeline</th>
                            {{-- <th>Actual Delivery <br> Days <span class="td-text-light">(Average)</span></th> --}}
                            <th>Disposed <br> Applications</th>
                            <th>% Within Stipulated <br> Timelines</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($responseData['bida_oss_public_service_data']))
                            <?php
                                $prevEntity = null;
                                $rowspanCount = 0;
                            ?>
                            @foreach ($responseData['bida_oss_public_service_data'] as $serviceData)
                                <?php
                                    if ($serviceData['Entity'] !== $prevEntity) {
                                        $rowspanCount = collect($responseData['bida_oss_public_service_data'])
                                            ->where('Entity', $serviceData['Entity'])
                                            ->count();
                                    }
                                ?>
                                <tr>
                                    {{-- Entity column start --}}
                                    @if ($serviceData['Entity'] !== $prevEntity) <!-- Display entity only for the first row -->
                                        <td rowspan="{{ $rowspanCount }}" class="text-start align-top">
                                            <div class="entry-title">
                                                <span class="entry-title-icon">
                                                    <img src="{{ $serviceData['logo'] ?: asset('assets/default-icon.svg') }}"
                                                        alt="Icon" style="width: 30px;">
                                                </span>
                                                <span class="entry-title-text">{{ $serviceData['Entity'] }}</span>
                                            </div>
                                        </td>
                                        <?php $prevEntity = $serviceData['Entity']; ?> <!-- Update previous entity -->
                                    
                                    @endif
                                    {{-- Entity column end --}}
                                    <td class="text-start">{{ $serviceData['Service'] }} {{ isset($serviceData['Agency/Section']) ? '(' . $serviceData['Agency/Section'] . ')' : null }}</td>
                                    <td>{{ $serviceData['Stipulated Delivery Timeline'] }}</td>
                                    <td>{{ $serviceData['Disposed'] }}</td>
                                    <td>{{ $serviceData['% within Stipulated Timelines'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bottom-collapse-sec">
        <p class="data-sec-title" data-bs-toggle="collapse" data-bs-target="#expandDataSec">Click to know the explanation of calculation of data</p>
        <div id="expandDataSec" class="collapse show">
            <div class="expand-data-content">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Source of the documentary</strong></p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p><strong>{{ $dateTimeText ?: null }}</strong></p>
                    </div>
                </div>
                <p>The One-Stop Service (OSS) Rules specify the timeline within which each service must be delivered. Based on these rules, the Service Level Agreement (SLA) is calculated to determine the duration for delivering each service. Any organization capable of providing all services within the stipulated SLA timeframe will fall under the SLA framework. However, if an organization fails to deliver services within the specified timeframe, it will not qualify under the SLA framework. The SLA's service delivery timeline begins from the initiation of the service to the point where the applicant receives the service. Notably, all public holidays are excluded from this timeframe</p>
            </div>
        </div>
    </div>