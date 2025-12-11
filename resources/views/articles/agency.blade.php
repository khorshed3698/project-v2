@extends('layouts.front')

@section('style')
    <style>
        .panel-title {
            text-align: left;
        }
        .panel-body {
            padding: 15px;
        }
    </style>

@endsection

@section('content')
    @include('articles.top-navbar')

    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="box-div">
                <h3>{{ $title }}</h3>

                <div class="panel panel-default">
                    <table class="table table-hover table-bordered table-striped" aria-label="Detailed Report Data Table">
                        <thead>
                            <tr  class="d-none">
                                <th aria-hidden="true" scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($regulatory_agencies as $key => $regulatory_agency)
                            <tr>
                                <td>
                                    <a class="down_up_arrow" style="cursor:pointer;" data-toggle="collapse" data-target="#agency_<?php echo $key; ?>">
                                        {{ $regulatory_agency->name }}
                                    </a>

                                    <div style="margin-top: 10px;" id="agency_<?php echo $key; ?>" class="collapse">
                                        @if(!empty($regulatory_agency->description))
                                            {!!  $regulatory_agency->description !!}
                                        @endif

                                        @if((!empty($regulatory_agency->contact_name)) || (!empty($regulatory_agency->designation)) || (!empty($regulatory_agency->mobile)) || (!empty($regulatory_agency->phone)) || (!empty($regulatory_agency->email)))
                                            <p class="text-info"><strong>Contact Information :</strong></p>

                                            <ul class="list-unstyled">
                                                @if(!empty($regulatory_agency->contact_name))
                                                    <li>Contact Name: {{ ucfirst($regulatory_agency->contact_name) }}</li>
                                                @endif
                                                @if(!empty($regulatory_agency->designation))
                                                    <li>Designation: {{ $regulatory_agency->designation }}</li>
                                                @endif
                                                @if(!empty($regulatory_agency->mobile))
                                                    <li>Mobile: {{ $regulatory_agency->mobile }}</li>
                                                @endif
                                                @if(!empty($regulatory_agency->phone))
                                                    <li>Phone: {{ $regulatory_agency->phone}}</li>
                                                @endif
                                                @if(!empty($regulatory_agency->email))
                                                    <li>Email: {{ $regulatory_agency->email}}</li>
                                                @endif
                                            </ul>
                                        @endif
                                        <p style="font-size: 11px; margin: 5px 0 0 0;">
                                            <i>Last updated: {{ Carbon\Carbon::parse($regulatory_agency->updated_at)->diffForHumans() }}</i>
                                        </p>

                                        @include('articles.agency_details')

                                        @if (!empty($regulatory_agency->url))
                                            <a style="display: block; margin-top: 5px;" href="{{ $regulatory_agency->url }}" target="_blank" rel="noopener">Learn more</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 hidden-sm hidden-xs">
            @include('public_home.login_panel')
        </div>
    </div>
@endsection

@section('footer-script')
    <script>
        $('.panel-collapse').on('shown.bs.collapse', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $panel = $(this).closest('.panel');
            $('html,body').animate({
                scrollTop: $panel.offset().top
            }, 'slow');
        });
    </script>
@endsection