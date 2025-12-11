<div id="noticeTab" class="tab-pane {!! (Request::segment(2)=='index' OR Request::segment(2)=='')?'active':'' !!}">
    <div class="panel-body">
        <div class="row">
            @if($notice)
                <div class="col-md-12">
                    <?php $arr = $noticeall; ?>
                    <table class="table basicDataTable" style="margin-bottom: 0px;" aria-label="Detailed Report Data Table">
                        <thead>
                            <tr>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($arr as $key => $value)
                            <?php
                            if (in_array(0, array($key))) {
                                $class = 'collapse in';
                            } else {
                                $class = 'collapse';
                            }
                            ?>
                            @if($value->prefix == 'board-meeting')
                                <tr>
                                    <td width="150px">
                                        <div class="notice">
                                            <span style="font-size: 13px;" class="asdf">{{ CommonFunction::changeDateFormat(substr($value->update_date, 0, 10)) }}</span>
                                            <br>
                                            <a class="down_up_arrow" style="cursor:pointer; font-size: 14px; display: block;" data-toggle="collapse" data-target="#notice_<?php echo $key; ?>">
                                                <strong>{{ $value->heading }}</strong>
                                            </a>
                                            <div style="font-size: 14px" id="notice_<?php echo $key; ?>" class="notice-collapse {{ $class }}">
                                                {!!  $value->details !!}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td width="150px">
                                        <div class="notice">
                                            <span style="font-size: 13px;">{{ CommonFunction::changeDateFormat(substr($value->update_date, 0, 10)) }}</span><br>
                                            <a class="down_up_arrow" style="cursor:pointer; font-size: 14px; display: block;" data-toggle="collapse" data-target="#notice_<?php echo $key; ?>">
                                                <strong>{{ $value->heading }}</strong>
                                            </a>
                                            <div style="font-size: 14px" id="notice_<?php echo $key; ?>" class="notice-collapse {{ $class }}">
                                                {!!  $value->details !!}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    $('.notice-collapse').on('shown.bs.collapse', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('html,body').animate({
            scrollTop: $(this).closest('.notice').offset().top
        }, 'slow');
    });
</script>