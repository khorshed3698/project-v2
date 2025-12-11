<?php

Route::group(['module' => 'WasaDeepTubewell', 'middleware' => ['XssProtection','checkAdmin'], 'namespace' => 'App\Modules\WasaDeepTubewell\Controllers'], function() {

    Route::get('licence-applications/wasa-dt/add', 'WasaDeepTubewellController@appForm');
    Route::post('wasa-dt/store', 'WasaDeepTubewellController@appStore');
    Route::get('licence-applications/wasa-dt/edit/{id}/{openMode}', "WasaDeepTubewellController@applicationViewEdit");
    Route::get('licence-applications/wasa-dt/view/{id}/{openMode}', "WasaDeepTubewellController@applicationView");
    Route::post('wasa-dt/upload-document', "WasaDeepTubewellController@uploadDocument");
    Route::get('wasa-dt/get-refresh-token', "WasaDeepTubewellController@getRefreshToken");
    Route::post('wasa-dt/get-dynamic-doc','WasaDeepTubewellController@getDynamicDoc');

    // end demand
    Route::get('licence-applications/wasa-dt/afterPayment/{payment_id}', 'WasaDeepTubewellController@afterPayment');
    Route::get('wasa-dt/{payment_id}', 'WasaDeepTubewellController@afterCounterPayment');


});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('wasa-dt/list/{process_id}', 'ProcessPathController@processListById');
});