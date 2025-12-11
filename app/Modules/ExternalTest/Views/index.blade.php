
@extends('layouts.admin')

@section('content')
    @section('content')
        <section class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h5>
                        <strong>{{ trans('Application List') }}</strong>
                    </h5>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    @include('partials.messages')
                    <div class="table-responsive" style="overflow:visible;">
                        <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" aria-label="Detailed External Test Data Table">
                            <thead>
                            <tr>
                                <th class="text-center">UID</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{$data->uid}}</td>
                                    <td> <a href="show/<?php echo Encryption::encodeId($data->id)?>" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> view</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div> <!-- /.table-responsive -->
                </div> <!-- /.panel-body -->
            </div><!-- /.panel -->

        </section>

    @endsection
@endsection

@section('footer-script')

    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

@endsection