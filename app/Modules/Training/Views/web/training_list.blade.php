@extends('layouts.front')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
    .home-wrapper {
        margin-top: 0;
    }

    .custom_nav {
        background-color: #35B94A;
        border-radius: 22px;
        padding: 5px;
    }

    .custom_nav>li>a {
        color: #fff !important;
        border-radius: 22px;
    }

    .dynamic_tab {
        border-radius: 18px !important;
    }

    .custom_nav>li.active>a {
        color: #35B94A !important;
    }

    .custom_nav>li {
        width: 33% !important;
        text-align: center !important;
    }

    .custom_nav>li>a:hover,
    .custom_nav>li>a:focus {
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

        .training_data_count_section ul {
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

    .help_widget_header {
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
        overflow: hidden;
        /* make sure it hides the content that overflows */
        white-space: normal;
        /* allow multiple lines of text */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* show 2 lines of text */
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
        /* give the beautiful '...' effect */
        height: 3em;
        /* adjust the height to control the number of lines shown */
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

    .green_text {
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

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="training_data_count_section">
                            <ul class="list-inline">

                                <li class="total_participation_li" style="background-color: #fca944">
                                    <p class="training_data_title">Course Category</p>
                                    <p class="input_ban training_data_value trainingTypeData"><span
                                            class="comma-format">{{ number_format($total_category) }}</span></p>
                                </li>

                                <li class="total_participation_li" style="background-color: #19b598">
                                    <p class="training_data_title">Total Participents</p>
                                    <p class="input_ban training_data_value totalParticipantRegistered">
                                        {{ number_format($total_participants) }}</p>
                                </li>
                                <li class="total_participation_li" style="background-color: #ed3f39">
                                    <p class="training_data_title">Total Schedule</p>
                                    <p class="input_ban training_data_value totalParticipantCompleted">
                                        {{ number_format($course->count()) }}</p>
                                </li>

                            </ul>
                        </div>
                    </div><!--./training_data_count_section-->
                </div><!--./row-->
            </div><!--./container-->
            <br />
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="input-group">
                            <input type="text" style="border-radius: 50px 0 0 50px" class="form-control input-lg"
                                placeholder="Write here" id="txtSearch" />
                            <div class="input-group-btn">
                                <button id="filter" class="btn btn-success btn-lg filter" type="button"
                                    style="padding-top: 8.2px" data-id="input-search">
                                    <span class="glyphicon glyphicon-search"> </span><span> Search</span>
                                </button>
                            </div>
                        </div>
                    </div><!--./col-md-6 col-md-offset-3-->
                </div><!-- ./row-->
                <div class="row" id="training_search_keywords_row" style="display: none;">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="training_search_keywords">
                            <h5 class="training_search_keywords_text"></h5>
                        </div>
                    </div>
                </div>
            </div><!--./container-->
            <br>
            <div id="list_8" class="tab-pane">
                <div class="box box-solid no-margin">
                    <div class="box-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs custom_nav col-md-8 col-md-offset-2" role="tablist">
                            <li role="presentation" class="active" id="upcoming" onclick="loadData(this.id)">
                                <a class="dynamic_tab" aria-controls="upcoming" role="tab" data-toggle="tab">Up-coming
                                    Course</a>
                            </li>
                            <li role="presentation" id="ongoing" onclick="loadData(this.id)">
                                <a class="dynamic_tab" aria-controls="ongoing" role="tab" data-toggle="tab">On-going
                                    Course </a>
                            </li>
                            <li role="presentation" id="allCourse" onclick="loadData(this.id)">
                                <a class="dynamic_tab" href="#tab_completed" aria-controls="training-course"
                                    role="tab" data-toggle="tab">Training Course</a>
                            </li>
                        </ul>
                        <br>
                        <!-- Nav tabs Content Loaded -->
                        <div id="load_content">
                            <div class="row tab-content" id="content" style="margin-top: 60px">



                            </div>
                        </div>
                    </div>
                </div>
            </div><!--#/list_8-->
        </div>
    </div>
    <script src="{{ asset("assets/scripts/jquery-1.12.4.min.js") }}"></script>
    <script src="{{ asset('assets/scripts/datatable/dataTables.bootstrap.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadData('upcoming');
        });

        function loadData(id) {
            var url = '{{ url('bida/get-training-data') }}';
            var data = {
                status: id
            };
            $.ajax({
                url: url,
                type: 'GET',
                data: data,
                beforeSend: function() {
                    $('#content').html(
                        '<div class="row"><div class="col-md-12"><h3 class="text-center text-info">Loading...<i class="fa fa-spinner fa-spin"></i></h3></div></div>'
                    );
                },
                success: function(response) {
                    if(response == ''){
                        toastr.options = {
                            positionClass: "toast-bottom-right",
                        }
                        toastr.error('<b>No data found</b>');
                        $('#content').html(
                            '<div class="row"><div class="col-md-12"><h3 class="text-center text-info">No Data Found</h3></div></div>'
                        );
                    }
                    else{
                        $('#content').html(response);
                    }
                }
            });
        }

        $("#filter").on('click', function() {
            var txtSearch = $("#txtSearch").val();
            if (txtSearch == '') {
                toastr.options = {
                    positionClass: "toast-bottom-right",
                }
                toastr.error('<b>Please enter course title</b>');
                return false;
            }

            $.ajax({
                type: "GET",
                url: "{{ url('bida/training/filter-data') }}",
                data: {
                    txtSearch: txtSearch,
                },
                beforeSend: function() {
                    $('#content').html(
                        '<div class="row"><div class="col-md-12"><h3 class="text-center text-info">Loading...<i class="fa fa-spinner fa-spin"></i></h3></div></div>'
                    );
                },
                success: function(response) {
                    if (response == '') {
                        toastr.options = {
                            positionClass: "toast-bottom-right",
                        }
                        toastr.error('<b>No data found</b>');
                        $('#content').html(
                            '<div class="row"><div class="col-md-12"><h3 class="text-center text-info">No Data Found</h3></div></div>'
                        );
                    }
                    else{
                        $('#content').html(response);
                    }
                }
            });
        });
    </script>
@endsection
