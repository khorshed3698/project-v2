<div class="row">
    <div class="col-md-6">
        @foreach($stakeholderServices as $key => $stakeholderService)
            @if($key % 2 == 0)
                <div class="stakeholder_service_box">
                    <div class="stakeholder_service_supper down_up_arrow" data-toggle="collapse" href="#service_{{ $key + 1 }}" aria-expanded="false" aria-controls="service_{{ $key + 1 }}">
                        <div class="stakeholder_service_supper_logo">
                            @if(!empty($stakeholderService['logo']) && file_exists($stakeholderService['logo']))
                                <img src="{{ asset($stakeholderService['logo']) }}" alt="{{ $stakeholderService['process_supper_name'] }}">
                            @else
                                <img src="{{ asset('assets/images/dashboard/government_of_bangladesh.png') }}" alt="{{ $stakeholderService['process_supper_name'] }}">
                            @endif
                        </div>

                        <div class="stakeholder_service_supper_name">
                            {{ $stakeholderService['process_supper_name'] }}
                        </div>
                    </div>
                    
                    <div class="stakeholder_service_sub_name text-justify collapse" id="service_{{ $key + 1 }}">
                        @foreach($stakeholderService['process_sub_names'] as $process_sub_name)
                            <ul>
                                <li>
                                    <div class="sssn_inner">
                                        <div class="sssn_inner_left">
                                            <img src="{{ asset('assets/images/dashboard/circle.png') }}" alt="{{ $process_sub_name['name'] }}">
                                            <a href="{{ url('dashboard/apply-service') }}" onclick="redirect({{ $process_sub_name['id'] }})">{{ $process_sub_name['name'] }}</a>
                                        </div>
                                        <div class="sssn_inner_right">
                                            <a style="color: #fff; text-decoration: none;" href="{{ url('dashboard/apply-service') }}" class="btn btn-xs btn-success" role="button" onclick="redirect({{ $process_sub_name['id'] }})">Apply</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                        
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    
    <div class="col-md-6">
        @foreach($stakeholderServices as $key => $stakeholderService)
            @if($key % 2 != 0)
                <div class="stakeholder_service_box">
                    <div class="stakeholder_service_supper down_up_arrow" data-toggle="collapse" href="#service_{{ $key + 1 }}" aria-expanded="false" aria-controls="service_{{ $key + 1 }}">
                        <div class="stakeholder_service_supper_logo">
                            @if(!empty($stakeholderService['logo']) && file_exists($stakeholderService['logo']))
                                <img src="{{ asset($stakeholderService['logo']) }}" alt="{{ $stakeholderService['process_supper_name'] }}">
                            @else
                                <img src="{{ asset('assets/images/dashboard/government_of_bangladesh.png') }}" alt="{{ $stakeholderService['process_supper_name'] }}">
                            @endif
                        </div>

                        <div class="stakeholder_service_supper_name">
                            {{ $stakeholderService['process_supper_name'] }}
                        </div>
                    </div>

                    <div class="stakeholder_service_sub_name text-justify collapse" id="service_{{ $key + 1 }}">
                        @foreach($stakeholderService['process_sub_names'] as $process_sub_name)
                            <ul>
                                <li>
                                    <div class="sssn_inner">
                                        <div class="sssn_inner_left">
                                            <img src="{{ asset('assets/images/dashboard/circle.png') }}" alt="{{ $process_sub_name['name'] }}">
                                            <a href="{{ url('dashboard/apply-service') }}" onclick="redirect({{ $process_sub_name['id'] }})">{{ $process_sub_name['name'] }}</a>
                                        </div>
                                        <div class="sssn_inner_right">
                                            <a style="color: #fff; text-decoration: none;" href="{{ url('dashboard/apply-service') }}" class="btn btn-xs btn-success" role="button" onclick="redirect({{ $process_sub_name['id'] }})">Apply</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>  
</div>