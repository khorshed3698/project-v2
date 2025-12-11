<?php

Route::group(['module' => 'SBaccount', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\SBaccount\Controllers'], function() {

    Route::get('licence-applications/sb-account/add', 'SBaccountController@appForm');
    Route::get('licence-applications/sb-account/preview', 'SBaccountController@preview');
    Route::post('sb-account/store', 'SBaccountController@appStore');
    Route::get('licence-applications/sb-account/edit/{id}/{openMode}', "SBaccountController@applicationViewEdit");
    Route::get('licence-applications/sb-account/view/{id}/{openMode}', "SBaccountController@applicationView");
    Route::post('sb-account/upload-document', "SBaccountController@uploadDocument");
    Route::get('licence-applications/sb-account/afterPayment/{payment_id}', 'SBaccountController@afterPayment');
    Route::post('sb-account/delete-dynamic-doc', 'SBaccountController@deleteDynamicDoc');
    Route::post('/sb-account/get-dynamic-doc', 'SBaccountController@getDynamicDoc');
    Route::get('/sb-account/get-refresh-token', "SBaccountController@getRefreshToken");

});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('sb-account/list/{process_id}', 'ProcessPathController@processListById');
});
