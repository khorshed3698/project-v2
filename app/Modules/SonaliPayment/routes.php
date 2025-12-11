<?php

Route::group(['module' => 'SonaliPayment', 'middleware' => ['auth','checkAdmin'], 'namespace' => 'App\Modules\SonaliPayment\Controllers'], function () {

    Route::get('/spg', 'SonaliPaymentController@index');
    Route::get('/spg/application_submission_payment/{process_type_id}/{app_id}', 'SonaliPaymentController@AppSubmissionPayment');
    Route::get('/spg/initiate', 'SonaliPaymentController@initiatePayment');
//    Route::get('/spg/initiate/stack-holder/{id}', 'SonaliPaymentStackHolderController@initiatePayment');
    Route::get('/spg/initiate/{id}', 'SonaliPaymentController@initiatePayment');
    Route::post('/spg/list', 'SonaliPaymentController@paymentList');
    Route::post('/spg/unverified-list', 'SonaliPaymentController@paymentUnverifiedList');
    Route::get('/spg/search', 'SonaliPaymentController@getPaymentList');
    Route::get('/spg/verify/{id}', 'SonaliPaymentController@verifyTransaction');
    Route::get('/spg/verifyAndComplete/{id}', 'SonaliPaymentController@verifyAndComplete');
    Route::get('/spg/payment-history/{id}', 'SonaliPaymentController@indivPaymentHistory');
    Route::post('/spg/history-data/', 'SonaliPaymentController@indivPaymentHistoryData');
//    Route::get('/spg/history-verify/{id}/{histId}', 'SonaliPaymentController@verifyTransactionHistory');
    Route::get('/spg/ref-verification/{id}', 'SonaliPaymentController@verifyTransactionByRefNo');

    Route::get('/spg/payment-voucher/{id}', 'SonaliPaymentController@paymentVoucher');
    Route::get('/spg/counter-payment-voucher/{id}', 'SonaliPaymentController@counterPaymentVoucher');
    Route::get('/spg/counter-payment-check/{id}/{status}', 'SonaliPaymentController@counterPaymentCheck');


//    Route::post('/spg/stack-holder/callback', 'SonaliPaymentStackHolderController@callback');
    Route::post('/spg/callback', 'SonaliPaymentController@callback');
//    Route::get('/spg/daily-tansaction', 'SonaliPaymentController@dailyTransaction');
    Route::get('/spg/offline-payment-verify', 'SonaliPaymentController@offlinePaymentVerify');

    // Multiple payment
    Route::get('/spg/initiate-multiple/{id}', 'SonaliPaymentController@initiatePaymentMultiple');
    Route::post('/spg/callbackM', 'SonaliPaymentController@callbackMultiple');


});


Route::group(['module' => 'SonaliPayment', 'namespace' => 'App\Modules\SonaliPayment\Controllers'], function() {

    Route::post('api/sp-ipn', 'IpnController@apiIpnRequestPOST');
});
