/**
 * Created on 5/28/17.
 */

$(document).on("click", "body a", function() {

    try {
        var action = $(this).text();
        var urls =                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            $(this).attr('href');
        var url = urls.replace("#", ":");
        if (typeof action === "undefined") {
            action = $(this).attr('id');
            if (typeof action === "undefined") {
                action = $(this).attr('name');
            }
        }
        var data = '' + web_service_url + '?param={"mongoDBRequest":{"requestData":{"action":"' + action + '","ip_address":"' + ip_address + '","message":"' + message + '","project_code":"' + project_code + '","url":"' + url + '","user_id":"' + user_id + '","client_id":"' + project_name + '","auth_token":"' + MongoAuthToken + '"},"requestType":"AUDIT_ACTION_REQUEST","version":"1.0"}}';
        var encodedUrl = encodeURI(data);
        console.log(encodedUrl);
        $.ajax({
            url: encodedUrl,
            type: 'get',
            success: function (response) {

            }

        });
    }
    catch (error){
//
    }


});


$(document).on("click", "body button", function() {

try{
    var action = $(this).text();
    var urls = $(this).attr('href');
    var url = urls.replace("#", ":");

    if(typeof action === "undefined"){
        action = $(this).attr('id');
        if(typeof action === "undefined"){
            action = $(this).attr('name');
        }
    }
    var data = ''+web_service_url+'?param={"mongoDBRequest":{"requestData":{"action":"'+action+'","ip_address":"'+ip_address+'","message":"'+message+'","project_code":"'+project_code+'","url":"'+url+'","user_id":"'+user_id+'","client_id":"'+project_name+'","auth_token":"'+MongoAuthToken+'"},"requestType":"AUDIT_ACTION_REQUEST","version":"1.0"}}';
    var encodedUrl = encodeURI(data);
    console.log(encodedUrl);
    $.ajax({
        url: encodedUrl,
        type: 'get',
        success: function (response) {

        }

    });
}
catch (error){
//
}
});

$( document ).ready(function() {
    try {
        var url      = window.location.href;
        var data = ''+web_service_url+'?param={"mongoDBRequest":{"requestData":{"ip_address":"'+ip_address+'","message":"'+message+'","project_code":"'+project_code+'","url":"'+url+'","method":"","user_id":"'+user_id+'","client_id":"'+project_name+'","auth_token":"'+MongoAuthToken+'"},"requestType":"AUDIT_URL_REQUEST","version":"1.0"}}';
        var encodedUrl = encodeURI(data);
        console.log(encodedUrl);
        $.ajax({

            url: encodedUrl,
            type: 'get',
            async:false,
            crossDomain:true,
            success: function (response) {
            }

        });
    }
    catch (error){
//
    }

});
