/* Function to allow numeric entries only with decimal points (.) */
$(document).ready(function () {
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
        }, 4);
    }).on('keyup', function (e) {
        var $this = $(this);
        setTimeout(function () {
            $this.val($this.val().replace(/[^0-9]/g, ''));
        }, 4);
    });
}); /***************Document ready ended***********/

/********************The functionalit to allow the applicants remove their attached documents***********************/
function makeBlank(id) {
    $('#validate_field_' + id).val('');
    $('.saved_file_' + id).html('');
    $('#other_doc_name_' + id).val('');
    //document.getElementById("other_doc_name_"+id).value='';
}

function ConfirmDeleteFile(id) {
    var sure_del = confirm("Are you sure you want to delete this file?");
    if (sure_del) {
        makeBlank(id);
    } else {
        return false;
    }
}

function makeBlank_value(id, doc_priority, required_Class = 0) {
    // alert(id)
    document.getElementById("validate_field_" + id).value = '';
    document.getElementById("file" + id).value = '';
    $('.saved_file_' + id).html('');
    $('.span_validate_field_' + id).html('');

    if (required_Class == 1 && doc_priority == 1) {
        $("#file" + id).addClass('travelFileRequired required');
    } else if (doc_priority == 1) {
        $("#file" + id).addClass('required');
    }
}

function EmptyFile(id, doc_priority, requiredClass = 0) {

    // define doc_priority as an optional parameter
    if (typeof doc_priority === 'undefined') {
        doc_priority = 0;
    }

    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(function (result) {
        if (result.value) {
            makeBlank_value(id, doc_priority, requiredClass);
            swal(
                'Deleted!',
                'Your file has been deleted.',
                'success'
            )
        } else {
            return false;
        }
    })

    // var sure_del = confirm("Are you sure you want to delete this file?");
    // if (sure_del) {
    //     makeBlank_value(id);
    // } else {
    //     return false;
    // }
}

/*****************End of the functionality to allow the applicants to remove their attached documents*******************/

/****************For showing tooltips in the function list page*******************/
function toolTipFunction() {
    $('[data-toggle="tooltip"]').tooltip();
}

