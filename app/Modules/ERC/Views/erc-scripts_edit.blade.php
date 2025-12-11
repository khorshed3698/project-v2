<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $(".search-box").select2();
        $('#submitForm').on('click', function (e) {


            var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length)

            if (ownerRowNumber === 0) {
                $("form#erc").validate();
                $('#owner_save').removeClass('btn-primary btn-white').addClass('btn-danger btn-red').focus()
                alert('Please add at least one owner!!')
                if (ownerSectionvalidate() === true) {
                    return false;
                }

            } else {
                $(".owner_req").removeClass('required')
                $("form#erc").validate({
                    ignore: ".ignore, .owner_req"
                })
            }
        })

        $('#save_as_draft').on('click', function (e) {
            var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length)
            if (ownerRowNumber === 0) {
                $('#organization_type').focus()
                $('#owner_save').removeClass('btn-primary btn-white').addClass('btn-danger btn-red').focus()
                alert('Please add at least one owner!!')
                return false;
            }
        });
    })

    function ownerphoto(input) {

        if (input.files && input.files[0]) {
            var MatLogo = input.id;
            var counter = MatLogo.split("_")[2];
            var mime_type = input.files[0].type;
            var fileSize = input.files[0].size;
            if ((mime_type !== 'image/jpeg' || mime_type !== 'image/jpg' || mime_type !== 'image/png') && (fileSize > 200000)) {
                alert('File size cannot be over 200 KB and file extension should be only jpg, jpeg and png');
                $("#" + MatLogo).val('');
                return false;
            } else {
                var reader = new FileReader();
                reader.onload = function (e) {

                    if (!isNaN(counter)) {
                        var owner_photo_viewer = "owner_photo_viewer_" + counter;
                        $("#" + owner_photo_viewer).attr('src', e.target.result);
                    } else {
                        $("#owner_photo_viewer").attr('src', e.target.result);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    }


    $(document).ready(function () {
        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            useCurrent: false
        });

        $('.datepickerall').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 100),
            useCurrent: false
        });


        /* Date must should be minimum today */
        $('.currentDate').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            useCurrent: false
        });

    });

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

    $(document).ready(function () {

        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "agent-id",
                value: "{{$agent}}"
            },
        ];

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/erc/ccie/get-refresh-token';
            $('#division').keydown();
            // $("#district").trigger('change');
            $('#bank_name').keydown();
            $('#organization_type').keydown();
            $('#country').keydown();
            $('#district_name').keydown();
            $('#quantity_type').keydown();
            $('#ypc_unit').keydown();
            $('#hypc_unit').keydown();
            $('#item_type').keydown();
            $('#share_type').keydown();
            $('#erc_slab').keydown();
            $('#association_name').keydown();
        });
        $("#division").on("keydown", function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/division";

            var selected_value = '{{isset($appData->division) ? $appData->division : ''}}';  // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "division_id"; //dynamic id for callback
            var element_name = "division_name"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $("#division").on("change", function () {
            // alert('ss');
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            var division = $('#division').val();
            var divisionId = division.split("@")[0];
            if (divisionId) {
                var e = $(this);
                var api_url = "{{$ccie_service_url}}/info/district" + '/' + divisionId;
                var selected_value = '{{isset($appData->district) ? $appData->district : ''}}'; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "district"; // for callback
                var element_id = "district_id"; //dynamic id for callback
                var element_name = "district_name"; //dynamic name for callback
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, callbackResponseDivision, arrays);

            } else {
                $("#district").html('<option value="">Select Division First</option>');
                $(self).next().hide();
            }

        });
        $("#district").on("change", function () {
            $('#police_station').select2('destroy');
            var self = $(this);
            $(self).next().hide();

            var district = $('#district').val();
            if (district !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                var districtId = district.split("@")[0];
                if (districtId) {
                    var e = $(this);
                    var api_url = "{{$ccie_service_url}}/info/thana/" + districtId;
                    var selected_value = '{{isset($appData->police_station) ? $appData->police_station : ''}}'; // for callback
                    var calling_id = $(this).attr('id');
                    var dependent_section_id = "police_station"; // for callback
                    var element_id = "police_station_id"; //dynamic id for callback
                    var element_name = "police_station_name_en"; //dynamic name for callback
                    var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                    var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


                    apiCallGet(e, options, apiHeaders, callbackResponseDependantSelect, arrays);

                } else {
                    $("#district").html('<option value="">Select Division First</option>');
                    $(self).next().hide();
                }

            }
        });

        $('#bank_name').on("keydown", function (el) {
            //alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            // $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/bank";

            var selected_value = '{{isset($appData->bank_name) ? $appData->bank_name : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "bank_id"; //dynamic id for callback
            var element_name = "bank_name_en"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $("#bank_name").on("change", function () {
            // alert('ss');
            var self = $(this);
            $(self).next().hide();
            // $(this).after('<span class="loading_data">Loading...</span>');
            var bank_name = $('#bank_name').val();
            var bank_name_id = bank_name.split("@")[0];
            if (bank_name_id) {
                var e = $(this);
                var api_url = "{{$ccie_service_url}}/info/bank-branch/" + bank_name_id;
                var selected_value = '{{isset($appData->branch_no) ? $appData->branch_no : ''}}'; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "branch_name"; // for callback
                var element_id = "branch_id"; //dynamic id for callback
                var element_name = "branch_name_en"; //dynamic name for callback
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


                apiCallGet(e, options, apiHeaders, callbackResponseDependantSelect, arrays);

            } else {
                $("#branch_name").html('<option value="">Select Bank First</option>');
                $(self).next().hide();
            }

        });
        $('#organization_type').on("keydown", function (el) {
            //alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/owner-type";

            var selected_value = '{{isset($appData->organization_type) ? $appData->organization_type : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "type"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $('#organization_type').change(function () {
            var type = $(this).val().split('@')[0];
            var _token = $('input[name="_token"]').val();
            var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}';


            if (type != '' && type != 0) {

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$ccie_service_url}}/erc/ownership-wise-designation/type/"+type+"/sub-service-id/55";

                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var dependent_section_id = "designation"; // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "fullname_en"; //dynamic name for callback
                var data = '';
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                apiCallGet(e, options, apiHeaders, callbackDesignation, arrays);
            }

            if (type == 'P') {
                $('#incorpornum').addClass('hidden');
                $('#incorporation').addClass('hidden');
                $('#registrationnum').addClass('hidden');
                $('#registration').addClass('hidden');
                $('#add_owner').addClass('hidden');
                $('#personal').removeClass('hidden');
                $('#owner').removeClass('hidden');
                $('#addowner').removeClass('hidden');

                var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length);
                if (ownerRowNumber > 1) {
                    $('#owner_details table tbody').html('');
                } else if (ownerRowNumber == 1) {
                    $('#ow_save').hide();
                }

            } else if (type == 'S') {
                $('#incorpornum').addClass('hidden');
                $('#incorporation').addClass('hidden');
                $('#registrationnum').removeClass('hidden');
                $('#registration').removeClass('hidden');
                $('#personal').removeClass('hidden');
                $('#owner').removeClass('hidden');
                $('#add_owner').removeClass('hidden');
                $('#addowner').removeClass('hidden');
                $('#ow_save').show();
            } else if (type == 'L') {
                $('#personal').removeClass('hidden');
                $('#owner').removeClass('hidden');
                $('#incorpornum').removeClass('hidden');
                $('#incorporation').removeClass('hidden');
                $('#registrationnum').addClass('hidden');
                $('#registration').addClass('hidden');
                $('#add_owner').addClass('hidden');
                $('#addowner').removeClass('hidden');
                $('#ow_save').show();
            } else {
                $('#incorpornum').addClass('hidden');
                $('#incorporation').addClass('hidden');
                $('#registrationnum').addClass('hidden');
                $('#registration').addClass('hidden');
                $('#personal').addClass('hidden');
                $('#owner').addClass('hidden');
                $('#addowner').removeClass('hidden');
                $('#ow_save').show();
            }

            if (type != '' && type != 0) {
                if (type) {
                    $.ajax({
                        type: "POST",
                        url: '/erc/get-dynamic-doc',
                        dataType: "json",
                        data: {
                            _token: _token,
                            type: type,
                            appId: appId
                        },
                        success: function (result) {
                            // console.log(result.responseCode);
                            $("#showDocumentDiv").html(result.data);

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $("#showDocumentDiv").html('');
                        },
                    });
                } else {
                    $("#showDocumentDiv").html('');

                }
            } else {
                $("#showDocumentDiv").html('');

            }
        });
        // $('#organization_type').trigger('change');
        $('#country').on("keydown", function (el) {
            //alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/country";

            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "country_id"; //dynamic id for callback
            var element_name = "country_name_en"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $('#district_name').on("keydown", function (el) {
            // alert('ss');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/district";
            var selected_value = '{{isset($appData->part_district_name) ? $appData->part_district_name : ''}}'; // for callback
            var calling_id = $(this).attr('id');
            var dependent_section_id = ""; // for callback
            var element_id = "district_id"; //dynamic id for callback
            var element_name = "district_name"; //dynamic name for callback
            var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);


        });
        $('#quantity_type').on("keydown", function (el) {
            // alert('ss');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/unit";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id');
            var dependent_section_id = ""; // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "unit_name"; //dynamic name for callback
            var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);


        });
        $('#ypc_unit').on("keydown", function (el) {
            // alert('ss');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/unit";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id');
            var dependent_section_id = ""; // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "unit_name"; //dynamic name for callback
            var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);


        });
        $('#hypc_unit').on("keydown", function (el) {
            // alert('ss');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/unit";
            var selected_value = '{{isset($appData->hypc_unit) ? $appData->hypc_unit : ''}}'; // for callback
            var calling_id = $(this).attr('id');
            var dependent_section_id = ""; // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "unit_name"; //dynamic name for callback
            var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);


        });
        $('#item_type').on("keydown", function (el) {
            //alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/adhoc-list";

            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "type"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $('#share_type').on("keydown", function (el) {
            // alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/share-type";

            var selected_value = '{{isset($appData->share_type) ? $appData->share_type : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "type"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, shareTypecallbackResponse, arrays);


        });

        $('#erc_slab').on("keydown", function (el) {
            // alert('ss');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/erc/slab/55";
            var selected_value = '{{isset($appData->erc_slab) ? $appData->erc_slab : ''}}'; // for callback
            var calling_id = $(this).attr('id');
            var dependent_section_id = ""; // for callback
            var element_id = "fees_id"; //dynamic id for callback
            var element_name = "max_price_limit"; //dynamic name for callback
            var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
            var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


            apiCallGet(e, options, apiHeaders, ercSlabResponse, arrays);


        });
        $('#association_name').on("keydown", function (el) {
            //alert('kkk');
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$ccie_service_url}}/info/association";

            var selected_value = '{{isset($appData->association_name) ? $appData->association_name : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "code"; //dynamic id for callback
            var element_name = "association_name"; //dynamic name for callback
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback


            apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

        });
        $("#erc_slab").on("change", function () {
            // alert('ss');
            var self = $(this);
            $(self).next().hide();

            var erc_slab = $('#erc_slab').val();
            var erc_slab_id = erc_slab.split("@")[0];

            if (erc_slab_id) {
                var e = $(this);
                var api_url = "{{$ccie_service_url}}/info/fee/" + erc_slab_id;
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // TODO:: need to change depending id
                var element_id = "fees_id"; //dynamic id for callback
                var element_name = "max_price_limit"; //dynamic name for callback
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name];


                // apiCallGet(e, options, apiHeaders, feeCallbackResponseDependent, arrays);

            } else {
                $(self).next().hide();
            }

        });

    });

    $('#organization_tin').on('focusout', function () {
        var tin = $('#organization_tin').val();
        if (tin.toString().length !== 12) {
            $("#organization_tin").addClass("error");
        } else {
            $("#organization_tin").removeClass("error");
        }
    });

    $('#owner_tin').on('focusout', function () {
        var tin = $('#owner_tin').val();
        if (tin.toString().length !== 12) {
            $("#owner_tin").addClass("error");
        } else {
            $("#owner_tin").removeClass("error");
        }
    });


    function sum_total() {
        var sum = 0;
        $.each($(".total_price"), function () {
            sum += +$(this).val();
        });
        $("#grand_total").val(sum);
    }



    $(document).on('click', '#addowner', function () {
        var rowcount = $('#ownerTable tbody tr.ownerRow').length + 1;

        if (ownerSectionvalidate() === false) {
            return false
        }

        var nationality = $('#nationality').val();
        var owner_nid_or_passport = '';
        if ($('#owner_nid_or_passport').val() != '') {
            owner_nid_or_passport = $('#owner_nid_or_passport').val();
        }
        var owner_name = $('#owner_name').val();
        var owner_father_name = $('#owner_father_name').val();
        var phone_number_office = $('#phone_number_office').val();
        var present_address = $('#present_address').val();
        var passport_no = $('#passport_no').val();
        var passport_expired_date = $('#passport_expired_date').val();
        var incorporation_number = $('#incorporation_number').val();
        var registration_number = $('#registration_number').val();
        var owner_tin = $('#owner_tin').val();
        var designation = $("#designation").val();
        var designation_val = designation.split('@')[1];
        var mother_name = $('#mother_name').val();
        var permanent_address = $('#permanent_address').val();
        var incorporation_date = $('#incorporation_date').val();
        var mobile = $('#mobile').val();
        var country = $('#country').val();
        var registration_date = $('#registration_date').val();
        var passport_issuing_country = $('#country').val();
        var owner_photo = $("#owner_photo_viewer").attr("src")
        var district = $('#district_name').val();
        var district_val = '';
        if (district != null) {
            district_val = district.split('@')[1];
        }

        var html = '<tr class="ownerRow" data-id="' + rowcount + '" id="owner_row_id_' + rowcount + '">' +
            '<td><input id="owner_name_' + rowcount + '" name="owner_name[]" value="' + owner_name + '" hidden >' + owner_name + '</td>' +
            '<td style="display:none;"><input id="nationality_' + rowcount + '" name="nationality[]" value="' + nationality + '" hidden ></td>' +
            '<td style="display:none;"><input id="owner_father_name_' + rowcount + '" name="owner_father_name[]" value="' + owner_father_name + '" hidden ></td>' +
            '<td style="display:none;"><input id="passport_no_' + rowcount + '" name="passport_no[]" value="' + passport_no + '" hidden ></td>' +
            '<td style="display:none;"><input id="passport_expired_date_' + rowcount + '" name="passport_expired_date[]" value="' + passport_expired_date + '" hidden ></td>' +
            '<td style="display:none;"><input id="incorporation_number_' + rowcount + '" name="incorporation_number[]" value="' + incorporation_number + '" hidden ></td>' +
            '<td style="display:none;"><input id="registration_number_' + rowcount + '" name="registration_number[]" value="' + registration_number + '" hidden ></td>' +
            '<td style="display:none;"><input id="mother_name_' + rowcount + '" name="mother_name[]" value="' + mother_name + '" hidden > < /td>' +
            '<td style="display:none;"><input id="permanent_address_' + rowcount + '" name="permanent_address[]" value="' + permanent_address + '" hidden ></td>' +
            '<td style="display:none;"><input id="incorporation_date_' + rowcount + '" name="incorporation_date[]" value="' + incorporation_date + '" hidden ></td>' +
            '<td style="display:none;"><input id="country_' + rowcount + '" name="country[]" value="' + country + '" hidden ></td>' +
            '<td style="display:none;"><input id="passport_issuing_country_' + rowcount + '" name="passport_issuing_country[]" value="' + passport_issuing_country + '" hidden ></td>' +
            '<td style="display:none;"><input id="registration_date_' + rowcount + '" name="registration_date[]" value="' + registration_date + '" hidden ></td>' +
            '<td><input id="owner_tin_' + rowcount + '" name="owner_tin[]" value="' + owner_tin + '" hidden >' + owner_tin + '</td>' +
            '<td><input id="owner_nid_or_passport_' + rowcount + '" name="owner_nid_or_passport[]" value="' + owner_nid_or_passport + '" hidden >' + owner_nid_or_passport + '</td>' +
            '<td><input id="designation_' + rowcount + '" name="designation[]" value="' + designation + '" hidden >' + designation_val + '</td>' +
            '<td><input id="mobile_' + rowcount + '" name="mobile[]" value="' + mobile + '" hidden >' + mobile + '</td>' +
            '<td><input id="phone_number_office_' + rowcount + '" name="phone_number_office[]" value="' + phone_number_office + '" hidden >' + phone_number_office + '</td>' +
            '<td><input id="present_address_' + rowcount + '" name="present_address[]" value="' + present_address + '" hidden >' + present_address + '</td>' +
            '<td><input id="district_name_' + rowcount + '" name="district_name[]" value="' + district + '" hidden >' + district_val + '</td>' +
            '<td><input id="owner_photo_' + rowcount + '" name="owner_photo[]" value="' + owner_photo + '" hidden ><img src="' + owner_photo + '" height="80px"/></td>' +
            '<td><button class="btn btn-minier btn-danger ownership" type="button"><i class="fa fa-trash"></i> Delete </button></td>' +
            '</tr>';
        $('#owner_details table').append(html);
        $('#owner_details table').show();

        $('#nationality option:contains("Select one")').prop('selected', true);
        $('#owner_tin').val('');
        $('#material_image').val('');
        $("#owner_photo_viewer").attr("src", "{{(url('assets/images/no-image.png'))}}");
        $('#owner_nid_or_passport').val('');
        $('#owner_name').val('');
        $('#owner_father_name').val('');
        $('#phone_number_office').val('');
        $('#present_address').val('');
        $('#passport_no').val('');
        $('#passport_expired_date').val('');
        $('#incorporation_number').val('');
        $('#registration_number').val('');
        $('#owner_tin').val();
        $('#designation option:contains("Select one")').prop('selected', true);
        $('#mother_name').val('');
        $('#permanent_address').val('');
        $('#incorporation_date').val('');
        $('#mobile').val('');
        $('#country').val('');
        $('#registration_date').val('');
        $("#owner_photo_viewer").attr("src");
        $('#district_name').val('');
        $('#passport_issuing_country').val('');

        $('.owner_req').each(function () {
            $(this).removeClass('error')
            $('#owner_image').removeClass('error')
        })

        var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length);

        if (ownerRowNumber === 1) {
            $('#owner_save').addClass('btn-primary btn-white').removeClass('btn-danger btn-red')
        }
        var organization_type_for_oner = $('#organization_type').val();
        if (organization_type_for_oner === 'P@Proprietorship' && ownerRowNumber === 1) {
            $('#ow_save').hide();
        }

    });

    function editOwnership(key) {
        $('#country').select2('destroy');
        $('#district_name').select2('destroy');
        $('#owner_edit').show();
        $('#ow_save').hide();
        var appId = "{{$appId}}";

        $.ajax({
            type: "get",
            url: "<?php echo url('/erc/getData'); ?>" + '/' + appId,
            data: {},
            success: function (response) {
                $('#owner_name').val(response.owner_name[key]);
                $('#nationality').val(response.nationality[key]);
                $('#nationality').trigger('change');

                $('#owner_tin').val(response.owner_tin[key]);
                $('#owner_nid_or_passport').val(response.owner_nid_or_passport[key]);
                $('#owner_father_name').val(response.owner_father_name[key]);
                $('#phone_number_office').val(response.phone_number_office[key]);
                $('#present_address').val(response.present_address[key]);
                $('#passport_no').val(response.passport_no[key]);
                $('#passport_expired_date').val(response.passport_expired_date[key]);
                $('#incorporation_number').val(response.incorporation_number[key]);
                $('#registration_number').val(response.registration_number[key]);
                $('#designation').val(response.designation[key]);
                $('#mother_name').val(response.mother_name[key]);

                $("#owner_photo_viewer").attr("src", response.owner_photo[key]);
                $('#permanent_address').val(response.permanent_address[key]);
                $('#incorporation_date').val(response.incorporation_date[key]);
                $('#mobile').val(response.mobile[key]);
                $('#country').val(response.country[key]);
                $('#registration_date').val(response.registration_date[key]);
                $('#district_name').val(response.district_name[key]);
                $('#arraykey').val(key);
                $('#country').select2();
                $('#district_name').select2();

            }

        });

    }

    $(document).on('click', '.editJqueryRow', function () {
        $('#owner_edit').show();
        $('#ow_save').hide();
        var id = $(this).closest('tr').attr('data-id')
        $('#arraykey').val(id);
        var owner_name = $("#owner_name_" + id).val();
        var owner_father_name = $("#owner_father_name_" + id).val();
        var nationality = $("#nationality_" + id).val();
        var passport_no = $("#passport_no_" + id).val();
        var passport_expired_date = $("#passport_expired_date_" + id).val();
        var incorporation_number = $("#incorporation_number_" + id).val();
        var registration_number = $("#registration_number_" + id).val();
        var mother_name = $("#mother_name_" + id).val();
        var incorporation_date = $("#incorporation_date_" + id).val();
        var country = $("#country_" + id).val();
        var permanent_address = $("#permanent_address_" + id).val();
        var passport_issuing_country = $("#passport_issuing_country_" + id).val();
        var registration_date = $("#registration_date_" + id).val();
        var owner_tin = $("#owner_tin_" + id).val();
        var owner_nid_or_passport = $("#owner_nid_or_passport_" + id).val();
        var designation = $("#designation_" + id).val();
        var mobile = $("#mobile_" + id).val();
        var phone_number_office = $("#phone_number_office_" + id).val();
        var present_address = $("#present_address_" + id).val();
        var district_name = $("#district_name_" + id).val();
        var owner_photo = $("#owner_photo_" + id).val();
        $('#nationality').val(nationality);
        $('#nationality').trigger('change');
        $('#owner_tin').val();
        $("#owner_photo_viewer").attr("src", "{{(url('assets/images/no-image.png'))}}");
        $('#owner_nid_or_passport').val(owner_nid_or_passport);
        $('#owner_name').val(owner_name);
        $('#owner_father_name').val(owner_father_name);
        $('#phone_number_office').val(phone_number_office);
        $('#present_address').val(present_address);
        $('#passport_no').val(passport_no);
        $('#passport_expired_date').val(passport_expired_date);
        $('#incorporation_number').val(incorporation_number);
        $('#registration_number').val(registration_number);
        $('#owner_tin').val(owner_tin);
        $('#designation').val(designation);
        $('#mother_name').val(mother_name);
        $('#permanent_address').val(permanent_address);
        $('#incorporation_date').val(incorporation_date);
        $('#mobile').val(mobile);
        $('#country').val(country);
        $('#registration_date').val(registration_date);
        $("#owner_photo_viewer").attr("src", owner_photo);
        $('#district_name').val(district_name);
        $('#passport_issuing_country').val(passport_issuing_country);


    });


    $(document).on('click', '#owner_edit', function () {
        $('#owner_edit').hide();
        var organization_type_for_oner = $('#organization_type').val();
        var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length);
        if (organization_type_for_oner === 'P@Proprietorship' && ownerRowNumber === 1) {
            $('#ow_save').hide();
        } else {
            $('#ow_save').show();
        }
        var tr_id = $('#arraykey').val();
        $("#addowner").trigger('click');
        $('#owner_row_id_' + tr_id).remove();
        return false;
    });

    $(document).on('click', 'button.ownership', function () {

        $(this).closest('tr').remove();
        var count = $('#ownerTable tbody tr').length;
        if (count == 0) {
            $("#owner_details table").hide();
            $("#addowner table").hide();
            $('#ow_save').show();
        }

        return false;
    });

    $('#nationality').change(function () {
        var type = $(this).val();
        if (type == 'B') {
            $('#passport_expired_date_div').addClass('hidden');
            $('#passport_no_div').addClass('hidden');
            $('#passport_issuing_country').addClass('hidden');
            $('#owner_nid_or_pass').removeClass('hidden');
            $('#part_district_name').removeClass('hidden');
        } else if (type == 'F') {
            $('#passport_expired_date_div').removeClass('hidden');
            $('#passport_no_div').removeClass('hidden');
            $('#passport_issuing_country').removeClass('hidden');
            $('#owner_nid_or_pass').addClass('hidden');
            $('#part_district_name').addClass('hidden');
        } else {
            $('#passport_expired_date_div').addClass('hidden');
            $('#passport_no_div').addClass('hidden');
            $('#passport_issuing_country').addClass('hidden');
        }
    });

    $("#nationality").trigger('change');

    $('#share_type').change(function () {
        var type = $(this).val();

        if (type != null) {
            var share_type = type.split('@')[0];
            if (share_type == 'D') {
                $('#doestic').show();
                $('#foreign').hide();
                $("#foreign_share").prop('disabled', true);
                $("#domestic_share").prop('disabled', false);

            } else if (share_type == 'F') {
                $('#foreign').show();
                $('#doestic').hide();
                $("#domestic_share").prop('disabled', true);
                $("#foreign_share").prop('disabled', false);
            } else {
                $('#doestic').hide();
                $('#foreign').hide();
            }
        }
    });
    $("#share_type").trigger('change');


    function independantcallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {

        var option = '<option value="">Select One</option>';
        //alert('dd');
        if (response.responseCode === 200) {
            //console.log(response);
            $.each(response.data, function (key, row) {
                //console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();

        $("#" + calling_id).trigger('change');

        //$(document).trigger(self.MY_FUNCTION);

    }

    function ercSlabResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        //alert('dd');
        var changeStatus = 0;
        if (response.responseCode === 200) {
            //console.log(response);
            $.each(response.data.data, function (key, row) {
                //console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id) {
                    changeStatus = 1;
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        if(changeStatus == 1){
            $("#" + calling_id).trigger('change');
        }
        $("#" + calling_id).next().hide();

    }

    function ownerSectionvalidate() {
        var organization_type_for_oner = $('#organization_type').val();
        var organization_type_val = organization_type_for_oner.split('@')[0];

        if (organization_type_val !== '') {
            var noOfError = 0;
            var return_val = false
            $('.owner_req').each(function () {
                if ($(this).val() == '') {
                    $(this).addClass('error')
                    return_val = false;
                } else {
                    $(this).removeClass('error')
                    return_val = true;
                }
            })
            if ($('#owner_photo_viewer').prop('src') == '{{ URL::asset('assets/images/no-image.png') }}') {
                $('#owner_image').addClass('error')
                return_val = false;
                noOfError +=1;
            } else {
                $('#owner_image').removeClass('error')
                return_val = true
            }
            var tin = $('#owner_tin').val();
            if (tin.toString().length !== 12) {
                $("#owner_tin").addClass("error")
                return_val = false;
                noOfError +=1;
            } else {
                $("#owner_tin").removeClass("error")
                return_val = true
            }
            if ($("#designation").val() == '') {
                $("#designation").addClass('error')
                noOfError +=1;
                return_val = false;
            } else {
                $("#designation").removeClass('error')
                return_val = true;
            }
            if(noOfError>0){
                return_val = false;
            }
            if (return_val === false) {
                $('#organization_type').focus()
            }
            return return_val;

        }

    }

    function shareTypecallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        //alert('dd');
        if (response.responseCode === 200) {
            //console.log(response);
            $.each(response.data, function (key, row) {
                //console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                //alert(selected_value);
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $("#share_type").trigger('change');

    }

    function callbackResponseDivision(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        // console.log(response.data);
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
        $("#" + dependent_section_id).html(option);
        $("#" + calling_id).next().hide();
        $('#district').trigger('change');

    }

    function callbackResponseDependantSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        // console.log(response.data);
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
        $("#" + dependent_section_id).html(option);
        $("#" + calling_id).next().hide();
        $('.search-box').select2();
        // $('#' + calling_id).trigger('change');
    }

    function callbackDesignation(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        console.log(selected_value);
        if (response.responseCode === 200) {
            $.each(response.data.data, function (key, row) {
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
        $("#" + dependent_section_id).html(option);
        //alert(dependent_section_id);
        $("#" + calling_id).next().hide();
    }

    function feeCallbackResponseDependent(response, [calling_id, selected_value, element_id, element_name]) {

        if (response.responseCode === 200) {
            var html = '<td>' + response.data.max_price_limit + '</td>' +
                '<td>' + response.data.registration_book + '</td>' +
                '<td>' + response.data.primary_reg_fee + '</td>';
            //console.log(html);
            $('#slab_data').find('td').remove();
            $('#slab_data').append(html);
        } else {
            console.log(response.status)
        }
    }

    function uploadDocument(targets, id, vField, isRequired) {
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
            var action = "{{URL::to('/erc/upload-document')}}";
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
                    $('#' + id).removeClass('required');
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

    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = '';
            var isReq = $('#' + abc).attr('data-required')
            if (isReq == 'required') {
                $('#' + abc).addClass('required error')
            }
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
        } else {
            return false;
        }
    });


    $(document).ready(function () {
        $("#domesticshare").blur(function () {
            var share = $(this).val();
            var total = 100 - parseInt(share);
            if (share > 100) {
                $('#domesticshare').val('');
                $('#domesticshare').addClass('error');
            } else {
                $('#foreignshare').val(total);
                $('#domesticshare').removeClass('error');
            }
        })
    });

    $(document).ready(function () {
        $("#foreignshare").blur(function () {
            var share = $(this).val();
            var total = 100 - parseInt(share);
            if (share > 100) {
                $('#foreignshare').val('');
                $('#foreignshare').addClass('error');
            } else {
                $('#domesticshare').val(total);
                $('#foreignshare').removeClass('error')
            }
        });

        var organization_type_for_oner = $('#organization_type').val();
        var ownerRowNumber = parseInt($('#ownerTable tbody tr.ownerRow').length);
        if (organization_type_for_oner === 'P@Proprietorship' && ownerRowNumber === 1) {
            $('#ow_save').hide();
        }

    });


</script>