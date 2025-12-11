@extends('layouts.admin')

@section('content')
@if(in_array(\App\Libraries\CommonFunction::getUserType(),['1x101','5x505','4x404']))
    {{--<div class="row">--}}
        <div class="col-lg-12">

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="pull-left">
                        {{--<h5><strong><i class="fa fa-list"></i> Feedback summery</strong></h5>--}}
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">

                        <div class="col-lg-12">
                        </div>
                        <div class="col-lg-12  text-center panel panel-default"><h4><b>Application wise Feedback Activities summary</b></h4></div>
                        <div class="col-lg-8 " style="color: #082e5d"><h4>Feedback (Pending): <span class="label label-danger">{{$pendingFeedbackApplication}}</span> </h4></div>
                        <div class="col-lg-8 " style="color: #082e5d"><h4>Feedback (Given): </h4></div>
                        <table id="list" class="table table-striped table-bordered" aria-label="Detailed Feedback summery">
                            <thead>
                            <tr>
                                <th style="text-align: center">#</th>
                                <th style="text-align: center">Task description</th>
                                <th style="text-align: center">Number of Application</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="text-align: center">1</td>
                                <td>
                                    {{--<label for="rate5"  class="fa fa-smile starlabel pointer" style="cursor:pointer;font-size: 15px; color: #DAC16C" id="star5"></label>--}}
                                    {{--<label for="rate5"  class="fa fa-smile starlabel pointer" style="cursor:pointer;font-size: 15px;" id="star5"></label>--}}
                                    {{--<label for="rate5"  class="fa fa-smile starlabel pointer" style="cursor:pointer;font-size: 15px;" id="star5"></label>--}}
                                    {{--<label for="rate5"  class="fa fa-smile starlabel pointer" style="cursor:pointer;font-size: 15px;" id="star5"></label>--}}
                                    {{--<label for="rate5"  class="fa fa-smile starlabel pointer" style="cursor:pointer;font-size: 15px;" id="star5"></label>--}}
                                    <label for="rate1" title="Very poor"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star1"  onclick="add(1)" >
                                        <img src="/assets/images/Feedbackimage/_horrible.png" alt="_horrible" style="width: 100%" id="image_11"> </label>
                                    <label for="rate2" title="Poor" class="starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star2"  onclick="add(2)">
                                        <img src="/assets/images/Feedbackimage/poor.png" alt="poor" style="width: 100%"  id="image_2"> </label>
                                    <label for="rate3" title="Average"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star3"  onclick="add(3)">
                                        <img src="/assets/images/Feedbackimage/averge.png" alt="averge" style="width: 100%" id="image_3"></label>
                                    <label for="rate4" title="Satisfied"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star4"  onclick="add(4)">
                                        <img src="/assets/images/Feedbackimage/good.png" alt="good"  style="width: 100%" id="image_4"></label>
                                    <label for="rate5" title="Strongly satisfied"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star5"  onclick="add(5)">
                                        <img src="/assets/images/Feedbackimage/excellent.png" alt="excellent" style="width: 100%"  id="image_5"></label>
                                    <span class="label label-danger label-large" style="font-size: small;  vertical-align: super;">(Very Poor)</span>
                                </td>
                                <td style="text-align: center">
                                    <span class="label label-danger  ">{{$getActivateFeedback->very_poor}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center">2</td>
                                <td>
                                    <label for="rate1" title="Very poor"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star1"  onclick="add(1)" >
                                        <img src="/assets/images/Feedbackimage/horrible.png" alt="horrible" style="width: 100%" id="image_11"> </label>
                                    <label for="rate2" title="Poor" class="starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star2"  onclick="add(2)">
                                        <img src="/assets/images/Feedbackimage/_poor.png" alt="_poor" style="width: 100%"  id="image_2"> </label>
                                    <label for="rate3" title="Average"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star3"  onclick="add(3)">
                                        <img src="/assets/images/Feedbackimage/averge.png" alt="averge" style="width: 100%" id="image_3"></label>
                                    <label for="rate4" title="Satisfied"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star4"  onclick="add(4)">
                                        <img src="/assets/images/Feedbackimage/good.png" alt="good" style="width: 100%" id="image_4"></label>
                                    <label for="rate5" title="Strongly satisfied"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star5"  onclick="add(5)">
                                        <img src="/assets/images/Feedbackimage/excellent.png" alt="excellent" style="width: 100%"  id="image_5"></label>
                                    <span class="label label-warning" style="font-size: small;  vertical-align: super;">(Poor)</span>
                                </td>
                                <td style="text-align: center"><span class="label label-danger">{{$getActivateFeedback->poor}}</span></td>
                            </tr>
                            <tr>

                            <tr>
                                <td style="text-align: center">3</td>
                                <td>
                                    <label for="rate1" title="Very poor"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star1"  onclick="add(1)" >
                                        <img src="/assets/images/Feedbackimage/horrible.png" alt="horrible" style="width: 100%" id="image_11"> </label>
                                    <label for="rate2" title="Poor" class="starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star2"  onclick="add(2)">
                                        <img src="/assets/images/Feedbackimage/poor.png" alt="poor" style="width: 100%"  id="image_2"> </label>
                                    <label for="rate3" title="Average"  class="starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star3"  onclick="add(3)">
                                        <img src="/assets/images/Feedbackimage/_averge.png" alt="_averge" style="width: 100%" id="image_3"></label>
                                    <label for="rate4" title="Satisfied"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star4"  onclick="add(4)">
                                        <img src="/assets/images/Feedbackimage/good.png" alt="good" style="width: 100%" id="image_4"></label>
                                    <label for="rate5" title="Strongly satisfied"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star5"  onclick="add(5)">
                                        <img src="/assets/images/Feedbackimage/excellent.png" alt="excellent" style="width: 100%"  id="image_5"></label>
                                    <span class="label label-primary" style="font-size: small;  vertical-align: super;">(Neither satisfied nor poor)</span>
                                </td>
                                <td style="text-align: center"><span class="label label-danger">{{$getActivateFeedback->neither_satisfied}}</span></td>
                            </tr>

                            <tr>
                                <td style="text-align: center">4</td>
                                <td>
                                    <label for="rate1" title="Very poor"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star1"  onclick="add(1)" >
                                        <img src="/assets/images/Feedbackimage/horrible.png" alt="horrible" style="width: 100%" id="image_11"> </label>
                                    <label for="rate2" title="Poor" class="starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star2"  onclick="add(2)">
                                        <img src="/assets/images/Feedbackimage/poor.png" alt="poor" style="width: 100%"  id="image_2"> </label>
                                    <label for="rate3" title="Average"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star3"  onclick="add(3)">
                                        <img src="/assets/images/Feedbackimage/averge.png" alt="averge" style="width: 100%" id="image_3"></label>
                                    <label for="rate4" title="Satisfied"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star4"  onclick="add(4)">
                                        <img src="/assets/images/Feedbackimage/_good.png" alt="_good"  style="width: 100%" id="image_4"></label>
                                    <label for="rate5" title="Strongly satisfied"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star5"  onclick="add(5)">
                                        <img src="/assets/images/Feedbackimage/excellent.png" alt="excellent" style="width: 100%"  id="image_5"></label>
                                    <span class="label label-info" style="font-size: small;  vertical-align: super;">(Satisfied)</span>
                                </td>
                                <td style="text-align: center"><span class="label label-danger">{{$getActivateFeedback->satisfied}}</span></td>
                            </tr>

                            <tr>
                                <td style="text-align: center">5</td>
                                <td>
                                    <label for="rate1" title="Very poor"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star1"  onclick="add(1)" >
                                        <img src="/assets/images/Feedbackimage/horrible.png" alt="horrible" style="width: 100%" id="image_11"> </label>
                                    <label for="rate2" title="Poor" class="starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star2"  onclick="add(2)">
                                        <img src="/assets/images/Feedbackimage/poor.png" alt="poor" style="width: 100%"  id="image_2"> </label>
                                    <label for="rate3" title="Average"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star3"  onclick="add(3)">
                                        <img src="/assets/images/Feedbackimage/averge.png" alt="averge" style="width: 100%" id="image_3"></label>
                                    <label for="rate4" title="Satisfied"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star4"  onclick="add(4)">
                                        <img src="/assets/images/Feedbackimage/good.png" alt="good" style="width: 100%" id="image_4"></label>
                                    <label for="rate5" title="Strongly satisfied"  class=" starlabel pointer" style="cursor:pointer;font-size: 50px;width: 50px" id="star5"  onclick="add(5)">
                                        <img src="/assets/images/Feedbackimage/_excellent.png" alt="_excellent" style="width: 100%"  id="image_5"></label>
                                    <span class="label label-success"  style="font-size: small;  vertical-align: super;">(Very Satisfied)</span>
                                </td>
                                <td style="text-align: center"> <span class="label label-danger">{{$getActivateFeedback->very_satisfied}}</span></td>
                            </tr>
                            <tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    {{--</div>--}}
    <div class="clearfix"><br></div>
@endif
@endsection

@section('footer-script')
@endsection