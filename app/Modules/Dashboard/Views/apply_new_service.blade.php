@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2.min.css') }}">
    <style>
        .app-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .app-list li {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        .dash-box-heading {
            color: #2C388B;
            font-size: 16px;
        }
        .dash-box {
            padding: 10px 15px;
            position: relative;
            display: block;
            margin-bottom: 20px;
            background-color: #FFFFFF;
            box-shadow: 3px 3px 5px #B5B5B585;
            border-radius: 11px;
        }
        .dash-box p {
            margin: 0;
            padding: 0;
            color: #333;
        }
        .dash-box-inner {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .dash-box-inner-left {
            flex-grow: 1;
        }
        .dash-box-inner-left h3 {
            /*font-size: 30px;*/
            color: #fff;
            letter-spacing: 0;
            margin: 0;
            padding: 0;
        }
        .dash-box-inner-right {
            flex-shrink: 0;
            flex-basis: 50px;
            border-radius: 11px;
            opacity: 1;
        }
        .dash-img {
            padding: 20px 10px;
        }
        .sssn_inner {
            display: flex;
            align-items: center;
        }
        .sssn_inner_left {
            flex-grow: 1;
        }
        .sssn_inner_right {
            flex-basis: 50px;
            flex-shrink: 0;
        }
        .down_up_arrow::after {
            font-weight: bold;
        }
        .right_sidebar_instruction {
            background: #F7F7FF 0% 0% no-repeat padding-box;
            box-shadow: 0 0 5px #DFDFDF;
            border-radius: 6px;
            opacity: 1;
            padding: 15px;
        }
        .right_sidebar_box {
            background: #FFFFFF 0% 0% no-repeat padding-box;
            box-shadow: 2px 2px 4px #F4F7FC;
            border: 1px solid #E6E6E6;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .right_sidebar_supper {
            display: flex;
            align-items: center;
            padding: 10px;
            cursor: pointer;
        }
        .right_sidebar_supper_logo {
            margin-right: 10px;
        }
        .right_sidebar_supper_logo img {
            max-width: 100px;
            height: auto;
        }
        .right_sidebar_supper_name {
            font-size: 14px;
            color: #393939;
            width: 100%;
        }
        .right_sidebar_anchor,
        .right_sidebar_anchor:hover,
        .right_sidebar_anchor:focus,
        .right_sidebar_anchor:active {
            text-decoration: none;
        }
        .right_sidebar_box {
            position: relative;
        }
        .right_sidebar_supper {
            position: relative;
            z-index: 1;
        }
        .right_sidebar_sub_name {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 20px 25px -5px, rgba(0, 0, 0, 0.04) 0px 10px 10px -5px;
            border-radius: 0px 0px 6px 6px;
            position: absolute;
            top: 46px;
            left: 0;
            z-index: 2;
            width: 100%;
            background-color: #fff;
        }
        .right_sidebar_sub_name ul {
            margin: 0;
            padding-left: 15px;
            list-style: none;
        }
        .right_sidebar_sub_name ul li {
            padding: 10px 0px;
            border-top: 1px solid #eee;
        }
        .right_sidebar_sub_name ul li a {
            color: #397CB9;
            text-decoration: none;
        }
        .down_up_arrow {
            position: relative;
        }
        .down_up_arrow::after {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: rgb(230, 240, 255);
            z-index: 99;
            text-align: center;
        }
        #search-heading {
            text-align: center;
            color: #004c99;
            font-weight: bold;
            font-size: 22px;
        }
        #search-bar {
            border-radius: 25px;
            height: 3px;
            padding: 3px;
            background-color: #9966ff;
        }
        /* Select a service search */
        .searchInput,
        .select2 {
            height: 40px !important;
            border: none;
            border-radius: 0px;
        }
        .search-application:hover {
            box-shadow: 1px 1px 8px 1px #dcdcdc;
            border-radius: 30px;
        }
        .search-btn {
            background-color: white;
            border: none;
            border-radius: 30px 0 0 30px;
            color: rgba(0, 0, 0, 0.8);
            height: 40px;
            width: 40px;
            border-right: none;
        }
        .applyBtn {
            height: 40px;
            border-radius: 20px;
            width: 90px;
            font-size: 18px;
            line-height: 25px;
            color: aliceblue;
            font-weight: bold;
            background-color: #9966ff;
        }
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 0 solid rgba(0, 0, 0, 0);
            border-radius: 7px 0 0 7px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 39px;
            font-size: 16px;
        }
        .select2-selection__rendered {
            height: 39px;
        }
        .select2-search__field {
            box-shadow: 0px 0px 8px 0px #918e8e;
        }
        .select2-container .select2-dropdown .select2-results ul {
            background: #fff;
            border: 1px solid #dcdcdc;
            box-shadow: 1px 1px 8px 1px #918e8e;
        }
        .select2-container .select2-dropdown .select2-search input {
            outline: none !important;
            border: 1px solid #dcdcdc !important;
            border-bottom: none !important;
            padding: 4px 6px !important;
            /* margin-top: 1px; */
        }
        .select2-container .select2-dropdown .select2-search {
            padding: 0;
        }
        .select2.select2-container .select2-selection {
            margin-bottom: 15px;
            outline: none !important;
            transition: all .15s ease-in-out;
            -webkit-border-radius: 0px;
            -moz-border-radius: 0px;
            border-radius: 0px;
            border: 0px;
            height: 40px;
        }
        .select2-selection__rendered {
            max-width: 400px;
        }
        .search-content {
            display: flex;
            justify-content: center;
            padding: 0px 30px;
        }
        .search-application {
            width: 80%;
            padding-left: 30px;
        }
        .select2 {
            width: 100% !important;
        }
        .searchInput,
        .select2 {
            border: 2px solid #337AB7;
            border-radius: 7px 0 0 7px;
            height: 50px !important;
        }
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 0 solid rgba(0, 0, 0, 0);
            border-radius: 7px 0 0 7px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px;
            font-size: 16px;
        }
        .select2-selection__rendered {
            height: 50px;
        }
        .searchBtn {
            height: 50px;
            border-radius: 7px;
            width: 140px;
            font-size: 16px;
            line-height: 35px;
            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
            font-weight: bold;
        }
        /*new dashboard design start*/
        .dash-box-heading {
            color: #2C388B;
            font-size: 16px;
        }
        .dash-box {
            padding: 7px 15px;
            position: relative;
            display: block;
            margin-bottom: 20px;
            background-color: #FFFFFF;
            box-shadow: 3px 3px 5px #B5B5B585;
            border-radius: 11px;
        }
        .dash-box p {
            margin: 0;
            padding: 0;
            color: #333;
        }
        .dash-box-inner {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .dash-box-inner-left {
            flex-grow: 1;
        }
        .dash-box-inner-left h3 {
            /*font-size: 30px;*/
            color: #fff;
            letter-spacing: 0;
            margin: 0;
            padding: 0;
        }
        .dash-box-inner-right {
            flex-shrink: 0;
            flex-basis: 50px;
            border-radius: 11px;
            opacity: 1;
        }
        .dash-img {
            padding: 10px 5px;
        }
        .sssn_inner {
            display: flex;
            align-items: center;
        }
        .sssn_inner_left {
            flex-grow: 1;
        }
        .sssn_inner_right {
            flex-basis: 50px;
            flex-shrink: 0;
        }
        .down_up_arrow::after {
            font-weight: bold;
        }
        .right_sidebar_instruction {
            background: #F7F7FF 0% 0% no-repeat padding-box;
            box-shadow: 0 0 5px #DFDFDF;
            border-radius: 6px;
            opacity: 1;
            padding: 15px;
        }
        .right_sidebar_box {
            background: #FFFFFF 0% 0% no-repeat padding-box;
            box-shadow: 2px 2px 4px #F4F7FC;
            border: 1px solid #E6E6E6;
            border-radius: 8px;
            margin-bottom: 10px;

        }
        .right_sidebar_supper {
            display: flex;
            align-items: center;
            padding: 10px;
            cursor: pointer;
        }
        .right_sidebar_supper_logo {
            margin-right: 10px;
        }
        .right_sidebar_supper_logo img {
            max-width: 100px;
            height: auto;
        }
        .right_sidebar_supper_name {
            font-size: 14px;
            color: #393939;
            width: 100%;
        }
        .right_sidebar_anchor,
        .right_sidebar_anchor:hover,
        .right_sidebar_anchor:focus,
        .right_sidebar_anchor:active {
            text-decoration: none;
        }
        .right_sidebar_box {
            position: relative;
        }
        .right_sidebar_supper {
            position: relative;
            z-index: 1;
        }
        .right_sidebar_sub_name {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 20px 25px -5px, rgba(0, 0, 0, 0.04) 0px 10px 10px -5px;
            border-radius: 0px 0px 6px 6px;
            position: absolute;
            top: 46px;
            left: 0;
            z-index: 2;
            width: 100%;
            background-color: #fff;
        }
        .right_sidebar_sub_name ul {
            margin: 0;
            padding-left: 15px;
            list-style: none;
        }
        .right_sidebar_sub_name ul li {
            padding: 10px 0px;
            border-top: 1px solid #eee;
        }
        .right_sidebar_sub_name ul li a {
            color: #397CB9;
            text-decoration: none;
        }
        .down_up_arrow {
            position: relative;
        }
        .down_up_arrow::after {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: rgb(230, 240, 255);
            z-index: 99;
            text-align: center;
        }
        #search-heading {
            text-align: center;
            color: #004c99;
            font-weight: bold;
            font-size: 22px;
        }
        #search-bar {
            border-radius: 25px;
            height: 3px;
            padding: 3px;
            background-color: #9966ff;
        }
        /* Select a service search */
        .searchInput,
        .select2 {
            height: 40px !important;
            border: none;
            border-radius: 0px;
        }
        .search-application:hover {
            box-shadow: 1px 1px 8px 1px #dcdcdc;
            border-radius: 30px;
        }
        .search-btn {
            background-color: white;
            border: none;
            border-radius: 30px 0 0 30px;
            color: rgba(0, 0, 0, 0.8);
            height: 40px;
            width: 40px;
            border-right: none;
        }
        .applyBtn {
            height: 40px;
            border-radius: 20px;
            width: 90px;
            font-size: 18px;
            line-height: 25px;
            color: aliceblue;
            font-weight: bold;
            background-color: #9966ff;
        }
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 0 solid rgba(0, 0, 0, 0);
            border-radius: 7px 0 0 7px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 39px;
            font-size: 16px;
        }
        .select2-selection__rendered {
            height: 39px;
        }
        .select2-search__field {
            box-shadow: 0px 0px 8px 0px #918e8e;
        }
        .select2-container .select2-dropdown .select2-results ul {
            background: #fff;
            border: 1px solid #dcdcdc;
            box-shadow: 1px 1px 8px 1px #918e8e;
        }
        .select2-container .select2-dropdown .select2-search input {
            outline: none !important;
            border: 1px solid #dcdcdc !important;
            border-bottom: none !important;
            padding: 4px 6px !important;
            /* margin-top: 1px; */
        }
        .select2-container .select2-dropdown .select2-search {
            padding: 0;
        }
        .select2.select2-container .select2-selection {
            margin-bottom: 15px;
            outline: none !important;
            transition: all .15s ease-in-out;
            -webkit-border-radius: 0px;
            -moz-border-radius: 0px;
            border-radius: 0px;
            border: 0px;
            height: 40px;
        }
        .select2-selection__rendered {
            max-width: 400px;
        }
        .search-content {
            display: flex;
            justify-content: center;
            padding: 0px 30px;
        }
        .search-application {
            width: 80%;
            padding-left: 30px;
        }
        .select2 {
            width: 100% !important;
        }
        .text-left{
            text-align: left;
            background-color: transparent;
            color: #1C60AB;
            /* margin-bottom: 30px; */
        }
        .text-left a, .text-left a:hover, .text-left a:focus, .text-left a:active{
            background-color: transparent;
            color: #1C60AB;
            border: 1px solid #1C60AB;
            box-shadow: 3px 3px 5px #e7e3e385;
        }
        .table-gray strong{
            color: rgb(148, 148, 148);
            font-weight: 400;
        }

        .table>tbody>tr:first-child>td {
            border-top: none;
        }

        .table>tbody>tr>td{
            padding: 5px;
        }

        #helpDiv {
            position: fixed;
            bottom: 5px;
            width: auto;
            right: 10px;
        }

        .bg-gradient-box-1 {
            background: linear-gradient(90deg, rgb(221, 211, 211) 0%, rgba(220,220,222,1) 45%, rgb(155, 206, 216) 100%);
        }
        .bg-gradient-box-2 {
            background: linear-gradient(90deg, rgb(255, 180, 180) 0%, rgba(220,220,222,1) 45%, rgb(148, 147, 221) 100%);
        }
        .bg-gradient-box-3 {
            background: linear-gradient(90deg, rgb(139, 201, 198) 0%, rgba(220,220,222,1) 45%, rgb(216, 191, 137) 100%);
        }
        .bg-gradient-box-4 {
            background: linear-gradient(90deg, rgb(219, 207, 169) 0%, rgba(220,220,222,1) 45%, rgb(167, 94, 148) 100%);
        }
        .mb-20{
            margin-bottom: 20px;
        }

        @media screen and (max-width: 592px) {
            .select2-selection__rendered {
                max-width: 200px !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row container-fluid m-auto" style="margin: auto;">
        <div class="col-md-12">

            {{-- Cart --}}
            <div class="row" style="margin-top: 1rem;">
                <div class="col-lg-3 col-md-6 col-xs-6">
                    {!! Form::open([
                        'url' => '/process/list',
                        'method' => 'POST',
                        'id' => $user_applications[0]->my_desk_app != 0 ? 'myDeskButtonForm' : '',
                        'role' => 'form',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class="dash-box" id="myDeskButton" style="background: linear-gradient(135deg, #7C5CF5 0%, #7C5CF5 45%, #ffffff 100%, #ffffff 100%);">
                        <div style="position: absolute; top: 0; bottom: 0; right: 30%;">
                            <img style="width: 100px;" src="{{ asset('assets/images/dashboard/cart_bg.svg') }}" alt="Approved Application">
                        </div>
                        <div class="dash-box-inner">
                            <div class="dash-box-inner-left">
                                <h3>{{ $user_applications[0]->my_desk_app }}</h3>
                                <p style="color: #fff; font-weight: 400; cursor: pointer">My Desk</p>
                            </div>
                            <div class="dash-box-inner-right" style="background: transparent linear-gradient(90deg, #7C5CF5 0%, #9B8BF7 100%) 0% 0% no-repeat padding-box;">
                                <img class="dash-img" src="{{ asset('assets/images/dashboard/draft_application.svg') }}" alt="My Desk Application">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="search_by_status" required class="form-control" placeholder="Search by keywords" value="-1, 3, 5, 15, 17, 22, 32">
                    {!! Form::close() !!}
                </div>

                <div class="col-lg-3 col-md-6 col-xs-6">
                    {!! Form::open([
                        'url' => '/process/list',
                        'method' => 'POST',
                        'id' => $user_applications[0]->in_process_app != 0 ? 'processButtonForm' : '',
                        'role' => 'form',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class="dash-box" id="processButton" style="background: linear-gradient(135deg, #6AD4D4 0%, #6AD4D4 45%, #ffffff 100%, #ffffff 100%);">
                        <div style="position: absolute; top: 0; bottom: 0; right: 30%;">
                            <img style="width: 100px;" src="{{ asset('assets/images/dashboard/cart_bg.svg') }}" alt="Approved Application">
                        </div>

                        <div class="dash-box-inner">
                            <div class="dash-box-inner-left">
                                <h3>{{ $user_applications[0]->in_process_app }}</h3>
                                <p style="color: #fff; font-weight: 400; cursor: pointer">In Process
                                </p>
                            </div>
                            <div class="dash-box-inner-right" style="background: #6AD4D4 0% 0% no-repeat padding-box;">
                                <img class="dash-img"
                                     src="{{ asset('assets/images/dashboard/process_application.svg') }}"
                                     alt="Process Application">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="search_by_status" required class="form-control"
                           placeholder="Search by keywords"
                           value="1,2,8,9,10,11,12,13,14,16,18,19,20,21,23,24,26,27,28,29,30,31,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50">
                    {!! Form::close() !!}
                </div>

                <div class="col-lg-3 col-md-6 col-xs-6">
                    {!! Form::open([
                        'url' => '/process/list',
                        'method' => 'POST',
                        'id' => $user_applications[0]->approved != 0 ? 'approvedButtonForm' : '',
                        'role' => 'form',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class="dash-box" id="approvedButton" style="background: linear-gradient(135deg, #5672DD 0%, #5672DD 45%, #ffffff 100%, #ffffff 100%);">
                        <div style="position: absolute; top: 0; bottom: 0; right: 30%;">
                            <img style="width: 100px;" src="{{ asset('assets/images/dashboard/cart_bg.svg') }}" alt="Approved Application">
                        </div>

                        <div class="dash-box-inner">
                            <div class="dash-box-inner-left">
                                <h3>{{ $user_applications[0]->approved }}</h3>
                                <p style="color: #fff; font-weight: 400; cursor: pointer">Approved
                                </p>
                            </div>
                            <div class="dash-box-inner-right"
                                 style="background: transparent linear-gradient(90deg, #5672DD 0%, #428DE0 100%) 0% 0% no-repeat padding-box;">
                                <img class="dash-img"
                                     src="{{ asset('assets/images/dashboard/approved_application.svg') }}"
                                     alt="Approved Application">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="search_by_status" required class="form-control" placeholder="Search by keywords" value="25">
                    {!! Form::close() !!}
                </div>

                <div class="col-lg-3 col-md-6 col-xs-6">
                    {!! Form::open([
                        'url' => '/process/list',
                        'method' => 'POST',
                        'id' => $user_applications[0]->others != 0 ? 'othersButtonForm' : '',
                        'role' => 'form',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class="dash-box" id="othersButton" style="background: linear-gradient(135deg, #F15753 0%, #F15753 45%, #ffffff 100%, #ffffff 100%);">
                        <div style="position: absolute; top: 0; bottom: 0; right: 30%;">
                            <img style="width: 100px;" src="{{ asset('assets/images/dashboard/cart_bg.svg') }}" alt="Approved Application">
                        </div>
                        <div class="dash-box-inner">
                            <div class="dash-box-inner-left">
                                <h3>{{ $user_applications[0]->others }}</h3>
                                <p style="color: #fff; font-weight: 400; cursor: pointer">Others
                                </p>
                            </div>
                            <div class="dash-box-inner-right"
                                 style="background: transparent linear-gradient(90deg, #F15753 0%, #FF8471 100%) 0% 0% no-repeat padding-box;">
                                <img class="dash-img" src="{{ asset('assets/images/dashboard/other_application.svg') }}"
                                     alt="Other Application">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="search_by_status" required class="form-control"
                           placeholder="Search by keywords" value="4, 6, 7">
                    {!! Form::close() !!}
                </div>
            </div>

            {{-- Select service Start --}}
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-12">
                    <p id="search-heading" class="dash-box-heading">
                        <strong> Select a service </strong>
                    </p>
                </div>
            </div>

            <div class="row" style="margin-bottom: 20px;">
                <div class=" col-md-8 col-sm-12 col-xs-12 search-content col-md-offset-2">
                    <div id="search-bar" class="input-group search-application" style="margin-bottom: 15px;">
                        <span class="input-group-btn">
                            <button id="global-search-submit" class="btn search-btn" type="submit" style="">
                                <i class="fas fa-search" style="margin-left: 8px;"></i>
                            </button>
                        </span>

                        <select name="process_type_id" class="form-control required searchInput" id="process_type_id"
                                data-placeholder="Select a service" required onchange="serviceApply(this.value)">
                            <option value=""></option>
                            @foreach ($primaryServices as $service)
                                <option value="{{ url('process/' . $service->form_url . '/add/' . Encryption::encodeId($service->id)) }}" id={{ $service->id }}>
                                    {{ $service->service_name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="input-group-btn">
                            <a class="btn applyBtn" type="button" role="button" id="applyBtn">Apply</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shortfall and Draft Applications Start--}}
            @include('Dashboard::recent_applications')
            {{-- Shortfall and Draft Applications End--}}

            {{-- Quick Link Start --}}
            <div class="row">
                <div class="col-md-12" style="padding-left: 20px;">
                    <p class="dash-box-heading" style="color: #1C60AB;">Quick Link</p>
                </div>
            </div>


            <div class="col-md-12" style="margin-bottom: 15px;">

                <div class="right_sidebar_instruction">
                    <div class="row">
                        <div class="col-md-12 row">

                            {{-- BIDA Registration menu start --}}
                            @if (in_array(102, $accessible_process) || in_array(12, $accessible_process))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#serice1" aria-expanded="false" aria-controls="serice1">
                                            <div class="right_sidebar_supper_logo">
                                                <img alt="bida-registration"
                                                     src="{{ url('assets/fonts_svg/registration.svg') }}"
                                                     width="20">
                                            </div>
                                            <div class="right_sidebar_supper_name">
                                                BIDA Registration
                                            </div>
                                        </div>

                                        <div class="right_sidebar_sub_name text-justify collapse" id="serice1">
                                            <ul>
                                                @if (in_array(102, $accessible_process))
                                                    <li>
                                                        <div class="sssn_inner">
                                                            <div class="sssn_inner_left">
                                                                <img alt="bida registration new"
                                                                     src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                                     width="20">
                                                                <a
                                                                        href="{{ url('process/bida-registration/add/' . \App\Libraries\Encryption::encodeId(102)) }}">New</a>
                                                            </div>
                                                            <div class="sssn_inner_right">
                                                                <a style="color: #fff; text-decoration: none;"
                                                                   href="{{ url('process/bida-registration/add/' . \App\Libraries\Encryption::encodeId(102)) }}"
                                                                   class="btn btn-xs btn-success"
                                                                   role="button">Apply</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif

                                                @if (in_array(12, $accessible_process))
                                                    <li>
                                                        <div class="sssn_inner">
                                                            <div class="sssn_inner_left">
                                                                <img alt="bida registration amendment" src="{{ url('assets/fonts_svg/application_amendment.svg') }}" width="20">
                                                                <a href="{{ url('process/bida-registration-amendment/add/' . \App\Libraries\Encryption::encodeId(12)) }}">Amendment</a>
                                                            </div>
                                                            <div class="sssn_inner_right">
                                                                <a style="color: #fff; text-decoration: none;"
                                                                   href="{{ url('process/bida-registration-amendment/add/' . \App\Libraries\Encryption::encodeId(12)) }}"
                                                                   class="btn btn-xs btn-success" role="button">Apply</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- BIDA Registration menu end --}}

                            {{-- Office Permission menu start --}}
                            @if (in_array(6, $accessible_process) || in_array(7, $accessible_process) || in_array(8, $accessible_process) || in_array(9, $accessible_process))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#service2" aria-controls="service2">
                                            <div class="right_sidebar_supper_logo">
                                                <img alt="Office Permission" src="{{ url('assets/fonts_svg/office_permission.svg') }}" width="20">
                                            </div>

                                            <div class="right_sidebar_supper_name">
                                                Office Permission
                                            </div>
                                        </div>

                                        <div class="right_sidebar_sub_name text-justify collapse collapse2" id="service2">

                                            <ul>
                                                @if (in_array(6, $accessible_process))
                                                    <li>
                                                        <div class="sssn_inner">
                                                            <div class="sssn_inner_left">
                                                                <img alt="office permission new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20">
                                                                <a href="{{ url('process/office-permission-new/add/' . \App\Libraries\Encryption::encodeId(6)) }}">New</a>
                                                            </div>
                                                            <div class="sssn_inner_right">
                                                                <a style="color: #fff; text-decoration: none;"
                                                                   href="{{ url('process/office-permission-new/add/' . \App\Libraries\Encryption::encodeId(6)) }}"
                                                                   class="btn btn-xs btn-success" role="button">Apply</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                                @if (in_array(7, $accessible_process))
                                                    <li>
                                                        <div class="sssn_inner">
                                                            <div class="sssn_inner_left">
                                                                <img alt="office permission extension" src="{{ url('assets/fonts_svg/application_extension.svg') }}" width="20">
                                                                <a href="{{ url('process/office-permission-extension/add/' . \App\Libraries\Encryption::encodeId(7)) }}">Extension</a>
                                                            </div>
                                                            <div class="sssn_inner_right">
                                                                <a style="color: #fff; text-decoration: none;"
                                                                   href="{{ url('process/office-permission-extension/add/' . \App\Libraries\Encryption::encodeId(7)) }}"
                                                                   class="btn btn-xs btn-success" role="button">Apply</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                                @if (in_array(8, $accessible_process))
                                                    <li>
                                                        <div class="sssn_inner">
                                                            <div class="sssn_inner_left">
                                                                <img alt="office permission amendment" src="{{ url('assets/fonts_svg/application_amendment.svg') }}" width="20">
                                                                <a href="{{ url('process/office-permission-amendment/add/' . \App\Libraries\Encryption::encodeId(8)) }}">Amendment</a>
                                                            </div>
                                                            <div class="sssn_inner_right">
                                                                <a style="color: #fff; text-decoration: none;"
                                                                   href="{{ url('process/office-permission-amendment/add/' . \App\Libraries\Encryption::encodeId(8)) }}"
                                                                   class="btn btn-xs btn-success" role="button">Apply</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                                @if (in_array(9, $accessible_process))
                                                    <li>
                                                        <div class="sssn_inner">
                                                            <div class="sssn_inner_left">
                                                                <img alt="office permission cancellation"
                                                                     src="{{ url('assets/fonts_svg/application_cancellation.svg') }}" width="20">
                                                                <a href="{{ url('process/office-permission-cancellation/add/' . \App\Libraries\Encryption::encodeId(9)) }}">Cancellation</a>
                                                            </div>
                                                            <div class="sssn_inner_right">
                                                                <a style="color: #fff; text-decoration: none;"
                                                                   href="{{ url('process/office-permission-cancellation/add/' . \App\Libraries\Encryption::encodeId(9)) }}"
                                                                   class="btn btn-xs btn-success" role="button">Apply</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- Office Permission menu End --}}

                            {{-- Project Office Permission menu start --}}
                            @if (in_array(22, $accessible_process))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#service22" aria-controls="service22">
                                            <div class="right_sidebar_supper_logo">
                                                <img alt="Project Office Permission" src="{{ url('assets/fonts_svg/office_permission.svg') }}" width="20">
                                            </div>

                                            <div class="right_sidebar_supper_name">
                                                Project Office Permission
                                            </div>
                                        </div>

                                        <div class="right_sidebar_sub_name text-justify collapse collapse2" id="service22">

                                            <ul>
                                                @if (in_array(22, $accessible_process))
                                                    <li>
                                                        <div class="sssn_inner">
                                                            <div class="sssn_inner_left">
                                                                <img alt="Project office permission new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20">
                                                                <a href="{{ url('process/project-office-new/add/' . \App\Libraries\Encryption::encodeId(22)) }}">New</a>
                                                            </div>
                                                            <div class="sssn_inner_right">
                                                                <a style="color: #fff; text-decoration: none;"
                                                                   href="{{ url('process/project-office-new/add/' . \App\Libraries\Encryption::encodeId(22)) }}"
                                                                   class="btn btn-xs btn-success" role="button">Apply</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- Project Office Permission menu End --}}

                            {{-- Vip Lounge menu start --}}
                            @if (in_array(17, $accessible_process))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#serice3" aria-expanded="false" aria-controls="serice3">
                                            <div class="right_sidebar_supper_logo">
                                                <img alt="vip lounge"
                                                     src="{{ url('assets/fonts_svg/visa_recommendation.svg') }}" width="20">
                                            </div>
                                            <div class="right_sidebar_supper_name">
                                                VIP/CIP Lounge
                                            </div>
                                        </div>

                                        <div class="right_sidebar_sub_name text-justify collapse" id="serice3">
                                            <ul>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="vip lounge new"
                                                                 src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20">
                                                            <a href="{{ url('process/vip-lounge/add/' . \App\Libraries\Encryption::encodeId(17)) }}">New</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/vip-lounge/add/' . \App\Libraries\Encryption::encodeId(17)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- Vip Lounge menu End --}}

                            {{-- Visa Recommendation menu start --}}
                            @if (in_array(1, $accessible_process) || in_array(10, $accessible_process))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#serice4" aria-expanded="false" aria-controls="serice4">
                                            <div class="right_sidebar_supper_logo">
                                                <img alt="visa recommendation"
                                                     src="{{ url('assets/fonts_svg/visa_recommendation.svg') }}" width="20">
                                            </div>
                                            <div class="right_sidebar_supper_name">
                                                Visa Recommendation
                                            </div>
                                        </div>

                                        <div class="right_sidebar_sub_name text-justify collapse" id="serice4">
                                            <ul>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="visa recommendation new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20">
                                                            <a href="{{ url('process/visa-recommendation/add/' . \App\Libraries\Encryption::encodeId(1)) }}">New</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/visa-recommendation/add/' . \App\Libraries\Encryption::encodeId(1)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="visa recommendation amendment"
                                                                 src="{{ url('assets/fonts_svg/application_amendment.svg') }}" width="20">
                                                            <a href="{{ url('process/visa-recommendation-amendment/add/' . \App\Libraries\Encryption::encodeId(10)) }}">Amendment</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/visa-recommendation-amendment/add/' . \App\Libraries\Encryption::encodeId(10)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- Visa Recommendation menu end --}}

                            {{-- Waiver Condition 7 & 8 menu start --}}
                            @if (in_array(19, $accessible_process) || in_array(20, $accessible_process))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#serice5" aria-expanded="false" aria-controls="serice5">
                                            <div class="right_sidebar_supper_logo">
                                                <img alt="waiver" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20">
                                            </div>
                                            <div class="right_sidebar_supper_name">
                                                Waiver
                                            </div>
                                        </div>

                                        <div class="right_sidebar_sub_name text-justify collapse" id="serice5">
                                            <ul>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="waiver condition7" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20">
                                                            <a href="{{ url('process/waiver-condition-7/add/' . \App\Libraries\Encryption::encodeId(19)) }}">Condition No 7</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/waiver-condition-7/add/' . \App\Libraries\Encryption::encodeId(19)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="waiver condition8"
                                                                 src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                                 width="20">
                                                            <a href="{{ url('process/waiver-condition-8/add/' . \App\Libraries\Encryption::encodeId(20)) }}">Condition No 8</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/waiver-condition-8/add/' . \App\Libraries\Encryption::encodeId(20)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- Waiver Condition 7 & 8 menu end --}}

                            {{-- Import Permission menu start --}}
                            @if (in_array(21, $accessible_process))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#serice6" aria-expanded="false" aria-controls="serice6">
                                            <div class="right_sidebar_supper_logo">
                                                <img alt="import permission" src="{{ url('assets/fonts_svg/application_amendment.svg') }}" width="20">
                                            </div>
                                            <div class="right_sidebar_supper_name">
                                                Import Permission
                                            </div>
                                        </div>

                                        <div class="right_sidebar_sub_name text-justify collapse" id="serice6">
                                            <ul>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="import permission new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20">
                                                            <a href="{{ url('process/import-permission/add/' . \App\Libraries\Encryption::encodeId(21)) }}">New</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/import-permission/add/' . \App\Libraries\Encryption::encodeId(21)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- Import Permission menu end --}}

                            {{-- Work Permit menu start --}}
                            @if (in_array(2, $accessible_process) || in_array(3, $accessible_process) || in_array(4, $accessible_process) || in_array(5, $accessible_process))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#serice7" aria-expanded="false" aria-controls="serice7">
                                            <div class="right_sidebar_supper_logo">
                                                <img alt="work permit"
                                                     src="{{ url('assets/fonts_svg/work_permit.svg') }}"
                                                     width="20">
                                            </div>
                                            <div class="right_sidebar_supper_name">
                                                Work Permit
                                            </div>
                                        </div>

                                        <div class="right_sidebar_sub_name text-justify collapse" id="serice7">
                                            <ul>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="work permit new" src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20">
                                                            <a href="{{ url('process/work-permit-new/add/' . \App\Libraries\Encryption::encodeId(2)) }}">New</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/work-permit-new/add/' . \App\Libraries\Encryption::encodeId(2)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="work permit extension"
                                                                 src="{{ url('assets/fonts_svg/application_extension.svg') }}" width="20">
                                                            <a href="{{ url('process/work-permit-extension/add/' . \App\Libraries\Encryption::encodeId(3)) }}">Extension</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/work-permit-extension/add/' . \App\Libraries\Encryption::encodeId(3)) }}"
                                                               class="btn btn-xs btn-success"
                                                               role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="work permit amendment"
                                                                 src="{{ url('assets/fonts_svg/application_amendment.svg') }}"
                                                                 width="20">
                                                            <a
                                                                    href="{{ url('process/work-permit-amendment/add/' . \App\Libraries\Encryption::encodeId(4)) }}">Amendment</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/work-permit-amendment/add/' . \App\Libraries\Encryption::encodeId(4)) }}"
                                                               class="btn btn-xs btn-success"
                                                               role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="work permit cancellation"
                                                                 src="{{ url('assets/fonts_svg/application_cancellation.svg') }}"
                                                                 width="20">
                                                            <a
                                                                    href="{{ url('process/work-permit-cancellation/add/' . \App\Libraries\Encryption::encodeId(5)) }}">Cancellation</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/work-permit-cancellation/add/' . \App\Libraries\Encryption::encodeId(5)) }}"
                                                               class="btn btn-xs btn-success"
                                                               role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- Work Permit menu end --}}

                            {{-- Remittance Menu Start --}}
                            @if (in_array(11, $accessible_process))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#serice8" aria-expanded="false" aria-controls="serice8">
                                            <div class="right_sidebar_supper_logo">
                                                <img alt="remittance"
                                                     src="{{ url('assets/fonts_svg/ramittance.svg') }}"
                                                     width="20">
                                            </div>
                                            <div class="right_sidebar_supper_name">
                                                Remittance
                                            </div>
                                        </div>

                                        <div class="right_sidebar_sub_name text-justify collapse" id="serice8">
                                            <ul>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="remittance new"
                                                                 src="{{ url('assets/fonts_svg/application_new.svg') }}" width="20">
                                                            <a href="{{ url('process/remittance-new/add/' . \App\Libraries\Encryption::encodeId(11)) }}">New</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('process/remittance-new/add/' . \App\Libraries\Encryption::encodeId(11)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- Remittance Menu end --}}

                            {{-- Security Clearance Menu start --}}
                            {{-- @if (in_array($type[0], [1, 2]) || ($type[0] == 4 && !in_array(20, $user_desk_ids)))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <a href="{{ url('security-clearance/list') }}" class="right_sidebar_anchor">
                                            <div class="right_sidebar_supper">
                                                <div class="right_sidebar_supper_logo">
                                                    <img alt="security clearance"
                                                         src="{{ url('assets/fonts_svg/security_clearance.svg') }}"
                                                         width="20">
                                                </div>
                                                <div class="right_sidebar_supper_name">
                                                    Security Clearance
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif --}}
                            {{-- Security Clearance Menu End --}}


                            {{-- IRC recommendation Menu Start --}}
                            @if (in_array(13, $accessible_process) || in_array(14, $accessible_process) || in_array(15, $accessible_process) || in_array(16, $accessible_process))
                                <div class=" col-md-4 ">
                                    <div class="right_sidebar_box" style="margin: 5px 5px;">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#serice9" aria-expanded="false" aria-controls="serice9">
                                            <div class="right_sidebar_supper_logo">
                                                <img alt="irc recommendation" src="{{ url('assets/fonts_svg/1st_adhoc.svg') }}" width="20">
                                            </div>
                                            <div class="right_sidebar_supper_name">
                                                IRC Recommendation
                                            </div>
                                        </div>

                                        <div class="right_sidebar_sub_name text-justify collapse" id="serice9">
                                            <ul>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="1st Adhoc" src="{{ url('assets/fonts_svg/1st_adhoc.svg') }}" width="20">
                                                            <a href="{{ url('process/irc-recommendation-new/add/' . Encryption::encodeId(13)) }}">1st Adhoc</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;" href="{{ url('process/irc-recommendation-new/add/' . Encryption::encodeId(13)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="2nd Adhoc" src="{{ url('assets/fonts_svg/2nd_adhoc.svg') }}" width="20">
                                                            <a href="{{ url('process/irc-recommendation-second-adhoc/add/' . Encryption::encodeId(14)) }}">2nd Adhoc</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;" href="{{ url('process/irc-recommendation-second-adhoc/add/' . Encryption::encodeId(14)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="3rd Adhoc"
                                                                 src="{{ url('assets/fonts_svg/3rd_adhoc.svg') }}"
                                                                 width="20">
                                                            <a href="{{ url('process/irc-recommendation-third-adhoc/add/' . Encryption::encodeId(15)) }}">3rd Adhoc</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;" href="{{ url('process/irc-recommendation-third-adhoc/add/' . Encryption::encodeId(15)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="Regular" src="{{ url('assets/fonts_svg/3rd_adhoc.svg') }}" width="20">
                                                            <a href="{{ url('process/irc-recommendation-regular/add/' . Encryption::encodeId(16)) }}">Regular</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;" href="{{ url('process/irc-recommendation-regular/add/' . Encryption::encodeId(16)) }}"
                                                               class="btn btn-xs btn-success" role="button">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- IRC recommendation Menu end --}}

                            {{-- Featured Stakeholder Service Menu Start --}}
                            @if (count($featuredStakeholderServices) > 0)
                                @foreach ($featuredStakeholderServices as $key => $featuredStakeholderService)
                                    <div class=" col-md-4 ">
                                        <div class="right_sidebar_box" style="margin: 5px 5px;">
                                            <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                                 href="#service_{{ $key + 1 }}" aria-expanded="false" aria-controls="service_{{ $key + 1 }}">
                                                <div class="right_sidebar_supper_logo">
                                                    @if (!empty($featuredStakeholderService['logo']) && file_exists($featuredStakeholderService['logo']))
                                                        <img src="{{ asset($featuredStakeholderService['logo']) }}"
                                                             alt="{{ $featuredStakeholderService['process_supper_name'] }}" width="20">
                                                    @else
                                                        <img src="{{ asset('assets/images/dashboard/government_of_bangladesh.png') }}"
                                                             alt="{{ $featuredStakeholderService['process_supper_name'] }}" width="20">
                                                    @endif
                                                </div>
                                                <div class="right_sidebar_supper_name">
                                                    {{ $featuredStakeholderService['process_supper_name'] }}
                                                </div>
                                            </div>

                                            <div class="right_sidebar_sub_name text-justify collapse" id="service_{{ $key + 1 }}">
                                                <ul>
                                                    @foreach ($featuredStakeholderService['process_sub_names'] as $process_sub_name)
                                                        <li>
                                                            <div class="sssn_inner">
                                                                <div class="sssn_inner_left">
                                                                    <img alt="{{ $process_sub_name['name'] }}"
                                                                         src="{{ url('assets/images/dashboard/circle.png') }}" width="10">
                                                                    <a href="{{ '/'.$process_sub_name['url'] }}">{{ $process_sub_name['name'] }}</a>
                                                                </div>
                                                                <div class="sssn_inner_right">
                                                                    <a style="color: #fff; text-decoration: none;"
                                                                       href="{{ '/'.$process_sub_name['url'] }}"
                                                                       class="btn btn-xs btn-success" role="button">Apply</a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            {{-- Featured Stakeholder Service Menu end --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/select2.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var selectedServiceId = sessionStorage.getItem("service_id");

            var serviceDropdown = document.getElementById("process_type_id");

            // Loop through each option and set the "selected" attribute if it matches the stored ID
            for (var i = 0; i < serviceDropdown.options.length; i++) {
                var option = serviceDropdown.options[i];
                var serviceId = option.getAttribute("id");
                if (selectedServiceId && selectedServiceId == serviceId) {
                    option.selected = true;
                    break;
                }
            }

            // Event listener to update sessionStorage when the selection changes
            serviceDropdown.addEventListener("change", function() {
                var selectedOption = serviceDropdown.options[serviceDropdown.selectedIndex];
                var newServiceId = selectedOption.getAttribute("data-service-id");
                sessionStorage.setItem("service_id", newServiceId);
            });
        });
    </script>

    <script>
        $(function() {
            $("#process_type_id").select2();
        });

        function serviceApply($url) {
            $("#applyBtn").attr('href', $url);
        }
        document.addEventListener('DOMContentLoaded', function () {
            serviceApply(document.getElementById('process_type_id').value);
        });
    </script>

    <script>
        const myDeskButton = document.querySelector('#myDeskButton');
        myDeskButton.addEventListener('click', function(e) {
            $('#myDeskButtonForm').submit();
        });

        const processButton = document.querySelector('#processButton');
        processButton.addEventListener('click', function(e) {
            $('#processButtonForm').submit();
        });

        const approvedButton = document.querySelector('#approvedButton');
        approvedButton.addEventListener('click', function(e) {
            $('#approvedButtonForm').submit();
        });

        const othersButton = document.querySelector('#othersButton');
        othersButton.addEventListener('click', function(e) {
            $('#othersButtonForm').submit();
        });
        $(document).ready(function(){
            $('.right_sidebar_supper').click(function(){
                // Hide all other collapsed elements within the same parent
                $(this).closest('.row').find('.right_sidebar_sub_name.collapse.in').collapse('hide');
            });
        });
    </script>

@endsection
