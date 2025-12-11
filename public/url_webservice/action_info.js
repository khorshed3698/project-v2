// Url storing in self database

$(document).on("click", "body a", function() {


    var _token = $('input[name="_token"]').val();
    var action = $(this).text();
    var url = $(this).attr('href');

    if(typeof action === "undefined"){
        action = $(this).attr('id');
        if(typeof action === "undefined"){
            action = $(this).attr('name');
        }
    }

    $.ajax({
        url: '/api/action/new-job',
        type: 'post',
        data: {
            _token : _token,
            project: project_name,
            user_id: user_id,
            url: url,
            message: message,
            ip_address:ip_address,
            action:action
        },
        success: function (response) {
            // console.log(response);
        }

    });

});


$(document).on("click", "body button", function() {

    var _token = $('input[name="_token"]').val();
    var action = $(this).text();
    var url = $(this).attr('href');

    if(typeof action === "undefined"){
        action = $(this).attr('id');
        if(typeof action === "undefined"){
            action = $(this).attr('name');
        }
    }
    $.ajax({
        url: '/api/action/new-job',
        type: 'post',
        data: {
            _token : _token,
            project: project_name,
            user_id: user_id,
            url: url,
            message: message,
            ip_address:ip_address,
            action:action
        },
        success: function (response) {
            // console.log(response);
        }
    });

});

$(window).bind("load", function () {

    var _token = $('input[name="_token"]').val();
    var url = window.location.href;

    $.ajax({
        _token: _token,
        url: '/api/new-job',
        type: 'post',
        async: false,
        crossDomain: true,
        data: {
            project: project_name,
            user_id: user_id,
            url: url,
            message: message,
            ip_address: ip_address
        },
        success: function (response) {
            // console.log(response);
        }
    });

});
