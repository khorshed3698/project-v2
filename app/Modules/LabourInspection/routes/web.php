<?php
Route::group(['module' => 'LabourInspection', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\LabourInspection\Controllers'], function () {

    Route::get('licence-applications/labour-inspection-application/add', 'LabourInspectionController@appForm');
    Route::post('licence-applications/labour-inspection-application/store', 'LabourInspectionController@appStore');
    Route::get('licence-applications/labour-inspection-application/edit/{id}/{openMode}', "LabourInspectionController@appFormEdit");
    Route::get('licence-applications/labour-inspection-application/view/{id}/{openMode}', "LabourInspectionController@appFormView");

    // Dynamic Doc Route
    Route::post('licence-applications/dife/get-dynamic-doc', 'LabourInspectionController@getDynamicDoc');
    Route::post('licence-applications/dife/upload-document', "LabourInspectionController@uploadDocument");






    Route::get('labour-inspection-application/get-refresh-token', "LabourInspectionController@getRefreshToken");
    Route::post('labour-inspection-application/getDocList', 'LabourInspectionController@getDocList');
    Route::post('labour-inspection-application/shortfall', "LabourInspectionController@shortfallDoc");
    Route::get('labour-inspection-application/check-payment/{app_id}', 'LabourInspectionController@waitForPayment');
    Route::post('labour-inspection-application/check-payment-info', 'LabourInspectionController@checkPayment');
    Route::post('labour-inspection-application/payment', 'LabourInspectionController@dcciPayment');
    Route::post('labour-inspection-application/check_status', 'LabourInspectionController@checkStatus');

    // end demand
    Route::get('licence-applications/labour-inspection-application/afterPayment/{payment_id}', 'LabourInspectionController@afterPayment');
    Route::get('licence-applications/labour-inspection-application/afterCounterPayment/{payment_id}', 'LabourInspectionController@afterCounterPayment');
    Route::post('labour-inspection-application/delete-dynamic-doc', 'LabourInspectionController@deleteDynamicDoc');

    /*check submission*/
    Route::post('labour-inspection-application/check-api-request-status', 'LabourInspectionController@checkApiRequestStatus');

    // Ajax
    Route::post('licence-applications/labour-inspection-application/ajax/managing-authority-modal', 'LabourInspectionController@managingAuthorityModal');
    Route::post('licence-applications/labour-inspection-application/ajax/managing-authority-form', 'LabourInspectionController@managingAuthorityForm');
});

Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('labour-inspection-application/list/{process_id}', 'ProcessPathController@processListById');
});
