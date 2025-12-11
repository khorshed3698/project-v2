@extends('layouts.admin')
@section('content')
    <?php
    $moduleName = Request::segment(1);
    $user_type = CommonFunction::getUserType();
    $accessMode = "V";
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');

    ?>
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="col-lg-12">
                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

                </div>

                @if(empty($delegated_desk))
                    <div class="modal fade" id="ProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" id="frmAddProject"></div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-12">
                    <div class="panel panel-info" style="">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h5><i class="fa fa-list"></i> <b>Individual Licence List</b></h5>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-primary txt-style">
                                            Licence Entities
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6  left-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-primary txt-style">
                                            Name of Licence
                                        </div>
                                    </div>
                                </div>
                                @foreach($stakeholder_services as $stakeholder_service)
                                    <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="{{$stakeholder_service->service_url}}" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                @if(!empty($stakeholder_service->logo) && file_exists(public_path($stakeholder_service->logo)))
                                                    <img class="img pull-left" src="{{ asset($stakeholder_service->logo) }}">
                                                @else
                                                    <img class="img pull-left" src="{{ asset('assets/images/u34.png') }}">
                                                @endif
                                            </div>
                                            <p class="pull-left licence_entity">{{$stakeholder_service->process_supper_name}}</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style ">
                                            <p class="pull-left licence_name ">{{$stakeholder_service->process_sub_name}}</p>

                                            <a style="margin: 0px 2px" type="button"
                                               href="{{$stakeholder_service->service_url}}"
                                               target="_blank" class="pull-right btn btn-default btn-primary btn-sm">Service
                                                Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">

                                        @if($stakeholder_service->total_app != 0)
                                                <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                                   href="{{url($stakeholder_service->form_url.'/list/'.\App\Libraries\Encryption::encodeId($stakeholder_service->id))}}"
                                                   role="button">Open</a>
                                            @else
                                                <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"

                                                    href="{{url('process/'.$stakeholder_service->form_url.'/add/'.\App\Libraries\Encryption::encodeId($stakeholder_service->id))}}"
                                                   role="button">Apply</a>
@endif
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="constructionAlert" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"><img src="{{URL::to('/assets/images/warning.png')}}" alt="warning"/> Under
                        Construction!!</h4>
                </div>
                <div class="modal-body">
                    <p><img src="{{URL::to('/assets/images/under-constructon.png')}}" style="width:60px;"
                            alt="warning"/> <b>This is under construction.</b></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    @include('partials.datatable-scripts')
    <script language="javascript">

    </script>
    <style>
        * {
            font-weight: normal;
        }

        .unreadMessage td {
            font-weight: bold;
        }
    </style>
    @yield('footer-script2')
@endsection
