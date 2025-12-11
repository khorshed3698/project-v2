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

<div class="pull-left" style="font-size: 11px; margin: 5px 0 0 0;">
    <i>Last updated: {{ Carbon\Carbon::parse($regulatory_agency_details->updated_at)->diffForHumans() }}</i>
</div>

<div class="pull-right">
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
