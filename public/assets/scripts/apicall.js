/* api library
   jquery version 1.7+
 */

function apiCallPost(domElement, options, apiHeaders, callback, ...param) {
    try {

        options.type = "POST";
        var expire_token = isTokenExpiry(options.token);
        if (!expire_token) {
            options.token = getValidJWTToken(options);
        }

        ajaxRequest(domElement, options, callback, apiHeaders, ...param);

    } catch (e) {
        reloadElement(domElement);
        if(options.errorLog){
            externalAPI(options, e); // will implement our mongodb API
        }
    }
}

function apiCallGet(domElement, options, apiHeaders, callback, ...param) {
    try {

        options.type = "GET";
        var expire_token = isTokenExpiry(options.token);
        if (!expire_token) {
            options.token = getValidJWTToken(options);
        }
        ajaxRequest(domElement, options, callback, apiHeaders, ...param);

    } catch (e) {
        reloadElement(domElement);
        if(options.errorLog){
            externalAPI(options, e);
        }
    }
}

function isTokenExpiry(token) {
    if (token !== '') {
        var base64Url = token.split('.')[1];
        var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        var jsonPayload = decodeURIComponent(atob(base64).split('').map(function (c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
        return Date.now() < JSON.parse(jsonPayload).exp * 1000;
    } else {
        return false;
    }
}

function getValidJWTToken(options) {
    var result = '';
    $.ajax({
        type: "get",
        url: options.tokenUrl,
        async: false,
        success: function (token) {
            result = token;
        },
        error: function (error) {
            console.log(error)
        }
    });
    return result;
}


function ajaxRequest(domElement, options, callback, apiHeaders, ...param) {

    options.authorizations = "Bearer " + options.token + "";
    let headers = [{key:"Authorization", value: options.authorizations}];
    Array.prototype.push.apply(headers,apiHeaders);

    $.ajax({
        type: options.type,
        url: options.apiUrl,
        beforeSend: function (xhr) {
            $.each(headers, function(key, row ) {
                xhr.setRequestHeader(row.key,  row.value);
            });
        },
        data: options.data,

        success: function (response) {
            callback(response, ...param);
        },
        error: function (error) {
            reloadElement(domElement);
            if(options.errorLog){
                externalAPI(options, error.status + ': ' + error.statusText);
            }

        }
    });
}

function externalAPI(options, error) {
    $.ajax({
        type: options.errorLog.method,
        url: options.errorLog.logUrl,
        data:  error,
    });
}

function reloadElement(domElement) {
    domElement.next().remove();domElement.next().hide();
    domElement.parent().addClass('input-group');
    domElement.after('<span title="Please reload again" style="background: green; color: white" data-id="'+domElement.attr('id')+'" class="reCallApi input-group-addon"><i class="glyphicon glyphicon-refresh" aria-hidden="true"></i></span>');
}

