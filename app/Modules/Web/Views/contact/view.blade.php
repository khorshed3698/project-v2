@extends('layouts.admin')

@section('page_heading', trans('messages.rollback'))

@section('content')
    <div class="panel panel-primary">
        <div class="panel-heading">
            <b>{{ $card_title }}</b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <input type="hidden" name="id" value="{{ \App\Libraries\Encryption::encodeId($data->id) }}">
            <div class="row">
                <div class="form-group col-md-12">
                    <div class="row">
                        <div class="col-md-9 col-lg-10">
                            <table class=" text-left" style="width:100% !important; line-height: 2rem;">
                                <tbody>
                                    <tr>
                                        <td style="width:40%; font-weight:bold">Name</td>
                                        <td style="width:60%">: {{$data->name}} </td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%; font-weight:bold">Email</td>
                                        <td style="width:60%">: {{$data->email}} </td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%; font-weight:bold">Mobile No</td>
                                        <td style="width:60%">: {{$data->phone}} </td>
                                    </tr>
                                    <tr>
                                        <td style="width:40%; font-weight:bold">Details</td>
                                        <td style="width:60%">: {{$data->details}} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                      
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="float-left">
                    <a href="{{route($list_route)}}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', ['type' => 'button', 'class' => 'btn btn-default']) !!}
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection

