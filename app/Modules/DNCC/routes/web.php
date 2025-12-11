<?php
Route::group(['module' => 'DNCC', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\DNCC\Controllers'], function () {

    Route::get('licence-applications/dncc/add', 'TradeLicenseDNCCController@appForm');
    Route::post('dncc/store', 'TradeLicenseDNCCController@appStore');
    Route::get('licence-applications/dncc/edit/{id}/{openMode}', "TradeLicenseDNCCController@applicationViewEdit");
    Route::get('licence-applications/dncc/view/{id}/{openMode}', "TradeLicenseDNCCController@applicationView");
    Route::post('dncc/upload-document', "TradeLicenseDNCCController@uploadDocument");
    Route::get('dncc/get-refresh-token', "TradeLicenseDNCCController@getRefreshToken");

    Route::post('dncc/shortfall', "TradeLicenseDNCCController@shortfallDoc");

    Route::post('dncc/get-dynamic-doc', 'TradeLicenseDNCCController@getDynamicDoc');
    Route::get('dncc/check-payment/{app_id}', 'TradeLicenseDNCCController@waitForPayment');
    Route::post('dncc/check-payment-info', 'TradeLicenseDNCCController@checkPayment');
    Route::post('dncc/payment', 'TradeLicenseDNCCController@dnccPayment');

    //demand fee payment
    Route::get('dncc/view-demand/{id}', "TradeLicenseDNCCController@demandView");
    Route::get('dncc/view/additional-payment/{id}', "TradeLicenseDNCCController@waitfordemandpayment");
    Route::post('dncc/check-payment-info-demand', 'TradeLicenseDNCCController@checkDemandPayment');
    Route::post('dncc/payment-demand', 'TradeLicenseDNCCController@additionalpayment');
    // end demand
    Route::get('licence-applications/dncc/afterPayment/{payment_id}', 'TradeLicenseDNCCController@afterPayment');
    Route::get('licence-applications/dncc/afterCounterPayment/{payment_id}', 'TradeLicenseDNCCController@afterCounterPayment');
    Route::post('dncc/delete-dynamic-doc', 'TradeLicenseDNCCController@deleteDynamicDoc');

});

Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('dncc/list/{process_id}', 'ProcessPathController@processListById');
});
