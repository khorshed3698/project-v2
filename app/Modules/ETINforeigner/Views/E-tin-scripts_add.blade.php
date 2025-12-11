<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>

<script>

    $(document).ready(function () {

        var sessionLastWPN = '{{ Session::get('wpneInfo.is_approval_online') }}';
        if (sessionLastWPN == 'yes') {
            etinApplication(sessionLastWPN);
            // $("#ref_app_tracking_no").prop('readonly', true);

            $(".custom_readonly").attr('readonly', true);
//        //$(".custom_readonly option:not(:selected)").prop('disabled', true);
            $(".custom_readonly option:not(:selected)").remove();
            $(".custom_readonly:radio:not(:checked)").attr('disabled', true);
//        $(".custom_readonlyPhoto").attr('disabled', true);
        }
        var form = $("#TINforeigner").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // Always allow previous action even if the current form is not valid!
                // return true;
                if (currentIndex > newIndex) {
                    return true;
                }
                if(newIndex == 1){
                    var is_approval_online = $("input[name='is_approval_online']:checked").val();
                    if(is_approval_online == 'yes') {
                        if(sessionLastWPN != 'yes') {
                            alert('Please, load work permit data.');
                            return false;
                        }
                    }
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
            form.validate().settings.ignore = ":disabled,:hidden";
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/preview?form=TINforeigner@3'); ?>');
            } else {
                return false;
            }
        });

        {{----end step js---}}
        $("#TINforeigner").validate({
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
        $('.datepickerOld').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 6),
            maxDate: 'now',
            useCurrent: false,
        });

        $('.datepickerPassportIssue').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: 'now',
            useCurrent: false,
        });

        $('.datepickerFuture').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            maxDate: '01/01/' + (yyyy + 6),
            useCurrent: false,
        });
        $('.datepickerPassExP').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            useCurrent: false,
        });

        var calculatedYear = (new Date).getFullYear() - 18;
        var currentMonth = (new Date).getMonth();
        var currentDay = (new Date).getDate();

        $('.datepickerDob').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 150),
            maxDate: new Date(calculatedYear, currentMonth, currentDay),
        });
        $('#date_of_birth').val('');

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
        $('#without_workpermit').hide();
        $('#workpermit').show();
        $('#localtion_main_source_income').change(function(){
            var getData= $('#localtion_main_source_income').val();
        if(getData !='') {
            $('#save_as_draft').removeAttr('disabled');
        }else{
            $('#save_as_draft').attr('disabled','disabled');
        }
        })
        $('#director_foreigner').change(function(){
            if($(this).prop("checked")) {
                $('#without_workpermit').show();
                $('#company_tin').removeAttr('disabled');
                $('#company_tin').addClass('required');
                $('#workpermit').hide();
                $('#authority_name').removeClass('required');
                $('#registration_number').removeClass('required');
                $('#registration_date').removeClass('required');
                $('#authority_name').attr('disabled','disabled');
                $('#registration_number').attr('disabled','disabled');
                $('#registration_date').attr('disabled','disabled');
            } else {
                $('#company_tin').attr('disabled','disabled');
                $('#company_tin').removeClass('required');
                $('#without_workpermit').hide();
                $('#workpermit').show();
                $('#authority_name').addClass('required');
                $('#registration_number').addClass('required');
                $('#registration_date').addClass('required');
                $('#authority_name').removeAttr('disabled');
                $('#registration_number').removeAttr('disabled');
                $('#registration_date').removeAttr('disabled');
            }
        });
        $('#director_foreigner').trigger('change');

        $('#business_location').change(function(){
            var type =$(this).val()
            var type_id = type.split('@')[1]
            $('#div_select_list_name').val(type_id);
        })
        $('#organization_id').change(function(){
            var type =$(this).val()
            var type_id = type.split('@')[1]
            $('#organization_id_text').val(type_id);
        })

        $('#business_location').change(function(){
            var type =$(this).val()
            var type_id = type.split('@')[1]
            $('#business_type_data').val(type_id);
            var type_val = type_id.split(' ')[0]
            if(type_val == "Limited") {
                $('#organization_name_div').removeClass('hidden');
            } else {
                $('#organization_name_div').addClass('hidden');
            }
        });
        $('#same_as_current').click(function(){
            $('#permanent_district option:selected').removeAttr('selected');
            $('#permanent_thana option:selected').removeAttr('selected');
            var present_country=$('#present_country').val();
            var present_line1=$('#address_line1_p').val();
            var present_line2=$('#address_line2_p').val();
            var present_district=$('#present_district').val();
            var present_state=$('#present_state').val();
            var present_thana=$('#present_thana').val();
            var present_tana_name=  '';
            if(present_thana !=''){
                present_tana_name = present_thana.split('@')[1]
            }
            var present_post=$('#present_post_code').val();
            $('#permanent_thana').attr('data-value2',present_tana_name);
            if($(this).prop("checked")) {
                $('#permanent_country').val(present_country);
                $('#address_line1_per').val(present_line1);
                $('#address_line2_per').val(present_line2);
                $('#permanent_district').val(present_district);
                $('#permanent_district').trigger('change');
                $('#permanent_state').val(present_state);
                $('#permanent_thana').val(present_thana);
                $('#permanent_post_code').val(present_post);

                $('#permanent_country').attr('readonly',true)
                $('#address_line1_per').attr('readonly',true);
                $('#address_line2_per').attr('readonly',true);
                $('#permanent_district').attr('readonly',true);
                $('#permanent_state').attr('readonly',true);
                $('#permanent_thana').attr('readonly',true);
                $('#permanent_post_code').attr('readonly',true);
                getdistrictByCountryId('permanent');
            }else {
                $('#permanent_country').val('');
                $('#address_line1_per').val('');
                $('#address_line2_per').val('');
                $('#permanent_district').val('');
                $('#permanent_state').val('');
                $('#permanent_thana').val('');
                $('#permanent_post_code').val('');

                $('#permanent_country').attr('readonly',false)
                $('#address_line1_per').attr('readonly',false);
                $('#address_line2_per').attr('readonly',false);
                $('#permanent_district').attr('readonly',false);
                $('#permanent_state').attr('readonly',false);
                $('#permanent_thana').attr('readonly',false);
                $('#permanent_post_code').attr('readonly',false);
                getdistrictByCountryId('permanent');
            }
        });
        $('#company_tin').on('keyup', function () {
            var tin = $('#company_tin').val();
            if (tin.length !== 12) {
                $("#company_tin").addClass("error");
            } else {
                $("#company_tin").removeClass("error");
            }
        });
        if(sessionLastWPN !='yes'){
            $( "#registration_div" ).hide();
        }

    });

    function etinApplication(value) {
        var sessionLastWPN = '{{ Session::get('wpneInfo.is_approval_online') }}';
        if (value == 'yes') {
            $("#ref_app_tracking_no_div").removeClass('hidden');
            $("#ref_app_tracking_no").addClass('required');
            $( "#director_foreigner" ).prop( "checked", false );
            if(sessionLastWPN =='yes'){
                $( "#registration_div" ).show();
                $( "#withoutWpdiv" ).hide();
            }else{
                $( "#registration_div" ).hide();
                $( "#withoutWpdiv" ).show();
            }


            $('#company_tin').attr('disabled','disabled');
            $('#company_tin').removeClass('required');
            $('#without_workpermit').hide();
            $('#workpermit').show();
            $('#authority_name').addClass('required');
            $('#registration_number').addClass('required');
            $('#registration_date').addClass('required');
            $('#authority_name').removeAttr('disabled');
            $('#registration_number').removeAttr('disabled');
            $('#registration_date').removeAttr('disabled');
        } else if (value == 'no') {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#ref_app_tracking_no").removeClass('required');
            $( "#director_foreigner" ).hide();
            // $( "#director_foreigner" ).prop( "checked", true );
            // $('#without_workpermit').show();
            // $('#company_tin').removeAttr('disabled');
            // $('#company_tin').addClass('required');
            // $('#workpermit').hide();
            // $('#authority_name').removeClass('required');
            // $('#registration_number').removeClass('required');
            // $('#registration_date').removeClass('required');
            // $('#authority_name').attr('disabled','disabled');
            // $('#registration_number').attr('disabled','disabled');
            // $('#registration_date').attr('disabled','disabled');
            $( "#registration_div" ).show();
        } else {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#ref_app_tracking_no").removeClass('required');
            $( "#director_foreigner" ).prop( "checked", false );
            $( "#registration_div" ).hide();
        }
    }
    function getdistrictByCountryId(Id){
        var type = $('#'+Id+'_country').val();
        if (type == '1@Bangladesh') {
            if(Id !='other' ) {
                $('#' + Id + '_thana').addClass('required');
                $('#' + Id + '_district').addClass('required');
                $('#' + Id + '_state').removeClass('required');
            }
            $('#'+Id+'_thana').addClass("search-box");
            $('#'+Id+'_district').select2();
            $('#'+Id+'_thana').select2();
            $('#'+Id+'_thana').css("display","block");
            $('#'+Id+'_district').addClass("search-box");
            $('#'+Id+'_district').css("display","block");
            $('#'+Id+'_thana').removeAttr('disabled');
            $('#'+Id+'_district').removeAttr('disabled');
            $('#'+Id+'_state').addClass('hidden');
            $('#'+Id+'_state').attr('disabled','disabled');
        } else {
            if(Id !='other' ) {
                $('#'+Id+'_district').removeClass('required');
                $('#'+Id+'_thana').removeClass('required');
                $('#'+Id+'_state').addClass('required');
            }
            $('#'+Id+'_district').attr('disabled','disabled');
            $('#'+Id+'_thana').attr('disabled','disabled');
            $('#'+Id+'_district').removeClass('search-box');
            $('#'+Id+'_district').select2('destroy');
            $('#'+Id+'_district').css("display","none");
            $('#'+Id+'_thana').removeClass('search-box');
            $('#'+Id+'_thana').select2('destroy');
            $('#'+Id+'_thana').css("display","none");
            $('#'+Id+'_state').removeClass('hidden');
            $('#'+Id+'_state').removeAttr('disabled');
        }
    };

    $(document).ready(function () {
        $(function () {
            token = "{{$token}}";
            tokenUrl = '/e-tin-foreigner/get-refresh-token';
            $('#taxpayer_status').keydown();
            $('#gender').keydown();
            $('#authority_name').keydown();
            $('.country_id').keydown();
            getDoc();
        });

        $('#taxpayer_status').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/tax-payer";
            var selected_value = '2@Individual (Foreigner/NRB/ without NID)'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "fieldId"; //dynamic id for callback
            var element_name = "fieldValue"; //dynamic name for callback
            var data = null;
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

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

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#taxpayer_status').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide()

            $("#taxpayer_status_b").html('<option value="">Please Wait...</option>')

            var taxpayer = $(this).val()
            var taxpayer_id = taxpayer.split('@')[0]
            if(taxpayer_id == 3){
                $('#country_id_div').addClass('hidden');
                $('#NBforForeign').addClass('hidden');
                $('#country_id').removeClass('required');
                $('#country_id').attr('disabled','disabled');
            }else{
                $('#country_id').addClass('required');
                $('#country_id').removeAttr('disabled');
                $('#country_id_div').removeClass('hidden');
                $('#NBforForeign').removeClass('hidden');
            }

            if (taxpayer_id) {
                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/tax-payer-sub-category/" + taxpayer_id
                var selected_value = '7@Foreigner (Non Bangladeshi)';  // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "fieldId"; //dynamic id for callback
                var element_name = "fieldValue"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "taxpayer_status_b";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#taxpayer_status_b").html('<option value="">Select Taxpayers Status First</option>')
                $(self).next().hide();
            }
        })
        $('#taxpayer_status_b').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide()

            $("#main_source_income").html('<option value="">Please Wait...</option>')

            var taxpayer = $(this).val()
            var taxpayer_id = taxpayer.split('@')[0]
            if(taxpayer_id == 7) {
                var selected_value ='31@Service';
                $('#non_bangladeshi').removeClass('hidden');
                $('#non_bangladeshi_minor').addClass('hidden');
                $('#guardian_passport').removeClass('required');
                $('#guardian_passport_issue_date').removeClass('required');

            } else {
                var selected_value ='41@Service';
                $('#non_bangladeshi_minor').removeClass('hidden');
                $('#non_bangladeshi').addClass('hidden');
                $('#guardian_passport').addClass('required');
                $('#guardian_passport_issue_date').addClass('required');
            }

            if (taxpayer_id) {
                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/income/" + taxpayer_id;
                var selected_value = selected_value;  // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "fieldId"; //dynamic id for callback
                var element_name = "fieldValue"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "main_source_income";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#main_source_income").html('<option value="">Select Taxpayers Status B First</option>')
                $(self).next().hide();
            }
        })
        $('.country_id').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/country";
            var selected_value = $(this).attr("data-value");  // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "fieldId"; //dynamic id for callback
            var element_name = "fieldValue"; //dynamic name for callback
            var data = null;
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

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

            apiCallGet(e, options, apiHeaders, callbackResponseMatchWithLabel, arrays);

        })
        $('.country_id').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide()
            var dependent_section_id = $(this).attr('dependent_id');

            $("#"+dependent_section_id).html('<option value="">Please Wait...</option>')

            var country = $(this).val()
            var country_id = country.split('@')[0]


            if (country_id) {
                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/district/" + country_id
                var selected_value =  $("#"+dependent_section_id).attr("data-value2");  // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "fieldId"; //dynamic id for callback
                var element_name = "fieldValue"; //dynamic name for callback
                var data = '';
                // var dependent_section_id = "present_district";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackMatchLabel, arrays);

            } else {
                $("#"+dependent_section_id).html('<option value="">Select Taxpayers Status First</option>')
                $(self).next().hide();
            }
        })
        $('.district_id').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide()
            var dependent_section_id = $(this).attr('dependent_id');

            $("#"+dependent_section_id).html('<option value="">Please Wait...</option>')

            var district = $(this).val()
            var district_id = district.split('@')[0];

            if (district_id) {
                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/thana/" + district_id
                var selected_value =  $("#"+dependent_section_id).attr("data-value2");  // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "fieldId"; //dynamic id for callback
                var element_name = "fieldValue"; //dynamic name for callback
                var data = '';
                // var dependent_section_id = "present_thana";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackMatchLabel, arrays);

            } else {
                $("#"+dependent_section_id).html('<option value="">Select Taxpayers Status First</option>')
                $(self).next().hide();
            }
        })
        $('#taxpayer_status_b').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide()

            $("#localtion_main_source_income").html('<option value="">Please Wait...</option>')

            var incomeSource = $(this).val()
            var incomeSource_val = incomeSource.split('@')[0];
            if(incomeSource_val == 7){
                var incomeSource_id=31;
            }else{
                var incomeSource_id=41;
            }


            if (incomeSource_id) {
                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/income-location/" + incomeSource_id
                var selected_value = '';  // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "fieldId"; //dynamic id for callback
                var element_name = "fieldValue"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "localtion_main_source_income";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#localtion_main_source_income").html('<option value="">Select Taxpayers Status First</option>')
                $(self).next().hide();
            }
        })
        $('#business_location').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide()

            $("#organization_id").html('<option value="">Please Wait...</option>')
            $("#organization_id_text").val('');

            var incomeSource = $(this).val()
            var incomeSource_val = incomeSource.split('@')[0];

            var tax_category = $("#taxpayer_status_b").val()
            var tax_category_id = tax_category.split('@')[0];

            if (incomeSource_val) {
                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/bol-sub-list/"+incomeSource_val +"/tax-category/"+tax_category_id
                var selected_value = '';  // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "fieldId"; //dynamic id for callback
                var element_name = "fieldValue"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "organization_id";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, locationdependantCallbackResponse, arrays);

            } else {
                $("#organization_id").html('<option value="">Select Service Location First</option>')
                $(self).next().hide();
            }
        })
        $('#gender').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/gender";
            var selected_value = '';  // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "type_id"; //dynamic id for callback
            var element_name = "type_name"; //dynamic name for callback
            var data = null;
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

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

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
        $('#localtion_main_source_income').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $('#organization_name_div').addClass('hidden');

            var tax_category = $('#taxpayer_status_b').val();
            var tax_category_id = tax_category.split('@')[0];

            var self = $(this);
            $(self).next().hide()

            var incomeSource = $(this).val()
            var incomeSource_id = incomeSource.split('@')[0];

            $("#business_location").html('<option value="">Please Wait...</option>')


            if (incomeSource_id) {
                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$service_url}}/info/business-location/"+incomeSource_id+"/type/1/tax-category/"+tax_category_id;
                var selected_value = '';  // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "fieldId"; //dynamic id for callback
                var element_name = "fieldValue"; //dynamic name for callback
                var data = '';
                var dependent_section_id = 'business_location';
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#business_location").html('<option value="">Select Business (Individual/Firm) First</option>')
                $(self).next().hide();
            }
        })
        $('#authority_name').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$service_url}}/info/authority";
            var selected_value = '1@Bangladesh Investment Development Authority (BIDA)';  // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "org_id"; //dynamic id for callback
            var element_name = "org_name"; //dynamic name for callback
            var data = null;
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

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

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })
    });
    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
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

        $("#" + calling_id).html(option)
         $("#" + calling_id).parent().find('.loading_data').hide();
        $("#" + calling_id).trigger('change');
        $(".search-box").select2();
    }
