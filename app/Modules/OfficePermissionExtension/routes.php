<?php

Route::group(['module' => 'OfficePermissionExtension', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\OfficePermissionExtension\Controllers'], function() {

    Route::get('office-permission-extension/add', 'OfficePermissionExtensionController@applicationForm');
    Route::post('office-permission-extension/upload-document', "OfficePermissionExtensionController@uploadDocument");
    Route::post('office-permission-extension/store', 'OfficePermissionExtensionController@appStore');
    Route::get('office-permission-extension/edit/{id}/{openMode}', 'OfficePermissionExtensionController@applicationViewEdit');
    Route::get('office-permission-extension/view/{id}/{openMode}', 'OfficePermissionExtensionController@applicationViewEdit');
    Route::get('office-permission-extension/view/{id}', 'OfficePermissionExtensionController@applicationView');
    Route::get('office-permission-extension/preview', "OfficePermissionExtensionController@preview");
    Route::get('office-permission-extension/app-pdf/{id}', 'OfficePermissionExtensionController@appFormPdf');
    Route::post('office-permission-extension/getDocList', 'OfficePermissionExtensionController@getDocList');

    Route::post('office-permission-extension/payment', "OfficePermissionExtensionController@Payment");
    Route::get('office-permission-extension/afterPayment/{payment_id}', 'OfficePermissionExtensionController@afterPayment');
    Route::get('office-permission-extension/afterCounterPayment/{payment_id}', 'OfficePermissionExtensionController@afterCounterPayment');

    Route::post('office-permission-extension/conditionalApproveStore', 'OfficePermissionExtensionController@conditionalApproveStore');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('office-permission-extension/list/{process_id}', 'ProcessPathController@processListById');
});
