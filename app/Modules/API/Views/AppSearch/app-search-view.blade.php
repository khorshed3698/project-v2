@extends('API::layouts.dod-layout')
@section('content')
    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="well">
                    <h3 class="text-center">You are search for <b> {{ $keyword }} </b> </h3>
                    <br>
                    <h1 class="text-center"> <b>404</b> </h1>
                    <h2 class="text-center">Result Not Found</h2>
                </div>
            </div>
        </div>
    </div>

@endsection