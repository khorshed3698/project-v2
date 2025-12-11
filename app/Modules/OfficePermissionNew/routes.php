<?php

Route::group(['module' => 'OfficePermissionNew', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\OfficePermissionNew\Controllers'], function() {

    Route::get('office-permission-new/add', 'OfficePermissionNewController@applicationForm');
    Route::post('office-permission-new/upload-document', "OfficePermissionNewController@uploadDocument");
    Route::post('office-permission-new/store', 'OfficePermissionNewController@appStore');
    Route::get('office-permission-new/edit/{id}/{openMode}', 'OfficePermissionNewController@applicationViewEdit');
    Route::get('office-permission-new/view/{id}/{openMode}', 'OfficePermissionNewController@applicationViewEdit');
    Route::get('office-permission-new/view/{id}', 'OfficePermissionNewController@applicationView');
    Route::get('office-permission-new/preview', "OfficePermissionNewController@preview");
    Route::get('office-permission-new/app-pdf/{id}', 'OfficePermissionNewController@appFormPdf');
    Route::post('office-permission-new/getDocList', 'OfficePermissionNewController@getDocList');
    Route::post('office-permission-new/payment', "OfficePermissionNewController@Payment");
    Route::get('office-permission-new/afterPayment/{payment_id}', 'OfficePermissionNewController@afterPayment');
    Route::get('office-permission-new/afterCounterPayment/{payment_id}', 'OfficePermissionNewController@afterCounterPayment');

    Route::post('office-permission-new/conditionalApproveStore', 'OfficePermissionNewController@conditionalApproveStore');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('office-permission-new/list/{process_id}', 'ProcessPathController@processListById');
});
