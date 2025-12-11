<?php

Route::group(['module' => 'SbAccountForeign', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\SbAccountForeign\Controllers'], function () {

    Route::get('licence-applications/sb-account-foreign/add', 'SbAccountForeignController@appForm');
    Route::get('licence-applications/sb-account-foreign/preview', 'SbAccountForeignController@preview');
    Route::post('sb-account-foreign/store', 'SbAccountForeignController@appStore');
    Route::get('licence-applications/sb-account-foreign/edit/{id}/{openMode}', "SbAccountForeignController@applicationViewEdit");
    Route::get('licence-applications/sb-account-foreign/view/{id}/{openMode}', "SbAccountForeignController@applicationView");
    Route::post('sb-account-foreign/upload-document', "SbAccountForeignController@uploadDocument");
    Route::get('licence-applications/sb-account-foreign/afterPayment/{payment_id}', 'SbAccountForeignController@afterPayment');
    Route::post('sb-account-foreign/delete-dynamic-doc', 'SbAccountForeignController@deleteDynamicDoc');
    Route::post('/sb-account-foreign/get-dynamic-doc', 'SbAccountForeignController@getDynamicDoc');
    Route::get('/sb-account-foreign/get-refresh-token', "SbAccountForeignController@getRefreshToken");

});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('sb-account-foreign/list/{process_id}', 'ProcessPathController@processListById');
});
