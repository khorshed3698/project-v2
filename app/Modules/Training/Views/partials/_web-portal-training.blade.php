@extends('public_home.front')

@section('header-resources')
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}"/>
    @include('public_home.web-page-sections.social-icon-top', ['title' => '', 'description' => '', 'image' => asset(Cache::get('logo-info')->logo)])
    <style>
        .home-wrapper {
            margin-top: 0;
        }

        .custom_nav {
            background-color: #35B94A;
            border-radius: 22px;
            padding: 5px;
        }

        .custom_nav > li > a {
            color: #fff !important;
            border-radius: 22px;
        }

        .custom_nav > li.active > a {
            color: #35B94A !important;
        }

        .custom_nav > li {
            width: 33% !important;
            text-align: center !important;
        }

        .custom_nav > li > a:hover, .custom_nav > li > a:focus {
            color: #35B94A !important;
            background: #fff !important;
        }

        #dropdownMenuButton {
            background: #ffffff;
            border-top: 1px solid #CECECE;
            border-bottom: 1px solid #CECECE;
        }

        #filter {
            border-top-right-radius: 25px;
            border-bottom-right-radius: 25px;
        }

        /** start -:- (training_data_count_section) **/
        .training_data_count_section ul {
            display: grid;
            grid-template-columns: repeat(3, auto);
            justify-content: center;
            gap: 20px;
        }

        .training_data_count_section .list-inline li {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 4px;
            /*background-color: #35B94A;*/
            padding: 12px 0;
            text-align: center;
            width: 270px;
        }

        .training_data_count_section .list-inline li p {
            color: #FFFFFF;
            margin: 0;
        }

        .training_data_count_section .training_data_title {
            font-size: 23px;
        }

        .training_data_count_section .training_data_value {
            font-size: 40px;
            font-weight: bold;
        }

        @media screen and (max-width: 574px) {

            .training_data_count_section ul{
                grid-template-columns: repeat(1, auto);
            }

            .training_data_count_section .list-inline li {
                width: 100%;
                padding: 5px 10px 5px 10px;
            }
            .training_data_count_section .training_data_title {
                font-size: 21px;
                overflow-wrap: break-word;
                word-wrap: break-word;
            }

            .training_data_count_section .training_data_value {
                font-size: 35px;
            }

            /** end -:- (Screen Less Than 574) **/
        }

        @media screen and (max-width: 374px) {
            .training_data_count_section .list-inline li {
                width: 100%;
            }
            .training_data_count_section .training_data_title {
                font-size: 18px;
                overflow-wrap: break-word;
                word-wrap: break-word;
            }

            .training_data_count_section .training_data_value {
                font-size: 32px;
            }

            /** end -:- (Screen Less Than 374) **/
        }

        @media screen and (max-width: 820px) {
            .training_data_count_section .list-inline li {
                width: 200px;
            }

            /** end -:- (Screen Less Than 374) **/
        }

        /** end -:- (training_data_count_section) **/
    </style>
@endsection

