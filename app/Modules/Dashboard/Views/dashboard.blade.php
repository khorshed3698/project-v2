<?php
$user_type = Auth::user()->user_type;
$type = explode('x', $user_type);
$user_desk_ids = \App\Libraries\CommonFunction::getUserDeskIds();

$accessible_process = [];
if (\Illuminate\Support\Facades\Session::has('accessible_process')) {
    $accessible_process = \Illuminate\Support\Facades\Session::get('accessible_process');
}

?>




<style>
    .autoProcessListTable>thead>tr>th,
    .autoProcessListTable>tbody>tr>th,
    .autoProcessListTable>tfoot>tr>th,
    .autoProcessListTable>thead>tr>td,
    .autoProcessListTable>tbody>tr>td,
    .autoProcessListTable>tfoot>tr>td {
        vertical-align: middle;
    }

    .autoProcessListTable>thead:first-child>tr:first-child>td {
        font-size: 14px;
    }

    .alert-blue {
        /*background: #6B7AE0;*/
        background: #31708f;
        color: #fff;
    }

    .in_list_style,
    .in_list_style li {
        list-style: inherit !important;
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

    /* .down_up_arrow::before {
        content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: rgb(230,240,255);
    top: 50%;
    right: 0;
    transform: translate(-50%,-50%);
    z-index: 0;
} */
    .dash-notice-box-heading.down_up_arrow {
        position: relative;

    }

    .dash-notice-box-heading.down_up_arrow a {
        max-width: 90%;

    }

    .dash-notice-box-heading.down_up_arrow::after {
        position: absolute;
        top: 50%;
        right: 0%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: rgb(230, 240, 255);
        z-index: 99;
        text-align: center;
    }

    .app-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .app-list li {
        padding: 5px;
        border-bottom: 1px solid #ddd;
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
    .mb-20{
        margin-bottom: 20px;
    }

</style>

{{-- Auto Process application List --}}
@if (isset($autoProcessList) && count($autoProcessList) > 0)
    @if (in_array($user_type, ['1x101']) || ($type[0] == 4 && !in_array(20, $user_desk_ids)))
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-sm-12">

                <div class="alert alert-info" style="margin-bottom: 0; border: 2px solid #31708f">
                    <h4 style="margin: 15px 2px;border-bottom: 1px solid;padding-bottom: 15px; font-weight: bold;">
                        <i class="fa fa-x fa-exclamation-circle"></i>
                        The following applications will be automatically processed immediately:
                    </h4>
                    {{-- <hr/> --}}
                    <table class="table table-bordered table-hover autoProcessListTable" aria-label="Detailed applications processed">
                        <thead class="alert alert-blue">
                        <tr class="d-none">
                            <th aria-hidden="true" scope="col"></th>
                        </tr>
                        <tr>
                            <td>SN#</td>
                            <td>Service name</td>
                            <td>Process by today</td>
                            <td>Process by tomorrow</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody style="background: #fff; color: #000">
                            <?php $sl = 1; ?>
                        @foreach ($autoProcessList as $process)
                            <tr>
                                <td>{{ $sl++ }}</td>
                                <td>{{ $process['process_type_name'] }}</td>
                                <td>{{ $process['process_by_today'] }}</td>
                                <td>{{ $process['process_by_tomorrow'] }}</td>
                                <td>
                                    <a href="{{ url('auto-process-list/' . \App\Libraries\Encryption::encodeId($process['process_type_id'])) }}"
                                       class="btn btn-info btn-sm" target="_blank" rel="noopener">View list</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endif

{{-- Basic information --}}
@if ($user_type == '5x505')
    <style>
        /*new dashboard design start*/
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
            color: #fff;
        }

        .dash-notice-box {
            background: #FFFFFF 0% 0% no-repeat padding-box;
            box-shadow: 2px 2px 4px #F4F7FC;
            border: 1px solid #E6E6E6;
            border-radius: 5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .dash-notice-box-content {
            border-left: 4px solid #337AB7;
            padding: 10px;
            border-radius: 5px 0 0 5px;
            flex-grow: 1;
        }

        .dash-notice-box-heading p {
            color: #393939;
            margin: 0;
            font-size: 13px;
        }

        .dash-notice-box-heading a {
            color: #393939;
            margin: 0;
            padding: 0;
            font-size: 14px;
            font-weight: bold;
            display: block;
        }

        .dash-notice-box-details {
            margin-top: 5px;
            border-top: 1px solid #E6E6E6;
            padding-top: 5px;
        }

        .dash-notice-box-priority {
            flex-basis: 70px;
            flex-shrink: 0;
            border-radius: 4px;
            padding: 6px;
            letter-spacing: 0;
            color: #397CB9;
            margin-right: 10px;
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
            /*padding: 20px 10px;*/
            padding: 10px 5px;
        }

        .notice_instruction {
            background: #F7F7FF 0% 0% no-repeat padding-box;
            box-shadow: 0 0 5px #DFDFDF;
            border-radius: 6px;
            opacity: 1;
            padding: 15px;
        }

        .stakeholder_service_box {
            background: #FFFFFF 0% 0% no-repeat padding-box;
            box-shadow: 2px 2px 4px #F4F7FC;
            border: 1px solid #E6E6E6;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .stakeholder_service_supper {
            display: flex;
            align-items: center;
            padding: 10px;
        }

        .stakeholder_service_supper_logo {
            margin-right: 10px;
        }

        .stakeholder_service_supper_logo img {
            max-width: 50px;
            height: auto;
        }

        .stakeholder_service_supper_name {
            font-size: 14px;
            color: #393939;
            width: 100%;
        }

        .stakeholder_service_sub_name {}

        .stakeholder_service_sub_name ul {
            margin: 0;
            padding-left: 15px;
            list-style: none;
        }

        .stakeholder_service_sub_name ul li {
            padding: 10px 0px;
            border-top: 1px solid #eee;
        }

        .stakeholder_service_sub_name ul li a {
            color: #397CB9;
            text-decoration: none;
        }

        .stakeholder_service_sub_name ul li a:hover {}

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

        /* Notice see more button */
        .see-more-btn {
            cursor: pointer;
            color: #6060fd;
            font-weight: bold;
        }

        .text-decoration-none:hover {
            text-decoration: none;
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

        .dashborad-carousel-indicators {
            position: absolute;
            left: 50%;
            z-index: 15;
            width: 60%;
            padding-left: 0;
            margin-left: -30%;
            text-align: center;
            list-style: none;
        }

        .carousel-indicators {
            position: absolute;
            bottom: -20% !important;
            left: 50%;
            z-index: 15;
            width: 60%;
            padding-left: 0;
            margin-left: -30%;
            text-align: center;
            list-style: none;
        }

        .carousel-indicators li {
            border: 1px solid #493cfc;
        }

        .dashborad-carousel-indicators li {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 1px;
            text-indent: -999px;
            cursor: pointer;
            border: 1px solid #493cfc;
            border-radius: 10px;
        }

        .carousel-indicators .active {
            width: 12px;
            height: 12px;
            margin: 0;
            background-color: #493cfc;
        }

        .dashborad-carousel-indicators .active {
            width: 12px;
            height: 12px;
            margin: 0;
            background-color: #493cfc;
        }
        .align-text{
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #helpDiv {
            position: fixed;
            bottom: 5px;
            width: auto;
            right: 10px;
        }

    </style>
    <div class="row">
        <div class="col-md-8">
            {{-- IRMS feedback initiate list --}}
            @include('Dashboard::irms_feedback_initiate_list')

            @if($user_type === '5x505')
                @include('Dashboard::invalid_email_user_notification')
            @endif
            

            <div class="row">

                @if ($newBida == 1 && $existingBida == 1)
                    <div class="text-center">
                        <a type="button" class="btn btn-warning" style="margin-bottom: 15px;" data-toggle="modal"
                           data-target="#youNeedToKnowModal">You need to know</a>
                    </div>
                @endif

                {{-- Start business category --}}
                {{-- @if (Auth::user()->user_type == '5x505' && Auth::user()->company->business_category != 2) --}}
                @if (
                    Auth::check() &&
                    Auth::user()->user_type == '5x505' &&
                    Auth::user()->company &&
                    Auth::user()->company->business_category != 2
                )
                    @if ($newStakeholder == 1)
                        <div class="col-lg-4 col-md-6 col-xs-6">
                            <a href="{{ url('basic-information/form-stakeholder', Encryption::encodeId('NCR')) }}{{ !empty($appInfo) && $appInfo->is_new_for_stakeholders == 1 ? '/' . Encryption::encodeId(Auth::user()->company_ids) : '' }}">
                                <div class="dash-box" style="background: linear-gradient(45deg, #216ed8 0%, #fa7799 100%)">
                                    <div class="row align-text">
                                        <div class="col-md-4 col-xs-6">
                                            <i style="color: #fff;" class="far fa-edit fa-3x"></i>
                                        </div>
                                        <div class="col-md-8 col-xs-6">
                                            <p>
                                                Basic Information <br>
                                                New Company Registration
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    @if ($existingStakeholder == 1)
                        <div class="col-lg-4 col-md-6 col-xs-6">
                            <a href="{{ url('basic-information/form-stakeholder', Encryption::encodeId('ECR')) }}{{ !empty($appInfo) && $appInfo->is_existing_for_stakeholders == 1 ? '/' . Encryption::encodeId(Auth::user()->company_ids) : '' }}">
                                <div class="dash-box" style="background: linear-gradient(45deg, #4caf50 0%, #ff9800 100%);">
                                    <div class="row align-text">
                                        <div class="col-md-4 col-xs-6">
                                            <i style="color: #fff;" class="fas fa-edit fa-3x"></i>
                                        </div>
                                        <div class="col-md-8 col-xs-6">
                                            <p>
                                                Basic Information <br>
                                                Existing Company Registration
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    @if ($newBida == 1)
                        <div class="col-lg-4 col-md-6 col-xs-6">
                            <a href="{{ url('basic-information/form-bida', Encryption::encodeId('NUBS')) }}{{ !empty($appInfo) && $appInfo->is_new_for_bida == 1 ? '/' . Encryption::encodeId(Auth::user()->company_ids) : '' }}">
                                <div class="dash-box" style="background: linear-gradient(45deg, #9c27b0 0%, #00bcd4 100%);">
                                    <div class="row align-text">
                                        <div class="col-md-4 col-xs-6">
                                            <i style="color: #fff;" class="fas fa-user-plus fa-3x"></i>
                                        </div>
                                        <div class="col-md-8 col-xs-6">
                                            <p>
                                                Basic Information <br>
                                                New User for BIDA's Services
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endif
                {{-- End business category --}}

                @if ($existingBida == 1)
                    <div class="col-lg-4 col-md-6 col-xs-6">
                        <a href="{{ url('basic-information/form-bida', Encryption::encodeId('EUBS')) }}{{ !empty($appInfo) && $appInfo->is_existing_for_bida == 1 ? '/' . Encryption::encodeId(Auth::user()->company_ids) : '' }}">
                            <div class="dash-box" style="background: linear-gradient(45deg, #e91e63 0%, #ffc107 100%);">
                                <div class="row align-text">
                                    <div class="col-md-4 col-xs-6">
                                        <i style="color: #fff;" class="fas fa-user-check fa-3x"></i>
                                    </div>
                                    <div class="col-md-8 col-xs-6">
                                        <p>
                                            Basic Information <br>
                                            Existing User for BIDA's Services
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                    <?php
                    $total_basic_cart = $newStakeholder + $existingStakeholder + $newBida + $existingBida;
                    ?>

                {{-- Switch Company = 4 (flag) --}}
                @if ($total_basic_cart == 1)
                    <div class="col-lg-4 col-md-6 col-xs-6">
                        <a href="{{ url('company-association/switch-company') }}">
                            <div class="dash-box" style="background: linear-gradient(45deg, #0d95ad 0%, #ffd2c1 100%);">
                                <div class="row align-text">
                                    <div class="col-md-4 col-xs-6">
                                        <i style="color: #fff;" class="fa fa-th fa-3x"></i>
                                    </div>
                                    <div class="col-md-8 col-xs-6">
                                        <p>Switch Your Company</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                @if ($total_basic_cart == 1 || $total_basic_cart == 2)
                    @if ($pendingFeedbackApplication > 0)
                        {{-- Feedback list = 3 (flag) --}}
                        <div class="col-lg-4 col-md-6 col-xs-6">
                            <a href="{{ url('process/list/feedback-list') }}">
                                <div class="dash-box" style="background: linear-gradient(45deg, #3f51b5 0%, #ff5722 100%);">
                                    <div class="row align-text">
                                        <div class="col-md-4 col-xs-6">
                                            <span style="color: #fff; font-size: 3em; font-style: normal; line-height: 1; font-weight: bold;">
                                                {{ $pendingFeedbackApplication }}
                                            </span>
                                        </div>
                                        <div class="col-md-8 col-xs-6">
                                            <p>
                                                Your Pending Feedbacks
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @else
                        {{-- Company association = 3 (flag) --}}
                        <div class="col-lg-4 col-md-6 col-xs-6">
                            <a href="{{ url('/company-association/list') }}">
                                <div class="dash-box" style="background: linear-gradient(45deg, #4CAF50 0%, #FF4081 100%);">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-6">
                                            <i style="color: #fff;" class="fa fa-industry fa-3x"></i>
                                        </div>
                                        <div class="col-md-8 col-xs-6">
                                            <p>Company association</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endif

            </div>
            {{-- Basic Information End --}}

            {{-- Dashboard Start --}}
            {{-- <div class="row">
                <div class="col-md-12 dash-box-inner" style="margin-bottom: 10px;">
                    <div class="dash-box-inner-left">
                        <p class="dash-box-heading" style="margin-bottom: 0; cursor: default;">Dashboard</p>
                    </div>
                    <div class="dash-box-inner-right">
                        <a href="{{ url('dashboard/new-application') }}" class="btn btn-success btn-xs">Apply new application</a>
                    </div>
                </div>
            </div> --}}

            <div class="row">
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
                                <p style="color: #fff; font-weight: 400; cursor: pointer">In Process</p>
                            </div>
                            <div class="dash-box-inner-right" style="background: #6AD4D4 0% 0% no-repeat padding-box;">
                                <img class="dash-img" src="{{ asset('assets/images/dashboard/process_application.svg') }}" alt="Process Application">
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
                                <p style="color: #fff; font-weight: 400; cursor: pointer">Approved</p>
                            </div>
                            <div class="dash-box-inner-right" style="background: transparent linear-gradient(90deg, #5672DD 0%, #428DE0 100%) 0% 0% no-repeat padding-box;">
                                <img class="dash-img" src="{{ asset('assets/images/dashboard/approved_application.svg') }}" alt="Approved Application">
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
                                <p style="color: #fff; font-weight: 400; cursor: pointer">Others</p>
                            </div>
                            <div class="dash-box-inner-right"
                                 style="background: transparent linear-gradient(90deg, #F15753 0%, #FF8471 100%) 0% 0% no-repeat padding-box;">
                                <img class="dash-img"
                                     src="{{ asset('assets/images/dashboard/other_application.svg') }}"
                                     alt="Other Application">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="search_by_status" required class="form-control"
                           placeholder="Search by keywords" value="4, 6, 7">
                    {!! Form::close() !!}
                </div>

            </div>
            {{-- Dashboard End --}}

            {{-- Select service Start --}}
            @include('Dashboard::search_apply_new_application')
            {{-- Select service End --}}

            {{-- Shortfall and Draft Applications Start--}}
            @include('Dashboard::recent_applications')
            {{-- Shortfall and Draft Applications End--}}

            {{-- Stakeholder Services Start --}}
            <div class="row">
                <div class="col-md-12">
                    <p class="dash-box-heading">Stakeholder Services</p>
                </div>
            </div>
            {{-- <div class="row grid" data-masonry='{ "itemSelector": ".grid-item" }'> --}}
                <?php
                // $current_supper_name = '';
                // $is_new_supper = 1; // 1 = new
                // $total_stakeholder = count($stakeholder_services);
                ?>
            @include('Dashboard::stakeholder_services')
            {{-- </div> --}}
            {{-- Stakeholder Services End --}}
        </div>


        @if (Auth::user()->user_type == '5x505')

            <div class="col-md-4" style="margin-bottom: 15px;">
                <div class="right_sidebar_instruction">
                    <div class="row">
                        <div class="col-md-12">

                            {{-- BIDA Registration menu start --}}
                            @if (in_array(102, $accessible_process) || in_array(12, $accessible_process))
                                <div class="right_sidebar_box">
                                    <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                         href="#serice1" aria-expanded="false" aria-controls="serice1">
                                        <div class="right_sidebar_supper_logo">
                                            <img alt="bida-registration"
                                                 src="{{ url('assets/fonts_svg/registration.svg') }}" width="20">
                                        </div>
                                        <div class="right_sidebar_supper_name">
                                            BIDA Registration
                                        </div>
                                    </div>

                                    <div class="right_sidebar_sub_name text-justify collapse" id="serice1">
                                        <ul>
                                            @if (in_array(102, $accessible_process))
                                                <li>
                                                    <div class="sssn_inner" onclick="redirect(102)">
                                                        <div class="sssn_inner_left">
                                                            <img alt="bida registration new"
                                                                 src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                                 width="20">
                                                            <a href="{{ url('dashboard/apply-service') }}">New</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('dashboard/apply-service') }}"
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
                                                            <img alt="bida registration amendment"
                                                                 src="{{ url('assets/fonts_svg/application_amendment.svg') }}"
                                                                 width="20">
                                                            <a href="{{ url('dashboard/apply-service') }}" onclick="redirect(12)">Amendment</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('dashboard/apply-service') }}"
                                                               class="btn btn-xs btn-success"
                                                               role="button" onclick="redirect(12)">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            {{-- BIDA Registration menu end --}}

                            {{-- Office Permission menu start --}}
                            @if (in_array(6, $accessible_process) ||
                                    in_array(7, $accessible_process) ||
                                    in_array(8, $accessible_process) ||
                                    in_array(9, $accessible_process))
                                <div class="right_sidebar_box">
                                    <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                         href="#service2" aria-controls="service2">
                                        <div class="right_sidebar_supper_logo">
                                            <img alt="Office Permission"
                                                 src="{{ url('assets/fonts_svg/office_permission.svg') }}"
                                                 width="20">
                                        </div>

                                        <div class="right_sidebar_supper_name">
                                            Office Permission
                                        </div>
                                    </div>

                                    <div class="right_sidebar_sub_name text-justify collapse collapse2"
                                         id="service2">

                                        <ul>
                                            @if (in_array(6, $accessible_process))
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="office permission new"
                                                                 src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                                 width="20">
                                                            <a
                                                                    href="{{ url('dashboard/apply-service') }}" onclick="redirect(6)">New</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('dashboard/apply-service') }}"
                                                               class="btn btn-xs btn-success"
                                                               role="button" onclick="redirect(6)">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                            @if (in_array(7, $accessible_process))
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="office permission extension"
                                                                 src="{{ url('assets/fonts_svg/application_extension.svg') }}"
                                                                 width="20">
                                                            <a
                                                                    href="{{ url('dashboard/apply-service') }}" onclick="redirect(7)">Extension</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('dashboard/apply-service') }}"
                                                               class="btn btn-xs btn-success"
                                                               role="button" onclick="redirect(7)">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                            @if (in_array(8, $accessible_process))
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="office permission amendment"
                                                                 src="{{ url('assets/fonts_svg/application_amendment.svg') }}"
                                                                 width="20">
                                                            <a
                                                                    href="{{ url('dashboard/apply-service') }}" onclick="redirect(8)">Amendment</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('dashboard/apply-service') }}"
                                                               class="btn btn-xs btn-success"
                                                               role="button" onclick="redirect(8)">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                            @if (in_array(9, $accessible_process))
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="office permission cancellation"
                                                                 src="{{ url('assets/fonts_svg/application_cancellation.svg') }}"
                                                                 width="20">
                                                            <a
                                                                    href="{{ url('dashboard/apply-service') }}" onclick="redirect(9)">Cancellation</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('dashboard/apply-service') }}"
                                                               class="btn btn-xs btn-success"
                                                               role="button" onclick="redirect(9)">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>

                                    </div>
                                </div>
                            @endif
                            {{-- Office Permission menu End --}}

                            {{-- Project Office Permission menu start --}}
                            @if (in_array(22, $accessible_process))
                                <div class="right_sidebar_box">
                                    <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                         href="#service22" aria-controls="service22">
                                        <div class="right_sidebar_supper_logo">
                                            <img alt="Project Office Permission"
                                                 src="{{ url('assets/fonts_svg/office_permission.svg') }}"
                                                 width="20">
                                        </div>

                                        <div class="right_sidebar_supper_name">
                                            Project Office Permission
                                        </div>
                                    </div>

                                    <div class="right_sidebar_sub_name text-justify collapse collapse2"
                                         id="service22">

                                        <ul>
                                            @if (in_array(22, $accessible_process))
                                                <li>
                                                    <div class="sssn_inner">
                                                        <div class="sssn_inner_left">
                                                            <img alt="project office permission new"
                                                                 src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                                 width="20">
                                                            <a href="{{ url('dashboard/apply-service') }}" onclick="redirect(22)">New</a>
                                                        </div>
                                                        <div class="sssn_inner_right">
                                                            <a style="color: #fff; text-decoration: none;"
                                                               href="{{ url('dashboard/apply-service') }}"
                                                               class="btn btn-xs btn-success"
                                                               role="button" onclick="redirect(22)">Apply</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            {{-- Project Office Permission menu End --}}

                            {{-- Vip Lounge menu start --}}
                            @if (in_array(17, $accessible_process))
                                <div class="right_sidebar_box">
                                    <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                         href="#serice3" aria-expanded="false" aria-controls="serice3">
                                        <div class="right_sidebar_supper_logo">
                                            <img alt="vip lounge"
                                                 src="{{ url('assets/fonts_svg/visa_recommendation.svg') }}"
                                                 width="20">
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
                                                             src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(17)">New</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(17)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            {{-- Vip Lounge menu End --}}

                            {{-- Visa Recommendation menu start --}}
                            @if (in_array(1, $accessible_process) || in_array(10, $accessible_process))
                                <div class="right_sidebar_box">
                                    <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                         href="#serice4" aria-expanded="false" aria-controls="serice4">
                                        <div class="right_sidebar_supper_logo">
                                            <img alt="visa recommendation"
                                                 src="{{ url('assets/fonts_svg/visa_recommendation.svg') }}"
                                                 width="20">
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
                                                        <img alt="visa recommendation new"
                                                             src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(1)">New</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(1)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="sssn_inner">
                                                    <div class="sssn_inner_left">
                                                        <img alt="visa recommendation amendment"
                                                             src="{{ url('assets/fonts_svg/application_amendment.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(10)">Amendment</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(10)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            {{-- Visa Recommendation menu end --}}

                            {{-- Waiver Condition 7 & 8 menu start --}}
                            @if (in_array(19, $accessible_process) || in_array(20, $accessible_process))
                                <div class="right_sidebar_box">
                                    <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                         href="#serice5" aria-expanded="false" aria-controls="serice5">
                                        <div class="right_sidebar_supper_logo">
                                            <img alt="waiver"
                                                 src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                 width="20">
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
                                                        <img alt="waiver condition7"
                                                             src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(19)">Condition
                                                            No 7</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(19)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="sssn_inner">
                                                    <div class="sssn_inner_left">
                                                        <img alt="waiver condition8"
                                                             src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(20)">Condition
                                                            No 8</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(20)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            {{-- Waiver Condition 7 & 8 menu end --}}

                            {{-- Import Permission menu start --}}
                            @if (in_array(21, $accessible_process))
                                <div class="right_sidebar_box">
                                    <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                         href="#serice6" aria-expanded="false" aria-controls="serice6">
                                        <div class="right_sidebar_supper_logo">
                                            <img alt="import permission"
                                                 src="{{ url('assets/fonts_svg/application_amendment.svg') }}"
                                                 width="20">
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
                                                        <img alt="import permission new"
                                                             src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(21)">New</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(21)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            @endif
                            {{-- Import Permission menu end --}}

                            {{-- Work Permit menu start --}}
                            @if (in_array(2, $accessible_process) ||
                                    in_array(3, $accessible_process) ||
                                    in_array(4, $accessible_process) ||
                                    in_array(5, $accessible_process))
                                <div class="right_sidebar_box">
                                    <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                         href="#serice7" aria-expanded="false" aria-controls="serice7">
                                        <div class="right_sidebar_supper_logo">
                                            <img alt="work permit"
                                                 src="{{ url('assets/fonts_svg/work_permit.svg') }}" width="20">
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
                                                        <img alt="work permit new"
                                                             src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(2)">New</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(2)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="sssn_inner">
                                                    <div class="sssn_inner_left">
                                                        <img alt="work permit extension"
                                                             src="{{ url('assets/fonts_svg/application_extension.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(3)">Extension</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(3)">Apply</a>
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
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(4)">Amendment</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(4)">Apply</a>
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
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(5)">Cancellation</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(5)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            @endif
                            {{-- Work Permit menu end --}}

                            {{-- Remittance Menu Start --}}
                            @if (in_array(11, $accessible_process))
                                <div class="right_sidebar_box">
                                    <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                         href="#serice8" aria-expanded="false" aria-controls="serice8">
                                        <div class="right_sidebar_supper_logo">
                                            <img alt="remittance" src="{{ url('assets/fonts_svg/ramittance.svg') }}"
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
                                                             src="{{ url('assets/fonts_svg/application_new.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(11)">New</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(11)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            {{-- Remittance Menu end --}}


                            {{-- IRC recommendation Menu Start --}}
                            @if (in_array(13, $accessible_process) ||
                                    in_array(14, $accessible_process) ||
                                    in_array(15, $accessible_process) ||
                                    in_array(16, $accessible_process))
                                <div class="right_sidebar_box">
                                    <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                         href="#serice9" aria-expanded="false" aria-controls="serice9">
                                        <div class="right_sidebar_supper_logo">
                                            <img alt="irc recommendation"
                                                 src="{{ url('assets/fonts_svg/1st_adhoc.svg') }}" width="20">
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
                                                        <img alt="1st Adhoc"
                                                             src="{{ url('assets/fonts_svg/1st_adhoc.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(13)">1st
                                                            Adhoc</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(13)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="sssn_inner">
                                                    <div class="sssn_inner_left">
                                                        <img alt="2nd Adhoc"
                                                             src="{{ url('assets/fonts_svg/2nd_adhoc.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(14)">2nd
                                                            Adhoc</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(14)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="sssn_inner">
                                                    <div class="sssn_inner_left">
                                                        <img alt="3rd Adhoc"
                                                             src="{{ url('assets/fonts_svg/3rd_adhoc.svg') }}"
                                                             width="20">
                                                        <a
                                                                href="{{ url('dashboard/apply-service') }}" onclick="redirect(15)">3rd
                                                            Adhoc</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(15)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="sssn_inner">
                                                    <div class="sssn_inner_left">
                                                        <img alt="Regular" src="{{ url('assets/fonts_svg/3rd_adhoc.svg') }}" width="20">
                                                        <a href="{{ url('dashboard/apply-service') }}" onclick="redirect(16)">Regular</a>
                                                    </div>
                                                    <div class="sssn_inner_right">
                                                        <a style="color: #fff; text-decoration: none;"
                                                           href="{{ url('dashboard/apply-service') }}"
                                                           class="btn btn-xs btn-success" role="button" onclick="redirect(16)">Apply</a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            {{-- IRC recommendation Menu end --}}

                            {{-- Featured Stakeholder Service Menu Start --}}
                            @if (count($featuredStakeholderServices) > 0)
                                @foreach ($featuredStakeholderServices as $key => $featuredStakeholderService)
                                    <div class="right_sidebar_box">
                                        <div class="right_sidebar_supper down_up_arrow" data-toggle="collapse"
                                             href="#serice61" aria-expanded="false" aria-controls="serice61">
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

                                        <div class="right_sidebar_sub_name text-justify collapse" id="serice61">
                                            <ul>
                                                @foreach ($featuredStakeholderService['process_sub_names'] as $process_sub_name)
                                                    <li>
                                                        <div class="sssn_inner">
                                                            <div class="sssn_inner_left">
                                                                <img alt="{{ $process_sub_name['name'] }}"
                                                                     src="{{ url('assets/images/dashboard/circle.png') }}"
                                                                     width="10">
                                                                <a
                                                                        href="{{ url('dashboard/apply-service') }}" onclick="redirect({{ $process_sub_name['id'] }})">{{ $process_sub_name['name'] }}</a>
                                                            </div>
                                                            <div class="sssn_inner_right">
                                                                <a style="color: #fff; text-decoration: none;"
                                                                   href="{{ url('dashboard/apply-service') }}"
                                                                   class="btn btn-xs btn-success" role="button" onclick="redirect({{ $process_sub_name['id'] }})">Apply</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            {{-- Featured Stakeholder Service Menu end --}}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-4" style="margin-bottom: 25px;">
            <div id="myCarousel1" class="carousel slide carousel-fade" data-ride="carousel" data-interval="2500" style="margin: 0 0 15px;">
                <div class="carousel-inner" style="margin-bottom: 10px;">
                        <?php
                        $i = 0;
                        ?>
                    @foreach ($dashBoardSlider as $dashData)
                        @if ($i == '0')
                            <div class="item active">
                                <a href="{{ url($dashData->url?$dashData->url:'#') }}" {{ $dashData->url?'target="_blank"':'' }} rel="noopener noreferrer">
                                    <img src="{{ url($dashData->image) }}" alt="{{ $dashData->title }}" style="width:100%; border-radius: 20px;" onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                                </a>
                            </div>
                        @else
                            <div class="item">
                                <a href="{{ url($dashData->url?$dashData->url:'#') }}" {{ $dashData->url?'target="_blank"':'' }} rel="noopener noreferrer">
                                    <img src="{{ url($dashData->image) }}" alt="{{ $dashData->title }}" style="width:100%; border-radius: 20px;" onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                                </a>
                            </div>
                        @endif
                            <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="notice_instruction">
                {{-- Notice & Instructions Start --}}
                <div class="row">
                    <div class="col-md-12">
                        <p class="dash-box-heading">Notice & Instructions</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @foreach ($notice as $key => $boardNotice)
                            <div class="dash-notice-box">
                                <div class="dash-notice-box-content">
                                    <div class="dash-notice-box-heading down_up_arrow">
                                        <p>{{ date('d M Y', strtotime($boardNotice->Date)) }}</p>

                                        <a class="" data-toggle="collapse" href="#notice_{{ $key }}" aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                           aria-controls="notice_{{ $key }}"> {{ $boardNotice->heading }}
                                        </a>
                                    </div>

                                    {{-- <div class="dash-notice-box-details text-justify collapse {{ $key == 0 ? 'in' : '' }}" id="notice_{{$key}}">
                                        {!! $boardNotice->details !!}
                                    </div> --}}

                                    <div class="dash-notice-box-details text-justify collapse {{ $key == 0 ? 'in' : '' }}"
                                         id="notice_{{ $key }}">
                                        <div class="short-content">
                                            {!! strlen($boardNotice->details) > 300 ? substr($boardNotice->details, 0, 300) . '.....' : $boardNotice->details !!}
                                        </div>
                                        <div class="full-content" style="display: none;">
                                            {!! $boardNotice->details !!}
                                        </div>
                                        @if (strlen($boardNotice->details) > 300)
                                            <a class="see-more-btn font-weight-bold">See More</a>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a
                                href="{{ url('support/view-notice/' . \App\Libraries\Encryption::encodeId($notice[0]->id)) }}">More
                            Notices</a>
                    </div>
                </div>
                {{-- Notice & Instructions End --}}
            </div>
        </div>
    </div>