/*selecteed value match with name/label*/
    function callbackResponseMatchWithLabel(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                if (data == '' || data == null) {
                    var id = row[element_id] + '@' + row[element_name];
                } else {
                    var id = row[element_id] + '@' + row[data] + '@' + row[element_name];
                }

                var value = row[element_name];
                if (selected_value == value) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option)
         $("#" + calling_id).parent().find('.loading_data').hide();
        $("#" + calling_id).trigger('change')
        $(".search-box").select2();
    }
    function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>'
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name]
                var value = row[element_name]
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            });
        }
        $("#" + dependent_section_id).html(option)
         $("#" + calling_id).parent().find('.loading_data').hide();
        $("#"+dependent_section_id).trigger('change');
        $(".search-box").select2();
    }
/*check matching with name/label*/
    function dependantCallbackMatchLabel(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>'
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name]
                var value = row[element_name]
                if (selected_value == value) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            });
        }
        $("#" + dependent_section_id).html(option)
         $("#" + calling_id).parent().find('.loading_data').hide();
        $("#"+dependent_section_id).trigger('change');
        $(".search-box").select2();
    }
    function locationdependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>'
        if (response.responseCode === 200) {
            var arrayLen = response.data.length;

            if(arrayLen == 1){
                $('#organization_name_div').removeClass('hidden');
                $('#div_select_list_name').addClass('hidden');
                $('#organization_id_div').addClass('hidden');
                $('#organization_id').removeClass('required');
                $('#organization_id').attr('disabled','disabled');
                $('#div_select_list_name').removeClass('required');
                $('#div_select_list_name').attr('disabled','disabled');
                $('#organization_name').removeAttr('disabled');
            }else if(arrayLen == ''){
                $('#div_select_list_name').removeClass('hidden');
                $('#organization_id_div').addClass('hidden');
                $('#organization_name_div').addClass('hidden');
                $('#organization_name').removeClass('required');
                $('#organization_name').attr('disabled','disabled');
                $('#organization_id').removeClass('required');
                $('#organization_id').attr('disabled','disabled');
            }else{
                var tax_category = $("#business_location").val()
                var tax_category_id = tax_category.split('@')[1];

                $('#div_select_list_name').removeClass('hidden');
                $('#organization_id_div').removeClass('hidden');
                $('#organization_id').removeAttr('disabled');
                $('#organization_id').addClass('required');
                $('#div_select_list_name').addClass('required');
                $('#div_select_list_name').removeAttr('disabled');
                $('#organization_id_label').text(tax_category_id);
                if(jQuery.inArray("nextContent", response.data)){
                    $('#organization_name_div').removeClass('hidden');
                    $('#organization_name').removeAttr('disabled');
                    $('#organization_name').addClass('required');
                }else{
                    $('#organization_name_div').addClass('hidden');
                    $('#organization_name').attr('disabled','disabled');
                    $('#organization_name').removeClass('required');
                }

                console.log(response.data);
                $.each(response.data, function (key, row) {
                    var id = row[element_id] + '@' + row[element_name]
                    var value = row[element_name]
                    if (selected_value == id) {
                        option += '<option selected="true" value="' + id + '">' + value + '</option>'
                    }else{
                        option += '<option value="' + id + '">' + value + '</option>'
                    }
                });

            }

        }
        $("#" + dependent_section_id).html(option)
         $("#" + calling_id).parent().find('.loading_data').hide();
        $(".search-box").select2();
    }

</script>