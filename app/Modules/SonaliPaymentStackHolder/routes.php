<?php

Route::group(['module' => 'SonaliPaymentStackHolder', 'middleware' => ['auth','checkAdmin'], 'namespace' => 'App\Modules\SonaliPaymentStackHolder\Controllers'], function () {

    Route::get('/spg/stack-holder', 'SonaliPaymentStackHolderController@index');
    Route::post('/spg/stack-holder/list', 'SonaliPaymentStackHolderController@paymentList');
    Route::get('/spg/stack-holder/verifyAndComplete/{id}', 'SonaliPaymentStackHolderController@verifyAndComplete');
    Route::get('/spg/stack-holder/{id}', 'SonaliPaymentStackHolderController@verifyTransaction');
    Route::get('/spg/stack-holder/verify/{id}', 'SonaliPaymentStackHolderController@verifyTransaction');
    Route::get('/spg/stack-holder/payment-history/{id}', 'SonaliPaymentStackHolderController@indivPaymentHistory');

    Route::post('/spg/stack-holder/history-data/', 'SonaliPaymentStackHolderController@indivPaymentHistoryData');
    Route::get('/spg/stack-holder/history-verify/{id}/{histId}', 'SonaliPaymentStackHolderController@verifyTransactionHistory');
    Route::get('/spg/ss/search', 'SonaliPaymentStackHolderController@getPaymentList');
    Route::get('/spg/stack-holder/ref-verification/{id}', 'SonaliPaymentStackHolderController@verifyTransactionByRefNo');


    Route::get('/spg/initiate/stack-holder/{id}', 'SonaliPaymentStackHolderController@initiatePayment');
    Route::get('/spg/initiate-multiple/stack-holder/{id}', 'SonaliPaymentStackHolderController@initiatePaymentMultiple');
    Route::post('/spg/stack-holder/callback', 'SonaliPaymentStackHolderController@callback');
    Route::post('/spg/stack-holder/callbackM', 'SonaliPaymentStackHolderController@callbackMultiple');
    Route::get('/spg/stack-holder/counter-payment-check/{id}/{status}', 'SonaliPaymentStackHolderController@counterPaymentCheck');
    Route::get('/spg/stack-holder/counter-payment-voucher/{id}', 'SonaliPaymentStackHolderController@counterPaymentVoucher');
    Route::get('/spg/stack-holder/payment-voucher/{id}', 'SonaliPaymentStackHolderController@paymentVoucher');

});
