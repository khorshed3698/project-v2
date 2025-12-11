<?php
date_default_timezone_set('Asia/Dhaka');

Route::group(['module' => 'API', 'middleware' => ['api'], 'namespace' => 'App\Modules\API\Controllers'], function() {

    Route::resource('API', 'APIController');

});

//IRMS API
Route::group(['prefix' => 'irms/api/v1', 'module' => 'API', 'middleware' => ['XssProtection'], 'namespace' => 'App\Modules\API\Controllers\IRMS\V1'], function () {
    Route::post('get-token', 'IrmsApiController@getToken');
    Route::post('feedback-request-initiate', 'IrmsApiController@feedbackRequestInitiate');
    Route::post('get-user-info', 'IrmsApiController@getUserInfo');
    Route::post('bida-registration-data-provider', 'IrmsApiController@BidaRegistrationDataProvider');
    Route::post('feedback-data', 'IrmsController@feedbackData');
    Route::post('irn', 'IrmsApiController@apiIrnRequest');
    Route::post('callback', 'IrmsApiController@callBack');
    Route::post('company-list', 'IrmsApiController@companyList');
    Route::post('company-store', 'IrmsApiController@companyStore');
    Route::post('company-update', 'IrmsApiController@companyUpdate');

});


Route::group(['prefix' => 'mutation/api/v1', 'module' => 'API', 'middleware' => ['XssProtection'], 'namespace' => 'App\Modules\API\Controllers\IRMS\V1'], function () {
    Route::post('get-token', 'IrmsApiController@getToken');
});
