<?php

Route::group(['module' => 'NewConnectionDPDC', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\NewConnectionDPDC\Controllers'], function () {

    Route::get('licence-applications/new-connection-dpdc/add', 'NewConnectionDPDCController@appForm');


    Route::post('new-connection-dpdc/store', 'NewConnectionDPDCController@appStore');
    Route::get('licence-applications/new-connection-dpdc/edit/{id}/{openMode}', "NewConnectionDPDCController@applicationViewEdit");
    Route::get('licence-applications/new-connection-dpdc/view/{id}/{openMode}', "NewConnectionDPDCController@applicationView");
    Route::post('new-connection-dpdc/upload-document', "NewConnectionDPDCController@uploadDocument");
    Route::get('new-connection-dpdc/get-refresh-token', "NewConnectionDPDCController@getRefreshToken");

    Route::post('new-connection-dpdc/shortfall', "NewConnectionDPDCController@shortfallDoc");

    //    Route::get('dpdc/connection-area','NewConnectionDPDCController@connection');
    Route::post('new-connection-dpdc/validate-nid', 'NewConnectionDPDCController@validateNID');
    Route::post('new-connection-dpdc/get-dynamic-doc', 'NewConnectionDPDCController@getDynamicDoc');
    Route::get('new-connection-dpdc/check-payment/{app_id}', 'NewConnectionDPDCController@waitForPayment');
    Route::post('new-connection-dpdc/check-payment-info', 'NewConnectionDPDCController@checkPayment');
    Route::post('new-connection-dpdc/payment', 'NewConnectionDPDCController@DPDCPayment');

    Route::get('new-connection-dpdc/view/additional-payment/{id}', "NewConnectionDPDCController@waitfordemandpayment");

    //demand fee payment
    Route::get('new-connection-dpdc/view-demand/{id}', "NewConnectionDPDCController@demandView");
    Route::get('new-connection-dpdc/view/additional-payment/{id}', "NewConnectionDPDCController@waitfordemandpayment");
    Route::post('new-connection-dpdc/check-payment-info-demand', 'NewConnectionDPDCController@checkDemandPayment');
    Route::post('new-connection-dpdc/payment-demand', 'NewConnectionDPDCController@additionalpayment');
    // end demand
    Route::get('new-connection-dpdc/afterPayment/{payment_id}', 'NewConnectionDPDCController@afterPayment');
    Route::get('new-connection-dpdc/afterCounterPayment/{payment_id}', 'NewConnectionDPDCController@afterCounterPayment');
    Route::post('new-connection-dpdc/delete-dynamic-doc', 'NewConnectionDPDCController@deleteDynamicDoc');

});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('new-connection-dpdc/list/{process_id}', 'ProcessPathController@processListById');
});
