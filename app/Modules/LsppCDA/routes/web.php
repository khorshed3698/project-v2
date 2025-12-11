<?php

Route::group(['module' => 'LsppCDA', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\LsppCDA\Controllers'], function () {

    Route::get('licence-applications/cda-lspp/add', "LsppCdaController@appForm");
    Route::post('cda-lspp/store', 'LsppCdaController@appStore');
    Route::get('/cda-lspp/preview', "LsppCdaController@preview");

    Route::post('/cda-lspp/store-resubmission', "LsppCdaController@storeResubmitInfo");

    Route::post('licence-application/cda-lspp/add', "LsppCdaController@appStore");
    Route::get('licence-applications/cda-lspp/edit/{id}/{openMode}', "LsppCdaController@applicationViewEdit");
    Route::get('licence-applications/cda-lspp/view/{id}/{openMode}', "LsppCdaController@applicationView");

    Route::post('cda-lspp/getDocList', 'LsppCdaController@getDocList');
    Route::post('cda-lspp/payment', 'LsppCdaController@cdapayment');

    Route::get('cda-lspp/check-payment/{app_id}', 'LsppCdaController@waitForPayment');
    Route::post('cda-lspp/check-payment-info', 'LsppCdaController@checkPayment');

    Route::get('cda-lspp/check-submission/{app_id}', 'LsppCdaController@checkstatus');
    Route::get('cda-lspp/check-cda-application-status', 'LsppCdaController@applicationstatus');
    Route::get('licence-applications/cda-lspp/afterPayment/{payment_id}', 'LsppCdaController@afterPayment');
    Route::get('licence-applications/cda-lspp/afterCounterPayment/{payment_id}', 'LsppCdaController@afterCounterPayment');


    Route::get('cda-lspp/get-refresh-token', "LsppCdaController@getRefreshToken");
    Route::get('/cda-form/preview', "CdaFormController@preview");
    /*upload document*/
    Route::post('cda-lspp/upload-document', 'LsppCdaController@uploadDocument');

    Route::post('cda-lspp/getDocList', 'LsppCdaController@getDocList');
});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('cda-lspp/list/{process_id}', 'ProcessPathController@processListById');
});

