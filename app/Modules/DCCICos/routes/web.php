<?php
Route::group(['module' => 'DCCICos', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\DCCICos\Controllers'], function () {

    Route::get('licence-applications/dcci-cos/add', 'DCCICosController@appForm');

    Route::post('dcci-cos/store', 'DCCICosController@appStore');
    Route::get('licence-applications/dcci-cos/edit/{id}/{openMode}', "DCCICosController@applicationEdit");
    Route::get('licence-applications/dcci-cos/view/{id}/{openMode}', "DCCICosController@applicationView");
    Route::post('dcci-cos/upload-document', "DCCICosController@uploadDocument");
    Route::get('dcci-cos/get-refresh-token', "DCCICosController@getRefreshToken");
    Route::post('dcci-cos/getDocList', 'DCCICosController@getDocList');
    Route::post('dcci-cos/search-hsCode', "DCCICosController@hsCodeSearch");

    Route::post('dcci-cos/get-unit-price', 'DCCICosController@getUnitPrice');
    Route::post('dcci-cos/member-info', 'DCCICosController@dcciUserInfo');

    Route::post('dcci-cos/get-dynamic-doc', 'DCCICosController@getDynamicDoc');
    Route::get('dcci-cos/check-payment/{app_id}', 'DCCICosController@waitForPayment');
    Route::post('dcci-cos/check-payment-info', 'DCCICosController@checkPayment');
    Route::post('dcci-cos/payment', 'DCCICosController@dcciPayment');

    Route::get('licence-applications/dcci-cos/afterPayment/{payment_id}', 'DCCICosController@afterPayment');
    Route::get('licence-applications/dcci-cos/afterCounterPayment/{payment_id}', 'DCCICosController@afterCounterPayment');
    Route::post('dcci-cos/delete-dynamic-doc', 'DCCICosController@deleteDynamicDoc');

});

Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('dcci-cos/list/{process_id}', 'ProcessPathController@processListById');
});
