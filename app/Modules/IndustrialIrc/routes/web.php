<?php

Route::group(['module' => 'IndustrialIrc', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\IndustrialIrc\Controllers'], function () {

    Route::get('licence-applications/ccie/add', "IndustrialIrcController@appForm");
    Route::get('industrial-IRC/getData/{app_id}', "IndustrialIrcController@appGetData");
    Route::post('industrial-IRC/store', 'IndustrialIrcController@appStore');
//    Route::get('industrial-irc/get-district-by-division', 'IndustrialIrcController@getDistrictByDivision');
//    Route::get('industrial-irc/get-thana-by-district-id', 'IndustrialIrcController@get_thana_by_district_id');
    Route::post('licence-application/industrial-IRC/add', "IndustrialIrcController@appStore");
//    Route::post('licence-application/industrial-irc/add', "IndustrialIrcController@appStore");

    Route::get('licence-applications/ccie/edit/{id}/{openMode}', "IndustrialIrcController@applicationViewEdit");
    Route::get('licence-applications/ccie/view/{id}/{openMode}', "IndustrialIrcController@applicationView");

    Route::get('licence-applications/ccie/shortfall-form/{app_id}', 'IndustrialIrcController@showShortfallForm');
    Route::post('licence-applications/ccie/shortfall-form/store', 'IndustrialIrcController@storeShortfall');


//    Route::get('industrial-IRC/edit/{id}/{openMode}', "IndustrialIrcController@applicationViewEdit");
//    Route::get('industrial-IRC/view/{id}/{openMode}', "IndustrialIrcController@applicationView");

//    Route::get('licence-applications/e-tin/view-pdf/{id}', 'EtinController@appFormPdf');
//    Route::post('licence-applications/e-tin/payment', "EtinController@Payment");
//    Route::post('licence-applications/e-tin/check-api-request-status', "EtinController@checkApiRequestStatus");
//    Route::get('licence-applications/show-certificate/{app_id}/{certificate_id}', "EtinController@showCertificate");
//    Route::get('licence-applications/e-tin/get-company-list', 'EtinController@getCompanyList');
    Route::post('industrial-IRC/getDocList', 'IndustrialIrcController@getDocList');
    Route::post('industrial-IRC/get-dynamic-doc', 'IndustrialIrcController@getDynamicDoc');
    Route::post('industrial-IRC/upload-document', "IndustrialIrcController@uploadDocument");


    Route::get('licence-applications/ccie/check-payment/{app_id}', 'IndustrialIrcController@waitForPayment');
    Route::post('licence-applications/ccie/check-payment-info', 'IndustrialIrcController@checkPayment');
    Route::post('licence-applications/ccie/payment', 'IndustrialIrcController@cciePayment');

    Route::get('licence-applications/ccie/afterPayment/{payment_id}', 'IndustrialIrcController@afterPayment');
    Route::get('licence-applications/ccie/afterCounterPayment/{payment_id}', 'IndustrialIrcController@afterCounterPayment');

    Route::get('industrial-IRC/regenerate-submission-json/{id}', 'IndustrialIrcController@reGenerateSubmissionJson');
    // API calls ends

    Route::get('industrial-IRC/ccie/get-refresh-token', "IndustrialIrcController@getRefreshToken");
});
Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('industrial-IRC/list/{process_id}', 'ProcessPathController@processListById');
});
