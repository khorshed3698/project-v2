<?php

Route::group(['module' => 'WorkPermitNew', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\WorkPermitNew\Controllers'], function() {

    Route::get('work-permit-new/add', 'WorkPermitNewController@applicationForm');
    Route::post('work-permit-new/store', 'WorkPermitNewController@appStore');
    Route::get('work-permit-new/edit/{id}/{openMode}', 'WorkPermitNewController@applicationViewEdit');
    Route::get('work-permit-new/view/{id}/{openMode}', 'WorkPermitNewController@applicationViewEdit');
    Route::get('work-permit-new/view/{id}', "WorkPermitNewController@applicationView");
    Route::post('work-permit-new/upload-document', "WorkPermitNewController@uploadDocument");
    Route::post('work-permit-new/payment', "WorkPermitNewController@Payment");
    Route::get('/work-permit-new/preview', "WorkPermitNewController@preview");
    Route::get('work-permit-new/app-pdf/{id}', 'WorkPermitNewController@appFormPdf');
    Route::post('work-permit-new/getDocList', 'WorkPermitNewController@getDocList');

    Route::get('work-permit-new/afterPayment/{payment_id}', 'WorkPermitNewController@afterPayment');
    Route::get('work-permit-new/afterCounterPayment/{payment_id}', 'WorkPermitNewController@afterCounterPayment');

    Route::post('work-permit-new/conditionalApproveStore', 'WorkPermitNewController@conditionalApproveStore');

});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('work-permit-new/list/{process_id}', 'ProcessPathController@processListById');

});
