<?php

Route::group(['module' => 'CdaForm', 'middleware' => ['auth','XssProtection'], 'namespace' => 'App\Modules\CdaForm\Controllers'], function () {

    Route::get('licence-applications/cda-form/add', "CdaFormController@appForm");
    Route::post('cda-form/store', 'CdaFormController@appStore');

    Route::post('/cda-form/store-resubmission', "CdaFormController@storeResubmitInfo");

    Route::post('licence-application/cda-form/add', "CdaFormController@appStore");
    Route::get('licence-applications/cda-form/view/{id}/{openMode}', "CdaFormController@applicationViewEdit");

    Route::post('cda-form/getDocList', 'CdaFormController@getDocList');
    Route::post('cda-form/payment', 'CdaFormController@cdapayment');
    Route::get('cda-form/check-payment/{app_id}', 'CdaFormController@waitForPayment');

    Route::get('cda-form/check-submission/{app_id}', 'CdaFormController@checkstatus');
    Route::get('cda-form/check-cda-application-status', 'CdaFormController@applicationstatus');
    Route::get('licence-applications/cda-form/afterPayment/{payment_id}', 'CdaFormController@afterPayment');
    Route::get('licence-applications/cda-form/afterCounterPayment/{payment_id}', 'CdaFormController@afterCounterPayment');
    //    API ROUTES
    Route::get('cda-form/land-use-list', "CdaFormController@getLandUseList");
    Route::get('cda-form/land-use-details-list/{land_Category}', "CdaFormController@getLandUseDetailList");
    Route::get('cda-form/sector-list', "CdaFormController@getSectorList");
    Route::get('cda-form/city-list', "CdaFormController@getCityList");
    Route::get('cda-form/thana-list', "CdaFormController@getThanaList");
    Route::get('cda-form/mouza-list/{thana_ID}', "CdaFormController@getMouzaList");
    Route::get('cda-form/block-list', "CdaFormController@getBlockList");
    Route::get('cda-form/sit-list', "CdaFormController@getSitList");
    Route::get('cda-form/ward-list', "CdaFormController@getWardList");

    Route::get('cda-form/get-refresh-token', "CdaFormController@getRefreshToken");

    /*upload document*/
    Route::post('cda-form/upload-document', 'CdaFormController@uploadDocument');

    Route::post('cda-form/getDocList', 'CdaFormController@getDocList');
});
