<?php

Route::group(['module' => 'Waiver', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\Waiver\Controllers'], function() {

    Route::get('waiver/add', 'WaiverController@applicationForm');
    Route::post('waiver/upload-document', "WaiverController@uploadDocument");
    Route::post('waiver/store', 'WaiverController@appStore');
    Route::get('waiver/edit/{id}/{openMode}', 'WaiverController@applicationViewEdit');
    Route::get('waiver/view/{id}/{openMode}', 'WaiverController@applicationViewEdit');
    Route::get('waiver/view/{id}', 'WaiverController@applicationView');
    Route::get('waiver/preview', "WaiverController@preview");
    Route::get('waiver/app-pdf/{id}', 'WaiverController@appFormPdf');
    Route::post('waiver/getDocList', 'WaiverController@getDocList');

    Route::post('waiver/payment', "WaiverController@Payment");
    Route::get('waiver/afterPayment/{payment_id}', 'WaiverController@afterPayment');
    Route::get('waiver/afterCounterPayment/{payment_id}', 'WaiverController@afterCounterPayment');

    Route::post('waiver/conditionalApproveStore', 'WaiverController@conditionalApproveStore');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('waiver/list/{process_id}', 'ProcessPathController@processListById');
});
