<?php

Route::group(['module' => 'ExternalLicense', 'prefix' => 'licence-applications/external-service',
    'middleware' => ['auth', 'checkAdmin','XssProtection'], 'namespace' => 'App\Modules\ExternalLicense\Controllers'], function () {
    Route::get('/add', 'ExternalLicenseController@appForm');
    Route::get('/edit/{id}/{openMode}', "ExternalLicenseController@applicationEdit");
    Route::get('/view/{id}/{openMode}', "ExternalLicenseController@applicationView");
    Route::get('/get-refresh-token', "ExternalLicenseController@getRefreshToken");
    Route::get('/afterPayment/{payment_id}', 'ExternalLicenseController@afterPayment');
    Route::get('/afterCounterPayment/{payment_id}', 'ExternalLicenseController@afterCounterPayment');
    Route::post('/status-check', 'ExternalLicenseController@getStatus');
    Route::post('/get-payment', 'ExternalLicenseController@getPayment');
    Route::post('/get-dynamic-doc', 'ExternalLicenseController@getDynamicDoc');
    Route::get('/preview-guideline/{process_id}', 'ExternalLicenseController@previewGuideline');
    Route::get('/preview-introduction/{process_id}', 'ExternalLicenseController@previewIntroduction');

});

Route::group(['module' => 'ExternalLicense', 'prefix' => 'licence-applications/external-service',
    'middleware' => ['auth', 'checkAdmin'], 'namespace' => 'App\Modules\ExternalLicense\Controllers'], function () {
    Route::post('/store', 'ExternalLicenseController@appStore');
});


Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('external-service/list/{process_id}', 'ProcessPathController@processListById');
});
