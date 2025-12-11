<?php
Route::group(['module' => 'RajukLUCGeneral', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\RajukLUCGeneral\Controllers'], function () {

    Route::get('licence-applications/rajuk-luc-general/add', 'RajukLUCGeneralController@appForm');

    Route::post('rajuk-luc-general/store', 'RajukLUCGeneralController@appStore');
    Route::get('licence-applications/rajuk-luc-general/edit/{id}/{openMode}', "RajukLUCGeneralController@applicationEdit");
    Route::get('licence-applications/rajuk-luc-general/view/{id}/{openMode}', "RajukLUCGeneralController@applicationView");
    Route::post('rajuk-luc-general/upload-document', "RajukLUCGeneralController@uploadDocument");
    Route::get('rajuk-luc-general/get-refresh-token', "RajukLUCGeneralController@getRefreshToken");
    Route::post('rajuk-luc-general/getDocList', 'RajukLUCGeneralController@getDocList');
    Route::post('rajuk-luc-general/shortfall', "RajukLUCGeneralController@shortfallDoc");

    Route::post('rajuk-luc-general/get-dynamic-doc', 'RajukLUCGeneralController@getDynamicDoc');
    Route::get('rajuk-luc-general/check-payment/{app_id}', 'RajukLUCGeneralController@waitForPayment');
    Route::post('rajuk-luc-general/check-payment-info', 'RajukLUCGeneralController@checkPayment');
    Route::post('rajuk-luc-general/payment', 'RajukLUCGeneralController@dcciPayment');

    // end demand
    Route::get('licence-applications/rajuk-luc-general/afterPayment/{payment_id}', 'RajukLUCGeneralController@afterPayment');
    Route::get('rajuk-luc-general/afterCounterPayment/{payment_id}', 'RajukLUCGeneralController@afterCounterPayment');
    Route::post('rajuk-luc-general/delete-dynamic-doc', 'RajukLUCGeneralController@deleteDynamicDoc');

    /*check submission*/
    Route::post('rajuk-luc-general/check-api-request-status', 'RajukLUCGeneralController@checkApiRequestStatus');
});

Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('rajuk-luc-general/list/{process_id}', 'ProcessPathController@processListById');
});
