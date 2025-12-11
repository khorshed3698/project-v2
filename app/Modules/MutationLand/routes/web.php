<?php
Route::group(['module' => 'MutationLand', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\MutationLand\Controllers'], function () {

    Route::get('licence-applications/mutation-land/add', 'MutationLandController@appForm');

    Route::post('mutation-land/store', 'MutationLandController@appStore');
    Route::get('licence-applications/mutation-land/edit/{id}/{openMode}', "MutationLandController@appFormEdit");
    Route::get('licence-applications/mutation-land/view/{id}/{openMode}', "MutationLandController@appFormView");
    Route::post('mutation-land/upload-document', "MutationLandController@uploadDocument");
    Route::get('mutation-land/get-refresh-token', "MutationLandController@getRefreshToken");
    Route::post('mutation-land/getDocList', 'MutationLandController@getDocList');
    Route::post('mutation-land/shortfall', "MutationLandController@shortfallDoc");

    Route::post('mutation-land/get-dynamic-doc', 'MutationLandController@getDynamicDoc');
    Route::get('mutation-land/check-payment/{app_id}', 'MutationLandController@waitForPayment');
    Route::post('mutation-land/check-payment-info', 'MutationLandController@checkPayment');
    Route::post('mutation-land/payment', 'MutationLandController@dcciPayment');
    Route::post('mutation-land/check_status', 'MutationLandController@checkStatus');

    // end demand
    Route::get('licence-applications/mutation-land/afterPayment/{payment_id}', 'MutationLandController@afterPayment');
    Route::get('licence-applications/mutation-land/afterCounterPayment/{payment_id}', 'MutationLandController@afterCounterPayment');
    Route::post('mutation-land/delete-dynamic-doc', 'MutationLandController@deleteDynamicDoc');

    /*check submission*/
    Route::post('mutation-land/check-api-request-status', 'MutationLandController@checkApiRequestStatus');
});

Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('mutation-land/list/{process_id}', 'ProcessPathController@processListById');
});
