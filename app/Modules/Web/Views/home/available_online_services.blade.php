<div class="card text-dark bg-light">
    <div class="card-body">
        <div class="bida-accordian-sec">
            <div id="bidaServiceAccordian" class="bida-accordian">

                @foreach($availableServices as $supperName => $Services)
                        <?php $supperNameCount++ ?>

                    <div class="bida-acd-item">
                        <div class="accordian-head" data-bs-toggle="collapse" data-bs-target="#supperName-{{ $supperNameCount }}" aria-expanded="true" aria-controls="supperName-{{ $supperNameCount }}">
                            <span class="accordian-indicator"><span class="icon-plus"></span></span>
                            <div class="bida-acd-title">
                                <h3>{{ $supperName }}</h3>
                            </div>
                        </div>

                        <div id="supperName-{{ $supperNameCount }}" class="collapse show">
                            <div class="bida-acd-content">
                                <div id="supperNameInner-{{ $subNameCount }}" class="accordian-content">
                                    @foreach($Services as $subName => $Service)
                                        <?php $subNameCount++ ?>
                                        <div class="bida-2nd-lavel-acd-item bida-acd-item">
                                            <div class="accordian-head collapsed" data-bs-toggle="collapse" data-bs-target="#subName-{{ $subNameCount }}" aria-expanded="true" aria-controls="subName-{{ $subNameCount }}">
                                                <span class="accordian-indicator"><span class="icon-plus"></span></span>
                                                <div class="bida-acd-title">
                                                    <h3>{{ $subName }}</h3>
                                                </div>
                                            </div>
                                            <div id="subName-{{ $subNameCount }}" class="collapse" data-bs-parent="#supperNameInner-{{ $subNameCount }}">
                                                <div class="bida-acd-content pb-0">
                                                    {!! $Service['description'] !!}
                                                </div>
                                                <div id="sub_agency_div" class="float-end pb-3 pe-4">
                                                    <label class="radio-inline">Is this article helpful?</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($Service['sd_id']) }}" onclick="isHelpFulArticle('yes', this.value, 1)">
                                                        Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="is_helpful" id="is_helpful" value="{{ Encryption::encodeId($Service['sd_id']) }}" onclick="isHelpFulArticle('no', this.value, 1)">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>