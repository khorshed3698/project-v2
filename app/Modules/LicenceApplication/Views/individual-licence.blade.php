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
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://www.roc.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left" src="{{ asset('assets/images/u34.png') }}">
                                            </div>
                                            <p class="pull-left licence_entity">Registrar of Joint Stock Companies And
                                                Firms (RJSC)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style ">
                                            <p class="pull-left licence_name ">Name Clearance</p>
                                            <?php $validstatus = 0; ?>
                                            @if($licenseApplications->nc_application != null)
                                                <?php
                                                $explodeCr1 = explode("@", $licenseApplications->nc_application);
                                                $ncdata = \App\Modules\LicenceApplication\Controllers\LicenceApplicationController::getNcvalidationdata($explodeCr1[1]);
                                                if ($ncdata != '') {
                                                    $validdate = \Carbon\Carbon::parse($ncdata)->format('d M Y H:i:m');;
                                                    $currentDate = \Carbon\Carbon::now()->format('d M Y H:i:m');;
                                                    if ($validdate > $currentDate) {
                                                        $validstatus = 1;
                                                    }
                                                } else if ($ncdata == '') {
                                                    $validstatus = 1;
                                                }

                                                ?>
{{--                                                @if($validstatus == 1)--}}
{{--                                                    <a type="button"--}}
{{--                                                       class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                                @endif--}}
                                            @endif
                                            <a style="margin: 0px 2px" type="button"
                                               href="http://www.roc.gov.bd/site/page/3de99758-e1a5-4ba0-aa0d-c0077c1d98f6/%E0%A6%A8%E0%A6%BE%E0%A6%AE%E0%A7%87%E0%A6%B0-%E0%A6%9B%E0%A6%BE%E0%A7%9C%E0%A6%AA%E0%A6%A4%E0%A7%8D%E0%A6%B0-%E0%A6%AA%E0%A7%8D%E0%A6%B0%E0%A6%A6%E0%A6%BE%E0%A6%A8"
                                               target="_blank" class="pull-right btn btn-default btn-primary btn-sm">Service
                                                Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">

                                        @if($licenseApplications->nc_application != null)

                                            <?php   $explodeCr = explode("@", $licenseApplications->nc_application); ?>
                                            @if($validstatus == 1)
                                                <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                                   href="{{url('process/licence-applications/name-clearance/view/'.\App\Libraries\Encryption::encodeId($explodeCr[1]).'/'.\App\Libraries\Encryption::encodeId($explodeCr[3]))}}"
                                                   role="button">Open</a>
                                            @else
                                                <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
{{--                                                   href="{{url('licence-applications/name-clearance/add')}}"--}}

                                                    href="{{url('process/licence-applications/name-clearance/add/'.\App\Libraries\Encryption::encodeId(107))}}"
                                                   role="button">Apply</a>
                                            @endif
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
{{--                                               href="{{url('licence-applications/name-clearance/add')}}" --}}
                                                href="{{url('process/licence-applications/name-clearance/add/'.\App\Libraries\Encryption::encodeId(107))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>
                                </div>


                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://www.roc.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left" src="{{ asset('assets/images/u34.png') }}">
                                            </div>
                                            <p class="pull-left licence_entity">Registrar of Joint Stock Companies And
                                                Firms (RJSC)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Company Registration</p>
{{--                                            @if($licenseApplications->cr_application != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://www.roc.gov.bd/site/page/855dc577-3035-4ca4-b376-49c517099a3e/Entity-Registration"
                                               target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->cr_application != null)
                                            <?php
                                            $explodeCr = explode("@", $licenseApplications->cr_application);
                                            ?>
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('process/licence-applications/company-registration/view/'.\App\Libraries\Encryption::encodeId($explodeCr[1]).'/'.\App\Libraries\Encryption::encodeId($explodeCr[2]))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/company-registration/add/'.\App\Libraries\Encryption::encodeId(104))}}">Apply</a>
{{--                                               href="{{url ('licence-applications/company-registration/company_type')}}">Apply</a>--}}
                                            {{--<a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success btn-xs licence_apply txt-style" href="{{url ('new-reg-page/new-reg-app')}}">New Reg</a>--}}
                                        @endif
                                    </div>
                                </div>
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://nbr.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left" src="{{ asset('assets/images/u34.png') }}">
                                            </div>
                                            <p class="pull-left licence_entity">National Board of Revenue</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">e-TIN Registration</p>
{{--                                            @if($licenseApplications->etin_application != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://nbr.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->etin_application != null)
                                            <?php
                                            $explodeCr = explode("@", $licenseApplications->etin_application);
                                            ?>
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('process/licence-applications/e-tin/view/'.\App\Libraries\Encryption::encodeId($explodeCr[1]).'/'.\App\Libraries\Encryption::encodeId($explodeCr[2]))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
{{--                                               href="{{url('licence-applications/e-tin/add')}}" role="button">Apply</a>--}}
                                            href="{{url('process/licence-applications/e-tin/add/'.\App\Libraries\Encryption::encodeId(106))}}" role="button">Apply</a>
                                        @endif
                                    </div>
                                </div>


                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://cda.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left"
                                                     src="{{ asset('stakeholder-logo/cda.jpg') }}">
                                            </div>
                                            <p class="pull-left licence_entity">Chattogram Development Authority
                                                (CDA)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Land Use Clearance</p>
                                            @if($licenseApplications->cda_app != null)
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
                                                {{--                                                <a type="button" href="{{url('licence-applications/cda-form/list/'.\App\Libraries\Encryption::encodeId(110))}}" class="pull-right btn btn-default btn-success btn-sm">List</a>--}}
                                            @endif
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://cda.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        <?php
                                        $explodeCr = explode("@", $licenseApplications->cda_app);
                                        ?>
                                        <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                           href="{{url('licence-applications/cda-form/list/'.\App\Libraries\Encryption::encodeId(110))}}"
                                           role="button">Open</a>
                                        {{--                                        @if($licenseApplications->cda_app != null)--}}
                                        <?php
                                        //                                            $explodeCr = explode("@",$licenseApplications->cda_app);
                                        ?>
                                        {{--                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style" href="{{url('licence-applications/cda-form/list/'.\App\Libraries\Encryption::encodeId(110))}}" role="button">Open</a>--}}
                                        {{--                                        @else--}}
                                        {{--                                            <a class="btn btn-sm btn-success" href="{{url('process/licence-applications/cda-form/add/'.\App\Libraries\Encryption::encodeId(110))}}" role="button">Apply</a>--}}
                                        {{--                                        @endif--}}
                                    </div>
                                </div>

                                {{--CDA Large and Special Porject permit--}}
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://cda.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left"
                                                     src="{{ asset('stakeholder-logo/cda.jpg') }}">
                                            </div>
                                            <p class="pull-left licence_entity">Chattogram Development Authority
                                                (CDA)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Large And Special Project Permit</p>

{{--                                            @if($licenseApplications->lspp_cda_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://cda.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->lspp_cda_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('cda-lspp/list/'.\App\Libraries\Encryption::encodeId(118))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/cda-lspp/add/'.\App\Libraries\Encryption::encodeId(118))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>
                                </div>

                                {{--CDA Building Construction Case--}}
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://cda.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left"
                                                     src="{{ asset('stakeholder-logo/cda.jpg') }}">
                                            </div>
                                            <p class="pull-left licence_entity">Chattogram Development Authority
                                                (CDA)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Building Construction Case</p>

{{--                                            @if($licenseApplications->bcc_cda_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://cda.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->bcc_cda_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('cda-bcc/list/'.\App\Libraries\Encryption::encodeId(121))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/cda-bcc/add/'.\App\Libraries\Encryption::encodeId(121))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://www.roc.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left" src="{{ asset('assets/images/u34.png') }}">
                                            </div>
                                            <p class="pull-left licence_entity">Registrar of Joint Stock Companies And
                                                Firms (RJSC)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Company Registration Foreign</p>
{{--                                            @if($licenseApplications->cr_application != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://www.roc.gov.bd/site/page/855dc577-3035-4ca4-b376-49c517099a3e/Entity-Registration"
                                               target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">

                                        <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                           href="{{url ('licence-applications/company-registration-foreign/company_type')}}">Apply</a>

                                    </div>
                                </div>


                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://nbr.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left" style="padding-top: 8px"
                                                     src="{{ asset('stakeholder-logo/VATLogo.png') }}">
                                            </div>
                                            <p class="pull-left licence_entity">National Board of Revenue</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">VAT Registration</p>
{{--                                            @if($licenseApplications->vat_application != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://nbr.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->vat_application != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('licence-applications/vat-registration/list/'.\App\Libraries\Encryption::encodeId(112))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/vat-registration/add/'.\App\Libraries\Encryption::encodeId(112))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://www.doe.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="https://ecc.doe.gov.bd/images/logodoe.png" width="30"
                                                     height="30">
                                            </div>
                                            <p class="pull-left licence_entity">DEPARTMENT OF ENVIRONMENT</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Environmental clearance</p>
{{--                                            @if($licenseApplications->doe_application != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://www.doe.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                           href="{{url('doe/list/'.\App\Libraries\Encryption::encodeId(108))}}"
                                           role="button">Open</a>
                                    </div>
                                </div>

                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="https://www.bpdb.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="https://www.bpdb.gov.bd/bpdb_new/bpdbasset/logo.png"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Bangladesh Power Development Board
                                                (BPDB)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">New Connection BPDB</p>
{{--                                            @if($licenseApplications->bpdb_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="https://www.bpdb.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                           href="{{url('new-connection-bpdb/list/'.\App\Libraries\Encryption::encodeId(109))}}"
                                           role="button">Open</a>
                                    </div>
                                </div>

                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="https://www.bpdb.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="https://dpdc.org.bd/img/logo.png"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Dhaka Power Distribution Company
                                                (DPDC)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">New Connection DPDC</p>
{{--                                            @if($licenseApplications->dpdc_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="https://dpdc.org.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                           href="{{url('new-connection-dpdc/list/'.\App\Libraries\Encryption::encodeId(114))}}"
                                           role="button">Open</a>
                                    </div>
                                </div>

                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://www.reb.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="{{ asset('stakeholder-logo/breb_logo.png') }}"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Bangladesh Rural Electrification Board
                                                (BREB)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">New Connection BREB</p>
{{--                                            @if($licenseApplications->breb_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://www.reb.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                           href="{{url('new-connection-breb/list/'.\App\Libraries\Encryption::encodeId(115))}}"
                                           role="button">Open</a>
                                    </div>
                                </div>
                                {{--New Connection NESCO--}}
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://nesco.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left"
                                                     style="padding-top: 8px"
                                                     src="{{ asset('stakeholder-logo/nesco.png') }}">
                                            </div>
                                            <p class="pull-left licence_entity">Northern Electricity Supply Company
                                                Limited (NESCO)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">New Connection NESCO</p>
{{--                                            @if($licenseApplications->nesco_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://nesco.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->nesco_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('new-connection-nesco/list/'.\App\Libraries\Encryption::encodeId(116))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/new-connection-nesco/add/'.\App\Libraries\Encryption::encodeId(116))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>
                                </div>

                                {{--New Connection DESCO--}}
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://desco.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left"
                                                     style="padding-top: 8px"
                                                     src="{{ asset('stakeholder-logo/desco.png') }}">
                                            </div>
                                            <p class="pull-left licence_entity">Dhaka Electric Supply Company Ltd.
                                                (DESCO)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">New Connection DESCO</p>
{{--                                            @if($licenseApplications->desco_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://desco.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->desco_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('new-connection-desco/list/'.\App\Libraries\Encryption::encodeId(117))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/new-connection-desco/add/'.\App\Libraries\Encryption::encodeId(117))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>
                                </div>

                                {{--New Connection WZPDCL--}}
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://www.wzpdcl.org.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div class="licence_logo">
                                                <img class="img pull-left"
                                                     src="{{ asset('stakeholder-logo/wzpdcl.png') }}"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">West Zone Power Distribution Company
                                                Limited
                                                (WZPDCL)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">New Connection WZPDCL</p>
{{--                                            @if($licenseApplications->wzpdcl_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://www.wzpdcl.org.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->wzpdcl_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('new-connection-wzpdcl/list/'.\App\Libraries\Encryption::encodeId(120))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/new-connection-wzpdcl/add/'.\App\Libraries\Encryption::encodeId(120))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="https://olm.ccie.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="https://olm.ccie.gov.bd/images/home_logo.jpg"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Chief Controller of Imports &
                                                Exports(CCI&E)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Industrial IRC (First Adhoc)</p>

{{--                                            @if($licenseApplications->cci_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="https://olm.ccie.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->cci_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('industrial-IRC/list/'.\App\Libraries\Encryption::encodeId(113))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/ccie/add/'.\App\Libraries\Encryption::encodeId(113))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>

                                </div>

                                {{-- trade license DSCC--}}

                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://116.68.194.158/cp/cportal/cp/southcc.aspx" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="{{ asset('stakeholder-logo/dhakasouth.jpg') }}"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Dhaka South City
                                                Corporation (DSCC)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Trade License (DSCC)</p>

{{--                                            @if($licenseApplications->tl_dscc_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="https://olm.ccie.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->tl_dscc_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('trade-license-dscc/list/'.\App\Libraries\Encryption::encodeId(119))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/trade-license-dscc/add/'.\App\Libraries\Encryption::encodeId(119))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>

                                </div>

                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://116.68.194.158/cp/cportal/cp/southcc.aspx" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="{{ asset('stakeholder-logo/dhakasouth.jpg') }}"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Dhaka North City
                                                Corporation (DNCC)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Trade License (DNCC)</p>

{{--                                            @if($licenseApplications->dncc_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="https://olm.ccie.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->dncc_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('trade-license-dncc/list/'.\App\Libraries\Encryption::encodeId(122))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/trade-license-dncc/add/'.\App\Libraries\Encryption::encodeId(122))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>

                                </div>

                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="https://www.dhakachamber.com/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="http://membership.dhakachamber.com/img/brand/logo_4.png"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Dhaka Chamber of Commerce &
                                                Industry(DCCI)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Country of Origin Service (COS)</p>

{{--                                            @if($licenseApplications->dcci_cos_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="https://olm.ccie.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->dcci_cos_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('dcci-cos/list/'.\App\Libraries\Encryption::encodeId(123))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/dcci-cos/add/'.\App\Libraries\Encryption::encodeId(123))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>

                                </div>

                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="http://www.rajuk.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="{{ asset('stakeholder-logo/rajuk.jpg') }}"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Rajdhani Unnayan Kartripakkha(RAJUK)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Land Use Clearance (General)</p>

{{--                                            @if($licenseApplications->rajuk_luc_general_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="http://www.rajuk.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->rajuk_luc_general_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('rajuk-luc-general/list/'.\App\Libraries\Encryption::encodeId(124))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/rajuk-luc-general/add/'.\App\Libraries\Encryption::encodeId(124))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>

                                </div>
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="https://www.ccc.org.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="{{ asset('stakeholder-logo/ctcclogo.jpg') }}"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Chattogram City Corporation (CCC)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Trade License CCC</p>

{{--                                            @if($licenseApplications->ctcc_apps != null)--}}
{{--                                                <a type="button" class="pull-right btn btn-default btn-success btn-sm">Applied</a>--}}
{{--                                            @endif--}}
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->ctcc_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('ctcc/list/'.\App\Libraries\Encryption::encodeId(125))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/ctcc/add/'.\App\Libraries\Encryption::encodeId(125))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>

                                </div>
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="{{ asset('stakeholder-logo/sonalibanklogo.jpg') }}"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Sonali Bank</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">Bank Account Opening </p>
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->sb_account_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('sb-account/list/'.\App\Libraries\Encryption::encodeId(126))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/sb-account/add/'.\App\Libraries\Encryption::encodeId(126))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>

                                </div>
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="https://nbr.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="{{ asset('assets/images/u34.png') }}"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">National Board of Revenue (NBR)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">e-TIN Foreigner </p>
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="https://nbr.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->etin_foreigner_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('e-tin-foreigner/list/'.\App\Libraries\Encryption::encodeId(127))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/e-tin-foreigner/add/'.\App\Libraries\Encryption::encodeId(127))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>

                                </div>
                                <div class="row top-buffer">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 right-buffer">
                                        <a href="https://olm.ccie.gov.bd/" target="_blank"
                                           class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-green txt-style">
                                            <div>
                                                <img class="img pull-left"
                                                     src="https://olm.ccie.gov.bd/images/home_logo.jpg"
                                                     width="30" height="30">
                                            </div>
                                            <p class="pull-left licence_entity">Chief Controller of Imports &
                                                Exports(CCI&E)</p>
                                        </a>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-5  left-buffer right-buffer">
                                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn-green txt-style">
                                            <p class="pull-left licence_name">ERC </p>
                                            <a style="margin: 0px 2px" type="button"
                                               class="pull-right btn btn-default btn-primary btn-sm"
                                               href="https://olm.ccie.gov.bd/" target="_blank">Service Details</a>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xs-1  left-buffer">
                                        @if($licenseApplications->erc_apps != null)
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-warning licence_apply txt-style"
                                               href="{{url('erc/list/'.\App\Libraries\Encryption::encodeId(128))}}"
                                               role="button">Open</a>
                                        @else
                                            <a class="col-md-12 col-lg-12 col-sm-12 col-xs-12 btn btn-success licence_apply txt-style"
                                               href="{{url('process/licence-applications/erc/add/'.\App\Libraries\Encryption::encodeId(128))}}"
                                               role="button">Apply</a>
                                        @endif
                                    </div>

                                </div>

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
