// Home page slider & what's new slider
$(function () {
    //Marquee Ticker
    var timer = !1;
    _Ticker = $(".TickerNews").newsTicker({});
    _Ticker.on("mouseenter", function () {
        var __self = this;
        timer = setTimeout(function () {
            __self.pauseTicker();
        }, 200);
    });
    _Ticker.on("mouseleave", function () {
        clearTimeout(timer);
        if (!timer) return !1;
        this.startTicker();
    });


    $('.notice_heading').click(function () {
        $(this).parent().parent().find('.details').show();
        return false;
    });


    $('#object_report').click(function () {
        $('#object_report_content').load('{{URL::to("/web/get-report-object/REG_HALNAGAT")}}');

    });
});

//for available service
// set flag for one time calling
var is_available_service_loaded = false;
document.getElementById('available_services').onclick = function () {
    LoadAvailableService();
};

function LoadAvailableService() {
    if (!is_available_service_loaded) {
        $.ajax({
            url: "/web/get-available-services",
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $("#availableServicesTab").html(response.response);
                $('#availableSericeLoading').hide();
                is_available_service_loaded = true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    }
}


//for user manual .....
var is_user_manual_loaded = false;
document.getElementById('user_manual').onclick = function () {
    LoadUserManual();
};

function LoadUserManual() {
    if (!is_user_manual_loaded) {
        $.ajax({
            url: "/web/user-manual",
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $("#showUserManual").html(response.response);
                $('#userManualLoading').hide();
                is_user_manual_loaded = true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    }
}


var is_load_bbs_code = false;
document.getElementById('licenceApplication').onclick = function () {
    $("#checkHiddenTabButton").click();
    //load bss code...
    loadBBSCode();

    //scrolling select bbs code table...
    $('html, body').animate({
        scrollTop: $("div.table-responsive").offset().top
    }, 350);
};

function loadBBSCode() {
    if (!is_load_bbs_code) {
        $.ajax({
            url: "/web/get-bbs-code",
            type: 'GET',
            dataType: 'json',
            async: false,
            success: function (response) {
                $("#bbsCode").html(response.response);
                $('#bbs_code_preloading').hide();
                is_load_bbs_code = true;
                //console.log(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    }
}

// IPA and CLP Agency load by ajax
// set flag for one time calling
var is_agency_loaded = false;
document.getElementById('ipaClpTabBtn').onclick = function () {
    loadIpaClpAgencyList();
};

function loadIpaClpAgencyList() {
    if (!is_agency_loaded) {
        $.ajax({
            url: "/web/get-agency-list",
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $("#ipa_clp_agency_content").html(response.response);
                $("#ipa_clp_agency_preloading").hide();
                is_agency_loaded = true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    }
}

// Declare an empty object to store flag for sub-agency content loading
var sub_agency_load_flags = {};

function loadSubAgencyDetails(sub_agency_id, service_name_slug) {
    if (sub_agency_load_flags[service_name_slug] !== true) {
        $.ajax({
            url: "/web/get-sub-agency-content/" + sub_agency_id,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $("#" + service_name_slug + "_content").html(response.response);
                $("#" + service_name_slug + "_preloading").hide();
                sub_agency_load_flags[service_name_slug] = true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    }
}


// Declare an empty object to store flag for service content loading
var sub_service_load_flags = {};

function LoadAvailableServiceDetails(service_detail_id, service_name_slug) {
    if (sub_service_load_flags[service_name_slug] !== true) {
        $.ajax({
            url: "/web/get-available-service-details/" + service_detail_id,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $("#" + service_name_slug + "_content").html(response.description);
                $("#" + service_name_slug + "_updated_at").html('<p style="font-size: 11px; margin: 5px 0 0 0;">Last updated: ' + response.last_update + ' </p>');
                $("#" + service_name_slug + "_preloading").hide();
                sub_service_load_flags[service_name_slug] = true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    }
}


function viewPageCount(id) {

    if (typeof id === 'undefined' || id === '') {
        return false;
    }

    $.ajax({
        url: "/web/view-page-link-count",
        type: 'POST',
        dataType: "text",
        data: {
            _token: $('input[name="_token"]').val(),
            log_key: id
        },
        async: false,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (response) {
            //console.log(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
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

// training details information
$('.training_heading').click(function () {
    $(this).hide();
    $(this).parent().parent().find('.training_details').show();
    return false;
});

// Training Schedule for public users
$(document).on('click', '.scheduleDetails', function (e) {
    btn = $(this);
    btn_content = btn.html();
    btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
    var training_id = btn.attr('id');
    $.ajax({
        url: '/training-public/get-training-public-schedule',
        type: 'GET',
        data: {
            training_id: training_id
        },
        success: function (response) {
            btn.html(btn_content);
            $(".scheduleInfo").html(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);

        },
        beforeSend: function (xhr) {
            console.log('before send');
        },
        complete: function () {
            //completed
        }
    });
});

// Training application form for public users
$(document).on('click', '.applyForTraining', function (e) {
    var schedule_id = $(this).attr('id');
    btn = $(this);
    btn_content = btn.html();
    btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
    btn.prop('disabled', true);

    $.ajax({
        url: '/training-public/application-form',
        type: 'POST',
        dataType: 'json',
        data: {
            _token: $('input[name="_token"]').val(),
            schedule_id: schedule_id
        },
        success: function (response) {
            btn.html(btn_content);
            if (response.responseCode == 1) {
                $(".scheduleInfo").html(response.public_html);
                $(".scheduleInfo").load();

                // Triggering on datepicker on-success
                $('.datepicker').datetimepicker({
                    viewMode: 'years',
                    format: 'DD-MMM-YYYY',
                    maxDate: 'now',
                    minDate: '01/01/1905'
                });
            } else {
                btn.prop('disabled', false);
                alert(response.msg);
                return false;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);

        },
        beforeSend: function (xhr) {
            console.log('before send'.xhr);
        },
        complete: function () {
            //completed
        }
    });
});