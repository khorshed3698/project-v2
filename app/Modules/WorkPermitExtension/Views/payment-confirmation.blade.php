@extends('layouts.admin')
@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">

                @if(\Session::has('payment_success'))
                    <div class="alert alert-success alert-dismissible">
                        <span style="font-size:28px"> {{ \Session::get("payment_success") }} </span>
                    </div>'
                @endif

                @if(\Session::has('payment_error'))
                    <div class="alert alert-danger alert-dismissible">
                        <span style="font-size:28px"> {{ \Session::get("payment_error") }} </span>
                    </div>'
                @endif

            </div>
        </div>
    </div>
@endsection