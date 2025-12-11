@extends('layouts.admin')

@section('content')
    <style>
        .no-border-input{
            border:none;
            background-color: inherit;
            padding-left: 1px;
            padding-right: 1px;
        }
    </style>
    
    <div class="col-lg-12">

        @include('partials.messages')

        <div class="panel panel-primary">
            <div class="panel-heading">

                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>Security Clearance Json</strong></strong></h5>
                </div>

                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <form>
                        <?php
                        dump($result);
                        ?>
                        
                    </form>
                    <div class="col-sm-12">
                        <br>
                        <a href="{{ url('security-clearance/list') }}" class="btn btn-primary"> Back</a>
                    </div>

                </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->

@endsection

