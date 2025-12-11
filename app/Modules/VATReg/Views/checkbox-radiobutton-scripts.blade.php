<script>
    $(document).ready(function () {
        token = "{{$token}}";
        var tokenUrl = '/vat-registration/get-refresh-token';

        $(function () {
            $('#reg_cagegory_div').keydown();
            $('#ownership_type_div').keydown();
            $('#withholding_entity_div').keydown();
            $('#registration_type_div').keydown();
            $('#equity_info_div').keydown();
            $('#economic_activity_div').keydown();
        });

        $('#reg_cagegory_div').on('keydown', function (el) {
            var e = $(this);
            var api_url = "{{$vat_service_url}}/registration-category";
            var selected_value = ''; // for callback
            var calling_id = "reg_cagegory_div"; // for callback
            var filedtype = "radio";
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var inputname = "reg_category";
            var clickevent = "checkRegCategory(this.value)";
            var inputClasses = "reg_category required";
            var labelClasses = "radio-inline";
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]; // for callback
            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, regcategorycallbackResponse, arrays);

        });


        $('#ownership_type_div').on('keydown', function (el) {
            var e = $(this);
            var api_url = "{{$vat_service_url}}/ownership";
            var selected_value = ''; // for callback
            var calling_id = "ownership_type_div"; // for callback
            var filedtype = "checkbox";
            var element_id = "IND_SECTOR"; //dynamic id for callback
            var element_name = "TEXT"; //dynamic name for callback
            var inputname = "ownership_type[]";
            var clickevent = "";
            var inputClasses = "required";
            var labelClasses = "col-md-4";
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]; // for callback
            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];
            apiCallGet(e, options, apiHeaders, ownershipcallbackResponse, arrays);

        });

        $('#withholding_entity_div').on('keydown', function (el) {
            var e = $(this);
            var api_url = "{{$vat_service_url}}/withholding-entity";
            var selected_value = ''; // for callback
            var calling_id = "withholding_entity_div"; // for callback
            var filedtype = "radio";
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var inputname = "withholding_entity";
            var clickevent = "";
            var inputClasses = "required withholding_entity";
            var labelClasses = "radio-inline";
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]; // for callback
            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];
            apiCallGet(e, options, apiHeaders, registrationcallbackResponse, arrays);

        });


        $('#registration_type_div').on('keydown', function (el) {
            var e = $(this);
            var api_url = "{{$vat_service_url}}/registration-type";
            var selected_value = ''; // for callback
            var calling_id = "registration_type_div"; // for callback
            var filedtype = "radio";
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var inputname = "registration_type";
            var clickevent = "";
            var inputClasses = "required registration_type";
            var labelClasses = "radio-inline";
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]; // for callback
            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];
            apiCallGet(e, options, apiHeaders, registrationcallbackResponse, arrays);

        });

        $('#equity_info_div').on('keydown', function (el) {
            var e = $(this);
            var api_url = "{{$vat_service_url}}/equity-info";
            var selected_value = ''; // for callback
            var calling_id = "equity_info_div"; // for callback
            var filedtype = "radio";
            var element_id = "CODE"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
            var inputname = "equity_info";
            var clickevent = "checkSharePercentage(this.value)";
            var inputClasses = "required";
            var labelClasses = "radio-inline";
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]; // for callback
            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];
            apiCallGet(e, options, apiHeaders, registrationcallbackResponse, arrays);

        });


        $('#manufacturing_area_checkboxes').on('keydown', function (el) {
            var e = $(this);
            var api_url = "{{$vat_service_url}}/manufacturing-area";
            var selected_value = ''; // for callback
            var calling_id = "manufacturing_area_checkboxes"; // for callback
            var filedtype = "checkbox";
            var element_id = "IND_SECTOR"; //dynamic id for callback
            var element_name = "TEXT"; //dynamic name for callback
            var inputname = "economic_area[]";
            var clickevent = "";
            var inputClasses = "required";
            var labelClasses = "col-md-4";
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]; // for callback
            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];
            apiCallGet(e, options, apiHeaders, manufactureareacallbackResponse, arrays);

        });

        $('#services_div_checkboxes').on('keydown', function (el) {
            var e = $(this);
            var api_url = "{{$vat_service_url}}/service-area";
            var selected_value = ''; // for callback
            var calling_id = "services_div_checkboxes"; // for callback
            var filedtype = "checkbox";
            var element_id = "IND_SECTOR"; //dynamic id for callback
            var element_name = "TEXT"; //dynamic name for callback
            var inputname = "area_service[]";
            var clickevent = "";
            var inputClasses = "required";
            var labelClasses = "col-md-4";
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]; // for callback
            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];
            apiCallGet(e, options, apiHeaders, serviceareacallbackResponse, arrays);

        });

        $('#economic_activity_div').on('keydown', function (el) {
            var e = $(this);
            var api_url = "{{$vat_service_url}}/economic-activity-area";
            var selected_value = ''; // for callback
            var calling_id = "economic_activity_div"; // for callback
            var filedtype = "checkbox";
            var element_id = "IND_SECTOR"; //dynamic id for callback
            var element_name = "TEXT"; //dynamic name for callback
            var inputname = "economic_activity[]";
            var clickevent = "";
            var inputClasses = "required";
            var labelClasses = "col-md-4";
            var data = '';
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]; // for callback
            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];
            apiCallGet(e, options, apiHeaders, economicAreaCallbackResponse, arrays);

        });


        function regcategorycallbackResponse(response, [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]) {
            var option = '';
            if (response.responseCode === 200) {
                $.each(response.data, function (key, row) {
                    // console.log(response.data);
                    var id = row[element_id] + '@' + row[element_name];
                    var value = row[element_name];
                    if (selected_value == id) {
                        option += '<label  class="' + labelClasses + '"><input class="helpTextCheckbox ' + inputClasses + '" id="category_' + row[element_id] + '"   onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '">' + row[element_name] + '</label>';
                    } else {
                        option += '<label  class="' + labelClasses + '"><input class="helpTextCheckbox ' + inputClasses + '"  id="category_' + row[element_id] + '" onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '">' + row[element_name] + '</label>';
                    }
                });
            }

            $("#" + calling_id).html(option);
            $("#" + calling_id).next().hide();
        }

        function ownershipcallbackResponse(response, [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]) {
            var option = '';

            if (response.responseCode === 200) {
                var i = 0;
                $.each(response.data, function (key, row) {
                    var id = row[element_id] + '@' + row[element_name];
                    var value = row[element_name];
                    var elementid = row[element_id];
                    var inputid = "";
                    if (elementid == "01") {
                        clickevent = "";
                        inputClasses = "limited business";
                        inputid = ""
                    } else if (elementid == "02") {
                        clickevent = "";
                        inputClasses = "limited business";
                    } else if (elementid == "03") {
                        clickevent = "BusinessFunction(this)";
                        inputClasses = "business";
                    } else if (elementid == "04") {
                        clickevent = "BusinessFunction(this)";
                        inputClasses = "business";
                    } else if (elementid == "05") {
                        clickevent = "";
                        inputClasses = "limited business";
                    } else if (elementid == "06") {
                        clickevent = "";
                        inputClasses = "limited business";
                    } else if (elementid == "07") {
                        clickevent = "";
                        inputClasses = "business";
                        inputid = "government"
                    } else if (elementid == "08") {
                        clickevent = "";
                        inputClasses = "business";
                        inputid = "ngo"
                    } else if (elementid == "09") {
                        clickevent = "";
                        inputClasses = "limited business";
                    } else if (elementid == "10") {
                        clickevent = "BusinessOtherFunction(this)";
                        inputClasses = "business";
                        inputid = "business_others";
                    } else {
                        clickevent = "";
                        inputClasses = "";
                    }
                    inputClasses = inputClasses + ' required'
                    var index = (i + 10).toString(36);
                    if (selected_value == id.split('@')[0]) {
                        option += '<label  class="' + labelClasses + '"><input class="helpTextCheckbox ' + inputClasses + '" id="' + inputid + '"   onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '"> (' + index + ') ' + row[element_name] + '</label>';
                    } else {
                        option += '<label  class="' + labelClasses + '"><input class="helpTextCheckbox ' + inputClasses + '"  id="' + inputid + '" onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '"> (' + index + ') ' + row[element_name] + '</label>';
                    }
                    i = i + 1;
                });

            }

            $("#" + calling_id).html(option);
            $("#" + calling_id).next().hide();
        }

        function registrationcallbackResponse(response, [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]) {
            var option = '';
            if (response.responseCode === 200) {
                $.each(response.data, function (key, row) {
                    var id = row[element_id] + '@' + row[element_name];
                    var value = row[element_name];
                    var elementid = row[element_id];
                    if (selected_value == id.split('@')[0]) {
                        option += '<label  class="' + labelClasses + '"><input class="' + inputClasses + '"   onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '">' + row[element_name] + '</label>';
                    } else {
                        option += '<label  class="' + labelClasses + '"><input class="' + inputClasses + '"  onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '">' + row[element_name] + '</label>';
                    }
                });
            }

            $("#" + calling_id).html(option);
            $("#" + calling_id).next().hide();
        }

        function serviceareacallbackResponse(response, [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]) {
            var option = '';
            if (response.responseCode === 200) {
                $.each(response.data, function (key, row) {
                    var id = row[element_id] + '@' + row[element_name];
                    var value = row[element_name];
                    var elementid = row[element_id];
                    if (elementid == "23") {
                        clickevent = "otherHFunction(this)";
                    } else {
                        clickevent = "";
                    }
                    var index = elementid.replace(/^0+/, '')
                    if (selected_value == id) {
                        option += '<label  class="' + labelClasses + '"><input data-value="H' + index + '" class="' + inputClasses + ' service"   onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '">  H' + index + ' ' + row[element_name] + '</label>';
                    } else {
                        option += '<label  class="' + labelClasses + '"><input data-value="H' + index + '" class="' + inputClasses + ' service"    onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '"> H' + index + ' ' + row[element_name] + '</label>';
                    }
                });
            }

            $("#" + calling_id).html(option);
        }


        function manufactureareacallbackResponse(response, [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]) {
            var option = '';
            if (response.responseCode === 200) {
                $.each(response.data, function (key, row) {
                    var id = row[element_id] + '@' + row[element_name];
                    var value = row[element_name];
                    var elementid = row[element_id];
                    if (elementid == "22") {
                        clickevent = "otherGFunction(this)";
                    } else {
                        clickevent = "";
                    }
                    var index = elementid.replace(/^0+/, '')
                    if (selected_value == id) {
                        option += '<label  class="' + labelClasses + '"><input data-value="G' + index + '" class="' + inputClasses + ' manufacture"   onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '">  G' + index + ' ' + row[element_name] + '</label>';
                    } else {
                        option += '<label  class="' + labelClasses + '"><input data-value="G' + index + '" class="' + inputClasses + ' manufacture"    onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '"> G' + index + ' ' + row[element_name] + '</label>';
                    }
                });
            }

            $("#" + calling_id).html(option);
        }

        function economicAreaCallbackResponse(response, [calling_id, selected_value, element_id, element_name, filedtype, inputname, clickevent, inputClasses, labelClasses]) {
            var option = '';
            if (response.responseCode === 200) {
                $.each(response.data, function (key, row) {
                    var id = row[element_id] + '@' + row[element_name];
                    var value = row[element_name];
                    var elementid = row[element_id];
                    if (elementid == "01") {
                        clickevent = "manufacturingFunction(this)";
                    } else if (elementid == "02") {
                        clickevent = "ServicesFunction(this)";
                    } else if (elementid == "04") {
                        clickevent = "importsFunction(this)";
                    } else if (elementid == "05") {
                        clickevent = "exportsFunction(this)";
                    } else if (elementid == "06") {
                        clickevent = "otherFunction(this)";
                    } else {
                        clickevent = "";
                    }
                    var index = elementid.replace(/^0+/, '')
                    if (selected_value == id) {
                        option += '<label  class="' + labelClasses + '"><input id="economic_activate_' + elementid + '" class="helpTextCheckbox ' + inputClasses + '"   onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '">  F' + index + ' ' + row[element_name] + '</label>';
                    } else {
                        option += '<label  class="' + labelClasses + '"><input id="economic_activate_' + elementid + '" class="helpTextCheckbox ' + inputClasses + '"    onclick="' + clickevent + '" name="' + inputname + '" type="' + filedtype + '" value="' + id + '"> F' + index + ' ' + row[element_name] + '</label>';
                    }
                });
            }

            $("#" + calling_id).html(option);
        }


    });


</script>
