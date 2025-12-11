<?php

Route::group(['module' => 'WasaNewConnection', 'middleware' => ['XssProtection','checkAdmin'], 'namespace' => 'App\Modules\WasaNewConnection\Controllers'], function() {

    Route::get('licence-applications/wasa-new-connection/add', 'WasaNewConnectionController@appForm');
    Route::post('wasa-new-connection/store', 'WasaNewConnectionController@appStore');
    Route::get('licence-applications/wasa-new-connection/edit/{id}/{openMode}', "WasaNewConnectionController@applicationViewEdit");
    Route::get('licence-applications/wasa-new-connection/view/{id}/{openMode}', "WasaNewConnectionController@applicationView");
    Route::post('wasa-new-connection/upload-document', "WasaNewConnectionController@uploadDocument");
    Route::get('wasa-new-connection/get-refresh-token', "WasaNewConnectionController@getRefreshToken");
    Route::post('wasa-new-connection/get-dynamic-doc', 'WasaNewConnectionController@getDynamicDoc');

    // end demand
    Route::get('licence-applications/wasa-new-connection/afterPayment/{payment_id}', 'WasaNewConnectionController@afterPayment');
    Route::get('rajuk-luc-general/wasa-new-connection/{payment_id}', 'WasaNewConnectionController@afterCounterPayment');


});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('wasa-new-connection/list/{process_id}', 'ProcessPathController@processListById');
});