function getCategory(old_visa_category_id) {
    // old_visa_category_id = old_visa_category_id || 0;
    // optional parameter define
    if (typeof old_visa_category_id === 'undefined') {
        old_visa_category_id = 0;
    }

    var DEPT_ID = $('#department_id').val(); // department id
    var SERVICE_ID = $('#SERVICE_ID').val(); // process type id
    var _token = $('input[name="_token"]').val();

    $.ajax({
        type: "POST",
        url: '/visa-recommendation/getCategory/byService',
        data: {_token: _token, DEPT_ID: DEPT_ID, SERVICE_ID: SERVICE_ID},
        dataType: "json",
        success: function (result) {
            html = '';
            if (result) {
                html += '<option value="">Select Category</option>';
                // $.each(result, function(index, value) {
                //     html += '<option value="' + value['TYPE_CTG_ID'] + '">' + value['TYPE_CTG_NAME'] + '</option>';
                // });

                $.each(result, function (id, value) {
                    if (id == old_visa_category_id) {
                        html += '<option value="' + id + '" selected>' + value + '</option>';
                    } else {
                        html += '<option value="' + id + '">' + value + '</option>';
                    }
                });
                $('#visa_category_id').html(html);
                // $("#visa_category_id").val(visaDept);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        },
        beforeSend: function (xhr) {
            //console.log('before send');
        },
        complete: function () {
            //completed
        }
    });
}

function setVisaType2(app_type_mapping_id, app_type_id) {
    $('input[name=agreeWithInstruction]').prop('checked', false);
    var _token = $('input[name="_token"]').val();
    var app_id = $("#app_id").val();
    var viewMode = $("#viewMode").val();

    // PI Type Visa
    if (app_type_id == 4) {
        $("#on_arrival_information_area").hide();
        $("#on_arrival_information_area1").hide();
        $(".oa_req_field").removeClass('required');
        $("#flight_details_area").hide();
        $(".fda_req_field").removeClass('required');

        $("#embassy_info_area").show();
        $(".embassy_info_req_field").addClass('required');

        $("#visit_purpose_div").hide();
        $("#visa_purpose_id").removeClass('required');

        $("#compensation_benefit_area").show();
        $("#particular_information").show();
        $(".cb_req_field").addClass('required');
        $(".basic_local_amount").attr("min", "1");

        $(".mp_req_field").addClass('required');

        $("#travel_history_area").show();
        $(".th_req_field").addClass('required');

        $("#embassy_airport_label").text("Embassy/ High Commission Info");
        $("#brief_job_description_div").show();
        $("#brief_job_description").addClass('required');

    }
    // if visa tye is on arrival
    else if (app_type_id == 5) {
        $("#compensation_benefit_area").hide();
        $("#particular_information").hide();
        $(".cb_req_field").removeClass('required');
        $(".basic_local_amount").removeAttr("min");

        $(".mp_req_field").removeClass('required');

        $("#travel_history_area").hide();
        $(".th_req_field").removeClass('required');

        $("#on_arrival_information_area").show();
        $("#on_arrival_information_area1").show();
        $(".oa_req_field").addClass('required');
        $("#flight_details_area").show();
        $(".fda_req_field").addClass('required');

        $("#embassy_info_area").hide();
        $(".embassy_info_req_field").removeClass('required');

        $("#visit_purpose_div").show();
        $("#visa_purpose_id").addClass('required');

        $("#embassy_airport_label").text("Airport Info");
        $("#brief_job_description_div").hide();
        $("#brief_job_description").removeClass('required');
    }
    // A3 Type Visa	or E1 Tye Visa
    else if (app_type_id == 1 || app_type_id == 3) {
        $("#compensation_benefit_area").show();
        $("#particular_information").show();
        $(".cb_req_field").removeClass('required');
        $(".basic_local_amount").attr("min", "1");

        $(".mp_req_field").addClass('required');
        $("#MP_LOC_EXECUTIVE").addClass('required');
        $("#MP_LOC_STAFF").addClass('required');
        $("#FOR_LOC_EXECUTIVE").addClass('required');
        $("#FOR_LOC_STAFF").addClass('required');

        $("#travel_history_area").show();
        $(".th_req_field").addClass('required');

        $("#on_arrival_information_area").hide();
        $("#on_arrival_information_area1").hide();
        $(".oa_req_field").removeClass('required');
        $("#flight_details_area").hide();
        $(".fda_req_field").removeClass('required');

        $("#embassy_info_area").show();
        $(".embassy_info_req_field").addClass('required');

        $("#visit_purpose_div").hide();
        $("#visa_purpose_id").removeClass('required');

        $("#embassy_airport_label").text("Embassy/ High Commission Info");
        $("#brief_job_description_div").show();
        $("#brief_job_description").addClass('required');
    } else {
        $("#compensation_benefit_area").show();
        $("#particular_information").show();
        $(".cb_req_field").addClass('required');
        $(".basic_local_amount").attr("min", "1");

        $(".mp_req_field").addClass('required');
        $("#MP_LOC_EXECUTIVE").addClass('required');
        $("#MP_LOC_STAFF").addClass('required');
        $("#FOR_LOC_EXECUTIVE").addClass('required');
        $("#FOR_LOC_STAFF").addClass('required');

        $("#travel_history_area").show();
        $(".th_req_field").addClass('required');

        $("#on_arrival_information_area").hide();
        $("#on_arrival_information_area1").hide();
        $(".oa_req_field").removeClass('required');
        $("#flight_details_area").hide();
        $(".fda_req_field").removeClass('required');

        $("#embassy_info_area").show();
        $(".embassy_info_req_field").addClass('required');

        $("#visit_purpose_div").hide();
        $("#visa_purpose_id").removeClass('required');

        $("#embassy_airport_label").text("Embassy/ High Commission Info");
        $("#brief_job_description_div").show();
        $("#brief_job_description").addClass('required');
    }

    $.ajax({
        type: "POST",
        url: '/visa-recommendation/getDocList',
        dataType: "json",
        data: {_token: _token, app_type_mapping_id: app_type_mapping_id, app_id: app_id, viewMode: viewMode},
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
}

function hideRows(visa_category_id) {
    if (visa_category_id == 15) {
        $('#hideableRow').show();
    } else {
        $('#hideableRow').hide();
    }
}

function getPurpose(visa_purpose_value) {
    if(visa_purpose_value == "3"){
        $("#PURPOSE_OTHERS").show("slow");
        $("#visa_purpose_others").addClass('required');
    }else{
        $("#PURPOSE_OTHERS").hide("slow");
        $("#visa_purpose_others").removeClass('required');
    }
}



function visaOnarrivalSought(on_arrival_value) {
    if (on_arrival_value == 6) {
        $("#VISA_ON_ARRIVAL_SOUGHT_OTHER_DIV").show('slow');
    } else {
        $("#VISA_ON_ARRIVAL_SOUGHT_OTHER_DIV").hide('slow');
    }
}

// Previous Travel history of the expatriate to Bangladesh section show and field validation
// Chain 1
function checkTravelHistory(travel_history) {
    if (travel_history == 'yes') {
        /* employment section show and required some field by class name */
        $("#travel_employment_period").show('slow');
        $(".visited_req_field").addClass('required');

        /* 'Type of visa availed' field trigger */
        $('.prev_tr_visa').each(function () {
            $(this).trigger('change');
        });

        /* 'Have you visited to Bangladesh with Employment Visa?' field trigger which is checked */
        $('input[name=th_visit_with_emp_visa]:checked').trigger('click');
    } else {
        /* employment section hide and required some field by class name */
        $("#travel_employment_period").hide('slow');
        $(".visited_req_field").removeClass('required');

        /* 'Have you received work permit from Bangladesh?'  hide and validation remove*/
        $("#visited_with_emp_visa").hide('slow');
        $('.visited_with_emp_visa_req').removeClass('required');

        /* 'Previous work permit information in Bangladesh' section hide and validation remove */
        $("#travel_employment").hide('slow');
        $(".travelFileRequired").removeClass('required');

        //In which period field reset
        $('.which-period').val("");

        //Have you visited to Bangladesh with Employment Visa? reset this value
        $('input[name=th_visit_with_emp_visa]:checked').prop('checked', false);

        //Have you received work permit from Bangladesh? reset this value
        $('input[name=th_emp_work_permit]:checked').prop('checked', false);

        //reset (Previous work permit information in Bangladesh) section all input value
        resetInputFieldValue(['th_emp_tin_no', 'th_emp_wp_no', 'th_emp_org_name', 'th_emp_org_address', 'th_org_district_id', 'th_org_thana_id', 'th_org_post_office', 'th_org_post_code', 'th_org_telephone_no', 'th_org_email']);

        //reset attachment
        $("#travelHistorydocListDiv").find('.reset-attachment').empty();
        $("#travelHistorydocListDiv").find('.upload-attachment').empty();
        $('#attachment_table').find('.save_file').empty();
    }
}

// Chain 2
function thVisitWithEmpVisa(visa_value) {
    if (visa_value == 'yes') {

        /* 'Have you received work permit from Bangladesh?' field show and required */
        $("#visited_with_emp_visa").show('slow');
        $('.visited_with_emp_visa_req').addClass('required');

        /* 'Have you received work permit from Bangladesh?' field trigger */
        $('input[name=th_emp_work_permit]:checked').trigger('click');
    } else {
        $("#visited_with_emp_visa").hide('slow');
        $('.visited_with_emp_visa_req').removeClass('required');

        /* 'Previous work permit information in Bangladesh' section hide and validation remove */
        $("#travel_employment").hide('slow');
        $(".travelFileRequired").removeClass('required');

        /* 'Have you received work permit from Bangladesh?' field reset */
        $('input[name=th_emp_work_permit]:checked').prop('checked', false);

        //reset (Previous work permit information in Bangladesh) section all input value
        resetInputFieldValue(['th_emp_tin_no', 'th_emp_wp_no', 'th_emp_org_name', 'th_emp_org_address', 'th_org_district_id', 'th_org_thana_id', 'th_org_post_office', 'th_org_post_code', 'th_org_telephone_no', 'th_org_email']);
        //reset attachment
        $("#travelHistorydocListDiv").find('.reset-attachment').empty();
        $("#travelHistorydocListDiv").find('.upload-attachment').empty();
        $('#attachment_table').find('.save_file').empty();
    }
}

// Previous work permit information in Bangladesh section show and required field
// Chain 3
function checkReceivedWorkPermit(travel_history) {
    if (travel_history == 'yes') {
        $("#travel_employment").show('slow');
        $(".travelFileRequired").addClass('required');
        $(".travelFileRequiredLabel").addClass('required-star');

        //load previous attachment
        loadPreviousTravelHistoryAttachment()
    } else if (travel_history == 'no') {
        $("#travel_employment").hide('slow');
        $(".travelFileRequired").removeClass('required');
        $(".travelFileRequiredLabel").removeClass('required-star');

        //reset (Previous work permit information in Bangladesh) section all input value
        resetInputFieldValue(['th_emp_tin_no', 'th_emp_wp_no', 'th_emp_org_name', 'th_emp_org_address', 'th_org_district_id', 'th_org_thana_id', 'th_org_post_office', 'th_org_post_code', 'th_org_telephone_no', 'th_org_email']);
        /* reset attachment*/
        $("#travelHistorydocListDiv").find('.reset-attachment').empty();
        $("#travelHistorydocListDiv").find('.upload-attachment').empty();
        $('#attachment_table').find('.save_file').empty();
    } else {
        $("#travel_employment").hide('slow');
        $(".travelFileRequired").removeClass('required');
        $(".travelFileRequiredLabel").removeClass('required-star');
        $(".reset-attachment").val("");
    }
}

function TravelHistoryVisaType(travel_visa_availed) {
    if (travel_visa_availed == 13) {
        $("#TRAVEL_VISA_OTHERS").show('slow');
    } else {
        $("#TRAVEL_VISA_OTHERS").hide('slow');
    }
}

function resetInputFieldValue(input_id) {
    $.each(input_id, function (index, value){
        $("#" + value).val("");
    });
}

function checkCourtesyService(courtesy_service) {
    if (courtesy_service == 'yes') {
        $("#courtesy_service_reason_div").show('slow');
    } else {
        $("#courtesy_service_reason_div").hide('slow');
    }
}

function setAddressForInsert(country_id) {
    if (country_id == '18') {
        $("#office_address_bd_div").show();
        $("#office_address_foreign_div").hide();
        $("#office_road_label").html('Road Name/ Road No.');
        // $("#office_address_bd_field").addClass('required');
        // $("#office_address_foreign_field").removeClass('required');
    } else {
        $("#office_address_bd_div").hide();
        $("#office_address_foreign_div").show();
        $("#office_road_label").html('Street Name/ Street No.');
        // $("#office_address_bd_field").removeClass('required');
        // $("#office_address_foreign_field").addClass('required');
    }
}

function getBranchByBankId(bank_id, bank_value, branch_div, old_data) {
    // define old_data as an optional parameter
    if (typeof old_data === 'undefined') {
        old_data = 0;
    }

    var _token = $('input[name="_token"]').val();
    if (bank_value !== '') {
        $("#" + bank_id).after('<span class="loading_data">Loading...</span>');
        $.ajax({
            type: "GET",
            url: "/users/get-branch-by-bank",
            data: {
                _token: _token,
                bankId: bank_value
            },
            success: function (response) {
                var option = '<option value="">Select One</option>';
                if (response.responseCode == 1) {
                    $.each(response.data, function (id, value) {
                        if (id == old_data) {
                            option += '<option value="' + id + '" selected>' + value + '</option>';
                        } else {
                            option += '<option value="' + id + '">' + value + '</option>';
                        }
                    });
                }
                $("#" + branch_div).html(option);
                $("#" + bank_id).next().hide('slow');
            }
        });
        $("#" + bank_id).next().hide('slow');
    }
}

function getDistrictByDivisionId(division_id, division_value, district_div, old_data) {
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
                        if (id == old_data) {
                            option += '<option value="' + id + '" selected>' + value + '</option>';
                        } else {
                            option += '<option value="' + id + '">' + value + '</option>';
                        }
                    });
                }
                $("#" + district_div).html(option);
                $("#" + division_id).next().hide('slow');
            }
        });
    } else {
        // console.log('Please select a valid district');
    }
}

