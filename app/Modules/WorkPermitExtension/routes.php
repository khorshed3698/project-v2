<?php

Route::group(['module' => 'WorkPermitExtension', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\WorkPermitExtension\Controllers'], function() {

    Route::get('work-permit-extension/add', 'WorkPermitExtensionController@applicationForm');
    Route::post('work-permit-extension/store', 'WorkPermitExtensionController@appStore');
    Route::get('work-permit-extension/edit/{id}/{openMode}', 'WorkPermitExtensionController@applicationEdit');
    //Route::get('work-permit-extension/view/{id}/{openMode}', 'WorkPermitExtensionController@applicationViewEdit');
    Route::get('work-permit-extension/view/{id}', "WorkPermitExtensionController@applicationView");
    Route::post('work-permit-extension/upload-document', "WorkPermitExtensionController@uploadDocument");
    Route::get('/work-permit-extension/preview', "WorkPermitExtensionController@preview");
    Route::get('work-permit-extension/app-pdf/{id}', 'WorkPermitExtensionController@appFormPdf');
    Route::post('work-permit-extension/payment', "WorkPermitExtensionController@Payment");
    Route::post('work-permit-extension/getDocList', 'WorkPermitExtensionController@getDocList');

    Route::get('work-permit-extension/afterPayment/{payment_id}', 'WorkPermitExtensionController@afterPayment');
    Route::get('work-permit-extension/afterCounterPayment/{payment_id}', 'WorkPermitExtensionController@afterCounterPayment');

    Route::post('work-permit-extension/conditionalApproveStore', 'WorkPermitExtensionController@conditionalApproveStore');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('work-permit-extension/list/{process_id}', 'ProcessPathController@processListById');
});
