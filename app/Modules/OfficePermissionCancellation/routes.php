<?php

Route::group(['module' => 'OfficePermissionCancellation', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\OfficePermissionCancellation\Controllers'], function() {

    Route::get('office-permission-cancellation/add', 'OfficePermissionCancellationController@applicationForm');
    Route::post('office-permission-cancellation/store', 'OfficePermissionCancellationController@appStore');
    Route::get('office-permission-cancellation/edit/{id}/{openMode}', 'OfficePermissionCancellationController@applicationViewEdit');
    Route::get('office-permission-cancellation/view/{id}/{openMode}', 'OfficePermissionCancellationController@applicationViewEdit');
    Route::get('office-permission-cancellation/view/{id}', 'OfficePermissionCancellationController@applicationView');
    Route::post('office-permission-cancellation/upload-document', "OfficePermissionCancellationController@uploadDocument");
    Route::post('office-permission-cancellation/payment', "OfficePermissionCancellationController@Payment");
    Route::get('/office-permission-cancellation/preview', "OfficePermissionCancellationController@preview");
    Route::get('office-permission-cancellation/app-pdf/{id}', 'OfficePermissionCancellationController@appFormPdf');
    Route::post('/office-permission-cancellation/getDocList', 'OfficePermissionCancellationController@getDocList');



    Route::get('office-permission-cancellation/payment-confirmation', 'OfficePermissionCancellationController@paymentConfirmation');
    Route::post('office-permission-cancellation/payment-success', 'OfficePermissionCancellationController@paymentSuccess');
    Route::post('office-permission-cancellation/payment-failed', 'OfficePermissionCancellationController@paymentFailed');
    Route::post('office-permission-cancellation/payment-cancelled', 'OfficePermissionCancellationController@paymentCancelled');
    Route::get('office-permission-cancellation/afterPayment/{payment_id}', 'OfficePermissionCancellationController@afterPayment');
    Route::get('office-permission-cancellation/afterCounterPayment/{payment_id}', 'OfficePermissionCancellationController@afterCounterPayment');
});






Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('office-permission-cancellation/list/{process_id}', 'ProcessPathController@processListById');
});