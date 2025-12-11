@extends('layouts.plane')

@section('style')
    @include('public_home.style')
@endsection
<style>
    .btn-selected {
    background-color: #007bff; /* Selected button color */
    color: white;
}

</style>
@section ('body')
    @include('public_home.top-notice')
    @include('public_home.header')
    @include('public_home.urgent-notice')
    <div class="container">
        
        <div class="row">
            <div class="col-sm-12">
                <div style="background-color: #D92923; margin-bottom: 10px !important;" class="label label-success welcome_label">
                    <h2><a style="color: #fff; text-decoration: none;" href="{{ url('articles/one-stop-service') }}">Welcome to BIDA One Stop Service (OSS) Portal</a></h2>
                </div>
            </div>
        </div>

{{--        <div class="row">--}}
{{--            <div class="col-sm-12">--}}
{{--                <picture>--}}
{{--                    <source srcset="{{ asset('assets/images/dashboard/service-provider-list.webp') }}" type="image/webp">--}}
{{--                    <source srcset="{{ asset('assets/images/dashboard/service-provider-list.jpg') }}" type="image/jpeg">--}}
{{--                    <img src="{{ asset('assets/images/dashboard/service-provider-list.jpg') }}" class="img-responsive" alt="services image">--}}
{{--                </picture>--}}
{{--            </div>--}}
{{--        </div>--}}

        @if(count($notice) > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info ticker-section">
                        <div class="col-md-1">
                            <p class="newsTicker-title" style="margin: 7px 0">
                                @if(count($notice) > 0)
                                    <strong>{!! trans('messages.latest_notice_title') !!}</strong>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-11">

                            @if(count($notice) > 0)
                                <?php
                                echo '<div class="TickerNews">';
                                echo '<div class="ti_wrapper">';
                                echo '<div class="ti_slide">';
                                echo '<div class="ti_content">';

                                $arr = $notice;
                                for ($i = 0; $i < count($arr); $i++) {
                                    echo '<div class="ti_news"><a href="single-notice/' . Encryption::encodeId($arr[$i]->id) . '">' . $arr[$i]->heading . '</a></div>';

                                }
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                ?>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div id="goto-login-section"></div>
    <div class="container" style="margin-top: 10px">
        <div class="row" id="HomePageFlexOrderDiv">
            <div class="col-md-8" id="HomePageFlexOrderDiv_1">
                @include('public_home.home_slider')

                <div class="btn-group btn-group-justified btn-group-lg" id="home_button">
                    <a onclick="viewPageCount('easeOfDoingBusiness')" id="easeOfDoingBusiness"
                       href="http://bida.gov.bd/doing-business" target="_blank" rel="noopener"
                       class="btn btn-success">
                        <i class="fa fa-sitemap" style="color: #288742"></i>
                        <span>Ease of Doing Business</span>
                    </a>
                    <a onclick="viewPageCount('startNewBusiness')" id="startNewBusiness"
                       href="http://bida.gov.bd/?page_id=133" target="_blank" rel="noopener"
                       class="btn btn-primary">
                        <i class="fa fa-user" style="color: #D6392A"></i>
                        <span>Start New Business</span>
                    </a>
                    <a id="licenceApplication"
                       class="btn btn-warning">
                        <i class="fas fa-info-circle" style="color: #3174DB"></i>
                        <span>Business Sector</span>
                    </a>
                    {{-- training-menu --}}
                    <a  id="training"
                    href="{{ url('bida/training-list') }}" rel="noopener"
                    class="btn btn-info">
                     <i class="fa fa-book-open" style="color: #5bc0de"></i>
                     <span style="line-height: 50px">Training</span>
                 </a>
                </div>

                @include('public_home.report')
            </div>

            <div class="col-md-4" id="HomePageFlexOrderDiv_2">
                <div>
                    @include('public_home.login_panel')
                </div>
                <div id="sdg_tracker">
                    <a href="http://www.sdg.gov.bd/" target="_blank" rel="noopener">
                        <div class="well text-center">
                            <picture>
                                <source srcset="{{ asset('assets/images/SDG_tracker.webp') }}" type="image/webp">
                                <source srcset="{{ asset('assets/images/SDG_tracker.jpg') }}" type="image/jpeg">
                                <img style="width: 100%;" src="{{ asset('assets/images/SDG_tracker.jpg') }}" alt="SDG Tracker Bangladesh Development Mirror"/>
                            </picture>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Include a simple form for month and year selection -->
            <?php
                use Carbon\Carbon;

                $months = Carbon::now()->month;
                $previousMonth1 = Carbon::now()->subMonth(1)->month;
                $previousMonth2 = Carbon::now()->subMonth(2)->month;
                $currentMonthName = Carbon::now()->format('F');
                $previousMonth1Name = Carbon::now()->subMonth(1)->format('F');
                $previousMonth2Name = Carbon::now()->subMonth(2)->format('F');

                $year = Carbon::now()->year;
                $previousYear1 = $year - 1;
                $previousYear2 = $year - 2;
            ?>

            <div class="container">
                <form id="dynamicDataForm">
                    <div class="form-group">
                        <label for="service_type">Select Service:</label>
                        <select name="service_type" id="service_type" class="form-control">
                            <option value="1" selected>Service 1</option>
                            <option value="2">Service 2</option>
                            <option value="3">Service 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="month">Select Month:</label>
                        <select name="month" id="month" class="form-control">
                            <option value="{{ $months }}" selected>{{ $currentMonthName }}</option>
                            <option value="{{ $previousMonth1 }}">{{ $previousMonth1Name }}</option>
                            <option value="{{ $previousMonth2 }}">{{ $previousMonth2Name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Select Year:</label>
                        <select name="year" id="year" class="form-control">
                            <option value="{{ $year }}" selected>{{ $year }}</option>
                            <option value="{{ $previousYear1 }}">{{ $previousYear1 }}</option>
                            <option value="{{ $previousYear2 }}">{{ $previousYear2 }}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <div id="result" class="mt-4"></div>

        </div>
    </div>
    <script>
        $(document).ready(function () {
            function fetchData(serviceType, month, year) {
                // $('#result').html('<div>Loading...</div>');
                $.ajax({
                    url: '{{ url('bida-oss-landing/dataSet') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    data: {
                        service_type: serviceType,
                        month: month,
                        year: year,
                    },
                    success: function (response) {
                        $('#result').html(response.data);
                    },
                    error: function (xhr, status, error) {
                        let errorMessage = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }

                        console.log(errorMessage);
                        // $('#result').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                    },
                });
            }

            const defaultServiceType = $('#service_type').val();
            const defaultMonth = $('#month').val();
            const defaultYear = $('#year').val();
            fetchData(defaultServiceType, defaultMonth, defaultYear);

            $('#dynamicDataForm').submit(function (e) {
                e.preventDefault();
                const serviceType = $('#service_type').val();
                const month = $('#month').val();
                const year = $('#year').val();
                fetchData(serviceType, month, year);
            });
        });

    </script>
    @include('public_home.footer')
@stop
