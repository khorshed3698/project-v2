@extends('layouts.admin')
@section('content')

    <div class="row">
        <div class="col-md-12 text-center">
            <h2>Etin Certificate </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <object  width="100%" height="550px" data="{{$certificate}}" type="application/pdf">
                <p>Sorry There is a problem, the PDF cannot be displayed.</p>
            </object>

        </div>
    </div>
@endsection