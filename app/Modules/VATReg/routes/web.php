<?php

Route::group(['module' => 'VATReg', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\VATReg\Controllers'], function () {

    Route::get('licence-applications/vat-registration/add', "VATRegController@appForm");
    Route::post('vat-registration/store', 'VATRegController@appStore');
    Route::get('/vat-registration/preview', "VATRegController@preview");

    Route::post('/vat-registration/store-resubmission', "VATRegController@storeResubmitInfo");

    Route::post('licence-application/vat-registration/add', "VATRegController@appStore");
    Route::get('licence-applications/vat-registration/edit/{id}/{openMode}', "VATRegController@applicationViewEdit");
    Route::get('licence-applications/vat-registration/view/{id}/{openMode}', "VATRegController@applicationView");

    Route::post('vat-registration/getDocList', 'VATRegController@getDocList');
    Route::post('vat-registration/upload-document', "VATRegController@uploadDocument");
    Route::post('vat-registration/payment', 'VATRegController@cdapayment');
    Route::post('vat-registration/service-hs-code', 'VATRegController@serviceHsCode');

    Route::get('licence-applications/vat-registration/show-shortfall-message/{app_id}', 'VATRegController@showShortfallMessage');

    Route::get('vat-registration/check-submission/{app_id}', 'VATRegController@checkstatus');
    Route::get('vat-registration/check-cda-application-status', 'VATRegController@applicationstatus');
    Route::get('licence-applications/vat-registration/afterPayment/{payment_id}', 'VATRegController@afterPayment');
    Route::get('licence-applications/vat-registration/afterCounterPayment/{payment_id}', 'VATRegController@afterCounterPayment');


    Route::get('vat-registration/get-refresh-token', "VATRegController@getRefreshToken");

    /*upload document*/
    Route::post('vat-registration/upload-document', 'VATRegController@uploadDocument');
});
