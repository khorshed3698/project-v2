<?php

Route::group(['module' => 'OcCDA', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\OcCDA\Controllers'], function () {

    Route::get('licence-applications/cda-oc/add', "OcCdaController@appForm");
    Route::post('cda-oc/store', 'OcCdaController@appStore');
    Route::get('/cda-oc/preview', "OcCdaController@preview");

    Route::post('/cda-oc/store-resubmission', "OcCdaController@storeResubmitInfo");

    Route::post('licence-application/cda-oc/add', "OcCdaController@appStore");
    Route::get('licence-applications/cda-oc/edit/{id}/{openMode}', "OcCdaController@applicationViewEdit");
    Route::get('licence-applications/cda-oc/view/{id}/{openMode}', "OcCdaController@applicationView");

    Route::post('cda-oc/getDocList', 'OcCdaController@getDocList');
    Route::post('cda-oc/payment', 'OcCdaController@cdapayment');

    Route::get('cda-oc/check-payment/{app_id}', 'OcCdaController@waitForPayment');
    Route::post('cda-oc/check-payment-info', 'OcCdaController@checkPayment');

    Route::get('cda-oc/check-submission/{app_id}', 'OcCdaController@checkstatus');

    Route::get('cda-oc/check-cda-application-status', 'OcCdaController@applicationstatus');
    Route::get('licence-applications/cda-oc/afterPayment/{payment_id}', 'OcCdaController@afterPayment');
    Route::get('licence-applications/cda-oc/afterCounterPayment/{payment_id}', 'OcCdaController@afterCounterPayment');


    Route::get('cda-oc/get-refresh-token', "OcCdaController@getRefreshToken");
    Route::get('/cda-form/preview', "CdaFormController@preview");
    /*upload document*/
    Route::post('cda-oc/upload-document', 'OcCdaController@uploadDocument');

    Route::post('cda-oc/getDocList', 'OcCdaController@getDocList');
});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('cda-oc/list/{process_id}', 'ProcessPathController@processListById');
});

