<?php
Route::group(['module' => 'LimaLayoutPlan', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\LimaLayoutPlan\Controllers'], function () {

    Route::get('licence-applications/lima-factory-layout/add', 'LimaLayoutPlanController@appForm');
    Route::post('licence-applications/lima-factory-layout/store', 'LimaLayoutPlanController@appStore');
    Route::get('licence-applications/lima-factory-layout/edit/{id}/{openMode}', "LimaLayoutPlanController@appFormEdit");
    Route::get('licence-applications/lima-factory-layout/view/{id}/{openMode}', "LimaLayoutPlanController@appFormView");

    // Dynamic Doc Route
    Route::post('licence-applications/lima-factory-layout/get-dynamic-doc', 'LimaLayoutPlanController@getDynamicDoc');
    Route::post('licence-applications/lima-factory-layout/upload-document', "LimaLayoutPlanController@uploadDocument");


    Route::get('lima-factory-layout/get-refresh-token', "LimaLayoutPlanController@getRefreshToken");
    Route::post('lima-factory-layout/getDocList', 'LimaLayoutPlanController@getDocList');
    Route::post('lima-factory-layout/shortfall', "LimaLayoutPlanController@shortfallDoc");
    Route::get('lima-factory-layout/check-payment/{app_id}', 'LimaLayoutPlanController@waitForPayment');
    Route::post('lima-factory-layout/check-payment-info', 'LimaLayoutPlanController@checkPayment');
    Route::post('lima-factory-layout/payment', 'LimaLayoutPlanController@dcciPayment');
    Route::post('licence-applications/lima-factory-layout/check_status', 'LimaLayoutPlanController@checkStatus');

    // end demand
    Route::get('licence-applications/lima-factory-layout/afterPayment/{payment_id}', 'LimaLayoutPlanController@afterPayment');
    Route::get('licence-applications/lima-factory-layout/afterCounterPayment/{payment_id}', 'LimaLayoutPlanController@afterCounterPayment');
    Route::post('lima-factory-layout/delete-dynamic-doc', 'LimaLayoutPlanController@deleteDynamicDoc');

    /*check submission*/
    Route::post('lima-factory-layout/check-api-request-status', 'LimaLayoutPlanController@checkApiRequestStatus');

    // Ajax
    Route::post('licence-applications/lima-factory-layout/ajax/managing-authority-modal', 'LimaLayoutPlanController@managingAuthorityModal');
    Route::post('licence-applications/lima-factory-layout/ajax/managing-authority-form', 'LimaLayoutPlanController@managingAuthorityForm');
});

Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('lima-factory-layout/list/{process_id}', 'ProcessPathController@processListById');
});
