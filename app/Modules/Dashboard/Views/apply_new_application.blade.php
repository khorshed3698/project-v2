@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
    <style>
        .searchInput, .select2 {
            border: 2px solid #337AB7;
            border-radius: 7px 0 0 7px;
            height: 50px !important;
        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 0 solid rgba(0,0,0,0);
            border-radius: 7px 0 0 7px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px;
            font-size: 16px;
        }

        .select2-selection__rendered {
            height: 50px;
        }

        .searchBtn {
            height: 50px;
            border-radius: 7px;
            width: 140px;
            font-size: 16px;
            line-height: 35px;
            text-shadow: 0 1px 0 rgba(0,0,0,0.1);
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="text-center" id="searchForm">
            <div class="col-md-8 col-md-offset-2" style="margin-top: 70px;color:#337AB7;">
                <h1 style="font-weight: bold;">Select a service</h1>
                <div class="input-group" style="margin-top: 30px;margin-bottom: 8px;">
                    <select name="process_type_id"
                            class="form-control required searchInput"
                            id="process_type_id"
                            data-placeholder="Select a service"
                            style="width: 100%;" required
                            onchange="serviceApply(this.value)"
                    >
                        <option value=""></option>
                        @foreach($services as $service)
                            <option value="{{ url('process/'.$service->form_url.'/add/'.Encryption::encodeId($service->id)) }}">
                                {{ $service->service_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="input-group-btn">
                        <a class="btn btn-primary searchBtn" type="button" role="button" id="searchBtn">Apply</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('navigation.footer')
@endsection

@section('footer-script')
    <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
    <script>
        $(function () {
            $("#process_type_id").select2();
        });

        function serviceApply($url) {
            $("#searchBtn").attr('href', $url);
        }
    </script>
@endsection