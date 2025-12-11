@extends('web.layouts.app')

@push('customStyles')
    <!-- Inner Page -->
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/pages/inner-page.css')}}">

    <style>
        .select2-container--default .select2-selection--single {
            height: 40px !important;
        }
    </style>
@endpush

@section('content')

<section class="inner-page-banner" style="background-image: url({{asset('assets/landingV2/assets/frontend/images/pages/inner-page-banner-image.jpg')}}">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Track Your Service</li>
            </ol>
        </nav>

        <div class="page-title">
            <h2>Service Tracking</h2>
        </div>
    </div>
</section>


<section class="inner-page-content bida-section">
    <div class="container">
        <div class="srv-tracking-content">
            <div class="srv-tracking-search-form">
                {!! Form::open([
                    'method' => 'post',
                    'id' => 'form_id',
                    'enctype' => 'multipart/form-data',
                    'role' => 'form',
                ]) !!}
                    <div class="row row-gap">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="bida_srv_name">Service Name</label>
                                {!! Form::select('process_type', $processType, null, 
                                ['class' => 'form-control required select2', 
                                'onchange'=>"getDataById('bida_srv_name', this.value, 'bida_srv_company_name')",
                                'id'=>'bida_srv_name', 'placeholder' => 'Select Service']) !!}
                                {!! $errors->first('process_type','<span class="help-block">:message</span>') !!}
                            </div>
                            <div id="bida_srv_div" style="height: 20px;">
                                
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="bida_srv_company_name">Company Name</label>
                                {!! Form::select('company_id', [], null, ['class' => 'form-control required select2', 'id'=>'bida_srv_company_name', 'placeholder' => 'Select Company']) !!}
                                {!! $errors->first('company_id','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Tracking Number</label>
                                <input type="text" name="tracking_number" class="form-control required" id="tracking_number" placeholder="Enter">
                                {!! $errors->first('tracking_number','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="srv-tracking-btn-group">
                                <button class="btn search-btn gradient-btn" type="submit" id="submit">Search</button>
                                <button class="btn bida-btn-outline" type="reset" id="reset">Reset</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>

            <div id="result" style="width: 100%;"></div>

        </div>
    </div>
</section>

@endsection

{{-- Page Style & Script--}}
@push('customScripts')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
    <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
    <script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
    <script src="{{ asset("assets/scripts/sweetalert2.all.min.js") }}" type="text/javascript"></script>
    <script>
        function getDataById(current_div_id, current_value, target_div) {
            if (current_value !== '') {
                // $("#" + current_div_id).after('<span class="loading_data">Loading...</span>');
                $('#bida_srv_div').html('<span class="loading_data">Loading...</span>');
                $.ajax({
                    type: "POST",
                    url: "{{ route('getCompanyByProcessType')}}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        value: current_value
                    },
                    success: function (response) {                        
                        var option = '<option value="">Select One</option>';
                        if (response) {
                            $.each(response, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#" + target_div).html(option);
                        // $("#" + current_div_id).next().hide('slow');
                        $('#bida_srv_div').html('');
                    }
                });
            } else {
                $("#" + target_div).html('<option value="">Select One</option>');
            }
        }

        $(document).on('click', '#reset', function() {
            $('.select2').val(null).trigger('change');
            $('.required-text').show(); // Show all required-text elements
        });

        $("#form_id").validate({
            errorPlacement: function () {
                return true;
            }
        });

        $(document).ready(function () {

            $(".select2").select2({
                //maximumSelectionLength: 1
            });

            // tracking_number
            // tracking_number = $('#tracking_number').val();

            $('#form_id').on('submit', function(event) {
                event.preventDefault();

                // Check if the form is valid
                var isValid = true;

                $('.required').each(function() {
                    if ($(this).val().trim() === '') {
                        isValid = false;
                        $(this).closest('.form-group').addClass('has-error');
                    } else {
                        $(this).closest('.form-group').removeClass('has-error');
                    }
                });

                if (!isValid) {
                    swal("Error", "Please fill in all required fields", "error");
                    return false;
                }
        

                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // if (tracking_number == '') {
                //     swal("Error", "Please input tracking number", "error");
                //     return false;
                // }

                $('#result').html('<div class="text-center">Loading...</div>');

                $.ajax({
                    url: '{{ route("searchServiceInfo") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#form_id').serialize(),
                    success: function(response) {
                        if (!response.data) {
                            swal("Error", response.message, "error");
                        }
                        $('#result').html(response.data);
                        // swal("Success", response.message, "success");
                        $('#form_id')[0].reset();
                        $('.select2').val(null).trigger('change');
                        grecaptcha.reset();
                        $('.required-text').show(); // Show all required-text elements
                    },
                    error: function(xhr, status, error) {
                        // Handle error if needed
                    }
                });
            });
        });
    </script>
@endpush

