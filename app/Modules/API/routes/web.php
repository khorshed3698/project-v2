<?php

Route::group(['module' => 'API', 'namespace' => 'App\Modules\API\Controllers'], function() {

    Route::get('osspid/api', 'APIController@apiRequest');
    Route::post('osspid/api', 'APIController@apiRequest');
    Route::get('web/view-mis-reports/{report_id}/{permission_id}/{unix_time}', "AppsWebController@misReportView");
    Route::get('web/search/{enc_reg_key}/{keyword}', "AppsWebController@appSearch");
    Route::get('web/view-image/{enc_user_id}', "AppsWebController@viewImage");
    Route::get('/qr-code/show','QRLoginController@showQrCode');
    Route::get('/qr-login-check','QRLoginController@qrLoginCheck');
    Route::get('/qr-log-out','QRLoginController@qrLogout');
    Route::get('/send-submission','CdaApiController@sendCdaSubmission');
});


//IRMS API
Route::group(['module' => 'API', 'namespace' => 'App\Modules\API\Controllers\IRMS\V1'], function() {
    Route::get('irms-portal-login/{tracking_no}',"IrmsPortalLoginController@irmsPortalLogin");
});


Route::group(['module' => 'API', 'namespace' => 'App\Modules\API\Controllers\V1'], function() {

    Route::post('api/v1/get-token', 'TokenGenerateController@getToken');

    Route::post('api/v1/check-payment-status', 'TradeLicenceApiProviderController@checkPaymentStatus');

    Route::get('api/v1/e-tin-process-json-file','ETinApiController@processJsonFile');

    Route::get('api/v1/e-tin-map-area','ETinApiController@mapAreaTable');

    Route::get('api/v1/trade-licence-map-table','TradeLicenceApiConsumerController@mapZoneWardArea');

    Route::get('api/v1/trade-licence-map-area','TradeLicenceApiConsumerController@mapAreaTable');

});

