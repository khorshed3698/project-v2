<?php

Route::group(['module' => 'CTCC', 'middleware' => ['XssProtection'], 'namespace' => 'App\Modules\CTCC\Controllers'], function() {
    Route::get('ctcc/test-base64', 'CTCCController@TestBase64');
});
Route::group(['module' => 'CTCC', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\CTCC\Controllers'], function() {

    Route::get('licence-applications/ctcc/add', 'CTCCController@appForm');
    Route::get('licence-applications/ctcc/terms-condition/{id}', 'CTCCController@CCCtermsCondition');
    Route::post('ctcc/store', 'CTCCController@appStore');
    Route::get('licence-applications/ctcc/edit/{id}/{openMode}', "CTCCController@applicationViewEdit");
    Route::get('licence-applications/ctcc/view/{id}/{openMode}', "CTCCController@applicationView");
    Route::get('ctcc/get-refresh-token', "CTCCController@getRefreshToken");
    Route::post('ctcc/get-dynamic-doc', 'CTCCController@getDynamicDoc');
    Route::post('ctcc/upload-document', "CTCCController@uploadDocument");
    Route::post('ctcc/shortfall', "CTCCController@shortfallDoc");
    Route::get('ctcc/check-payment/{app_id}', 'CTCCController@waitForPayment');
    Route::post('ctcc/check-payment-info', 'CTCCController@checkPayment');
    Route::post('ctcc/payment', 'CTCCController@ctccPayment');

    Route::get('ctcc/view/additional-payment/{id}', "CTCCController@waitfordemandpayment");

    //demand fee payment
    Route::get('ctcc/view-demand/{id}', "CTCCController@demandView");
    Route::get('ctcc/view/additional-payment/{id}', "CTCCController@waitfordemandpayment");
    Route::post('ctcc/check-payment-info-demand', 'CTCCController@checkDemandPayment');
    Route::post('ctcc/payment-demand', 'CTCCController@additionalpayment');
    // end demand
    Route::get('licence-applications/ctcc/afterPayment/{payment_id}', 'CTCCController@afterPayment');
    Route::get('licence-applications/ctcc/afterCounterPayment/{payment_id}', 'CTCCController@afterCounterPayment');
    Route::post('ctcc/delete-dynamic-doc', 'CTCCController@deleteDynamicDoc');

});

Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('ctcc/list/{process_id}', 'ProcessPathController@processListById');
});