function getThanaByDistrictId(district_id, district_value, thana_div, old_data) {
    // define old_data as an optional parameter
    if (typeof old_data === 'undefined') {
        old_data = 0;
    }

    var _token = $('input[name="_token"]').val();
    if (district_value !== '') {
        $("#" + district_id).after('<span class="loading_data">Loading...</span>');
        // $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
        $.ajax({
            type: "GET",
            url: "/users/get-thana-by-district-id",
            data: {
                _token: _token,
                districtId: district_value
            },
            success: function (response) {
                var option = '<option value="">Select One</option>';
                if (response.responseCode == 1) {
                    $.each(response.data, function (id, value) {
                        if (id == old_data) {
                            option += '<option value="' + id + '" selected>' + value + '</option>';
                        } else {
                            option += '<option value="' + id + '">' + value + '</option>';
                        }
                    });
                }
                $("#" + thana_div).html(option);
                $("#" + district_id).next().hide('slow');
            }
        });
    } else {
        // console.log('Please select a valid district');
    }
}

function getEmbassyByCountryId(country_field, embassy_id, old_data) {
    // define old_data as an optional parameter
    if (typeof old_data === 'undefined') {
        old_data = 0;
    }

    var _token = $('input[name="_token"]').val();
    var country_value = country_field.id;
    if (country_value !== '') {
        $("#" + country_field.id).after('<span class="loading_data">Loading Embassy/ High Commission...</span>');
        // $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
        $.ajax({
            type: "GET",
            url: "/visa-recommendation/get-embassy-by-country",
            data: {
                _token: _token,
                country_id: country_field.value
            },
            success: function (response) {
                var option = '<option value="">Select One</option>';
                if (response.responseCode == 1) {
                    $.each(response.data, function (id, value) {
                        if (id == old_data) {
                            option += '<option value="' + id + '" selected>' + value + '</option>';
                        } else {
                            option += '<option value="' + id + '">' + value + '</option>';
                        }
                    });
                }
                $("#" + embassy_id).html(option);
                $("#" + country_field.id).next().hide('slow');
            }
        });
    } else {
        // console.log('Please select a valid country');
    }
}

