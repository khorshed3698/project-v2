<?php
$current_supper_name = '';
$is_new_supper = 1; // 1 = new
$is_closed = 0; // 0 = no
$supper_key = 1;
?>

@forelse($dynamicSection as $key => $service)
    {{-- Super name start --}}
    <?php
    $service_name_slug = str_slug($service->process_sub_name) . $service->sd_id;
    if ($key == 0) {
        $current_supper_name = $service->process_supper_name;
    } else {
        if ($current_supper_name != $service->process_supper_name) {
            $current_supper_name = $service->process_supper_name;
            $is_new_supper = 1;
            $supper_key++;
        } else {
            $is_new_supper = 0;
        }
    }
    ?>

    @if ($is_new_supper == 1)
        <div class="bida-acd-item">
            <div class="accordian-head collapsed" data-bs-toggle="collapse"
                data-bs-target="#bidaAgencyAccordian-{{ $supper_key }}" aria-expanded="false"
                aria-controls="bidaAgencyAccordian-{{ $supper_key }}">
                <span class="accordian-indicator"><span class="icon-plus"></span></span>
                <div class="bida-acd-title">
                    <h3>{{ $current_supper_name }}</h3>
                </div>
            </div>
            <div id="bidaAgencyAccordian-{{ $supper_key }}" class="collapse">
                <div class="bida-acd-content">
                    <div id="bidaIPA_Accordian" class="accordian-content">

                        <div class="bida-2nd-lavel-acd-item bida-acd-item" onclick="LoadAvailableServiceDetails('{{ \App\Libraries\Encryption::encodeId($service->sd_id) }}', '{{ $service_name_slug }}')">
                            <div class="accordian-head collapsed" data-bs-toggle="collapse"
                                data-bs-target="#bidaAgencyAccordian-{{ $service_name_slug }}" aria-expanded="true"
                                aria-controls="bidaAgencyAccordian-{{ $service_name_slug }}">
                                <span class="accordian-indicator"><span class="icon-plus"></span></span>
                                <div class="bida-acd-title">
                                    <h3>
                                        {{ !empty($service->process_sub_name) ? $service->process_sub_name : 'Learn more' }}
                                    </h3>
                                </div>
                            </div>
                            <div id="bidaAgencyAccordian-{{ $service_name_slug }}" class="collapse"
                                data-bs-parent="#bidaIPA_Accordian">
                                <div class="bida-acd-content" id="{{ $service_name_slug }}_content">
                                    
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif
@empty
@endforelse
