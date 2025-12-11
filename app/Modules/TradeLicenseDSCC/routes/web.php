<?php

Route::group(['module' => 'TradeLicenseDSCC', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\TradeLicenseDSCC\Controllers'], function () {

    Route::get('licence-applications/trade-license-dscc/add', 'TradeLicenseDSCCController@appForm');

    Route::post('trade-license-dscc/store', 'TradeLicenseDSCCController@appStore');
    Route::get('licence-applications/trade-license-dscc/edit/{id}/{openMode}', "TradeLicenseDSCCController@applicationViewEdit");
    Route::get('licence-applications/trade-license-dscc/view/{id}/{openMode}', "TradeLicenseDSCCController@applicationView");
    Route::post('trade-license-dscc/upload-document', "TradeLicenseDSCCController@uploadDocument");
    Route::get('trade-license-dscc/get-refresh-token', "TradeLicenseDSCCController@getRefreshToken");

    Route::post('trade-license-dscc/shortfall', "TradeLicenseDSCCController@shortfallDoc");

    //    Route::get('desco/connection-area','TradeLicenseDSCCController@connection');
    Route::post('trade-license-dscc/get-dynamic-doc', 'TradeLicenseDSCCController@getDynamicDoc');
    Route::get('trade-license-dscc/check-payment/{app_id}', 'TradeLicenseDSCCController@waitForPayment');
    Route::post('trade-license-dscc/check-payment-info', 'TradeLicenseDSCCController@checkPayment');
    Route::post('trade-license-dscc/payment', 'TradeLicenseDSCCController@dsccPayment');

    Route::get('trade-license-dscc/view/additional-payment/{id}', "TradeLicenseDSCCController@waitfordemandpayment");

    //demand fee payment
    Route::get('trade-license-dscc/view-demand/{id}', "TradeLicenseDSCCController@demandView");
    Route::get('trade-license-dscc/view/additional-payment/{id}', "TradeLicenseDSCCController@waitfordemandpayment");
    Route::post('trade-license-dscc/check-payment-info-demand', 'TradeLicenseDSCCController@checkDemandPayment');
    Route::post('trade-license-dscc/payment-demand', 'TradeLicenseDSCCController@additionalpayment');
    // end demand
    Route::get('licence-applications/trade-license-dscc/afterPayment/{payment_id}', 'TradeLicenseDSCCController@afterPayment');
    Route::get('licence-applications/trade-license-dscc/afterCounterPayment/{payment_id}', 'TradeLicenseDSCCController@afterCounterPayment');
    Route::post('trade-license-dscc/delete-dynamic-doc', 'TradeLicenseDSCCController@deleteDynamicDoc');

});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('trade-license-dscc/list/{process_id}', 'ProcessPathController@processListById');
});