@section ('body')
    @include('public_home.web-page-sections.social-icon-data')

    <?php
    $getDivision = getDivision();
    $getCourse = getCourse();
    $getOffice = getOffice();
    ?>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="training_data_count_section">
                    <ul class="list-inline">

                        <li class="total_participation_li" style="background-color: #fca944">
                            <p class="training_data_title">প্রশিক্ষণের ধরন</p>
                            <p class="input_ban training_data_value trainingTypeData"><span class="comma-format"></span></p>
                        </li>

                        <li class="total_participation_li" style="background-color: #19b598">
                            <p class="training_data_title">মোট রেজিস্ট্রেশন</p>
                            <p class="input_ban training_data_value totalParticipantRegistered"></p>
                        </li>
                        <li class="total_participation_li" style="background-color: #ed3f39">
                            <p class="training_data_title">প্রশিক্ষণ সম্পন্নকারী</p>
                            <p class="input_ban training_data_value totalParticipantCompleted"></p>
                        </li>

                    </ul>
                </div>
            </div><!--./training_data_count_section-->
        </div><!--./row-->
    </div><!--./container-->
    <br/>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="input-group">
                    <input type="text" style="border-radius: 50px 0 0 50px" class="form-control input-lg"
                           placeholder="{!! trans('Training::messages.write_here') !!}" id="txtSearch"/>
                    <div class="input-group-btn">
                        <button class="btn btn-lg click" type="button" style="padding-top: 8.2px"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                        <button id="filter" class="btn btn-success btn-lg filter" type="button"
                                style="padding-top: 8.2px" data-id="input-search">
                            <span class="glyphicon glyphicon-search"> </span><span> {!! trans('Training::messages.search') !!}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg-left dropdown pull-right"
                             aria-labelledby="dropdownMenu2"
                             style="width: 340px; color:black; border:1px solid #ccc; ">
                            <div class="col-md-12">
                                <form class="px-4 py-3 ">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::select('division', $getDivision, '', ['class' => 'form-control required', 'placeholder' => 'নির্বাচন বিভাগ','id'=>'division']) !!}
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::select('district', [], '', ['class' => 'form-control required', 'placeholder' => 'নির্বাচন জেলা','id'=>'district']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::select('office', $getOffice,'', ['class' => 'form-control', 'placeholder'=>'অফিস নির্বাচন করুন', 'id' => 'office']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::select('course', $getCourse,'', ['class' => 'form-control', 'placeholder'=>'কোর্স নির্বাচন করুন', 'id' => 'course']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-primary col-md-offset-5 filter" data-id=""
                                            onMouseOver="this.style.background='#3c763d'" id="filter"><span
                                                class="fa fa-filter"></span> Filter
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!--./col-md-6 col-md-offset-3-->
        </div><!-- ./row-->
    </div><!--./container-->
    <br>
    <div id="list_8" class="tab-pane">
        <div class="box box-solid no-margin">
            <div class="box-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs custom_nav col-md-8 col-md-offset-2" role="tablist">
                    <li role="presentation" class="active">
                        <a class="dynamic_tab" href="" aria-controls="upcoming" role="tab" data-toggle="tab">{!! trans('Training::messages.upcoming_course') !!}</a>
                    </li>
                    <li role="presentation">
                        <a class="dynamic_tab" href="" aria-controls="ongoing" role="tab" data-toggle="tab">{!! trans('Training::messages.ongoing_course') !!}</a>
                    </li>
{{--                    <li role="presentation">--}}
{{--                        <a class="dynamic_tab" href="list_22" aria-controls="closed" role="tab" data-toggle="tab">{!! trans('Training::messages.closed_course') !!}</a>--}}
{{--                    </li>--}}
                    <li role="presentation">
                        <a id="allCourse" class="dynamic_tab" href="#list5_7" aria-controls="training-course" role="tab" data-toggle="tab">প্রশিক্ষন সেবা</a>
                    </li>
                </ul>
                <br>
                <!-- Nav tabs Content Loaded -->
                <div id="load_content"></div>
            </div>
        </div>
    </div><!--#/list_8-->

    <script src="{{ asset('/assets/plugins/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset("assets/plugins/toastr/toastr.min.js") }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var elements = document.querySelectorAll('.comma-format');

            elements.forEach(function(element) {
                var value = element.textContent;
                var formattedValue = numberWithCommas(value);
                element.textContent = formattedValue;
            });

            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        });
    </script>

    <script>

        // Get the input field
        var input = document.getElementById("txtSearch");

        // Execute a function when the user releases a key on the keyboard
        input.addEventListener("keyup", function (event) {
            // Number 13 is the "Enter" key on the keyboard
            if (event["keyCode"] === 13) {
                // Cancel the default action, if needed
                event.preventDefault();
                // Trigger the button element with a click
                document.getElementById("filter").click();
            }
        });
        $('.dynamic_tab').on('click', function () {
            var flag = $(this).attr('aria-controls');
            $.ajax({
                type: "GET",
                url: "{{ url('training/load-content') }}",
                dataType: "json",
                data: {
                    flag: flag
                },
                beforeSend: function () {
                    $('#load_content').html('<div class="row"><div class="col-md-12"><h3 class="text-center text-info">Loading...<i class="fa fa-spinner fa-spin"></i></h3></div></div>');
                },
                success: function (response) {
                    $('#load_content').html(response.html);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#load_content').html('<div class="row"><div class="col-md-12"><h3 class="text-center text-danger">Something went wrong !</h3></div></div>');
                }
            })
        });
        $(document).ready(function () {
            getTrainingDataCardCount();
            var flag = 'upcoming';
            $.ajax({
                type: "GET",
                url: "{{ url('training/load-content') }}",
                dataType: "json",
                data: {
                    flag: flag
                },
                beforeSend: function () {
                    $('#load_content').html('<div class="row"><div class="col-md-12"><h3 class="text-center text-info">Loading...<i class="fa fa-spinner fa-spin"></i></h3></div></div>');
                },
                success: function (response) {
                    $('#load_content').html(response.html);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#load_content').html('<div class="row"><div class="col-md-12"><h3 class="text-center text-danger">Something went wrong !</h3></div></div>');
                }
            });
        });// Document Ready

        $('.click').click(function (e) {
            $(this).siblings('.dropdown').fadeToggle(100);
        });

        $("#division").change(function () {
            var divisionId = $('#division').val();
            $.ajax({
                type: "GET",
                url: "/training/get-district-by-division-id",
                data: {
                    divisionId: divisionId
                },
                success: function (response) {
                    var option = '<option value="">নির্বাচন জেলা</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#district").html(option);
                }
            });
        });

        $("#district").change(function () {
            var districtId = $('#district').val();
            $.ajax({
                type: "GET",
                url: "/training/get-office-by-district-id",
                data: {
                    districtId: districtId
                },
                success: function (response) {
                    var option = '<option value="">অফিস নির্বাচন করুন</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#office").html(option);
                }
            });
        });

        $("#office").change(function () {
            var districtId = $('#office').val();
            $.ajax({
                type: "GET",
                url: "/training/get-course-by-office",
                data: {
                    districtId: districtId
                },
                success: function (response) {
                    var option = '<option value="">কোর্স নির্বাচন করুন</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#course").html(option);
                }
            });
        });

        $(".filter").on('click', function () {
            var dataId = $(this).attr('data-id');
            var division_id = $("#division").val();
            var district_id = $("#district").val();
            var thana_id = $("#thana").val();
            var course_id = $("#course").val();
            txtSearch = '';
            if (dataId == 'input-search') {
                var txtSearch = $("#txtSearch").val();
                if (txtSearch == '') {
                    toastr.options = {
                        positionClass: "toast-bottom-right",
                    }
                    toastr.error('<b>Please enter course title</b>');
                    return false;
                }
            } else {
                if (course_id == '' && district_id == '' && course_id == '') {
                    toastr.options = {
                        positionClass: "toast-bottom-right",
                    }
                    toastr.error('<b>Please select at list one</b>');
                    return false;
                }
            }
            $.ajax({
                type: "GET",
                url: "/training/filter-data",
                data: {
                    division_id: division_id,
                    district_id: district_id,
                    thana_id: thana_id,
                    course_id: course_id,
                    txtSearch: txtSearch,
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        $('.dropdown').fadeOut();
                        $('#load_content').html(response.html);
                    }
                }
            });
        });

        function getTrainingDataCardCount() {
            $.ajax({
                type: "GET",
                url: "{{ url('training/get-data-card/count') }}",
                dataType: "json",
                data: {},
                beforeSend: function () {

                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        $('.totalParticipantRegistered').text(response.totalParticipantRegistered.toString().replace(/(\d)(?=(\d\d)+\d$)/g, "$1,"));
                        $('.totalParticipantCompleted').text(response.totalParticipantCompleted.toString().replace(/(\d)(?=(\d\d)+\d$)/g, "$1,"));
                        $('.trainingTypeData').text(response.trainingTypeData.toString().replace(/(\d)(?=(\d\d)+\d$)/g, "$1,"));
                    } else {
                        $('.totalParticipantRegistered').text(0);
                        $('.totalParticipantCompleted').text(0);
                        $('.trainingTypeData').text(0);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('.totalParticipantRegistered').text(0);
                    $('.totalParticipantCompleted').text(0);
                    $('.trainingTypeData').text(0);
                    console.log('Error : ' + errorThrown);
                },
                complete: function () {

                }
            });
        }// end -:- getTrainingDataCardCount()
    </script>
    @include('public_home.web-page-sections.social-icon-bottom')
@endsection
