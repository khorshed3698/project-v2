<?php

Route::group(['prefix' => 'security-clearance', 'module' => 'SecurityClearance', 'middleware' => ['auth','checkAdmin'], 'namespace' => 'App\Modules\SecurityClearance\Controllers'], function() {

    // Internal Business Logic
    Route::get('list', 'SecurityClearanceController@index');
    Route::get('list/{status?}/{desk?}', 'SecurityClearanceController@getList')->name('security.getSecurityClearanceList');
    Route::get('status-wise-list', 'SecurityClearanceController@getList');
    Route::get('search-tracking-no', 'SecurityClearanceController@getTrackingNoList');
    Route::get('verify-json-request/{request_queue_id}', 'SecurityClearanceController@verifyJsonRequest');

    // Prepare for API Calling
    Route::get('send/{app_id}','SecurityClearanceApiController@send');
    Route::get('check-status/{app_id}','SecurityClearanceApiController@checkStatus');

});
