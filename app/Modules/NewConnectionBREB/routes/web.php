<?php

Route::group(['module' => 'NewConnectionBREB', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\NewConnectionBREB\Controllers'], function () {

    Route::get('licence-applications/new-connection-breb/add', 'NewConnectionBREBController@appForm');


    Route::post('new-connection-breb/store', 'NewConnectionBREBController@appStore');
    Route::get('licence-applications/new-connection-breb/edit/{id}/{openMode}', "NewConnectionBREBController@applicationViewEdit");
    Route::get('licence-applications/new-connection-breb/view/{id}/{openMode}', "NewConnectionBREBController@applicationView");
    Route::post('new-connection-breb/upload-document', "NewConnectionBREBController@uploadDocument");
    Route::get('new-connection-breb/get-refresh-token', "NewConnectionBREBController@getRefreshToken");

    //PDF generator link
    Route::get('new-connection-breb/applicant-copy/{id}', "NewConnectionBREBController@applicantPDF");
    Route::post('new-connection-breb/shortfall-store', "NewConnectionBREBController@shortfallStore");

    //    Route::get('breb/connection-area','NewConnectionBREBController@connection');
    Route::post('new-connection-breb/get-dynamic-doc', 'NewConnectionBREBController@getDynamicDoc');
    Route::post('new-connection-breb/get-shortfall-doc', 'NewConnectionBREBController@getShortfallDoc');
    Route::get('new-connection-breb/check-payment/{app_id}', 'NewConnectionBREBController@waitForPayment');
    Route::post('new-connection-breb/check-payment-info', 'NewConnectionBREBController@checkPayment');
    Route::post('new-connection-breb/payment', 'NewConnectionBREBController@brebPayment');

    //demand fee payment
    Route::get('new-connection-breb/view-demand/{id}', "NewConnectionBREBController@demandView");
    Route::get('new-connection-breb/view/additional-payment/{id}', "NewConnectionBREBController@waitfordemandpayment");
    Route::post('new-connection-breb/check-payment-info-demand', 'NewConnectionBREBController@checkDemandPayment');
    Route::post('new-connection-breb/payment-demand', 'NewConnectionBREBController@additionalpayment');
    // end demand
    Route::get('new-connection-breb/afterPayment/{payment_id}', 'NewConnectionBREBController@afterPayment');
    Route::get('new-connection-breb/afterCounterPayment/{payment_id}', 'NewConnectionBREBController@afterCounterPayment');
    Route::post('new-connection-breb/delete-dynamic-doc', 'NewConnectionBREBController@deleteDynamicDoc');

});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('new-connection-breb/list/{process_id}', 'ProcessPathController@processListById');
});