function getDesiredDurationAmount(process_id, startDate, endDate, duration_id, amount_id, year_id,
                                  is_from_board_meeting = '', approved_desired_duration = '', approved_duration_amount = '', approved_duration_year = '') {
    // define year_id as an optional parameter
    if (typeof year_id === 'undefined') {
        year_id = '';
    }

    var actualStartDate = new Date(startDate.replace(/-/g, ' ')); // convert to actual date
    var actualEndDate = new Date(endDate.replace(/-/g, ' ')); // convert to actual date

    if ((Date.parse(actualStartDate) > Date.parse(actualEndDate))) {
        $('#date_compare_error').text('Please enter here the valid start and end date.');
    } else {

        if (startDate != '' && endDate != '' && process_id != '') {

            $.ajax({
                url: "/process/get-duration",
                type: "POST",
                dataType: "text",
                data: {
                    _token: $('input[name="_token"]').val(),
                    start_date: startDate,
                    end_date: endDate,
                    process_type_id: process_id
                },
                success: function (response) {
                    var res_obj = jQuery.parseJSON(response);

                    if (is_from_board_meeting == 1) {
                        approved_desired_duration.val(res_obj.duration_difference);
                        approved_duration_amount.val(res_obj.duration_fees);
                        approved_duration_year.text(res_obj.duration_year);

                    } else {
                        $("#" + duration_id).val(res_obj.duration_difference);
                        $("#" + amount_id).val(res_obj.duration_fees);
                        $("#" + year_id).text(res_obj.duration_year);
                    }

                    $('#date_compare_error').text('');
                }
            });
        }
    }
}

