@extends('layouts.admin')

@section('content')

    <style>
        .ex-page-content .text-error {
            color: #252932;
            font-size: 98px;
            font-weight: 700;
            line-height: 150px;
        }
        .ex-page-content .text-error i {
            font-size: 90px;
            padding: 0px 10px;
        }
        .text-pink {
            color: #fb6d9d;
        }
        .text-primary {
            color: #5d9cec;
        }.text-info {
             color: #34d3eb;
         }

    </style>

    <div class="col-sm-12">
        <div class="ex-page-content text-center">
            <div class="text-error">
                <span class="text-primary">401</span>
            </div>
            @if(!empty($exception->getMessage()))
                <h3>{{ $exception->getMessage() }}</h3>
            @else
                <h3>You don't have permission to access on this Page.Please Contact with System Admin</h3>
            @endif
            <br>
            <a class="btn btn-info" href="{{ URL::to('dashboard') }}">Return Home</a>
        </div>
    </div>
@endsection