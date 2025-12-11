<?php

Route::group(['module' => 'NewConnectionNESCO', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\NewConnectionNESCO\Controllers'], function () {

    Route::get('licence-applications/new-connection-nesco/add', 'NewConnectionNESCOController@appForm');

    Route::post('new-connection-nesco/store', 'NewConnectionNESCOController@appStore');
    Route::get('licence-applications/new-connection-nesco/edit/{id}/{openMode}', "NewConnectionNESCOController@applicationViewEdit");
    Route::get('licence-applications/new-connection-nesco/view/{id}/{openMode}', "NewConnectionNESCOController@applicationView");
    Route::post('new-connection-nesco/upload-document', "NewConnectionNESCOController@uploadDocument");
    Route::get('new-connection-nesco/get-refresh-token', "NewConnectionNESCOController@getRefreshToken");

    Route::post('new-connection-nesco/shortfall', "NewConnectionNESCOController@shortfallDoc");

    //    Route::get('nesco/connection-area','NewConnectionNESCOController@connection');
    Route::post('new-connection-nesco/validate-nid', 'NewConnectionNESCOController@validateNID');
    Route::post('new-connection-nesco/get-dynamic-doc', 'NewConnectionNESCOController@getDynamicDoc');
    Route::get('new-connection-nesco/check-payment/{app_id}', 'NewConnectionNESCOController@waitForPayment');
    Route::post('new-connection-nesco/check-payment-info', 'NewConnectionNESCOController@checkPayment');
    Route::post('new-connection-nesco/payment', 'NewConnectionNESCOController@nescoPayment');

    Route::get('new-connection-nesco/view/additional-payment/{id}', "NewConnectionNESCOController@waitfordemandpayment");

    //demand fee payment
    Route::get('new-connection-nesco/view-demand/{id}', "NewConnectionNESCOController@demandView");
    Route::get('new-connection-nesco/view/additional-payment/{id}', "NewConnectionNESCOController@waitfordemandpayment");
    Route::post('new-connection-nesco/check-payment-info-demand', 'NewConnectionNESCOController@checkDemandPayment');
    Route::post('new-connection-nesco/payment-demand', 'NewConnectionNESCOController@additionalpayment');
    // end demand
    Route::get('licence-applications/new-connection-nesco/afterPayment/{payment_id}', 'NewConnectionNESCOController@afterPayment');
    Route::get('licence-applications/new-connection-nesco/afterCounterPayment/{payment_id}', 'NewConnectionNESCOController@afterCounterPayment');
    Route::post('new-connection-nesco/delete-dynamic-doc', 'NewConnectionNESCOController@deleteDynamicDoc');

});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('new-connection-nesco/list/{process_id}', 'ProcessPathController@processListById');
});
