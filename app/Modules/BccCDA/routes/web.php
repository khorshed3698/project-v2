<?php

Route::group(['module' => 'BccCDA', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\BccCDA\Controllers'], function () {

    Route::get('licence-applications/cda-bcc/add', "BccCdaController@appForm");
    Route::post('cda-bcc/store', 'BccCdaController@appStore');

    Route::post('licence-application/cda-bcc/add', "BccCdaController@appStore");
    Route::get('licence-applications/cda-bcc/edit/{id}/{openMode}', "BccCdaController@applicationViewEdit");
    Route::get('licence-applications/cda-bcc/view/{id}/{openMode}', "BccCdaController@applicationView");

    Route::post('/cda-bcc/store-resubmission', "BccCdaController@storeResubmitInfo");
    Route::post('cda-bcc/payment', 'BccCdaController@cdaBCCpayment');

    Route::get('licence-applications/cda-bcc/afterPayment/{payment_id}', 'BccCdaController@afterPayment');

    Route::get('cda-bcc/check-payment/{app_id}', 'BccCdaController@waitForPayment');
    Route::post('cda-bcc/check-payment-info', 'BccCdaController@checkPayment');
    Route::get('cda-bcc/get-refresh-token', "BccCdaController@getRefreshToken");

    /*upload document*/
    Route::post('cda-bcc/upload-document', 'BccCdaController@uploadDocument');

    Route::post('cda-bcc/getDocList', 'BccCdaController@getDocList');
});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('cda-bcc/list/{process_id}', 'ProcessPathController@processListById');
});