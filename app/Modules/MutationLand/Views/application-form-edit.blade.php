<?php
$accessMode = ACL::getAccsessRight('MutationLand');
if (!ACL::isAllowed($accessMode, '-E')) {
    die('You have no access right! Please contact with system admin if you have any query.[ML-1201]');
}
?>
<style>
    .form-group {
        margin-bottom: 5px;
    }

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .photo_size{
        max-height: 150px;
        max-width: 200px;
        float: right;
    }

    .padding-l-r{
        padding: 0px 20px;
    }

    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

    }
</style>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box" id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h5>Mutation Land</h5>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'mutation-land/store','method' => 'post', 'class' =>'form-horizontal', 'id' => 'NewApplication',
                            'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($app_info->id) }}"
                               id="app_id"/>

                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Company Information</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                            {!! Form::label('company_name_bn','Company Name (Bangla)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('company_name_bn', $app_data->company_name_bn,['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('company_name_en') ? 'has-error': ''}}">
                                            {!! Form::label('company_name_en','Company Name (English)',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('company_name_en', $app_data->company_name_en, ['class' =>'form-control input-md required']) !!}
                                                {!! $errors->first('company_name_en','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('owner_type') ? 'has-error': ''}}">
                                            {!! Form::label('owner_type','Owner Type',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('owner_type', [], '', ['class' =>'form-control input-md required', 'id'=> 'owner_type']) !!}
                                                {!! $errors->first('owner_type','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Authorization</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('full_name') ? 'has-error': ''}}">
                                            {!! Form::label('full_name', 'Full Name',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('full_name', $app_data->full_name, ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('full_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('designation') ? 'has-error': ''}}">
                                            {!! Form::label('designation', 'Designation',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('designation', $app_data->designation, ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('mobile_no') ? 'has-error': ''}}">
                                            {!! Form::label('mobile_no','Mobile Number',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('mobile_no', $app_data->mobile_no, ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('mobile_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('email') ? 'has-error': ''}}">
                                            {!! Form::label('email','Email',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('email', $app_data->email, ['class' =>'form-control input-md email required']) !!}
                                                {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-sm-6 col-md-7">
                                                <h4 class="required-star" id="profile_image">Applicant Photo</h4>
                                                <span style="font-size: 9px; color: grey">
                                                    [File Format: *.jpg / *.png, Dimension: 300x300 pixel]
                                                </span>
                                                <br><br>
                                                <label id="profile_image_div" class="btn btn-primary btn-file" {{ $errors->has('applicant_photo') ? 'has-error' : '' }}>
                                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                    Browse
                                                    <input type="file" style="display: none;"
                                                           class="form-control input-sm {{!empty($app_data->applicant_photo) ? '' : 'required'}}"
                                                           name="applicant_photo"
                                                           id="applicant_photo"
                                                           onchange="imageUploadWithCroppingAndDetect(this, 'applicant_photo_preview', 'applicant_photo_base64')"
                                                           size="300x300"/>
                                                </label>

{{--                                                <label class="btn btn-primary" id="captureProfilePicture" data-profile-capture="yes">--}}
{{--                                                    <i class="fa fa-picture-o" aria-hidden="true"></i>--}}
{{--                                                    Camera--}}
{{--                                                </label>--}}
                                            </div>
                                            <div class="col-sm-6 col-md-5 pull-right">
                                                <label class="center-block" for="applicant_photo">
                                                    <figure>
                                                        <img src="{{ (!empty($app_data->applicant_photo)? url('users/upload/'.$app_data->applicant_photo) : url('assets/images/no-image.png')) }}"
                                                             class="img-responsive img-thumbnail photo_size"
                                                             id="applicant_photo_preview"/>
                                                    </figure>
                                                    <input type="hidden" id="applicant_photo_base64"
                                                           name="applicant_photo_base64"/>
                                                    @if(!empty($app_data->applicant_photo))
                                                        <input type="hidden" id="applicant_photo_hidden" name="applicant_photo"
                                                               value="{{$app_data->applicant_photo}}"/>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>

                                        {{--                                        <div class="col-md-6 {{$errors->has('photo') ? 'has-error': ''}}">--}}
                                        {{--                                            {!! Form::label('photo','Photo',['class'=>'col-md-5 text-left required-star']) !!}--}}
                                        {{--                                            <div class="col-md-7">--}}
                                        {{--                                                {!! Form::file('photo', ['class'=>'form-control input-md required', 'id' => 'photo','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1,'img'), imagePreview(this)"]) !!}--}}
                                        {{--                                                <span id="span_photo"--}}
                                        {{--                                                      style="font-size: 12px; font-weight: bold;color:#993333">[N.B. Supported file extension is png/jpg/jpeg.Max size less than 150KB]</span>--}}
                                        {{--                                                <div id="preview_photo">--}}
                                        {{--                                                    {!! Form::hidden('validate_field_photo','', ['class'=>'form-control input-md', 'id' => 'validate_field_photo']) !!}--}}
                                        {{--                                                </div>--}}
                                        {{--                                                <div class="col-md-5" style="position:relative;">--}}
                                        {{--                                                    <img id="photo_viewer_photo"--}}
                                        {{--                                                         style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"--}}
                                        {{--                                                         src="{{ ($app_data->applicant_photo != '' && file_exists('users/upload/'.$app_data->applicant_photo))  ? url('users/upload/'.$app_data->applicant_photo) : url('assets/images/no-image.png') }}"--}}
                                        {{--                                                         alt="photo" value="{{ $app_data->applicant_photo != '' ? $app_data->applicant_photo : ''}}">--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Service fee payment</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_name', $app_data->sfp_contact_name,
                                                ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('sfp_contact_email', $app_data->sfp_contact_email, ['class' =>
                                                'form-control input-md email required']) !!}
                                                {!! $errors->first('sfp_contact_email','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_phone', $app_data->sfp_contact_phone, ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_address', $app_data->sfp_contact_address, ['class' =>
                                                'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_address','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_pay_amount') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_pay_amount','Pay amount',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_pay_amount', $payment_config->amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_vat_on_pay_amount') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_vat_on_pay_amount','VAT on pay amount',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_vat_on_pay_amount', number_format($payment_config->vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                                {!! $errors->first('sfp_vat_on_pay_amount','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_total_amount','Total amount',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_total_amount', number_format($payment_config->amount + $payment_config->vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_status','Payment status',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                <span class="label label-warning">Not Paid</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 padding-l-r">
                                            <div class="alert alert-danger" role="alert">
                                                Vat/ tax and service charge is an approximate amount, it may vary
                                                based
                                                on the
                                                Sonali Bank system.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 padding-l-r">
                                            <div class="checkbox">
                                                <label>
                                                    {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms',
                                                    'class'=>'required')) !!}
                                                    All the details and information provided in this form are true and
                                                    complete.
                                                    I am aware that any untrue/incomplete statement may result in delay in
                                                    BIN
                                                    issuance and I may be subjected to full penal action under the Value
                                                    Added
                                                    Tax and Supplementary Duty Act, 2012 or any other applicable Act
                                                    Prevailing
                                                    at present.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="pull-left">
                                    <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn">Save as Draft
                                    </button>
                                </div>

                            </div>
                            <div class="col-md-6 col-xs-12 button_last">
                                <div class="pull-right" style="padding-left: 1em;">
                                    <button type="submit" id="submitForm" style="cursor: pointer;"
                                            class="btn btn-success btn-md" value="Submit" name="actionBtn">Payment &amp;
                                        Submit
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div> {{--row--}}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.image-resize.image-upload')
{{--@include('partials.profile-capture')--}}
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $("#NewApplication").validate();
        $('#nid_number').on('blur', function (e) {
            var nid = $('#nid_number').val().length
            if (nid == 10 || nid == 13 || nid == 17) {
                $('#nid_number').removeClass('error')
            } else {
                $('#nid_number').addClass('error')
            }
        })
    });

    function imagePreview(input) {
        if (input.files && input.files[0]) {
            var calling_id = input.id;
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#photo_viewer_" + calling_id).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    var yyyy = today.getFullYear();
    $('.datepicker').datetimepicker({
        viewMode: 'days',
        format: 'DD-MMM-YYYY',
        maxDate: 'now',
        minDate: '01/01/' + (yyyy - 100),
        ignoreReadonly: true
    });

    $(document).ready(function () {
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/mutation-land/get-refresh-token';

            $('#owner_type').keydown();
        });

        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: "OSS_BIDA"
            },
            {
                key: "agent-id",
                value: "{{ config('stakeholder.agent_id') }}"
            },
        ]

        $('#owner_type').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$ml_service_url}}/info/land-owners";
            let selected_value = "{{!empty($app_data->owner_type) ? $app_data->owner_type : ''}}"; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "owner_type";//dynamic id for callback
            let element_name = "owner_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        function callbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
            var option = '<option value="">Select One</option>';
            if (response.responseCode === 200) {
                $.each(response.data, function (key, row) {
                    let id = row[element_id] + '@' + row[element_name];
                    let value = row[element_name];

                    if (selected_value.split('@')[0] == id.split('@')[0]) {
                        option += '<option selected ="selected" value="' + id + '">' + value + '</option>';
                    } else {
                        option += '<option value="' + id + '">' + value + '</option>';
                    }
                });
            }

            $("#" + calling_id).html(option)
            if (selected_value !== '') {
                $("#" + calling_id).trigger('change')
            }
            $("#" + calling_id).next().hide()
        }

    });

</script>

