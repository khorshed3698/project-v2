<?php
$accessMode = ACL::getAccsessRight('RajukLUCGeneral');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>
    .wizard > .content,
    .wizard,
    .tabcontrol {
        overflow: visible;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
    }

    .wizard > .steps .number {
        font-size: 1.2em;
    }

    .intl-tel-input .country-list {
        z-index: 5;
    }

    .form-group {
        margin-bottom: 5px;
    }

    label {
        float: left !important;
    }

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .adhoc {
        margin-left: 15px;
    }

    .adhoc button {
        margin-top: 15px;
    }

    table thead {
        background-color: #ddd;
    }

    .none-pointer {
        pointer-events: none;
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
                        <h5><strong>Land Use Clearance Application (General)</strong></h5>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'rajuk-luc-general/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewApplication',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                        <div class="modal fade" id="modalContactForm" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title w-150 font-weight-bold">Occupancy Type</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-md-12" id="sub-occupancy-list">

                                        </div>
                                        <div class="modal-footer d-flex">
                                            <button class="btn btn-primary" id="additem" type="button">Add/Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('land_use','Land Use :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('land_use', [],'',['class' => 'form-control input-md ','id'=>'land_use']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('occupancy_type',"Occupancy Type's :",['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <a href="" class="btn btn-sm btn-primary" data-toggle="modal"
                                                       data-target="#modalContactForm">Add Occupancy</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('applicant_name_en',"Applicant's Name (English) :",['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_name_en','',['class' => 'form-control input-md ','id'=>'applicant_name_en']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('applicant_name_bn',"Applicant's Name (Bangla):",['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_name_bn','',['class' => 'form-control input-md ','id'=>'applicant_name_bn']) !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('land_owner_email',"Land Owner Email Address :",['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('land_owner_email','',['class' => 'form-control input-md email','id'=>'land_owner_email']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('land_owner_mobile',"Land Owner Mobile Number:",['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('land_owner_mobile','',['class' => 'form-control input-md bd_mobile','id'=>'land_owner_mobile']) !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('present_address',"Present Address (Bangla) :",['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('present_address','',['class' => 'form-control input-md ','id'=>'present_address']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('nid_passport',"NID / Passport No.:",['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('nid_passport','',['class' => 'form-control input-md ','id'=>'nid_passport']) !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('holding_no',"Holding Number.:",['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('holding_no','',['class' => 'form-control input-md ','id'=>'holding_no']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('district_name',"District Name :",['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('district_name', [],'',['class' => 'form-control input-md search-box','id'=>'district_name']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('thana_name',"Thana Name. :",['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('thana_name',[],'',['class' => 'form-control input-md search-box','id'=>'thana_name']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>Service & Government Fee Payment</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left
                                                required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(),
                                                    ['class' => 'form-control input-md required']) !!}
                                                    {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>')
                                                    !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left
                                                required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' =>
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
                                                {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left
                                                required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' =>
                                                    'form-control input-md required']) !!}
                                                    {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>')
                                                    !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left
                                                required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_contact_address', Auth::user()->road_no .
                                                    (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' =>
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


                                    {{--Vat/ tax and service charge is an approximate amount--}}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger" role="alert">
                                                    Vat/ tax and service charge is an approximate amount, it may vary
                                                    based
                                                    on the
                                                    Sonali Bank system.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="panel-heading" style="padding-bottom: 4px;">
                                    <strong>DECLARATION</strong>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered table-striped">
                                        <thead class="alert alert-info">
                                        <tr>
                                            <th colspan="3" style="font-size: 15px">Authorized person of the
                                                organization
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                {!! Form::label('auth_name','Full name:', ['class'=>'required-star'])
                                                !!}
                                                {!! Form::text('auth_name',
                                                \App\Libraries\CommonFunction::getUserFullName(), ['class' =>
                                                'form-control input-md required', 'readonly']) !!}
                                                {!! $errors->first('auth_name','<span
                                                    class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::label('auth_email','Email:', ['class'=>'required-star']) !!}
                                                {!! Form::email('auth_email', Auth::user()->user_email, ['class' =>
                                                'form-control required input-md email', 'readonly']) !!}
                                                {!! $errors->first('auth_email','<span
                                                    class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::label('auth_cell_number','Cell number:',
                                                ['class'=>'required-star']) !!}<br>
                                                {!! Form::text('auth_cell_number', Auth::user()->user_phone, ['class' =>
                                                'form-control input-md required phone_or_mobile', 'readonly']) !!}
                                                {!! $errors->first('auth_cell_number','<span
                                                    class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Date : </strong><?php echo date('F d,Y')?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="form-group">
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

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>

    $(document).ready(function () {
        $("#NewApplication").validate();

        var popupWindow = null;

        {{----end step js---}}



        $('#nid_number').on('blur', function (e) {
            var nid = $('#nid_number').val().length
            if (nid == 10 || nid == 13 || nid == 17) {
                $('#nid_number').removeClass('error')
            } else {
                $('#nid_number').addClass('error')
                $('#nid_number').val('')
            }
        })

        $('.onlyNumber').on('keydown', function (e) {
            //period decimal
            if ((e.which >= 48 && e.which <= 57)
                //numpad decimal
                || (e.which >= 96 && e.which <= 105)
                // Allow: backspace, delete, tab, escape, enter and .
                || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                // Allow: Ctrl+A
                || (e.keyCode == 65 && e.ctrlKey === true)
                // Allow: Ctrl+C
                || (e.keyCode == 67 && e.ctrlKey === true)
                // Allow: Ctrl+V
                || (e.keyCode == 86 && e.ctrlKey === true)
                // Allow: Ctrl+X
                || (e.keyCode == 88 && e.ctrlKey === true)
                // Allow: home, end, left, right
                || (e.keyCode >= 35 && e.keyCode <= 39)) {

                var $this = $(this);
                setTimeout(function () {
                    $this.val($this.val().replace(/[^0-9.]/g, ''));
                }, 4);

                var thisVal = $(this).val();
                if (thisVal.indexOf(".") != -1 && e.key == '.') {
                    return false;
                }
                $(this).removeClass('error');
                return true;
            } else {
                $(this).addClass('error');
                return false;
            }
        }).on('paste', function (e) {
            var $this = $(this);
            setTimeout(function () {
                $this.val($this.val().replace(/[^0-9]/g, ''));
            }, 5);
        });

    });

    $(document).ready(function () {
        /*api header for micro service*/
        const apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: 'OSS_BIDA'
            },
            {
                key: "agent-id",
                value: '{{$agentId}}'
            },
        ];
        const apiBaseUrl = "{{$service_url}}";
        $('body').on('click', '.reCallApi', function () {
            const id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}"
            tokenUrl = '/rajuk-luc-general/get-refresh-token'
            $('#land_use').keydown()
            $('#district_name').keydown()
        });

        $('#land_use').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = apiBaseUrl + "/info/occupancy-type";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "id"; //dynamic id for callback
            let element_name = "type_en"; //dynamic name for callback
            let data = '';
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#land_use').on('change', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            let occupancyType = $(this).val();

            if (occupancyType != '') {
                var occupancyID = occupancyType.split("@")[0].toUpperCase();
            } else {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = apiBaseUrl + "/info/sub-occupancy-type/"+occupancyID;
            let dependent_section_id = "sub-occupancy-list"; // for callback
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "sub_type"; //dynamic id for callback
            let element_name = "sub_en"; //dynamic name for callback
            let data = '';
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponseCheckBox, arrays);

        })
        $('#district_name').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = apiBaseUrl + "/info/district";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "district_id"; //dynamic id for callback
            let element_name = "district_name"; //dynamic name for callback
            let data = '';
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#district_name').on('change', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            let districtName = $(this).val();

            if (districtName != '') {
                var districtId = districtName.split("@")[0].toUpperCase();
            } else {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = apiBaseUrl + "/info/thana/" + districtId;
            let dependent_section_id = "thana_name"; // for callback
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "police_station_id"; //dynamic id for callback
            let element_name = "police_station_name_en"; //dynamic name for callback
            let data = '';
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

        })
    });

    /*document upload start*/
    function uploadDocument(targets, id, vField, isRequired) {
        var check = document.getElementById(id).getAttribute("flag")
        if (check == "img") {
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop();
            if ((fileSize >= 149999) || (extension !== "jpg" && extension !== "jpeg" && extension !== "png")) {
                alert('File size cannot be over 150 KB and file extension should be only jpg, jpeg and png');
                document.getElementById(id).value = "";
                return false;
            }
        } else {
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop();
            if ((fileSize >= 1000000) || (extension !== "pdf")) {
                alert('File size cannot be over 1 MB and file extension should be only pdf');
                document.getElementById(id).value = "";
                return false;
            }

        }
        var inputFile = $("#" + id).val();
        if (inputFile == '') {
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if ($('#label_' + id).length)
                $('#label_' + id).remove();
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{URL::to('/dcci-cos/upload-document')}}";
            $("#" + targets).html('Uploading....');
            var file_data = $("#" + id).prop('files')[0];
            var form_data = new FormData();
            form_data.append('selected_file', id);
            form_data.append('isRequired', isRequired);
            form_data.append('validateFieldName', vField);
            form_data.append('_token', "{{ csrf_token() }}");
            form_data.append(id, file_data);
            $.ajax({
                target: '#' + targets,
                url: action,
                dataType: 'text', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = id;
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" class="filedelete" docid="' + id + '" ><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    if (response.includes("Error") == false) {
                        $('#' + id).removeClass('required');
                        $('#span_' + id).hide();
                    }

                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field == '') {
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            console.log(err)
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

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

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_name];

                let value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        let searchboxStatus = $("#" + calling_id).hasClass('search-box');

        $("#" + calling_id).html(option)
        if(searchboxStatus){
            $("#" + calling_id).select2();
        }
        $("#" + calling_id).parent().find('.loading_data').hide();
        $("#" + calling_id).trigger('change')

    }

    function callbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                // console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        let searchboxStatus = $("#" + dependent_section_id).hasClass('search-box');

        $("#" + dependent_section_id).html(option);
        if(searchboxStatus){
            $("#" + dependent_section_id).select2();
        }
        $("#" + calling_id).parent().find('.loading_data').hide();
    }

    function callbackResponseCheckBox(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '';
        if (response.responseCode === 200) {
            var i = 0;
            $.each(response.data, function (key, row) {
                console.log(response.data);
                var id = row['id']+'@'+row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<label for="land_use_sub_occupancy_' + i + '"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="land_use_sub_occupancy" name="land_use_sub_occupancy[' + i + ']" id="land_use_sub_occupancy_' + i + '" value="' + id + '">  <b>' +row[element_id]+'</b> '+ value + '  </label> ';
                } else {
                    option += '<label for="land_use_sub_occupancy_' + i + '"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="land_use_sub_occupancy" name="land_use_sub_occupancy[' + i + ']"  id="land_use_sub_occupancy_' + i + '" value="' + id + '">  <b>' +row[element_id]+'</b> '+ value + '  </label>';
                }
                i++;
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        $("#" + calling_id).next().hide();
    }

    //Doc and image upload section
    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = ''
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
            $('#span_' + abc).show()
            $('#photo_viewer_' + abc).attr('src', '{{(url('assets/images/no-image.png'))}}')
        } else {
            return false;
        }
    });


</script>