@extends('layouts.front')

@section('style')
    <style>
        .panel-title {
            text-align: left;
        }
        .panel-body {
            padding: 15px;
        }
    </style>
@endsection

@section('content')
    @include('articles.top-navbar')
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="box-div">
                <h3>Available online services</h3>

                <div class="panel panel-success">

                    <div class="panel-body" style="padding: 0">
                        <table class="table table-hover table-bordered table-striped" style="margin-bottom: 0" id="available_service_click" aria-label="Detailed Report Data Table">
                            <thead>
                                <tr class="d-none">
                                    <th aria-hidden="true" scope="col"></th>
                                </tr>
                            </thead>
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
                                            <a style="font-size: 14px;" class="down_up_arrow" role="button" data-toggle="collapse" data-parent="#accordion_service_<?php echo $supper_key; ?>" href="#collapse_service_<?php echo $key; ?>" aria-controls="collapse_service_<?php echo $key; ?>">
                                                {{ !empty($service->process_sub_name) ? $service->process_sub_name : 'Learn more' }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_service_<?php echo $key; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_service_<?php echo $key; ?>">
                                        <div class="panel-body">

                                            <div>{!! $service->description !!}</div>

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
                                                <a style="font-size: 14px;" class="down_up_arrow" role="button" data-toggle="collapse" data-parent="#accordion_service_<?php echo $supper_key; ?>" href="#collapse_service_<?php echo $key; ?>" aria-controls="collapse_service_<?php echo $key; ?>">
                                                    {{ $service->process_sub_name }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse_service_<?php echo $key; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_service_<?php echo $key; ?>">
                                            <div class="panel-body">
                                                <div>{!! $service->description !!}</div>
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
    </div>
    </div>
    <div class="col-md-4 hidden-sm hidden-xs">
        @include('public_home.login_panel')
    </div>
    </div>
@endsection

@section('footer-script')
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
@endsection