@if ($shortfall_applications['count'] > 0)
    <div class="row mb-20">
        <div class="col-md-12" >
            <ul class="app-list">
                @foreach($shortfall_applications['data'] as $shortfall_application)
                    <li>
                        <span class="text-danger">Attention Required</span>
                        (Shortfall) for {{ $shortfall_application->service_name }}
                        {{ empty($shortfall_application->tracking_no) ? '' : ' / '.$shortfall_application->tracking_no }}

                        <i style="color: rgb(148, 148, 148)">Updated {{ Carbon\Carbon::parse($shortfall_application->updated_at)->diffForHumans() }}</i>
                        <a href="{{ url('process') .'/' . $shortfall_application->form_url . '/'.$shortfall_application->appRedirectPath['edit'].'/' . Encryption::encodeId($shortfall_application->ref_id) . '/' . Encryption::encodeId($shortfall_application->process_type_id) }}" class="btn btn-xs btn-primary button-color" style="margin : 0 15px;">
                            <i class="fa fa-folder-open"></i> Open
                        </a>
                    </li>
                @endforeach

            </ul>

            @if ($shortfall_applications['count'] > 4)
                <div class="text-left" style="margin-top: 5px;">
                    <a href="{{ url('process/list') }}" class="btn btn-sm ">More Shortfall Applications </a>
                </div>
            @endif
        </div>
    </div>
@endif

@if ($draft_applications['count'] > 0)
    <div class="row mb-20">
        <div class="col-md-12">
            <ul class="app-list">
                @foreach($draft_applications['data'] as $draft_application)
                    <li>
                        Draft application for {{ $draft_application->service_name }}
                        {{ empty($draft_application->tracking_no) ? '' : ' / '.$draft_application->tracking_no }}
                        <i style="color: rgb(148, 148, 148)">Updated {{ Carbon\Carbon::parse($draft_application->updated_at)->diffForHumans() }}</i>
                        <a href="{{ url('process') .'/' . $draft_application->form_url . '/'.$draft_application->appRedirectPath['edit'].'/' . Encryption::encodeId($draft_application->ref_id) . '/' . Encryption::encodeId($draft_application->process_type_id) }}" class="btn btn-xs btn-success button-color" style="color: white; margin : 0px 15px;">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </a>
                    </li>
                @endforeach
            </ul>

            @if ($draft_applications['count'] > 4)
                <div class="text-left" style="margin-top: 5px;">
                    <a href="{{ url('process/list') }}" class="btn btn-sm ">More Draft Applications </a>
                </div>
            @endif
        </div>
    </div>
@endif