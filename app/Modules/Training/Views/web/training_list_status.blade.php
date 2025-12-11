@foreach ($course as $row)
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
        <div class="help_widget">
            <div class="help_widget_header">
                <img alt="{{ $row->course->course_title }}"
                    src="{{ asset('/uploads/training/course/' . $row->course_thumbnail_path) }}"
                    onerror="this.src=`{{ asset('/assets/images/no-image.png') }}`"
                    title="{{ $row->course->course_title }}" />
            </div>
            <div class="row" style="padding: 5px 15px">
                <span class="col-md-12 text-left">
                    <button class="btn btn-success btn-xs" style="border-radius: 50%; font-size: 10px; height: 20px; width: 20px;"><i
                            class="fa fa-calendar"></i></button>
                    <span class="input_ban" style="font-size: 12px">Duration:
                        {{ date('d F Y', strtotime($row->course_duration_start)) }} -
                        {{ date('d F Y', strtotime($row->course_duration_end)) }}</span>
                </span>

            </div>
            <div class="help_widget_content text-left">
                <h3>{{ $row->course->course_title }}</h3>
                <span style="font-size: 20px">{{ mb_substr($row->venue, 0, 40, 'UTF-8') }}</span>
                <br>
                    <?php
                    $enroll_deadline = strtotime($row->enroll_deadline);
                    $current_date = strtotime(date('Y-m-d'));
                    ?>
                    @if($enroll_deadline >= $current_date)
                    <span style="color: #811B8C">Registration Deadline:</span>
                    <span class="" style="color: #811B8C">
                        {{ date('d M', $enroll_deadline) }}</span>
                    @else
                        <span class="text-danger">Registration Closed</span>
                    @endif
                <div class="row footerElement">
                    <div class="pull-left">
                        {{-- <p class="green_text">
                            @if ($row->amount == 0)
                                <b class="input_ban">Free</b>
                            @else
                                <b class="input_ban"><span>Price
                                        : </span>{{ round($row->amount) }}</b><b> Taka</b>
                            @endif
                        </p> --}}
                    </div>
                    <div class="pull-right">
                        <a href="{{ url('bida/training-details/' . \App\Libraries\Encryption::encodeId($row->id)) }}"
                            class="btn btn-success btn-sm"
                            style="font-size: 13px">{{ $row->enroll_deadline >= date('Y-m-d') ? 'Apply' : 'Open' }}
                            <i class="fa fa-arrow-right"></i> </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endforeach
