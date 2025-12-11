@extends('layouts.admin')

@section('page_heading', trans('messages.rollback'))

@section('content')

<section class="content container-fluid">

    <!--------------------------
    | Your Page Content Here |
    -------------------------->
    <div class="row">
        <div class="col-lg-12">


        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="container-fluid">
                <style>
                    .radio_label {
                        cursor: pointer;
                    }

                    .small-box {
                        margin-bottom: 0;
                        cursor: pointer;
                    }

                    @media (min-width: 481px) {
                        .g_name {
                            font-size: 29px;
                            height: 98px;
                            overflow: auto;
                        }
                    }

                    @media (max-width: 480px) {
                        .g_name {
                            font-size: 18px;
                        }

                        span {
                            font-size: 14px;
                        }

                        label {
                            font-size: 14px;
                        }
                    }

                    @media (min-width: 767px) {
                        .has_border {
                            border-left: 1px solid lightgrey;
                        }

                        .has_border_right {
                            border-right: 1px solid lightgrey;
                        }
                    }

                    .card-counter {
                        box-shadow: 2px 2px 10px #DADADA;
                        margin: 5px;
                        padding: 20px 10px;
                        background-color: #fff;
                        height: 100px;
                        border-radius: 5px;
                        transition: .3s linear all;
                    }

                    .card-counter:hover {
                        box-shadow: 4px 4px 20px #DADADA;
                        transition: .3s linear all;
                    }

                    .card-counter.primary {
                        background-color: #007bff;
                        color: #FFF;
                    }

                    .card-counter.danger {
                        background-color: #ef5350;
                        color: #FFF;
                    }

                    .card-counter.success {
                        background-color: #66bb6a;
                        color: #FFF;
                    }

                    .card-counter.info {
                        background-color: #26c6da;
                        color: #FFF;
                    }

                    .card-counter i {
                        font-size: 5em;
                        opacity: 0.2;
                    }

                    .card-counter .count-numbers {
                        position: absolute;
                        right: 35px;
                        top: 20px;
                        font-size: 32px;
                        display: block;
                    }

                    .card-counter .count-name {
                        position: absolute;
                        right: 35px;
                        top: 56px;
                        /*font-style: italic;*/
                        text-transform: capitalize;
                        opacity: 0.7;
                        display: block;
                        font-size: 18px;
                        max-width: 180px;
                    }
                </style>
                <br>
                <style>
                    .small-box {
                        box-shadow: 0px 1px 0px 2px rgb(0 0 0 / 10%);
                        background: #f5f4f4;
                        border-radius: 10px;
                        padding: 15px;
                    }
                </style>

                <div class="col-md-12">
                    <div class="row">

                        <div class="form-group col-lg-3 col-md-3 col-xs-6">
                            <a href="">
                                <div class="small-box">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-6">
                                            <p class="input_ban"
                                                style="color: #452A73; font-size: 34px; font-weight: 600">{{ $completed+$ongoing+$upcoming }}</p>
                                            <p style="color: #452A73; font-size: 16px; font-weight: 600">All Course </p>
                                        </div>
                                        <div class="col-md-4 col-xs-6">
                                            <div class="small-box"
                                                style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #7C5CF5, #9B8BF7); border-radius: 10px; padding: 15px; height: 100%;">
                                                <i class="fas fa-book-open" style="color:#DADADA; font-size: 16px"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-xs-6">

                            <a href="">
                                <div class="small-box">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-6">
                                            <p class="input_ban"
                                                style="color: #452A73; font-size: 34px; font-weight: 600">{{ $ongoing }}</p>
                                            <p style="color: #452A73; font-size: 16px; font-weight: 600">On-going Course
                                            </p>
                                        </div>
                                        <div class="col-md-4 col-xs-6">
                                            <div class="small-box"
                                                style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #69D4D4, #6CD2D5); border-radius: 10px; padding: 15px; height: 100%;">
                                                <i class="fas fa-chalkboard-teacher" style="color:#DADADA; font-size: 16px"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-xs-6">
                            <a href="">
                                <div class="small-box">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-6">
                                            <p class="input_ban"
                                                style="color: #452A73; font-size: 34px; font-weight: 600">{{ $upcoming }}</p>
                                            <p style="color: #452A73; font-size: 16px; font-weight: 600">Up-coming Course
                                            </p>
                                        </div>
                                        <div class="col-md-4 col-xs-6">
                                            <div class="small-box"
                                                style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #5373DF, #458DDD); border-radius: 10px; padding: 15px; height: 100%;">
                                                <i class="fas fa-address-book" style="color:#DADADA; font-size: 16px"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="form-group col-lg-3 col-md-3 col-xs-6">
                            <a href="">
                                <div class="small-box">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-6">
                                            <p class="input_ban"
                                                style="color: #452A73; font-size: 34px; font-weight: 600">{{ $completed }}</p>
                                            <p style="color: #452A73; font-size: 16px; font-weight: 600">Completed Course
                                            </p>
                                        </div>
                                        <div class="col-md-4 col-xs-6">
                                            <div class="small-box"
                                                style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #EC6060, #FC8170); border-radius: 10px; padding: 15px; height: 100%;">
                                                <i class="fas fa-user-graduate" style="color:#DADADA; font-size: 16px"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div><!--/.row-->
                </div><!--/.col-md-12(Main)-->


            </div>
        </div>
    </div>
</section>


@endsection <!--content section-->
@section('footer-script')


@endsection <!--- footer-script--->
