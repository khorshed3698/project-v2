<?php

Route::group(['module' => 'WaiverCondition8', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\WaiverCondition8\Controllers'], function() {
    Route::get('waiver-condition-8/add', 'WaiverCondition8Controller@applicationForm');
    Route::get('waiver-condition-8/edit/{id}/{openMode}', 'WaiverCondition8Controller@applicationEdit');
    Route::get('waiver-condition-8/view/{id}', 'WaiverCondition8Controller@applicationView');
    Route::post('waiver-condition-8/store', 'WaiverCondition8Controller@applicationStore');

    Route::post('waiver-condition-8/getDocList', 'WaiverCondition8Controller@getDocList');
    Route::post('waiver-condition-8/upload-document', "WaiverCondition8Controller@uploadDocument");
    Route::get('waiver-condition-8/preview', "WaiverCondition8Controller@preview");

    Route::get('waiver-condition-8/afterPayment/{payment_id}', 'WaiverCondition8Controller@afterPayment');
    Route::get('waiver-condition-8/afterCounterPayment/{payment_id}', 'WaiverCondition8Controller@afterCounterPayment');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('waiver-condition-8/list/{process_id}', 'ProcessPathController@processListById');
});
