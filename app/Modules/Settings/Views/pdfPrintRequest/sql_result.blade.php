@extends('layouts.admin')

@section('page_heading',trans('messages.pdf-print-requests'))

@section('content')
    <style>
        .no-border-input{
            border:none;
            background-color: inherit;
            padding-left: 1px;
            padding-right: 1px;
        }
    </style>
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'PPR-ESQ')) die('no access right!');
    ?>
    <div class="col-lg-12">

        @include('partials.messages')

        <div class="panel panel-primary">
            <div class="panel-heading">

                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>{!!trans('messages.pdf-print-requests')!!}</strong></strong></h5>
                </div>

                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <form>
                        <?php
                        dump($result);?>
                        <div class="col-sm-offset-5 col-sm-7">
                            <button type="button" class="btn btn-warning btn-circle btn-lg" style="padding:0px">OR</button>
                        </div>
                        <div class="col-sm-12">
                        <pre style="margin-top: 10px;">
                            <?php
                            print_r($result);?>
                        </pre>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-offset-5 col-sm-7">
                                <button type="button" class="btn btn-warning btn-circle btn-lg" style="padding:0px">JSON
                                </button>
                            </div>
                            <?php
                                echo json_encode($result,JSON_PRETTY_PRINT);
                            ?>
                        </div>
                    </form>
                    <div class="col-sm-12">
                        <br>
                        <a href="{{ url('settings/pdf-print-requests') }}" class="btn btn-primary"> Back</a>
                    </div>

                </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->

@endsection

