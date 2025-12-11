<?php

Route::group(['module' => 'WorkPermitAmendment', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\WorkPermitAmendment\Controllers'], function() {

    Route::get('work-permit-amendment/add', 'WorkPermitAmendmentController@applicationForm');
    Route::get('work-permit-amendment/preview', "WorkPermitAmendmentController@preview");
    Route::post('work-permit-amendment/store', 'WorkPermitAmendmentController@appStore');
    Route::get('work-permit-amendment/edit/{id}/{openMode}', 'WorkPermitAmendmentController@applicationEdit');
    Route::get('work-permit-amendment/view/{id}', "WorkPermitAmendmentController@applicationView");
    Route::post('work-permit-amendment/upload-document', "WorkPermitAmendmentController@uploadDocument");
    Route::post('work-permit-amendment/payment', "WorkPermitAmendmentController@Payment");
    Route::get('work-permit-amendment/afterPayment/{payment_id}', 'WorkPermitAmendmentController@afterPayment');
    Route::get('work-permit-amendment/afterCounterPayment/{payment_id}', 'WorkPermitAmendmentController@afterCounterPayment');

    Route::post('work-permit-amendment/conditionalApproveStore', 'WorkPermitAmendmentController@conditionalApproveStore');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('work-permit-amendment/list/{process_id}', 'ProcessPathController@processListById');
});
