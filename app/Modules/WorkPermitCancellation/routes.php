<?php

Route::group(['module' => 'WorkPermitCancellation', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\WorkPermitCancellation\Controllers'], function() {

    Route::get('work-permit-cancellation/add', 'WorkPermitCancellationController@applicationForm');
    Route::post('work-permit-cancellation/upload-document', "WorkPermitCancellationController@uploadDocument");
    Route::get('work-permit-cancellation/preview', "WorkPermitCancellationController@preview");
    Route::post('work-permit-cancellation/store', 'WorkPermitCancellationController@appStore');
    Route::get('work-permit-cancellation/edit/{id}/{openMode}', 'WorkPermitCancellationController@applicationViewEdit');
    Route::get('work-permit-cancellation/view/{id}/{openMode}', 'WorkPermitCancellationController@applicationViewEdit');
    Route::get('work-permit-cancellation/view/{id}', "WorkPermitCancellationController@applicationView");
    Route::get('work-permit-cancellation/app-pdf/{id}', 'WorkPermitCancellationController@appFormPdf');

    Route::get('work-permit-cancellation/afterPayment/{payment_id}', 'WorkPermitCancellationController@afterPayment');
    Route::get('work-permit-cancellation/afterCounterPayment/{payment_id}', 'WorkPermitCancellationController@afterCounterPayment');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('work-permit-cancellation/list/{process_id}', 'ProcessPathController@processListById');
});
