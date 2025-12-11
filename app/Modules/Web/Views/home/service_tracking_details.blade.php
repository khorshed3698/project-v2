<div class="application-status-box">
    <div class="form-title">
        <h3>Application Information</h3>
    </div>
    <div class="srv-tracking-info">
        <div class="row row-gap">
            <div class="col-md-6">
                <div class="tracking-info-item">
                    <span class="info-label">Tracking Number</span>
                    <p>{{ $responseData['tracking_id'] }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tracking-info-item">
                    <span class="info-label">Submitted At</span>
                    <p>{{ $responseData['submit_date'] }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tracking-info-item">
                    <span class="info-label">Current Status</span>
                    <p>{{ $responseData['current_status'] }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tracking-info-item">
                    <span class="info-label">Service Name</span>
                    <p>{{ $responseData['service_name'] }}</p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="tracking-info-item">
                    <span class="info-label">Company Name</span>
                    <p>{{ $responseData['company_name'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="status-tree">
        <div class="status-steps-items">
            <ul>
                @forelse ($responseData['details'] as $item)
                    <li class="step-done">
                        <span class="steps-text">{{ $item->status_name }}</span>
                    </li>
                @empty
                @endforelse
                @if (!in_array($responseData['status'], [25, 6]))
                    <li >
                        <span class="steps-text">In Progress</span>
                    </li>
                @endif
            </ul>
        </div>
    </div> --}}
</div>
