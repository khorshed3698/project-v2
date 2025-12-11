<?php

Route::group(['module' => 'ERC', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\ERC\Controllers'], function() {

    Route::get('licence-applications/erc/add', "ERCController@appForm");
    Route::get('erc/getData/{app_id}', "ERCController@appGetData");
    Route::post('erc/store', 'ERCController@appStore');
    Route::post('licence-application/erc/add', "ERCController@appStore");
    Route::get('licence-applications/erc/edit/{id}/{openMode}', "ERCController@applicationViewEdit");
    Route::get('licence-applications/erc/view/{id}/{openMode}', "ERCController@applicationView");
    Route::get('licence-applications/erc/shortfall-form/{app_id}', 'ERCController@showShortfallForm');
    Route::post('licence-applications/erc/shortfall-form/store', 'ERCController@storeShortfall');
    Route::post('erc/getDocList', 'ERCController@getDocList');
    Route::post('erc/get-dynamic-doc', 'ERCController@getDynamicDoc');
    Route::post('erc/upload-document', "ERCController@uploadDocument");
    Route::get('licence-applications/erc/check-payment/{app_id}', 'ERCController@waitForPayment');
    Route::post('licence-applications/erc/check-payment-info', 'ERCController@checkPayment');
    Route::post('licence-applications/erc/payment', 'ERCController@cciePayment');
    Route::get('licence-applications/erc/afterPayment/{payment_id}', 'ERCController@afterPayment');
    Route::get('licence-applications/erc/afterCounterPayment/{payment_id}', 'ERCController@afterCounterPayment');

    // API calls ends

    Route::get('erc/get-refresh-token', "IndustrialIrcController@getRefreshToken");
});
Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('erc/list/{process_id}', 'ProcessPathController@processListById');
});
