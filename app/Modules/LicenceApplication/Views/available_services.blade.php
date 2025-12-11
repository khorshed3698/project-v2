<div class="panel panel-success" style="margin-bottom: 0">
    <div class="panel-heading"><h5><strong>Available Services</strong></h5></div>
    <div class="panel-body" style="padding: 0">
        <table class="table table-hover table-bordered table-striped" style="margin-bottom: 0" id="available_service_click">
            <tbody>

            <?php
            $current_supper_name = '';
            $is_new_supper = 1; // 1 = new
            $is_closed = 0; // 0 = no
            $supper_key = 1;
            ?>

            @forelse($dynamicSection as $key => $service)

            {{--Super name start--}}
            <?php
            $service_name_slug = str_slug($service->process_sub_name).$service->sd_id;
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


            @if($is_new_supper == 1)

            @if ($key != 0)
            </td></tr></div></div>
@endif

<tr>
    <td>
        <a class="down_up_arrow" style="cursor:pointer;" data-toggle="collapse"  id="supper_services" data-target="#supper_services_<?php echo $supper_key; ?>">
            <strong>{{ $current_supper_name }}</strong>
        </a>
        <div id="supper_services_<?php echo $supper_key; ?>" class="collapse in">
            <div style="margin-bottom: 0px;" class="panel-group" id="accordion_service_<?php echo $supper_key; ?>" role="tablist" aria-multiselectable="true">
                {{--sub name start--}}
                <div style="margin-top: 10px;" class="panel panel-info">
                    <div style="padding: 10px 15px;" class="panel-heading" role="tab" id="heading_service_<?php echo $key; ?>">
                        <h4 class="panel-title">
                            <a style="font-size: 14px;" class="down_up_arrow" role="button" data-toggle="collapse" data-parent="#accordion_service_<?php echo $supper_key; ?>" href="#collapse_service_<?php echo $key; ?>" aria-controls="collapse_service_<?php echo $key; ?>" onclick="LoadAvailableServiceDetails('{{ \App\Libraries\Encryption::encodeId($service->sd_id)}}', '{{ $service_name_slug }}')">
                                {{ !empty($service->process_sub_name) ? $service->process_sub_name : 'Learn more' }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_service_<?php echo $key; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_service_<?php echo $key; ?>">
                        <div class="panel-body">
                            <div class="text-center" id="{{$service_name_slug}}_preloading">
                                <br/>
                                <br/>
                                <i class="fa fa-spinner fa-pulse fa-4x"></i>
                                <br/>
                                <br/>
                            </div>
                            <div id="{{$service_name_slug}}_content"></div>

                            <div class="pull-left" id="{{$service_name_slug}}_updated_at"></div>
                            <div class="pull-right">
                                <div id="availbe_service_div">
                                    <label class="radio-inline">Is this article helpful?</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($service->sd_id) }}" onclick="isHelpFulArticle('yes', this.value, 1)">
                                        Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($service->sd_id) }}" onclick="isHelpFulArticle('no', this.value, 1)">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--sub name end--}}
                @else
                    {{--sub name start--}}
                    <div class="panel panel-info">
                        <div style="padding: 10px 15px;" class="panel-heading" role="tab" id="heading_service_<?php echo $key; ?>">
                            <h4 class="panel-title">
                                <a style="font-size: 14px;" class="down_up_arrow" role="button" data-toggle="collapse" data-parent="#accordion_service_<?php echo $supper_key; ?>" href="#collapse_service_<?php echo $key; ?>" aria-controls="collapse_service_<?php echo $key; ?>" onclick="LoadAvailableServiceDetails('{{ \App\Libraries\Encryption::encodeId($service->sd_id)}}', '{{ $service_name_slug }}')">
                                    {{ $service->process_sub_name }}
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_service_<?php echo $key; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_service_<?php echo $key; ?>">
                            <div class="panel-body">
                                <div class="text-center" id="{{$service_name_slug}}_preloading">
                                    <br/>
                                    <br/>
                                    <i class="fa fa-spinner fa-pulse fa-4x"></i>
                                    <br/>
                                    <br/>
                                </div>
                                <div id="{{$service_name_slug}}_content"></div>
                                <div class="pull-left" id="{{$service_name_slug}}_updated_at"></div>

                                <div class="pull-right">
                                    <div id="availbe_service_div">
                                        <label class="radio-inline">Is this article helpful?</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($service->sd_id) }}" onclick="isHelpFulArticle('yes', this.value, 1)">
                                            Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($service->sd_id) }}" onclick="isHelpFulArticle('no', this.value, 1)">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
{{--sub name end--}}
{{--Super name end--}}
@endif
@empty
    <tr>
        <td colspan="2">No service info</td>
    </tr>
    @endforelse
    </tbody>
    </table>
    </div>
    </div>