@endif

{{--  Widget box --}}
@if ($services && \Illuminate\Support\Facades\Auth::user()->first_login == 1 && !in_array($type[0], [11, 13, 5])) <!-- Bank User -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" style="margin-bottom: 20px; border: 2px solid #337ab7;">
            <div class="panel-heading">
                <div class="pull-left" style="line-height: 35px;">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </strong>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body" style="padding: 15px 5px 5px 5px;">

                {{-- <div class="col-lg-3 col-md-3"> --}}
                {{-- <div class="panel panel-default" style="margin-bottom: 15px"> --}}
                {{-- <div class="panel-heading" style="padding: 10px 15px; min-height: 90px"> --}}
                {{-- <div class="row"> --}}
                {{-- <div class="col-xs-3"> --}}
                {{-- <i class="fa fa-list"></i> --}}
                {{-- </div> --}}
                {{-- <div class="col-xs-9 text-right"> --}}
                {{-- <div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;"> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <div class="row"> --}}
                {{-- <div class="col-xs-12 text-right"> --}}
                {{-- <div style="font-size: 13px;font-weight: bold"> --}}
                {{-- Feedback List --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <a href="/dashboard/feedback-lists" target="&quot;_blank&quot;"> --}}
                {{-- <div class="panel-footer"> --}}
                {{-- <span class="pull-left">View details</span> --}}
                {{-- <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span> --}}
                {{-- <div class="clearfix"></div> --}}
                {{-- </div> --}}
                {{-- </a> --}}
                {{-- </div> --}}
                {{-- </div> --}}

                @foreach ($services as $service)
                    <div class="col-lg-3 col-md-3">
                        {{-- <div class="panel panel-green"> --}}
                        <div class="panel panel-{{ !empty($service['panel']) ? $service['panel'] : 'default' }}"
                             style="margin-bottom: 15px">
                            <div class="panel-heading" style="padding: 10px 15px; min-height: 90px">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa {{ $service['icon'] }}"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;">
                                            {{ !empty($service['totalApplication']) ? $service['totalApplication'] : '0' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div style="font-size: 13px;font-weight: bold">
                                            {{ !empty($service['name']) ? $service['name'] : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a
                                    href="{{ !empty($service['form_url']) && $service['form_url'] == '/#'
                                        ? 'javascript:void(0)'
                                        : url($service['form_url'] . '/list/' . \App\Libraries\Encryption::encodeId($service['id'])) }}">
                                <div class="panel-footer">
                                    <span class="pull-left">{!! trans('messages.details') !!}</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
{{-- <div class="row"> --}}

{{-- </div> --}}
{{-- <br> --}}
@endif


{{-- Chart list --}}
<div class="row">
    <?php
    $desk_id_array = explode(',', \Session::get('user_desk_ids'));

    ?>
    @if (!empty($desk_id_array[0]) || $user_type == '1x101' || $user_type == '5x505')

        <div class="text-center">
            <!-- Morris Chart -->
                <?php
            if (!empty($deshboardObject)) {
            foreach ($deshboardObject as $row) {
                $div = 'dbobj_' . $row->db_obj_id;
                ?>
            <div class="col-md-4">
                    <?php
                    $para1 = DB::select(DB::raw($row->db_obj_para1));
                switch ($row->db_obj_type) {
                case 'PIE_CHART':

                    ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5><strong><?php echo $row->db_obj_title; ?></strong></h5>
                    </div>
                    <div class="panel-body">
                        <div id="<?php echo $div; ?>" style="width: 100%; height: 350px; text-align:center;">
                            <br /><br />Chart will be loading in 5 sec...
                        </div>
                    </div>
                </div>

                    <?php

                    $script = $row->db_obj_para2;
                    //$datav['charttitle'] = $row->db_obj_title;
                    $datav['charttitle'] = '';
                    $datav['chartdata'] = json_encode($para1);
                    $datav['baseurl'] = url();
                    $datav['chartediv'] = $div;

                    echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $datav) . '</script>';
                    break;
                case 'CANVAS':
                    ?>
                <canvas style="width: 100%; height: 350px; " id="<?php echo $div; ?>"><br /><br />Chart will be
                    loading in 5 sec...</canvas>
                    <?php
                    $script = $row->db_obj_para2;
                    $datav['charttitle'] = $row->db_obj_title;
                    $datav['chartdata'] = json_encode($para1);

                    $datav['baseurl'] = url();
                    $datav['chartediv'] = $div;
                    echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $datav) . '</script>';
                    break;
                    default:
                        break;
                }
                    ?>
            </div>
                <?php
            }
            }
                ?>
        </div>

        <!-- Bar Chart -->
        @if (!empty($dashboardObjectBarChart))
                <?php
                $i = 0;
                ?>
            @foreach ($dashboardObjectBarChart as $record)
                    <?php
                    $i++;
                    ?>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5><strong>{!! $record->db_obj_title !!}</strong>
                        </div>
                        <div class="panel-body">
                                <?php
                                $barChartData = DB::select(DB::raw($record->db_obj_para1));
                                $barChartArray = [];

                                foreach ($barChartData as $data) {
                                    $barChartArray[$data->TITLE] = $data->VALUE;
                                }
                                ?>
                            <div id="bar-example<?php echo $i; ?>"></div>

                            <script>
                                Morris.Bar({
                                    element: 'bar-example' + '{{ $i }}',
                                    data: [
                                                <?php
                                            foreach ($barChartArray as $key => $val) {?> {
                                            y: '<?php echo $key; ?>',
                                            a: '<?php echo $val; ?>'
                                        },
                                            <?php

                                        }?>
                                    ],
                                    xkey: 'y',
                                    ykeys: ['a'],
                                    labels: ['Series A'],
                                    barColors: function(row, series, type) {
                                        if (series.key == 'a') {
                                            if (row.y < 10)
                                                return "red";
                                            else if (row.y >= 10 && row.y <= 50)
                                                return "green";
                                            else
                                                return "blue";
                                        } else {
                                            return "green";
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 10px;" class="text-center">
                <!-- Canvas Chart -->
                    <?php
                if (!empty($dashboardObjectCanvas)) {
                foreach ($dashboardObjectCanvas as $row) {
                    $div = 'dbobj_' . $row->db_obj_id;
                    ?>
                <div class="col-md-12">
                        <?php
                        $para1 = DB::select(DB::raw($row->db_obj_para1));
                    switch ($row->db_obj_type) {
                    case 'SCRIPT':
                        ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5><strong><?php echo $row->db_obj_title; ?></strong></h5>
                        </div>
                        <div class="panel-body">
                            <div id="<?php echo $div; ?>" style="width: 100%; height: 350px; text-align:center;">
                                <br /><br />Chart will be loading in 5 sec...
                            </div>
                        </div>
                    </div>

                        <?php
                        $script = $row->db_obj_para2;
                        //$datav['charttitle'] = $row->db_obj_title;
                        $datav['charttitle'] = '';
                        $datav['chartdata'] = json_encode($para1);
                        $datav['baseurl'] = url();
                        $datav['chartediv'] = $div;
                        echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $datav) . '</script>';
                        break;
                    case 'CANVAS':
                        ?>
                    <canvas style="width: 100%; height: 350px; " id="<?php echo $div; ?>"><br /><br />Chart will be
                        loading in 5 sec...</canvas>
                        <?php
                        $script = $row->db_obj_para2;
                        $datav['charttitle'] = $row->db_obj_title;
                        $datav['chartdata'] = json_encode($para1);

                        $datav['baseurl'] = url();
                        $datav['chartediv'] = $div;
                        echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $datav) . '</script>';
                        break;
                        default:
                            break;
                    }
                        ?>
                </div>
                    <?php
                }
                }
                    ?>
            </div>
        @endif
    @endif
</div>
<!-- /.row -->
<!-- charts -->

@if (!in_array($user_type, ['5x505']))
    <!-- Desk User Notice & Instruction -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info" style="border: 2px solid #bce8f1; margin-bottom: 20px;">
                <div class="panel-heading">
                    <div class="pull-left" style="line-height: 35px;">
                        <strong><i class="far fa-newspaper" aria-hidden="true"></i> More Notice &
                            Instructions:</strong>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- /.panel-heading -->

                <div class="panel-body" style=" max-height:200px; overflow-y: scroll">
                    @foreach ($notice as $boardNotice)
                        <a target="_blank" rel="noopener"
                           href="{{ url('support/view-notice/' . \App\Libraries\Encryption::encodeId($boardNotice->id)) }}"
                           class="hover-item" style="text-decoration: none">
                            <div class="panel panel-default hover-item"
                                 style="margin-top: 2px; border: 1px solid #86bb86">
                                <div>
                                    <div class="pull-right" style="margin: 8px 30px 0px 0px;">
                                        <button
                                                class="btn btn-{{ $boardNotice->importance }} btn-xs">{{ $boardNotice->importance }}
                                            <span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                        </button>
                                    </div>

                                    {{-- <div class="pull-right" style="margin: 15px 15px 0px 0px;"><i class="fa fa-chevron-right"></i></div> --}}
                                    <div class="panel-heading" style="border-left: 5px solid #31708f">
                                        <div>{{ $boardNotice->heading }}
                                            <br>{{ date('d M Y', strtotime($boardNotice->Date)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endif
<style>
    .panel {
        margin: 0px;
    }
</style>

<div class="row">
    {{-- <h2>Modal Example</h2> --}}
    <!-- Trigger the modal with a button -->
    {{-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Open Modal</button> --}}

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog" style="margin-top:300;padding-top:0">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">

                    <style type="text/css">
                        h1 {
                            font-size: 16px;
                            font-weight: bold;
                        }

                        h2 {
                            margin-top: 10px;
                            text-align: center;
                        }

                        ol,
                        ul,
                        li {
                            list-style: none;
                        }

                        #touchSlider2 {
                            width: 570px;
                            height: 230px;
                            margin: 0 auto;
                            background: #ccc;
                            overflow-x: hidden;
                        }

                        #touchSlider2 ul {
                            width: 99999px;
                            height: 150px;
                            top: 0;
                            left: 0;
                            position: relative;
                            overflow: hidden;
                        }

                        #touchSlider2 ul li {
                            overflow: scroll;
                            float: left;
                            width: 500px;
                            height: 600px;
                            background: #7d868f;
                            font-size: 14px;
                            color: #fff;
                        }

                        .paging {
                            background: #f5f5f5;
                            text-align: center;
                            overflow: hidden;
                        }

                        .paging .btn_page {
                            display: inline-block;
                            width: 10px;
                            height: 10px;
                            margin: 3px;
                            font-size: 0px;
                            line-height: 0;
                            text-indent: -9999px;
                            background: #3399CC;
                        }

                        .paging .btn_page.on {
                            background: #ff0000;
                        }

                        .modal-footer {
                            padding: 5px;
                        }

                        .header {
                            width: 800%;
                            height: 50px;
                            background: skyblue;
                            position: fixed;
                            margin: 0 auto;
                        "

                        }
                    </style>

                    <!-- jQuery 1.7+, IE 7+ -->

                    <script src="{{ asset('assets/plugins/jquery.touchSlider.js') }}" type="text/javascript"></script>

                    <script type="text/javascript">
                        //<![CDATA[
                        $(document).ready(function() {
                            $("#touchSlider2").touchSlider({
                                roll: false,
                                page: 1,
                                speed: 300,
                                btn_prev: $("#touchSlider2").next().find(".btn_prev"),
                                btn_next: $("#touchSlider2").next().find(".btn_next")
                            });

                        });
                        //]]>
                    </script>

                    {{-- <div id="touchSlider2"> --}}
                    {{-- <ul> --}}
                    {{-- @foreach ($SurveyFeatures as $surveyData) --}}
                    {{-- <li> --}}
                    {{-- <div class="" style=""> --}}
                    {{-- {!!  $surveyData->feature_description!!} --}}
                    {{-- </div> --}}

                    {{-- <div id="features_{{$surveyData->id}}" style="bottom: 0; font-weight: bold;color: rebeccapurple; position: fixed;margin: 0px; background: white;">Are you helpful for this features? <button class="btn btn-xs btn-warning feedback" value="yes#{{\App\Libraries\Encryption::encodeId($surveyData->id)}}">Yes</button> <button class="btn btn-xs btn-danger feedback" value="no#{{\App\Libraries\Encryption::encodeId($surveyData->id)}}">No</button> --}}
                    {{-- </div> --}}
                    {{-- </li> --}}

                    {{-- @endforeach --}}
                    {{-- </ul> --}}
                    {{-- </div> --}}

                    <div class="btn_area modal-footer">


                        <button type="button" class="btn btn-default steps_modal" data-dismiss="modal"
                                value="skip">Skip</button>
                        <button type="button" class="btn_prev btn btn-info"><i
                                    class="fa fa-angle-double-left"></i>prev</button>
                        <button type="button" class="btn btn-primary btn_next steps_modal" value="next">Next <i
                                    class="fa fa-angle-double-right"></i></button>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <!--You need to know modal-->
    <div class="modal fade" id="youNeedToKnowModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Information regarding four categories of Basic Information</h4>
                </div>
                <div class="modal-body text-justify">
                    <span class="text-danger"><strong>Caution:</strong> It is needed to complete Basic information at
                        first for getting any service. You may select any one category and it can’t be changed later.
                        So, please know in details for selecting your desired category properly according to the
                        following information:</span> <br> <br>

                    <strong>(i) New Company registration:</strong> The company is new in BIDA One Stop Service Portal
                    and did not take any service from the BIDA E-serve (old portal of BIDA’ services
                    https://eservice.bida.gov.bd) yet.<br> <br>
                    <strong>(ii) Existing Company registration:</strong> The company is new in BIDA One Stop Service
                    Portal and took a few services from the BIDA E-serve (old portal of BIDA’s services
                    https://eservice.bida.gov.bd).<br> <br>

                    <strong>The above categories (i) & (ii) can get only company registration services like:</strong>

                    <ul class="in_list_style">
                        <li>Registrar of Joint Stock Companies And Firms (RJSC)</li>
                        <li>National Board of Revenue</li>
                        <li>Chittagong Development Authority</li>
                        <li>Bangladesh Power Development Board</li>
                        <li>Department of Environment</li>
                    </ul>

                    <strong>(iii) New User of BIDA’s services:</strong> The company is new in BIDA One Stop Service
                    Portal and did not take any service from the BIDA E-serve (old portal of BIDA’s services
                    https://eservice.bida.gov.bd) yet.<br> <br>
                    <strong>(iv) Existing User of BIDA’s services:</strong> The company is new in BIDA One Stop Service
                    Portal and takes a few services from the BIDA E-serve (old portal of BIDA’s services
                    https://eservice.bida.gov.bd).<br> <br>

                    <strong>The above categories (iii) & (iv) can get all the services of BIDA including Company
                        registration related services.</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<style>
    .modal {
        text-align: center;
        padding: 0 !important;
    }

    .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px;
    }

    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
</style>
{{-- //Mosonry js.. --}}
<script src="{{ asset('assets/plugins/masonry/masonry.min.js') }}" type="text/javascript"></script>

<script>
    $(window).on('load', function() {

        {{-- $.ajax({ --}}
        {{-- url: '{{ url("/dashboard-featureShow") }}', --}}
        {{-- type: "get", --}}
        {{-- data: {}, --}}
        {{-- success: function(data){ --}}
        {{-- if(data == "show"){ --}}
        {{-- $('#myModal').modal('show'); --}}
        {{-- } --}}

        {{-- } --}}
        {{-- }) --}}
    });

    $(document).ready(function() {

        $('.feedback').click(function() {
            var the = $(this).parent();
            var data = $(this).val();
            var value = data.split("#")[0];
            var id = data.split("#")[1];

            $.ajax({
                url: '{{ url('/dashboard/store-feedback') }}',
                type: "post",
                data: {
                    _token: $('input[name="_token"]').val(),
                    value: value,
                    id: id
                },
                success: function(data) {
                    the.hide();
                }
            })
        });

        $('.steps_modal').click(function() {

            var link = $('.modal-footer');
            var offset = link.offset();

            var top = offset.top;
            var left = offset.left;

            var bottom = top + link.outerHeight();
            var right = left + link.outerWidth();
            console.log(bottom, right);
            $('#features_6').css({
                bottom: bottom + 'px',
                right: right + 'px'
            });


            var skip_id = $(this).val();

            $.ajax({
                url: '{{ url('/dashboard/steps-modal') }}',
                type: "post",
                data: {
                    _token: $('input[name="_token"]').val(),
                    value: skip_id
                },
                success: function(data) {

                }
            })
        });

        // Stakeholder Services
        setTimeout(function() {
            $('.grid').masonry({
                itemSelector: '.grid-item',
            })
        }, 100);



        // see more button
        $('.see-more-btn').click(function() {
            var shortContent = $(this).siblings('.short-content');
            var fullContent = $(this).siblings('.full-content');

            if (shortContent.is(':visible')) {
                shortContent.hide();
                fullContent.show();
                $(this).text('See Less');
            } else {
                shortContent.show();
                fullContent.hide();
                $(this).text('See More');
            }
        });
    });

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
</script>

<script>
    $(document).ready(function() {
        // Collapse all except the first one initially
        $(".stakeholder_service_sub_name.collapse").not(".show").collapse("hide");

        // Toggle collapse when the parent is clicked
        $(".right_sidebar_supper").click(function() {
            $(".stakeholder_service_sub_name.show").not($(this).next()).collapse("hide");
        });
    });
</script>
<script>
    function redirect(id){
        sessionStorage.removeItem("service_id");
        sessionStorage.setItem("service_id", id);
        console.log(sessionStorage.getItem("service_id"));
    }
</script>

<script>
    $(document).ready(function() {
        $('#myCarousel1').on('slide.bs.carousel', function() {
            var currentIndex = $('div.item.active').index();
            var indicators = $('.dashborad-carousel-indicators li');

            indicators.removeClass('active');
            indicators.eq(currentIndex).addClass('active');
        });
    });
</script>
