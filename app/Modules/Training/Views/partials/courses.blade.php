@if($course->isEmpty())
    <div class="row" id="content">
        <div class="col-md-12">
            <h3 class="text-center text-danger">কোর্স পাওয়া যায়নি</h3>
        </div>
    </div>
@else

<style>
    .help_widget {
        height: 450px;
        background: inherit;
        background-color: rgba(255, 255, 255, 1);
        border: none;
        border-radius: 10px;
        box-shadow: 0px 0px 13px rgba(0, 0, 0, 0.117647058823529);
        position: relative;
        margin-bottom: 10px;
    }

    .help_widget:hover {
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.167647058823529);
    }
    .help_widget_header{
        /*padding: 10px;*/
    }
    .help_widget_header img {
        width: 100%;
        /*border-radius: 10px;*/
        height: 200px;
        padding-top: 0 !important;
        background-position: center;
        background-size: cover;
    }

    .help_widget_content {
        padding: 0 15px;
    }

    .help_widget_content h3 {
        font-weight: 600;
        overflow: hidden; /* make sure it hides the content that overflows */
        white-space: normal; /* allow multiple lines of text */
        display: -webkit-box;
        -webkit-line-clamp: 2; /* show 2 lines of text */
        -webkit-box-orient: vertical;
        text-overflow: ellipsis; /* give the beautiful '...' effect */
        height: 3em; /* adjust the height to control the number of lines shown */
        line-height: 1.5em;
        font-size: 22px;
    }

    .help_widget_content p {
        font-size: 14px;
    }

    .footerElement {
        padding: 5px 10px;
        width: 100%;
        position: absolute;
        bottom: 0;
    }
    .green_text{
        font-size: 16px;
        color: #00a157;
    }
    @media screen and (min-width: 1900px) {
        .help_widget {
            /*height: 520px;*/
        }
        /** end -:- (Screen Greater Than 1200px) **/
    }

    .district_box {
        position: absolute;
        top: 15px;
        left: 0;
        background-color: #EBBE55;
        height: 50px;
        width: 180px;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 0.5px solid #707070;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .district_box .district_name {
        color: #333333;
        margin: 0;
        font-weight: normal;
        font-size: 32px;
        text-align: center;
        align-items: center;
    }

</style>
<div class="row" id="content">
        @foreach($course as $row)
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
            <div class="help_widget">
                <div class="help_widget_header">
                    <div class="district_box">
                        <p class="district_name">{{ !empty($row->area_nm_ban)?$row->area_nm_ban:'' }}</p>
                    </div>
                    <img alt="{{ $row->course_title }}" src="{{ asset('/uploads/training/'.$row->course_thumbnail_path) }}"
                         onerror="this.src=`{{asset('/assets/images/no-image.png')}}`" title="{{ $row->course_title }}"/>
                </div>
                <div class="row" style="padding: 5px 15px">
                            <span class="col-md-12 text-left">
                                <button class="btn btn-success btn-xs" style="border-radius: 50%; font-size: 8px"><i
                                            class="fa fa-calendar"></i></button>
                                <span class="input_ban" style="font-size: 12px">{{ trans('Training::messages.duration') }}: {{date("d-m-Y", strtotime($row->course_duration_start))}} হতে {{date("d-m-Y", strtotime($row->course_duration_end))}}</span>
                            </span>

                </div>
                <div class="help_widget_content text-left">
                        <h3 title="{{ !empty($row->master_tracking_no)?$row->master_tracking_no:'' }}">{{ $row->course_title }}</h3>
                        <span style="font-size: 20px">{{ mb_substr($row->district_office_name, 0, 40, 'UTF-8') }}</span>
                        <br>
                        <span style="color: #811B8C">রেজিস্ট্রেশন শেষ:</span>
                        @php
                            $enroll_deadline = date("d M", strtotime($row->enroll_deadline));
                            $enroll_deadline = \App\Libraries\CommonFunction::convertDate2Bangla($enroll_deadline);
                        @endphp
                        <span class="" style="color: #811B8C"> {{ $enroll_deadline }}</span>
                    <div class="row footerElement">
                        <div class="pull-left">
                            <p class="green_text">
                                <b class="input_ban"><span>{{ trans('Training::messages.price') }} : </span>{{ round($row->amount) }}</b><b> টাকা</b>
                            </p>
                            <p class="green_text">
                                <b class="input_ban"><span>{{ trans('Training::messages.service_fee') }} :</span> {{$fixedServiceFeeAmount ?? 0}}</b><b> টাকা</b>
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


</div>
<div class="text-center" id="pagingControls"></div>
{{--<script src="{{asset('assets/plugins/jquery-paginate/flexible.pagination.js')}}"></script>--}}
<script>
    // $('#content').flexiblePagination({
    //     itemsPerPage: 4,
    // });

</script>

@endif
