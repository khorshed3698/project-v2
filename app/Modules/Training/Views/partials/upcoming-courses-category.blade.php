<style>
    .help_widget_d {
        height: 450px;
        background: inherit;
        background-color: rgba(255, 255, 255, 1);
        border: none;
        border-radius: 10px;
        box-shadow: 0px 0px 13px rgba(0, 0, 0, 0.117647058823529);
        position: relative;
        margin-bottom: 10px;
    }

    .help_widget_d:hover {
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.167647058823529);
    }

    .help_widget_header_d img {
        width: 90%;
        height: 25vh;
        margin-top: 15px;
        border-radius: 10px;
        padding-top: 0 !important;
    }

    .help_widget_content {
        padding: 0 15px;
    }

    .help_widget_content h3 {
        font-weight: 600;
    }

    .help_widget_content p {
        font-size: 16px;
    }

    .footerElement {
        padding: 5px 10px;
        width: 100%;
        position: absolute;
        bottom: 0;
    }
</style>
@if(count($upComingCourses) > 0)
<div class="col-md-12">
    <h3 style="color: #00a157;text-align: center;">{{ $category_name }} এর আরো কোর্স সমূহ</h3>
</div>
@endif
@foreach($upComingCourses as $row)

    <div class="col-md-4 col-xs-12 col-sm-6 item">
        <div class="help_widget_d">
            <div class="help_widget_header_d text-center">
                <img src="{{ asset('/uploads/training/'.$row->course_thumbnail_path) }}"
                     onerror="this.src=`{{asset('/assets/images/no-image.png')}}`"/>
            </div>
            <div class="row" style="padding: 5px 15px">
                            <span class="col-md-12 text-left">
                                <button class="btn btn-success btn-xs" style="border-radius: 50%; font-size: 8px"><i
                                            class="fa fa-calendar"></i></button>
                                <span class="input_ban" style="font-size: 12px">{{ trans('Training::messages.duration') }}: {{date("d-m-Y", strtotime($row->course_duration_start))}} হতে {{date("d-m-Y", strtotime($row->course_duration_end))}}</span>
                            </span>
            </div>
            <div class="help_widget_content text-left">
                <h3>{{ mb_substr($row->course_title, 0, 45, 'UTF-8') }}</h3>

                <span style="font-size: 20px">{{ $row->district_office_name }}</span>
                <br>
                <span style="color: #811B8C">রেজিস্ট্রেশন শেষ:</span><span class="input_ban" style="color: #811B8C"> {{date("d-m-Y", strtotime($row->enroll_deadline))}}</span>

                <div class="row footerElement">
                    <div class="pull-left">
                        <p style="font-size: 16px; color: #00a157;">
                            <b class="input_ban" style="font-size:18px"><span>{{ trans('Training::messages.price') }} : {{ $row->amount}}</b><b style="font-size:18px"> টাকা</b>
                        </p>
                        <p style="font-size: 16px; color: #00a157;">
                            <b class="input_ban" style="font-size:18px"><span>{{ trans('Training::messages.service_fee') }} :</span> {{$fixedServiceFeeAmount ?? 0}}</b><b style="font-size:18px"> টাকা</b>
                        </p>
                    </div>

                    <div class="pull-right">
                        <a href="/training-details/{{\App\Libraries\Encryption::encodeId($row->id)}}"
                           class="btn btn-success btn-sm"
                           style="font-size: 13px">{{$row->enroll_deadline >= \Carbon\Carbon::now() ? trans('Training::messages.apply'): trans('Training::messages.open') }} |<i class="fa fa-arrow-right"></i> </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endforeach