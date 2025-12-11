<?php

Route::group(['module' => 'InputPermit', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\InputPermit\Controllers'], function() {
    Route::get('input-permit/add', 'InputPermitController@applicationForm');
    Route::get('input-permit/edit/{id}/{openMode}', 'InputPermitController@applicationEdit');
    Route::get('input-permit/view/{id}', 'InputPermitController@applicationView');
    Route::post('input-permit/store', 'InputPermitController@applicationStore');

    Route::post('input-permit/getDocList', 'InputPermitController@getDocList');
    Route::post('input-permit/upload-document', "InputPermitController@uploadDocument");
    Route::get('input-permit/preview', "InputPerInputPermitControllermitController@preview");

    Route::get('input-permit/afterPayment/{payment_id}', 'InputPermitController@afterPayment');
    Route::get('input-permit/afterCounterPayment/{payment_id}', 'InputPermitController@afterCounterPayment');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('input-permit/list/{process_id}', 'ProcessPathController@processListById');
});
