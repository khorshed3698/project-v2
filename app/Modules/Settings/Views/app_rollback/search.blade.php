@extends('layouts.admin')

@section('content')

    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'ARB')) {
        die('You have no access right! For more information please contact system admin.');
    }
    ?>

    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><i class="fa fa-list"></i> <strong>Application Search</strong></h5>
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! Form::open(['url' => 'settings/app-rollback-open', 'method' => 'POST', 'id' => 'appRollback'])!!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3"></div>
                        <div class="col-md-5">
                            <label for="">Search Application: </label>
                            {!! Form::text('tracking_no', '', ['class' => 'form-control search_text', 'placeholder'=>'Tracking Number']) !!}
                        </div>
                        <div class="col-md-1">
                            <label for="">&nbsp;</label>
                            <button type="submit" id="search_process" class="btn btn-primary">Search</button>
                        </div>
                        <div class="col-md-3">
                        </div>
                    </div>
                </div>
                {!! Form::close()!!}
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div>
@endsection
