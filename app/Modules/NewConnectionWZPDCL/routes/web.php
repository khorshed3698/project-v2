<?php

Route::group(['module' => 'NewConnectionWZPDCL', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\NewConnectionWZPDCL\Controllers'], function () {

    Route::get('licence-applications/new-connection-wzpdcl/add', 'NewConnectionWZPDCLController@appForm');

    Route::post('new-connection-wzpdcl/store', 'NewConnectionWZPDCLController@appStore');
    Route::get('licence-applications/new-connection-wzpdcl/edit/{id}/{openMode}', "NewConnectionWZPDCLController@applicationViewEdit");
    Route::get('licence-applications/new-connection-wzpdcl/view/{id}/{openMode}', "NewConnectionWZPDCLController@applicationView");
    Route::post('new-connection-wzpdcl/upload-document', "NewConnectionWZPDCLController@uploadDocument");
    Route::get('new-connection-wzpdcl/get-refresh-token', "NewConnectionWZPDCLController@getRefreshToken");

    Route::post('new-connection-wzpdcl/shortfall', "NewConnectionWZPDCLController@shortfallDoc");

    //    Route::get('wzpdcl/connection-area','NewConnectionWZPDCLController@connection');
    Route::post('new-connection-wzpdcl/validate-nid', 'NewConnectionWZPDCLController@validateNID');
    Route::post('new-connection-wzpdcl/get-dynamic-doc', 'NewConnectionWZPDCLController@getDynamicDoc');
    Route::get('new-connection-wzpdcl/check-payment/{app_id}', 'NewConnectionWZPDCLController@waitForPayment');
    Route::post('new-connection-wzpdcl/check-payment-info', 'NewConnectionWZPDCLController@checkPayment');
    Route::post('new-connection-wzpdcl/payment', 'NewConnectionWZPDCLController@wzpdclPayment');

    Route::get('new-connection-wzpdcl/view/additional-payment/{id}', "NewConnectionWZPDCLController@waitfordemandpayment");

    //demand fee payment
    Route::get('new-connection-wzpdcl/view-demand/{id}', "NewConnectionWZPDCLController@demandView");
    Route::get('new-connection-wzpdcl/view/additional-payment/{id}', "NewConnectionWZPDCLController@waitfordemandpayment");
    Route::post('new-connection-wzpdcl/check-payment-info-demand', 'NewConnectionWZPDCLController@checkDemandPayment');
    Route::post('new-connection-wzpdcl/payment-demand', 'NewConnectionWZPDCLController@additionalpayment');
    // end demand
    Route::get('licence-applications/new-connection-wzpdcl/afterPayment/{payment_id}', 'NewConnectionWZPDCLController@afterPayment');
    Route::get('licence-applications/new-connection-wzpdcl/afterCounterPayment/{payment_id}', 'NewConnectionWZPDCLController@afterCounterPayment');
    Route::post('new-connection-wzpdcl/delete-dynamic-doc', 'NewConnectionWZPDCLController@deleteDynamicDoc');

});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('new-connection-wzpdcl/list/{process_id}', 'ProcessPathController@processListById');
});
