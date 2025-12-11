<?php

Route::group(['module' => 'NewConnectionDESCO', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\NewConnectionDESCO\Controllers'], function () {

    Route::get('licence-applications/new-connection-desco/add', 'NewConnectionDESCOController@appForm');

    Route::post('new-connection-desco/store', 'NewConnectionDESCOController@appStore');
    Route::get('licence-applications/new-connection-desco/edit/{id}/{openMode}', "NewConnectionDESCOController@applicationViewEdit");
    Route::get('licence-applications/new-connection-desco/view/{id}/{openMode}', "NewConnectionDESCOController@applicationView");
    Route::post('new-connection-desco/upload-document', "NewConnectionDESCOController@uploadDocument");
    Route::get('new-connection-desco/get-refresh-token', "NewConnectionDESCOController@getRefreshToken");

    Route::post('new-connection-desco/shortfall', "NewConnectionDESCOController@shortfallDocSave");

    Route::post('new-connection-desco/get-dynamic-doc', 'NewConnectionDESCOController@getDynamicDoc');
    Route::get('new-connection-desco/check-payment/{app_id}', 'NewConnectionDESCOController@waitForPayment');
    Route::post('new-connection-desco/check-payment-info', 'NewConnectionDESCOController@checkPayment');
    Route::post('new-connection-desco/payment', 'NewConnectionDESCOController@descoPayment');

    Route::get('new-connection-desco/view/additional-payment/{id}', "NewConnectionDESCOController@waitfordemandpayment");

    Route::get('new-connection-desco/view/shortfall-document/{id}', "NewConnectionDESCOController@shortfallDoc");

    //Solar documents
    Route::get('new-connection-desco/view/solar-documents/{id}', "NewConnectionDESCOController@solarDocumentView");
    Route::post('new-connection-desco/view/solar-documents', "NewConnectionDESCOController@solarDocumentUpload");

    //demand fee payment
    Route::get('new-connection-desco/view-demand/{id}', "NewConnectionDESCOController@demandView");
    Route::get('new-connection-desco/view/additional-payment/{id}', "NewConnectionDESCOController@waitfordemandpayment");
    Route::post('new-connection-desco/check-payment-info-demand', 'NewConnectionDESCOController@checkDemandPayment');
    Route::post('new-connection-desco/payment-demand', 'NewConnectionDESCOController@additionalpayment');
    // end demand
    Route::get('licence-applications/new-connection-desco/afterPayment/{payment_id}', 'NewConnectionDESCOController@afterPayment');
    Route::get('new-connection-desco/afterCounterPayment/{payment_id}', 'NewConnectionDESCOController@afterCounterPayment');
    Route::post('new-connection-desco/delete-dynamic-doc', 'NewConnectionDESCOController@deleteDynamicDoc');

    /*nid validate */
    Route::post('new-connection-desco/validate-nid', 'NewConnectionDESCOController@validateNID');
});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('new-connection-desco/list/{process_id}', 'ProcessPathController@processListById');
});
