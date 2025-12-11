<?php

Route::group(['module' => 'OfficePermissionAmendment', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\OfficePermissionAmendment\Controllers'], function() {

    Route::get('office-permission-amendment/add', 'OfficePermissionAmendmentController@applicationForm');
    Route::post('office-permission-amendment/upload-document', "OfficePermissionAmendmentController@uploadDocument");
    Route::post('office-permission-amendment/store', 'OfficePermissionAmendmentController@appStore');
    Route::get('office-permission-amendment/edit/{id}/{openMode}', 'OfficePermissionAmendmentController@applicationViewEdit');
    Route::get('office-permission-amendment/view/{id}/{openMode}', 'OfficePermissionAmendmentController@applicationViewEdit');
    Route::get('office-permission-amendment/view/{id}', 'OfficePermissionAmendmentController@applicationView');
    Route::get('office-permission-amendment/preview', "OfficePermissionAmendmentController@preview");
    Route::get('office-permission-amendment/app-pdf/{id}', 'OfficePermissionAmendmentController@appFormPdf');

    Route::post('office-permission-amendment/payment', "OfficePermissionAmendmentController@Payment");
    Route::get('office-permission-amendment/afterPayment/{payment_id}', 'OfficePermissionAmendmentController@afterPayment');
    Route::get('office-permission-amendment/afterCounterPayment/{payment_id}', 'OfficePermissionAmendmentController@afterCounterPayment');
    Route::post('office-permission-amendment/getDocList', 'OfficePermissionAmendmentController@getDocList');

    Route::post('office-permission-amendment/conditionalApproveStore', 'OfficePermissionAmendmentController@conditionalApproveStore');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('office-permission-amendment/list/{process_id}', 'ProcessPathController@processListById');
});
