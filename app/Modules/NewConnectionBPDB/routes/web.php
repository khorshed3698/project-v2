<?php

Route::group(['module' => 'NewConnectionBPDB', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\NewConnectionBPDB\Controllers'], function () {

    Route::get('licence-applications/new-connection-bpdb/add', 'NewConnectionBPDBController@appForm');


    Route::post('new-connection-bpdb/store', 'NewConnectionBPDBController@appStore');
    Route::get('licence-applications/new-connection-bpdb/edit/{id}/{openMode}', "NewConnectionBPDBController@applicationViewEdit");
    Route::get('licence-applications/new-connection-bpdb/view/{id}/{openMode}', "NewConnectionBPDBController@applicationView");
    Route::post('new-connection-bpdb/upload-document', "NewConnectionBPDBController@uploadDocument");
    Route::get('new-connection-bpdb/get-refresh-token', "NewConnectionBPDBController@getRefreshToken");


    //    Route::get('bpdb/connection-area','NewConnectionBPDBController@connection');
    Route::post('new-connection-bpdb/get-dynamic-doc', 'NewConnectionBPDBController@getDynamicDoc');
    Route::get('new-connection-bpdb/check-payment/{app_id}', 'NewConnectionBPDBController@waitForPayment');
    Route::post('new-connection-bpdb/check-payment-info', 'NewConnectionBPDBController@checkPayment');
    Route::post('new-connection-bpdb/payment', 'NewConnectionBPDBController@BPDBPayment');

    //demand fee payment
    Route::get('new-connection-bpdb/view-demand/{id}', "NewConnectionBPDBController@demandView");
    Route::get('new-connection-bpdb/view/additional-payment/{id}', "NewConnectionBPDBController@waitfordemandpayment");
    Route::post('new-connection-bpdb/check-payment-info-demand', 'NewConnectionBPDBController@checkDemandPayment');
    Route::post('new-connection-bpdb/payment-demand', 'NewConnectionBPDBController@additionalpayment');
    // end demand
    Route::get('licence-applications/new-connection-bpdb/afterPayment/{payment_id}', 'NewConnectionBPDBController@afterPayment');
    Route::get('licence-applications/new-connection-bpdb/afterCounterPayment/{payment_id}', 'NewConnectionBPDBController@afterCounterPayment');
    Route::post('new-connection-bpdb/delete-dynamic-doc', 'NewConnectionBPDBController@deleteDynamicDoc');

});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('new-connection-bpdb/list/{process_id}', 'ProcessPathController@processListById');
});