function getDateDuration(startDate, endDate, duration_id) {
    if (startDate != '' && endDate != '') {
        $.ajax({
            url: "/process/get-date-duration",
            type: "POST",
            dataType: "text",
            data: {
                _token: $('input[name="_token"]').val(),
                start_date: startDate,
                end_date: endDate
            },
            success: function (response) {
                var res_obj = jQuery.parseJSON(response);
                $("#" + duration_id).val(res_obj.duration_difference);
            }
        });
    } else {

    }
}

function removeAttachedFile(docID, doc_priority) {
    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $('#validate_field_' + docID).val('');
            $('.saved_file_' + docID).html('');
            $('#other_doc_name_' + docID).val('');

            if (doc_priority == 1) {
                $("#file" + docID).addClass('required');
            }
            swal(
                'Deleted!',
                'Your file has been deleted.',
                'success'
            )
        } else {
            return false;
        }
    })

    // var sure_del = confirm("Are you sure you want to delete this file?");
    // if (sure_del) {
    //     $('#validate_field_' + docID).val('');
    //     $('.saved_file_' + docID).html('');
    //     $('#other_doc_name_' + docID).val('');
    //
    //     if(doc_priority == 1){
    //         $("#file"+docID).addClass('required');
    //     }
    // } else {
    //     return false;
    // }
}

