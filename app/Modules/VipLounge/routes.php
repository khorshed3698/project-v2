<?php

Route::group(['module' => 'VipLounge', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\VipLounge\Controllers'], function() {

    Route::get('vip-lounge/add', 'VipLoungeController@applicationForm');
    Route::get('vip-lounge/edit/{id}/{openMode}', "VipLoungeController@applicationEdit");
    Route::get('vip-lounge/view/{id}', "VipLoungeController@applicationView");
    Route::post('vip-lounge/store', 'VipLoungeController@appStore');
    Route::get('/vip-lounge/preview', "VipLoungeController@preview");
    Route::post('vip-lounge/upload-document', "VipLoungeController@uploadDocument");
    Route::get('vip-lounge/app-pdf/{id}', 'VipLoungeController@appFormPdf');
    Route::post('/vip-lounge/getDocList', "VipLoungeController@loadDocList");
    Route::get('vip-lounge/afterPayment/{payment_id}', 'VipLoungeController@afterPayment');
    Route::get('vip-lounge/afterCounterPayment/{payment_id}', 'VipLoungeController@afterCounterPayment');

});


Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('vip-lounge/list/{process_id}', 'ProcessPathController@processListById');
});

