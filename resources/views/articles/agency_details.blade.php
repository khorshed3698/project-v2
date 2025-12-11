<?php
$get_agency_details = \App\Libraries\CommonFunction::getAgencyDetailsInfo($regulatory_agency->regulatory_agencies_details_ids);
$accordion_id = "accordionDetails_" . $regulatory_agency->id . '_'.$key;
?>

@if($get_agency_details != false)
    <div style="margin-bottom: 0;" class="panel-group" id="{{$accordion_id}}" role="tablist" aria-multiselectable="true">
        <p class="text-info"><strong>Available Services:</strong></p>
        @foreach($get_agency_details as $agency_details_key => $agency_details)
            <div class="panel panel-info">
                <div style="padding: 10px 15px;" class="panel-heading" role="tab" id="headingAgencyDetails_{{ $agency_details_key.'_'.$key }}">
                    <h4 class="panel-title">
                        <a style="font-size: 14px;" class="down_up_arrow" role="button"
                           data-toggle="collapse" data-parent="#{{$accordion_id}}"
                           href="#collapseAgencyDetails_{{ $agency_details_key.'_'.$key }}"
                           aria-controls="collapseAgencyDetails_{{ $agency_details_key.'_'.$key }}">
                            {{ $agency_details->service_name }}
                        </a>
                    </h4>
                </div>
                <div id="collapseAgencyDetails_{{ $agency_details_key.'_'.$key }}"
                     class="panel-collapse collapse"
                     role="tabpanel"
                     aria-labelledby="headingAgencyDetails_{{ $agency_details_key.'_'.$key }}"
                     aria-expanded="false" style="height: 0;">
                    <div class="panel-body">
                        @if(!empty($agency_details->method_of_recv_service))
                            <strong>Service Description and Procedure : </strong>
                            {!! $agency_details->method_of_recv_service !!}
                        @endif

                        @if(!empty($agency_details->who_get_service))
                            <strong>Who get services?</strong>
                            {!! $agency_details->who_get_service !!}
                        @endif

                        @if(!empty($agency_details->documents))
                            <strong>Required Documents: </strong>
                            {!! $agency_details->documents !!}
                        @endif
                        @if(!empty($agency_details->fees))
                            <div><strong>Fees: </strong>
                                {!! $agency_details->fees !!}
                            </div>
                        @endif

                        <div class="pull-left" style="font-size: 11px; margin: 5px 0 0 0;">
                            <i>Last updated: {{ Carbon\Carbon::parse($agency_details->updated_at)->diffForHumans() }}</i>
                        </div>

                        <div class="pull-right">
                            <div id="sub_agency_div">
                                <label class="radio-inline">Is this article helpful?</label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($agency_details->id) }}" onclick="isHelpFulArticle('yes', this.value, 2)">
                                    Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($agency_details->id) }}" onclick="isHelpFulArticle('no', this.value, 2)">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif