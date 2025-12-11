<?php

Route::group(['module' => 'WaiverCondition7', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\WaiverCondition7\Controllers'], function() {
    Route::get('waiver-condition-7/add', 'WaiverCondition7Controller@applicationForm');
    Route::get('waiver-condition-7/edit/{id}/{openMode}', 'WaiverCondition7Controller@applicationEdit');
    Route::get('waiver-condition-7/view/{id}', 'WaiverCondition7Controller@applicationView');
    Route::post('waiver-condition-7/store', 'WaiverCondition7Controller@applicationStore');

    Route::post('waiver-condition-7/getDocList', 'WaiverCondition7Controller@getDocList');
    Route::post('waiver-condition-7/upload-document', "WaiverCondition7Controller@uploadDocument");
    Route::get('waiver-condition-7/preview', "WaiverCondition7Controller@preview");

    Route::get('waiver-condition-7/afterPayment/{payment_id}', 'WaiverCondition7Controller@afterPayment');
    Route::get('waiver-condition-7/afterCounterPayment/{payment_id}', 'WaiverCondition7Controller@afterCounterPayment');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('waiver-condition-7/list/{process_id}', 'ProcessPathController@processListById');
});