// on select department get process/service list
function getProcessByDepartment(dept_id, dept_value, process_id, old_value) {
    // define old_data as an optional parameter
    if (typeof old_value === 'undefined') {
        old_value = 0;
    }
    var _token = $('input[name="_token"]').val();

    if (dept_id != '') {
        $("#" + dept_id).after('<span class="loading_data">Loading service...</span>');
        $.ajax({
            type: "POST",
            url: "/settings/getProcessByDept",
            data: {
                _token: _token,
                dept_id: dept_value
            },
            success: function (response) {
                var option = '<option value="">Select service/process</option>';
                if (response.responseCode == 1) {
                    $.each(response.data, function (id, value) {
                        if (value == old_value) {
                            option += '<option value="' + value + '" selected>' + id + '</option>';
                        } else {
                            option += '<option value="' + value + '">' + id + '</option>';
                        }
                    });
                }
                $('#' + process_id).html(option);
                $("#" + dept_id).next().hide('slow');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
            beforeSend: function (xhr) {

            },
            complete: function () {

            }
        });
    } else {
        console.log('Please select a valid department');
    }
}

//========== End Foreign Borrowing Script ============


// Add table Row script
function addTableRow(tableID, templateRow) {
    //rowCount++;
    //Direct Copy a row to many times
    var x = document.getElementById(templateRow).cloneNode(true);
    x.id = "";
    x.style.display = "";
    var table = document.getElementById(tableID);
    var rowCount = $('#' + tableID).find('tr').length - 1;
    var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
    var production_desc_val = $('#' + tableID).find('tr').last().find('.production_desc_1st').val();
    if (lastTr != '' && typeof lastTr !== "undefined") {
        rowCount = parseInt(lastTr) + 1;
    }
    //var rowCount = table.rows.length;
    //Increment id
    var rowCo = rowCount;
    var idText = 'rowCount' + tableID + rowCount;
    x.id = idText;
    $("#" + tableID).append(x);

    // Clear file input and remove "Open File" link in the cloned row
    $("#" + idText).find('input[type="file"]').val('');  // Clear the file input
    $("#" + idText).find('a.documentUrl').remove();  // Remove "Open File" link if it exists

    //get select box elements
    var attrSel = $("#" + tableID).find('#' + idText).find('select');
    //edited by ishrat to solve select box id auto increment related bug
    for (var i = 0; i < attrSel.length; i++) {
        var nameAtt = attrSel[i].name;
        var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
        attrSel[i].name = repText;
    }
    attrSel.val(''); //value reset
    // end of  solving issue related select box id auto increment related bug by ishrat

    //get input elements
    var attrInput = $("#" + tableID).find('#' + idText).find('input');
    for (var i = 0; i < attrInput.length; i++) {
        var nameAtt = attrInput[i].name;
        //increment all array element name
        var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
        attrInput[i].name = repText;
    }
    attrInput.val(''); //value reset
    //edited by ishrat to solve textarea id auto increment related bug
    //get textarea elements
    var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
    for (var i = 0; i < attrTextarea.length; i++) {
        var nameAtt = attrTextarea[i].name;
        //increment all array element name
        var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
        attrTextarea[i].name = repText;
        $('#' + idText).find('.readonlyClass').prop('readonly', true);
    }
    attrTextarea.val(''); //value reset
    // end of  solving issue related textarea id auto increment related bug by ishrat
    attrSel.prop('selectedIndex', 0);
    if ((tableID === 'machinaryTbl' && templateRow === 'rowMachineCount0') || (tableID === 'machinaryTbl' && templateRow === 'rowMachineCount')) {
        $("#" + tableID).find('#' + idText).find('select.m_currency').val("107");  //selected index reset
    } else {
        attrSel.prop('selectedIndex', 0);  //selected index reset
    }
    //$('.m_currency ').prop('selectedIndex', 102);
    //Class change by btn-danger to btn-primary
    $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
        .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
    $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
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
    $("#" + tableID).find('.datepicker').datetimepicker({
        viewMode: 'years',
        format: 'DD-MMM-YYYY',
        extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
        maxDate: 'now',
        minDate: '01/01/1905'
    });

    $("#" + tableID).find('.YearPicker').datetimepicker({
        viewMode: 'years',
        format: 'YYYY',
        extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
        // maxDate: 'now',
        minDate: '01/01/1905'
    });
} // end of addTableRow() function

// Add table Row script
function addTableRowTraHis(tableID, templateRow) {
    //rowCount++;
    //Direct Copy a row to many times
    var x = document.getElementById(templateRow).cloneNode(true);
    x.id = "";
    x.style.display = "";
    var table = document.getElementById(tableID);
    var rowCount = $('#' + tableID).find('tr').length - 1;
    var lastTr = $('#' + tableID).find('tr').last().attr('data-number');

    var production_desc_val = $('#' + tableID).find('tr').last().find('.production_desc_1st').val();
    if (lastTr != '' && typeof lastTr !== "undefined") {
        rowCount = parseInt(lastTr) + 1;
    }
    //var rowCount = table.rows.length;
    //Increment id
    var rowCo = rowCount;
    var idText = 'rowCount' + tableID + rowCount;
    x.id = idText;
    $("#" + tableID).append(x);
    //get select box elements
    var attrSel = $("#" + tableID).find('#' + idText).find('select');
    //edited by ishrat to solve select box id auto increment related bug
    for (var i = 0; i < attrSel.length; i++) {
        var nameAtt = attrSel[i].name;
        var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
        attrSel[i].name = repText;
    }
    attrSel.val(''); //value reset
    // end of  solving issue related select box id auto increment related bug by ishrat

    //get input elements
    var attrInput = $("#" + tableID).find('#' + idText).find('input');
    for (var i = 0; i < attrInput.length; i++) {
        var nameAtt = attrInput[i].name;
        //increment all array element name
        var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
        attrInput[i].name = repText;
    }
    attrInput.val(''); //value reset
    //edited by ishrat to solve textarea id auto increment related bug
    //get textarea elements
    var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
    for (var i = 0; i < attrTextarea.length; i++) {
        var nameAtt = attrTextarea[i].name;
        //increment all array element name
        var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
        attrTextarea[i].name = repText;
        $('#' + idText).find('.readonlyClass').prop('readonly', true);
    }
    attrTextarea.val(''); //value reset
    // end of  solving issue related textarea id auto increment related bug by ishrat
    attrSel.prop('selectedIndex', 0);
    if ((tableID === 'machinaryTbl' && templateRow === 'rowMachineCount0') || (tableID === 'machinaryTbl' && templateRow === 'rowMachineCount')) {
        $("#" + tableID).find('#' + idText).find('select.m_currency').val("107");  //selected index reset
    } else {
        attrSel.prop('selectedIndex', 0);  //selected index reset
    }
    //$('.m_currency ').prop('selectedIndex', 102);
    //Class change by btn-danger to btn-primary
    $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
        .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
    $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
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

    $("#" + tableID).find('#' + idText).find('.datepickerTraHisStart0').datetimepicker({
        viewMode: 'days',
        format: 'DD-MMM-YYYY',
        extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
        maxDate: 'now',
        minDate: '01/01/1905'
    })

    $("#" + tableID).find('#' + idText).find('.datepickerTraHisEnd0').datetimepicker({
        viewMode: 'days',
        format: 'DD-MMM-YYYY',
        useCurrent: false
    });

    $("#" + tableID).find('#' + idText).find('.datepickerTraHisStart0').on("dp.change", function (e) {
        var start = $("#" + tableID).find('#' + idText).find('.datepickerTraHisStart0').find('input').val();
        var day = moment(start, ['DD-MMM-YYYY']);

        //var minStartDate = moment(day).add(1, 'day');
        $("#" + tableID).find('#' + idText).find('.datepickerTraHisEnd0').data("DateTimePicker").minDate(day);

    })
} // end of addTableRowTraHis() function

// Remove Table row script
function removeTableRow(tableID, removeNum) {
    $('#' + tableID).find('#' + removeNum).remove();
}

function getAttachmentType(service_id, attachment_id, old_data) {
    if (typeof old_data === 'undefined') {
        old_data = 0;
    }

    var _token = $('input[name="_token"]').val();
    $.ajax({
        type: "POST",
        url: '/settings/get-attachment-type',
        data: {
            _token: _token,
            service_id: service_id
        },
        success: function (response) {
            var option = '<option value="">Select Attachment type</option>';
            var counter = 0;
            $.each(response.result, function (id, value) {
                counter = 1;
                if (id == old_data) {
                    option += '<option value="' + id + '" selected>' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });

            if (counter == 0) {
                option = '<option value="">no attachment type exist!</option>';
                $("#" + attachment_id).html(option);
                $("#" + attachment_id).removeClass('required');
            } else {
                $("#" + attachment_id).html(option);
                // $("#"+ctg_field_id).addClass('required');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Unknown error occured. Please, try again after reload');
        },
        beforeSend: function (xhr) {
            //console.log('before send');
        },
        complete: function () {
            //completed
        }
    });
}

function LoadSubSector(sector_id, sector_others_div_id, sector_others_field_id, sub_sector_field_id, old_data = false) {

    var _token = $('input[name="_token"]').val();

    $.ajax({
        type: "POST",
        url: '/basic-information/load-sub-sector',
        data: {
            _token: _token,
            sector_id: sector_id
        },
        success: function (response) {
            var option = '<option value="">Select Sub-sector</option>';
            var counter = 0;
            $.each(response.result, function (id, value) {
                counter = 1;
                //console.log(typeof id,id, typeof old_data, old_data);
                /*
                compare between new option's id and stored option's id.
                if equal then select the option.
                current option `id` is string type data
                and `old_date` is number type data,
                so we convert the `old_data` to string type data
                to easy and sure to compare.
                 */
                if (Number(id) == old_data.toString()) {
                    option += '<option value="' + id + '" selected>' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });

            if (counter == 0) {
                option = '<option value="">no sub-sector exist!</option>';
                $("#" + sub_sector_field_id).html(option);
                $("#" + sub_sector_field_id).removeClass('required');
            } else {
                $("#" + sub_sector_field_id).html(option);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Unknown error occured. Please, try again after reload');
        },
        beforeSend: function (xhr) {
            //console.log('before send');
        },
        complete: function () {
            //completed
            /*
            `old_data` is equal false as default.
            when it comes in function by parameter then `old_data` will set as number type data.
            when `old_data` is not equal false then we need to trigger `SubSectorOthersDiv()` function.
             */
            if (old_data !== false) {
                SubSectorOthersDiv(old_data);
            }
        }
    });


    /*
    if `sector_id` is equal to '0' ('0' means 'Others')
    then show an input field to entry some description.
    `sector_others_div_id` is the name of div which contain an input field.
     */
    if (sector_id == '0') {
        $("#" + sector_others_div_id).show();
        $("#" + sector_others_field_id).addClass('required');
    } else {
        $("#" + sector_others_div_id).hide();
        $("#" + sector_others_field_id).removeClass('required');
    }
}

function SubSectorOthersDiv(sub_sector_value, sub_sector_others_div_id = 'SUB_SECTOR_OTHERS', sub_sector_others_field_id) {
    //console.log(typeof sub_sector_value, sub_sector_value);
    /*
    if `sub_sector_value` is equal to '0' ('0' means 'Others')
    then show an input field to entry some description.
    `sub_sector_others_div_id` is the name of div which contain an input field.
     */
    if (sub_sector_value == '0') {
        $("#" + sub_sector_others_div_id).show();
        $("#" + sub_sector_others_field_id).addClass('required');
    } else {
        $("#" + sub_sector_others_div_id).hide();
        $("#" + sub_sector_others_field_id).removeClass('required');
    }
}

function openChangeCompanyModal(btn) {
    var this_action = btn.getAttribute('data-action');
    if (this_action != '') {
        $.get(this_action, function (data, success) {
            if (success === 'success') {
                $('#changeCompanyModal .load_modal').html(data);
            } else {
                $('#changeCompanyModal .load_modal').html('Unknown Error!');
            }
            $('#changeCompanyModal').modal('show', {backdrop: 'static'});
        });
    }
}

function checkPdfDocumentType(id, max_size) {
    var file_id = document.getElementById(id);
    var file = file_id.files;
    if (file && file[0]) {
        if (!(file[0].type == 'application/pdf')) {
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'The file format is not valid! Please upload in pdf format.'
            });
            file_id.value = '';
            return false;
        }

        var file_size = parseFloat((file[0].size) / (1024 * 1024)).toFixed(1); //MB Calculation
        if (!(file_size <= max_size)) {
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'Max file size ' + max_size + 'MB. You have uploaded ' + file_size + 'MB'
            });
            file_id.value = '';
            return false;
        }
    }
}

function startEndDateValidation(start_date_id, end_date_id) {

    var start_date = $("#" + start_date_id).val();
    var end_date = $("#" + end_date_id).val();

    if (start_date != '' && end_date != '') {
        var getStatus = $.ajax({
            url: "/process/get-start-end-date-validation",
            type: "POST",
            dataType: "text",
            async: false,
            data: {
                _token: $('input[name="_token"]').val(),
                start_date: start_date,
                end_date: end_date
            },
            success: function (response) {
                var res_obj = jQuery.parseJSON(response);
                if (res_obj.response == 0) {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'The end date must be higher than the start date.'
                    });
                    $("#" + start_date_id).addClass('error');
                    $("#" + end_date_id).addClass('error');
                } else {
                    $("#" + start_date_id).removeClass('error');
                    $("#" + end_date_id).removeClass('error');
                }
            }
        });
        var res_val = jQuery.parseJSON(getStatus.responseText);
        return res_val.response;
    }
}

//this function for is helpful article
function isHelpFulArticle(is_helful_status, value, slug) {
    if (slug == 1) { // 1 = available service
        var service_detail_id = value;
    }
    if (slug == 2) { // 2 = agency info
        var sub_agency_id = value;
    }

    $.ajax({
        url: "/web/is-helpful-article",
        type: 'GET',
        data: {
            is_helpful: is_helful_status,
            service_detail_id: service_detail_id,
            sub_agency_id: sub_agency_id,
            slug: slug
        },
        async: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (response) {
            console.log(response);
            toastr.success("Thanks for your feedback");
            return false;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
}

//logout alert message
function confirmLogout(btn) {
    var url = btn.getAttribute('data-action');
    swal({
        title: 'Are you sure?',
        text: "You want to log out!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then(function (isConfirm) {
        if (isConfirm.value){
            window.location=(url);
        } else {
            return false;
        }
    })
}