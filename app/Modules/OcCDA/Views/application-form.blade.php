<?php
$accessMode = ACL::getAccsessRight('OcCDA');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>

    .wizard > .content, .wizard, .tabcontrol {
        overflow: visible;
    }

    .form-group {
        margin-bottom: 5px;
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

    textarea {
        height: 60px !important;
    }

    .col-md-7 {
        margin-bottom: 10px;
    }

    label {
        float: left !important;
    }

    .col-md-5 {
        position: relative;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 8px;
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


    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

        .siteDivLR {
            margin-top: 12px;
            margin-right: 8px;
        }

        .siteDivFB {
            margin-top: 12px;
            margin-right: 8px;
        }

        .pull-right {
            float: none !important;
        }

        .pull-left {
            float: none !important;
        }

        .text-right {
            text-align: left !important;
        }
    }

    @media screen and (min-width: 350px) {

        .siteDivLR {
            margin-top: 12px;
        }

        .siteDivFB {
            margin-top: 12px;
        }


    }
</style>

<div class="col-md-12">
    @include('message.message')
</div>
<div class="col-md-12">
    <div class="panel panel-primary" id="inputForm">
        <div class="panel-heading">
            <h5><strong>বসবাস বা ব্যবহার সনদপত্রের আবেদন ফরম (Occupancy Certificate) - সম্পূর্ন সমাপ্ত</strong></h5>
        </div>

        {!! Form::open(array('url' => 'cda-oc/store','method' => 'post', 'class' => 'form-horizontal', 'id' => 'OcCDAForm',
        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
        <input type="hidden" name="selected_file" id="selected_file"/>
        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
        <input type="hidden" name="isRequired" id="isRequired"/>
        <h3 class="text-center stepHeader">Details Information</h3>
        <fieldset>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('occupancy_type','অকুপেন্সির ধরন',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::select('occupancy_type', [], '', ['placeholder' => 'Select One','class' => 'form-control', 'id'=>'occupancy_type']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check col-md-12 " id="occupancy_sub_type">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <label>
                                {!! Form::checkbox('is_residential_area',1,false, array('id'=>'is_residential_area', 'class'=>'required')) !!}
                                অনুমোদিত আবাসিক এলাকা কিনা?
                            </label>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6 {{$errors->has('app_date') ? 'has-error': ''}}">
                            {!! Form::label('app_date','আবেদনের তারিখ',['class'=>'col-md-5']) !!}
                            <div class=" col-md-7">
                                <div class="datepicker input-group date"
                                     data-date-format="dd-mm-yyyy">
                                    {!! Form::text('ceo_dob', '', ['class'=>'form-control input-md', 'id' => 'app_date', 'placeholder'=>'Pick from datepicker']) !!}
                                    <span class="input-group-addon"><span
                                                class="fa fa-calendar"></span></span>
                                </div>
                                {!! $errors->first('app_date','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('construction_approval_no','নির্মাণ অনুমোদন নাম্বার',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('construction_approval_no', null,['class' => 'form-control onlyNumber engOnly input-md','id'=>'construction_approval_no']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6 {{$errors->has('work_completed_date') ? 'has-error': ''}}">
                            {!! Form::label('work_completed_date','কাজ সমাপ্তের তারিখ',['class'=>'col-md-5']) !!}
                            <div class=" col-md-7">
                                <div class="datepicker input-group date"
                                     data-date-format="dd-mm-yyyy">
                                    {!! Form::text('work_completed_date', '', ['class'=>'form-control input-md', 'id' => 'work_completed_date', 'placeholder'=>'Pick from datepicker']) !!}
                                    <span class="input-group-addon"><span
                                                class="fa fa-calendar"></span></span>
                                </div>
                                {!! $errors->first('work_completed_date','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('city_corporation_municipality','সিটি কর্পোরেশন/পৌরসভা/গ্রাম/মহল্লা',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::select('city_corporation_municipality', $city_corporation, 0, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('b_s_no','বি. এস. নং',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('b_s_no', null,['class' => 'form-control onlyNumber engOnly input-md','id'=>'b_s_no']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('r_s_no','আর. এস. নং',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('r_s_no', null,['class' => 'form-control onlyNumber engOnly input-md','id'=>'r_s_no']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('thana_name','থানার নাম',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::select('thana_name', [], '', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('mouza_name','মৌজার নাম',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::select('mouza_name', [], '', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('block_no','ব্লক নং',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::select('block_no', [], '', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('seat_no','সিট নং',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::select('seat_no', [], '', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('ward_no','ওয়ার্ড নং',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::select('ward_no', [], '', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('sector_no','সেক্টর নং',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::select('sector_no', [], '', ['placeholder' => 'Select One','class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            {!! Form::label('road_name','রাস্তার নাম',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('road_name', null,['class' => 'form-control input-md','id'=>'road_name']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('extent_land_plot_length_including_arms','বাহুর মাপ সহ জমি/প্লটের পরিমাণ',['class'=>'text-left col-md-5 ']) !!}
                            <div class="col-md-7">
                                {!! Form::text('extent_land_plot_length_including_arms', null,['class' => 'form-control onlyNumber engOnly input-md','id'=>'extent_land_plot_length_including_arms']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            {!! Form::label('existing_houses_structures_details','জমি/প্লট এ বিদ্যমান বাড়ি/কাঠামোর বিবরণ',['class'=>'text-left col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('existing_houses_structures_details', null,['class' => 'form-control input-md','id'=>'existing_houses_structures_details']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <h3 class="text-center stepHeader">Declaration</h3>
        <fieldset>
            <div class="panel panel-info">
                <div class="panel-heading" style="padding-bottom: 4px;">
                    <strong>আংশিক সমাপ্তের ব্যবহারের ধরণ</strong>
                </div>
                <div class="panel-body">
                    <div class="col-md- col-xs-12">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">তলার ধরণ</th>
                                <th class="text-center">ব্যবহারের ধরণ</th>
                                <th class="text-center">আংশিক(বর্গমিটার)</th>
                                <th class="text-center">পূর্ণ সমাপ্ত(বর্গমিটার)</th>
                                <th class="text-center">মোট ক্ষেত্রফল</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="coveredAreaDetails">
                            <tr id="coveredAreaDetailsRow_0">
                                <td width="20%"> {!! Form::select('floor_no[]',[],'',[ 'class'=>' form-control','id'=>'floor_no_1']) !!}</td>
                                <td width="20%">{!! Form::select('usage_1[]',[], '', [ 'class' => 'form-control', 'id'=>'usage_1']) !!}</td>
                                <td>{!! Form::text('partial[]','',['class' => 'form-control input-md onlyNumber engOnly partial', 'id'=>'partial', 'onkeyup'=>"calculateTotalArea.call(this)"]) !!}</td>
                                <td>{!! Form::text('full_finished[]','',['class' => 'form-control input-md onlyNumber engOnly full_finished', 'id'=>'full_finished', 'onkeyup'=>"calculateTotalArea.call(this)"]) !!}</td>
                                <td>{!! Form::text('total_floor[]','',['readonly'=>'readonly', 'class' => 'form-control input-md total_floor enbnNumber required total_floor', 'id'=>'total_floor_1']) !!}</td>
                                <td style="vertical-align: middle; text-align: center">
                                    <a class="btn btn-sm btn-primary addTableRows"
                                       title="Add more LOAD DETAILS"
                                       onclick="addTableRowCDA('coveredAreaDetails', 'coveredAreaDetailsRow_0');">
                                        <i class="fa fa-plus"></i></a>
                                </td>
                            </tr>
                            </tbody>
                            <tr>
                                <td colspan="4" class="text-right">
                                    ভবনের মেঝের মোট ক্ষেত্রফল
                                </td>
                                <td>
                                    {!! Form::text('building_total_floor_area', null,['readonly'=>'readonly', 'class' => 'form-control input-md','id'=>'building_total_floor_area']) !!}
                                    {!! $errors->first('building_total_floor_area','<span class="help-block">:message</span>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </fieldset>


        {{--Attachments--}}
        <h3 class="text-center stepHeader">Attachments</h3>
        <fieldset>
            <div class="row">
                <div class="col-md-12">
                    <div id="docListDiv">
                    </div>
                </div>
            </div>
        </fieldset>


        <h3 class="text-center stepHeader">Payment & Submit</h3>
        <fieldset>
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
                                    {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' => 'form-control input-md email required']) !!}
                                    {!! $errors->first('sfp_contact_email','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                                {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('sfp_contact_address', Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('sfp_contact_address','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
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
                                    Vat/ tax and service charge is an approximate amount, it may vary based on the
                                    Sonali Bank system.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>


        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="pull-left">
                    <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                            value="draft" name="actionBtn">Save as Draft
                    </button>
                </div>

                <div class="pull-left" style="padding-left: 1em;">
                    <button type="submit" id="submitForm" style="cursor: pointer;"
                            class="btn btn-success btn-md" value="Submit" name="actionBtn">Payment &amp;
                        Submit
                    </button>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 button_last">
                <div class="clearfix"></div>
            </div>
        </div> {{--row--}}

    </div>


    {!! Form::close() !!}
</div>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>

    $(document).ready(function () {
        var form = $("#OcCDAForm").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {

                return true;
                if (newIndex == 1) {

                }
                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex != -1) {
                    form.find('#save_as_draft').css('display', 'block');
                    form.find('.actions').css('top', '-42px');
                } else {
                    form.find('#save_as_draft').css('display', 'none');
                    form.find('.actions').css('top', '-15px');
                }
                if (currentIndex == 3) {
                    form.find('#submitForm').css('display', 'block');

                } else {
                    form.find('#submitForm').css('display', 'none');
                }

            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                }
            }
        });

        {{----end step js---}}
        $("#OcCDAForm").validate({
            rules: {
                field: {
                    required: true,
                    email: true,

                }
            }
        });

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            form.validate().settings.ignore = ":disabled,:hidden";
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=OcCDAForm@3'); ?>');
            } else {
                return false;
            }
        });

        $(document).on('blur', '.mobile', function () {
            var mobile = $(this).val()
            if (mobile) {
                if (mobile.length !== 11) {
                    $(this).addClass('error');
                    return false;
                } else {
                    $(this).removeClass('error');
                    return true;
                }
            }
        });

        $(document).on('keydown', '.mobile', function () {
            var mobile = $(this).val();
            var reg = /^01/;
            if (mobile) {
                if (mobile.length === 2) {
                    if (reg.test(mobile)) {
                        $(this).removeClass('error');
                        return true;
                    } else {
                        $(this).addClass('error')
                        $(this).val('')
                        return false;
                    }
                }
            }
        });

        $(document).on('blur', '.enbnNumber', function () {
            var enbn = $(this).val();
            var reg = /^([0-9]|[০-৯])/;
            if (enbn) {
                if (reg.test(enbn)) {
                    $(this).removeClass('error');
                    return true;
                } else {
                    $(this).addClass('error')
                    return false;
                }
            }
        });

        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 100),
            ignoreReadonly: true
        });

        $("#OcCDAForm").find('.onlyNumber').on('keydown', function (e) {
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

    })


    function calculateTotalArea() {
        var parentTd = $(this).parent();
        var parentRow = parentTd.parent();

        var partial = parentRow.find('.partial').val();
        var full_finished = parentRow.find('.full_finished').val();

        if (partial != null && partial != 0 && full_finished != null && full_finished != 0){
            var totalFloor = parseFloat(partial) + parseFloat(full_finished);
            parentRow.find('.total_floor').val(totalFloor.toFixed(2));
        }else if(full_finished != null && full_finished != 0) {
            parentRow.find('.total_floor').val(parseFloat(full_finished).toFixed(2));
        }else{
            parentRow.find('.total_floor').val(parseFloat(partial).toFixed(2));
        }
        calculateBuildingTotalFloorArea('total_floor', 'building_total_floor_area')
    }

    function calculateBuildingTotalFloorArea(className, totalShowFieldId) {
        var total = 0.00;
        $("." + className).each(function () {
            total = total + (this.value ? parseFloat(this.value) : 0.00);
        })
        $("#" + totalShowFieldId).val(total.toFixed(2));
    }

    // Add table Row script
    function addTableRowCDA(tableID, templateRow) {
        //alert(templateRow)
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length;
        //alert(rowCount)
        if (rowCount < 8) {

            var rowCo = rowCount + 1;
            var idText = tableID + 'Row_' + rowCount;

            x.id = idText;
            $("#" + tableID).append(x);
            var bid = idText.split("_").pop()
            //get input elements
            var attrInput = $("#" + tableID).find('#' + idText).find('input');
            for (var i = 0; i < attrInput.length; i++) {
                var nameAtt = attrInput[i].name;
                var inputId = attrInput[i].id;
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
                var ret = inputId.split('_')[0];
                var repTextId = ret + '_' + rowCo;
                attrInput[i].id = repTextId;
                attrInput[i].name = repText;
            }
            attrInput.val(''); //value reset

            // $("#" + tableID).find('#' + idText).find('.floorNo').val(floor[rowCount]);

            //$('.m_currency ').prop('selectedIndex', 102);
            //Class change by btn-danger to btn-primary
            $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                .attr('onclick', 'removeTableRowCustom("' + tableID + '","' + idText + '")');
            $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
            // alert(rowCount);

            //alert(floor[rowCount])
            $('#' + tableID).find('tr').last().attr('data-number', rowCount);

            $("#" + tableID).find('#' + idText).find('.onlyNumber').on('keydown', function (e) {
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
                    $this.val($this.val().replace(/[^.0-9]/g, ''));
                }, 4);
            });
            $("#OcCDAForm").find('.enbnNumber').on('keydown', function (e) {
                var enbn = $(this).val();
                var reg = /^([0-9]|[০-৯])/;
                if (enbn) {
                    if (reg.test(enbn)) {
                        $(this).removeClass('error');
                        return true;
                    } else {
                        $(this).addClass('error')
                        return false;
                    }
                }
            })

        }
    }

    // Remove Table row script
    function removeTableRowCustom(tableID, removeNum) {
        //alert(removeNum)
        $('#' + tableID).find('#' + removeNum).remove();
        var index = 0
        var rowCo = 0
        $('#' + tableID + ' tr').each(function () {
                var trId = $(this).attr("id")
                var id = trId.split("_").pop()
                var trName = trId.split("_").shift()

                var attrInput = $("#" + tableID).find('#' + trId).find('input');
                for (var i = 0; i < attrInput.length; i++) {
                    var inputId = attrInput[i].id
                    var ret = inputId.split('_')[0]
                    var repTextId = ret + '_' + rowCo
                    attrInput[i].id = repTextId
                }
                var rowCount = $('#' + tableID).find('tr').length;

                var ret = trId.replace('_' + id, '');
                var repTextId = ret + '_' + rowCo;
                $(this).removeAttr("id")
                $(this).attr("id", repTextId)
                $(this).removeAttr("data-number")
                $(this).attr("data-number", rowCo)
                if (rowCo != 0) {
                    $(this).find('.addTableRows').removeAttr('onclick');
                    $(this).find('.addTableRows').attr('onclick', 'removeTableRowCustom("' + tableID + '","' + trName + '_' + rowCo + '")');
                }
                index++;
                rowCo++;
            }
        )
    }

    //Doc and image upload section
    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            $("#validate_field_" + abc).val = '';
            $('#' + abc).val = ''
            var isReq = $('#' + abc).attr('data-required')
            if (isReq == 'required') {
                $('#' + abc).addClass('required error')
            }
            document.getElementById(abc).value = ''
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
            $('#span_' + abc).show()
            let img = $('#old_image_' + abc).val()
            let old_img = $('#old_image_' + abc).attr('data-img')
            $('#validate_field_' + abc).val(img)
            if (!old_img) {
                $('#photo_viewer_' + abc).attr('src', '{{(url('assets/images/no-image.png'))}}')
            } else {
                $('#photo_viewer_' + abc).attr('src', old_img)
            }


        } else {
            return false;
        }
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
            var action = "{{URL::to('/cda-lspp/upload-document')}}";
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
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

    $(document).ready(function () {
        var doc_type = 1;
        var app_id = $("#app_id").val();
        var _token = "{{ csrf_token() }}";

        var attachment_key = "cda_lspp_";
        if (doc_type == 1) {
            attachment_key += "mp";
        } else if (doc_type == 2) {
            attachment_key += "pw";
        } else if (doc_type == 3) {
            attachment_key += "ap";
        } else {
            alert('Please Select one type of Land');
        }

        $.ajax({
            type: "POST",
            url: '/cda-lspp/getDocList',
            dataType: "json",
            data: {_token: _token, attachment_key: attachment_key, app_id: app_id},
            success: function (result) {
                if (result.html != undefined) {
                    $('#docListDiv').html(result.html);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //console.log(errorThrown);
                alert('Unknown error occured. Please, try again after reload');
            },
        });
    });

    $(document).ready(function () {

        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/cda-oc/get-refresh-token';

            $('#occupancy_type').keydown()
            $('#block_no').keydown()
            $('#seat_no').keydown()
            $('#ward_no').keydown()
            $('#sector_no').keydown()
            $('#floor_no_1').keydown()
            $('#usage_1').keydown()
            $('#thana_name').keydown()


        });

        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: "{{ $client_id }}"
            },
            {
                key: "agent-id",
                value: "{{ $bida_agent_id }}"
            },
        ]

        $('#occupancy_type').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_oc_service_url}}/buildingClasses";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "BuildingClassId";//dynamic id for callback
            let element_name = "BuildingClassName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });

        $("#occupancy_type").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            var occupancy_type = $('#occupancy_type').val()
            var occupancy_type_id = occupancy_type.split("@")[0]
            if (occupancy_type_id) {
                let api_url = "{{$cda_oc_service_url}}/buildingSubClasses/" + occupancy_type_id;
                let selected_value = ''; // for callback
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "occupancy_sub_type";
                let element_id = "BuildingSubClassId";//dynamic id for callback
                let element_name = "BuildingSubClassName";//dynamic name for callback
                let element_calling_id = "BuildingClassId";//dynamic name for callback
                let element_details = "UseType";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, element_details, dependant_select_id]; // for callback
                apiCallGet(e, options, apiHeaders, checkboxCallbackResponse, arrays);
            }
        });

        $('#block_no').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_oc_service_url}}/blocks";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "BlockId";//dynamic id for callback
            let element_name = "BlockName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#seat_no').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_oc_service_url}}/seats";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "SeatId";//dynamic id for callback
            let element_name = "SeatNo";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#ward_no').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_oc_service_url}}/wards";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "WardId";//dynamic id for callback
            let element_name = "WardName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#sector_no').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_oc_service_url}}/sector";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "SectorId";//dynamic id for callback
            let element_name = "SectorName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#floor_no_1').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_oc_service_url}}/floorType";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "FloorTypeId";//dynamic id for callback
            let element_name = "FloorTypeName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#usage_1').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_oc_service_url}}/floorUseType";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "FloorUseId";//dynamic id for callback
            let element_name = "FloorUseType";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#thana_name').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$cda_oc_service_url}}/thana";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "ThanaId";//dynamic id for callback
            let element_name = "ThanaName";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $("#thana_name").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#mouza_name").html('<option value="">Please Wait...</option>')
            var thana = $('#thana_name').val()
            var thana_id = thana.split("@")[0]
            if (thana_id) {
                let e = $(this);
                let api_url = "{{$cda_oc_service_url}}/mouja/" + thana_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "mouza_name";
                let element_id = "MoujaId";//dynamic id for callback
                let element_name = "MoujaName";//dynamic name for callback
                let element_calling_id = "ThanaId";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#mouza_name").html('<option value="">Select Thana First</option>')
                $(e).next().hide()
            }

        })
    })

    function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = (element_calling_id === '' || element_calling_id == null) ? (row[element_id] + '@' + row[element_name]) : (row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name]);
                let value = row[element_name]
                option += '<option value="' + id + '">' + value + '</option>'
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + calling_id).next().hide()
    }

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = (element_calling_id === '' || element_calling_id == null) ? (row[element_id] + '@' + row[element_name]) : (row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name]);
                let value = row[element_name]
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
    }

    function checkboxCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, element_details, dependant_select_id]) {
        var option = '';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_calling_id] + '@' + row[element_details] + '@' + row[element_name];
                let value = row[element_name] + ':' + row[element_details]
                option += '<input type="checkbox" name="occupancy_sub_type[' + key + ']" value="' + id + '"> ' + value + '  ';
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + calling_id).next().hide()
    }

</script>