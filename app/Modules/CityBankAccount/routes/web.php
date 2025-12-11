<?php

Route::group(['module' => 'CityBankAccount', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\CityBankAccount\Controllers'], function() {

    Route::get('licence-applications/city-bank-account/add', 'CityBankAccountController@appForm');
    Route::get('licence-applications/city-bank-account/preview', 'CityBankAccountController@preview');
    Route::post('city-bank-account/store', 'CityBankAccountController@appStore');
    Route::get('licence-applications/city-bank-account/edit/{id}/{openMode}', "CityBankAccountController@applicationViewEdit");
    Route::get('licence-applications/city-bank-account/view/{id}/{openMode}', "CityBankAccountController@applicationView");
    Route::post('city-bank-account/upload-document', "CityBankAccountController@uploadDocument");
    Route::get('licence-applications/city-bank-account/afterPayment/{payment_id}', 'CityBankAccountController@afterPayment');
    Route::post('city-bank-account/delete-dynamic-doc', 'CityBankAccountController@deleteDynamicDoc');
    Route::post('/city-bank-account/get-dynamic-doc', 'CityBankAccountController@getDynamicDoc');
    Route::get('/city-bank-account/get-refresh-token', "CityBankAccountController@getRefreshToken");

});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('city-bank-account/list/{process_id}', 'ProcessPathController@processListById');
});
