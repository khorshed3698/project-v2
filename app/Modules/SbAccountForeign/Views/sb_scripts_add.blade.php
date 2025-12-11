<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>

    $(document).ready(function () {

        var form = $("#SbAccount").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
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

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('licence-applications/sb-account/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

        {{----end step js---}}
        $("#NewConnection").validate({
            rules: {
                field: {
                    required: true,
                    email: true,

                }
            }
        });

        // Datepicker Plugin initialize
        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100),
            ignoreReadonly: true
        });
        $('.datepickerFuture').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            maxDate: '01/01/' + (yyyy + 6),
            useCurrent: false,
        });

        $('#nid_number').on('blur', function (e) {
            var nid = $('#nid_number').val().length
            if (nid == 10 || nid == 13 || nid == 17) {
                $('#nid_number').removeClass('error')
            } else {
                $('#nid_number').addClass('error')
                $('#nid_number').val('')
            }
        })

/*number validation*/
        $(document).on('keydown', '.onlyNumber', function (e) {
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

    /* Get list from API end */

    $(document).ready(function () {
        /*api header for micro service*/
        var apiHeaders = [
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
                value: '3'
            },
        ];
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });
        $('#type_of_business').change(function () {
            var type = $('#type_of_business').val();
            if (type == '4@Other') {
                $('#if_other_business_div').removeClass('hidden')
            } else {
                $('#if_other_business_div').addClass('hidden')
            }

        })

        $(function () {
            token = "{{$token}}"
            tokenUrl = '/ctcc/get-refresh-token'
            $('#bank_district').keydown()
            $('#currency').keydown()
            $('#nature_of_organization').keydown()
            $('#sex').keydown()
            $('#type_of_business').keydown()
            $('#nature_of_bus').keydown()
            $('#nationality_personal').keydown()
            $('#registration_country').keydown()
            $('#resident').keydown()
            $('#ac_nature').keydown()
            $('#identification_doc').keydown()
            $('#account_operation').keydown()
            $('#customer_category').keydown()
            $('#occupation_code').keydown()
            $('#entity_type').keydown()
            $('#account_type').keydown()
        });

        $('#office_post').on('keyup', function (el) {
            let postCode = $(this).val();
            var key = el.which;
            let officeCountry = $("#office_country").val();
            let officeCountryId = '';
            if (officeCountry == '') {
                alert("Please Select country first");
                $(this).val('');
                return false;
            }

            officeCountryId = officeCountry.split('@')[0];

            if (officeCountryId != 'BD' || postCode.length < 4) {
                return true
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/post-office/" + postCode;
            let calling_id = $(this).attr('id'); // for callback
            let data = '';
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, data]; // for callback
            apiCallGet(e, options, apiHeaders, callbackShowOfficeInfo, arrays);

        })

        $('#present_post').on('keyup', function (el) {
            var key = el.which;
            let presentCountry = $("#present_country").val();
            let presentCountryId = '';
            if (presentCountry == '') {
                alert("Please Select country first");
                $(this).val('');
                return false;
            }
            let postCode = $(this).val();
            presentCountryId = presentCountry.split('@')[0];
            if (presentCountryId != 'BD' || postCode.length < 4) {
                return true
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/post-office/" + postCode;
            let calling_id = $(this).attr('id'); // for callback
            let data = '';
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, data]; // for callback
            apiCallGet(e, options, apiHeaders, callbackShowPresentInfo, arrays);
        })

        $('#permanent_post').on('keyup', function (el) {
            var key = el.which;
            let permanentCountry = $("#permanent_country").val();
            let permanentCountryId = '';
            if (permanentCountry == '') {
                alert("Please Select country first");
                $(this).val('');
                return false;
            }
            let postCode = $(this).val();
            permanentCountryId = permanentCountry.split('@')[0];
            if (permanentCountryId != 'BD' || postCode.length < 4) {
                return true
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$service_url}}/info/post-office/" + postCode;
            let calling_id = $(this).attr('id'); // for callback
            let data = '';
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, data]; // for callback
            apiCallGet(e, options, apiHeaders, callbackShowPermanentInfo, arrays);

        })

        $('#office_country').on('change', function (el) {
            let country = $(this).val();
            let countryCode = country.split('@')[0];
            if (countryCode == "BD") {
                $("#office_post").addClass('onlyNumber');
                $("#office_post").attr('maxlength', '4');
                $("#office_thana").attr('readonly', true);
                $("#office_dsp").attr('readonly', true);
                $("#office_thana_code").attr('disabled', false);
                $("#office_dsp_code").attr('disabled', false);
                $("#office_division").attr('disabled', false);
            } else {
                $("#office_thana").attr('readonly', false);
                $("#office_dsp").attr('readonly', false);
                $("#office_post").removeClass('onlyNumber');
                $("#office_post").attr('maxlength', '');
                $("#office_thana_code").attr('disabled', true);
                $("#office_dsp_code").attr('disabled', true);
                $("#office_division").attr('disabled', true);
            }
        });

        $('#present_country').on('change', function (el) {
            let country = $(this).val();
            let countryCode = country.split('@')[0];
            if (countryCode == "BD") {
                $("#present_post").addClass('onlyNumber');
                $("#present_post").attr('maxlength', '4');
                $("#present_thana").attr('readonly', true);
                $("#present_dsp").attr('readonly', true);
                $("#present_thana_code").attr('disabled', false);
                $("#present_dsp_code").attr('disabled', false);
                $("#present_division").attr('disabled', false);
            } else {
                $("#present_thana").attr('readonly', false);
                $("#present_dsp").attr('readonly', false);
                $("#present_post").removeClass('onlyNumber');
                $("#present_post").attr('maxlength', '');
                $("#present_thana_code").attr('disabled', true);
                $("#present_dsp_code").attr('disabled', true);
                $("#present_division").attr('disabled', true);
            }
        });

        $('#permanent_country').on('change', function (el) {
            let country = $(this).val();
            let countryCode = country.split('@')[0];
            if (countryCode == "BD") {
                $("#permanent_post").addClass('onlyNumber');
                $("#permanent_post").attr('maxlength', '4');
                $("#permanent_thana").attr('readonly', true);
                $("#permanent_dsp").attr('readonly', true);
                $("#permanent_thana_code").attr('disabled', false);
                $("#permanent_dsp_code").attr('disabled', false);
                $("#permanent_division").attr('disabled', false);
            } else {
                $("#permanent_thana").attr('readonly', false);
                $("#permanent_dsp").attr('readonly', false);
                $("#permanent_post").removeClass('onlyNumber');
                $("#permanent_post").attr('maxlength', '');
                $("#permanent_thana_code").attr('disabled', true);
                $("#permanent_dsp_code").attr('disabled', true);
                $("#permanent_division").attr('disabled', true);
            }
        });


        $('#entity_type').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/constitution-code";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#account_type').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/account-type";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "appsFrontEndAcType"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponseAccountType, arrays);

        })

        $('#customer_category').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/customer-category";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "categoryCode"; //dynamic id for callback
            var element_name = "categoryName"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback


            apiCallGet(e, options, apiHeaders, callbackResponseCCategory, arrays);

        })

        $('#bank_district').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/district";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "districtCode"; //dynamic id for callback
            var element_name = "district"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#bank_district').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var districtName = $(this).val();
            if (districtName != '') {
                var districtId = districtName.split("@")[1].toUpperCase();
            }

            $(this).parent().find('.loading_data').hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/bank-by-district/" + districtId;
            var selected_value = ''; // for callback
            var dependent_section_id = "bank_branch"; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "branchCode"; //dynamic id for callback
            var element_name = "branchName"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback


            apiCallGet(e, options, apiHeaders, callbackResponseBranch, arrays);

        })

        $('#customer_category').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var category = $(this).val();
            if (category != '') {
                var categoryId = category.split("@")[0].toUpperCase();
            } else {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/sub-customer-category/" + categoryId;
            var selected_value = ''; // for callback
            var dependent_section_id = "customer_sub_category"; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "subCategoryCode"; //dynamic id for callback
            var element_name = "subCategoryName"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback


            apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

        })
        $('#account_operation').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/conn-role";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#occupation_code').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/occupation";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "occupationCode"; //dynamic id for callback
            var element_name = "occupationName"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#currency').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/currency";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "currentyCode"; //dynamic id for callback
            var element_name = "currencyName"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#nature_of_organization').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/nature-of-organization";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "organizationNature"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#type_of_business').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/type-of-business";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "type"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback


            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#nature_of_bus').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/business-type";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#sex').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            let gender = "{{!empty($basicAppInfo->ceo_gender)?$basicAppInfo->ceo_gender:''}}"
            let selectedGender ='';
            if(gender == 'Male'){
                selectedGender = 'M@Male';
            }else if(gender == 'Female'){
                selectedGender = 'F@Female';
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/gender?agent-id=3";
            var selected_value = selectedGender; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "gender"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        })

        $('#nationality_personal').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/nationality";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "nationalityCode"; //dynamic id for callback
            var element_name = "nationalityName"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        })

        $('#registration_country').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/nationality";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "nationalityCode"; //dynamic id for callback
            var element_name = "nationalityName"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponseCountry, arrays);
        })

        $('#resident').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/resident-type";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "resident"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        })

        $('#permanent_dsp').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/district";
            var selected_value = '{{!empty($appData->bank_district) ?$appData->bank_district: ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "districtCode"; //dynamic id for callback
            var element_name = "district"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        })


        $('#ac_nature').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/ac-type";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#identification_doc').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/document-type";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "type"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })



        $('#identification_doc').on('change',function (el){
            let docType = $(this).val();
            let docTypeCode = docType.split('@')[0];
            if(docTypeCode == 'PP'){
                $('#identification_doc_date_issue_div').show();
                $('#identification_doc_date_exp_div').show();
                $("#identification_doc_date_issue").attr('disabled', false);
                $("#identification_doc_date_exp").attr('disabled', false);
            }else if(docTypeCode == 'BC'){
                $('#identification_doc_date_issue_div').show();
                $('#identification_doc_date_exp_div').hide();
                $("#identification_doc_date_issue").attr('disabled', false);
                $("#identification_doc_date_exp").attr('disabled', true);
            }else{
                $('#identification_doc_date_issue_div').hide();
                $('#identification_doc_date_exp_div').hide();
                $("#identification_doc_date_issue").attr('disabled', true);
                $("#identification_doc_date_exp").attr('disabled', true);
            }
        });

        $('#identification_country').on('change',function (el){
            let countryData = $(this).val();
            let country_code = countryData.split('@')[0];
            if(country_code !=''){
                if(country_code == 'BD'){
                    $("#identification_doc").val('SID@Smart Card ID')
                }else {
                    $("#identification_doc").val('PP@Passport')
                }
                $("#identification_doc").trigger('change');
            }

        });

        $(document).on('click', '.filedelete', function () {
            var abc = $(this).attr('docid');
            var sure_del = confirm("Are you sure you want to delete this file?");
            if (sure_del) {
                document.getElementById("validate_field_" + abc).value = '';
                document.getElementById(abc).value = '';
                $('.saved_file_' + abc).html('');
                $('.span_validate_field_' + abc).html('');
            } else {
                return false;
            }
        });

    })

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            // console.log(response.data);
            $.each(response.data, function (key, row) {
                if (data == '' || data == null) {
                    var id = row[element_id] + '@' + row[element_name];
                } else {
                    var id = row[element_id] + '@' + row[data] + '@' + row[element_name];
                }

                var value = row[element_name];
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
    function callbackResponseAccountType(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            // console.log(response.data);
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name]+'@'+row['cbsSectorCode']+'@'+row['productCode'];
                var value = row[element_name];
                if(row[element_id] !=0){
                    if (selected_value == id) {
                        option += '<option selected="true" value="' + id + '">' + value + '</option>';
                    } else {
                        option += '<option value="' + id + '">' + value + '</option>';
                    }
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

    function callbackResponseCountry(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            // console.log(response.data);
            $.each(response.data, function (key, row) {
                if (data == '' || data == null) {
                    var id = row[element_id] + '@' + row[element_name];
                } else {
                    var id = row[element_id] + '@' + row[data] + '@' + row[element_name];
                }

                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }
        let searchboxStatus = $("#" + calling_id).hasClass('search-box');
        $("#" + calling_id).html(option)
        $("#present_country").html(option)
        $("#permanent_country").html(option)
        $("#office_country").html(option)
        $("#identification_country").html(option)
        if(searchboxStatus){
            $(".country-select").select2();
        }

        $("#" + calling_id).parent().find('.loading_data').hide();
        $(".country-select").trigger('change')
    }


    function callbackResponseCCategory(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            // console.log(response.data);
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];

                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
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
        //alert(dependent_section_id);
        $("#" + calling_id).next().hide();
    }

    function callbackResponseBranch(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data.branchList, function (key, row) {
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

        //alert(dependent_section_id);
        $("#" + calling_id).parent().find('.loading_data').hide();
    }

    function callbackShowOfficeInfo(response, [calling_id, data]) {
        let postOfficeDetails = response.data.postOfficeList;
        if (postOfficeDetails != '') {
            $("#office_division").val(postOfficeDetails[0].divisionCode + '@' + postOfficeDetails[0].division);
            $("#office_thana_code").val(postOfficeDetails[0].policeStationCode);
            $("#office_thana").val(postOfficeDetails[0].thana);
            $("#office_dsp").val(postOfficeDetails[0].district);
            $("#office_dsp_code").val(postOfficeDetails[0].districtCode);
        } else {
            alert('Invalid Post Code');
            $("#office_post").val('');
        }
        $("#" + calling_id).next().hide();
    }

    function callbackShowPresentInfo(response, [calling_id, data]) {
        let postOfficeDetails = response.data.postOfficeList;
        if (postOfficeDetails != '') {
            $("#present_division").val(postOfficeDetails[0].divisionCode + '@' + postOfficeDetails[0].division);
            $("#present_thana_code").val(postOfficeDetails[0].policeStationCode);
            $("#present_thana").val(postOfficeDetails[0].thana);
            $("#present_dsp").val(postOfficeDetails[0].district);
            $("#present_dsp_code").val(postOfficeDetails[0].districtCode);
        } else {
            alert('Invalid Post Code');
            $("#present_post").val('');
        }
        $("#" + calling_id).next().hide();
    }

    function callbackShowPermanentInfo(response, [calling_id, data]) {
        let postOfficeDetails = response.data.postOfficeList;
        if (postOfficeDetails != '') {
            $("#permanent_division").val(postOfficeDetails[0].divisionCode + '@' + postOfficeDetails[0].division);
            $("#permanent_thana_code").val(postOfficeDetails[0].policeStationCode);
            $("#permanent_thana").val(postOfficeDetails[0].thana);
            $("#permanent_dsp").val(postOfficeDetails[0].district);
            $("#permanent_dsp_code").val(postOfficeDetails[0].districtCode);
        } else {
            alert('Invalid Post Code');
            $("#permanent_post").val('');
        }
        $("#" + calling_id).next().hide();
    }


    function getDistrictByDivisionCustom(division_id, division_value, district_div, old_data) {
        // define old_data as an optional parameter
        if (typeof old_data === 'undefined') {
            old_data = 0;
        }

        var _token = $('input[name="_token"]').val();
        if (division_value !== '') {
            $("#" + division_id).after('<span class="loading_data">Loading...</span>');
            // $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
            $.ajax({
                type: "GET",
                url: "/users/get-district-by-division",
                data: {
                    _token: _token,
                    divisionId: division_value
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            var element_id = (id + '@' + value)
                            if (id == old_data) {
                                option += '<option value="' + element_id + '" selected>' + value + '</option>';
                            } else {
                                option += '<option value="' + element_id + '">' + value + '</option>';
                            }
                        });
                    }
                    $("#" + district_div).html(option);
                    $("#" + division_id).next().hide();
                }
            });
        } else {
            // console.log('Please select a valid district');
        }
    }

</script>