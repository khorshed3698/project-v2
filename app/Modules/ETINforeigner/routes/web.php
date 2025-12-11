<?php

Route::group(['module' => 'ETINforeigner', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\ETINforeigner\Controllers'], function() {

//    Route::resource('eTINforeigner', 'ETINforeignerController');
    Route::get('licence-applications/e-tin-foreigner/add', 'ETINforeignerController@appForm');
    Route::post('/e-tin-foreigner/get-dynamic-doc', 'ETINforeignerController@getDynamicDoc');
    Route::post('e-tin-foreigner/store', 'ETINforeignerController@appStore');
    Route::get('licence-applications/e-tin-foreigner/edit/{id}/{openMode}', "ETINforeignerController@applicationViewEdit");
    Route::get('licence-applications/e-tin-foreigner/view/{id}/{openMode}', "ETINforeignerController@applicationView");
    Route::get('/e-tin-foreigner/get-refresh-token', "ETINforeignerController@getRefreshToken");
    Route::post('e-tin-foreigner/upload-document', "ETINforeignerController@uploadDocument");
    Route::get('licence-applications/e-tin-foreigner/afterPayment/{payment_id}', 'ETINforeignerController@afterPayment');
    Route::post('e-tin-foreigner/delete-dynamic-doc', 'ETINforeignerController@deleteDynamicDoc');
    Route::get('licence-applications/e-tin-foreigner/afterCounterPayment/{payment_id}', 'ETINforeignerController@afterCounterPayment');
    Route::get('e-tin-foreigner/regenerate-submission-json/{id}', 'ETINforeignerController@reGenerateSubmissionJson');
});
Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('e-tin-foreigner/list/{process_id}', 'ProcessPathController@processListById');
});

Route::group(['module' => 'ETINforeigner', 'middleware' => [ 'XssProtection'], 'namespace' => 'App\Modules\ETINforeigner\Controllers'], function() {
    Route::get('e-tin-foreigner/send-user-info', 'ETINforeignerController@sendEmailToUser');
});

