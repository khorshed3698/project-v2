<?php

Route::group(['module' => 'CdaOc', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\CdaOc\Controllers'], function () {

    Route::get('licence-applications/cda-oc/add', "CdaOcController@appForm");
    Route::get('licence-applications/cda-oc/edit/{id}/{openMode}', "CdaOcController@appFormEdit");
    Route::get('licence-applications/cda-oc/view/{id}/{openMode}', "CdaOcController@appFormView");

    Route::post('cda-oc/store', 'CdaOcController@appStore');
    Route::get('/cda-oc/preview', "CdaOcController@preview");

    Route::post('/cda-oc/store-resubmission', "CdaOcController@storeResubmitInfo");

    //Route::post('licence-application/cda-oc/add', "CdaOcController@appStore");


    Route::post('cda-oc/payment', 'CdaOcController@cdaPayment');

    Route::get('cda-oc/check-payment/{app_id}', 'CdaOcController@waitForPayment');
    Route::post('cda-oc/check-payment-info', 'CdaOcController@checkPayment');

    Route::get('cda-oc/check-submission/{app_id}', 'CdaOcController@checkstatus');

    Route::get('cda-oc/check-cda-application-status', 'CdaOcController@applicationstatus');
    Route::get('licence-applications/cda-oc/afterPayment/{payment_id}', 'CdaOcController@afterPayment');
    Route::get('licence-applications/cda-oc/afterCounterPayment/{payment_id}', 'CdaOcController@afterCounterPayment');

    Route::get('cda-oc/get-refresh-token', "CdaOcController@getRefreshToken");
    Route::get('/cda-form/preview', "CdaFormController@preview");

    Route::post('cda-oc/get-dynamic-doc', 'CdaOcController@getDynamicDoc');
    Route::post('cda-oc/upload-document', "CdaOcController@uploadDocument");

});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('cda-oc/list/{process_id}', 'ProcessPathController@processListById');
});

