<?php

Route::group(['module' => 'DOE', 'middleware' => ['auth','checkAdmin'], 'namespace' => 'App\Modules\DOE\Controllers'], function() {

    Route::get('licence-applications/doe/add','DOEController@appForm');
    Route::post('doe/store', "DOEController@appStore");
    Route::get('licence-applications/doe/view/{id}/{openMode}', "DOEController@applicationViewEdit");
//    Route::get('doe/get-thana-by-district-id', 'DOEController@get_thana_by_district_id');
    Route::post('doe/first-part-store', 'DOEController@storefirstpart');
    Route::post('doe/first-part-store', 'DOEController@storefirstpart');
    Route::get('doe/view/{id}/{openMode}', "DOEController@applicationViewEdit");
    Route::post('doe/upload-document', "DOEController@uploadDocument");
    Route::get('licence-applications/doe/afterPayment/{payment_id}', 'DOEController@afterPayment');
    Route::get('licence-applications/doe/afterCounterPayment/{payment_id}', 'DOEController@afterCounterPayment');

    /*resubmit*/
        Route::post('doe/store-comment','DOEController@storeComment');
    Route::get('doe/view-comments/{app_id}','DOEController@viewComments');
    Route::get('doe/change-info/{app_id}','DOEController@viewChangesInfo');

    Route::get('licence-applications/doe/check-payment/{app_id}', 'DOEController@waitForPayment');
    Route::post('licence-applications/doe/check-payment-info', 'DOEController@checkPayment');
    Route::post('licence-applications/doe/payment', 'DOEController@doePayment');

    /*api*/
    Route::get('doe/get-application-type','DOEController@getApplicationType');
    Route::get('doe/get-industry','DOEController@getIndustry');
    Route::get('doe/get-category','DOEController@getCagegoryByindustryId');
    Route::get('doe/get-districts','DOEController@getDOEDistricts');
    Route::get('doe/get-thana-by-district-id', 'DOEController@getDOEThanaByDistrictId');
    Route::get('doe/get-fee-categories', 'DOEController@getDOEFeeCategories');
    Route::get('doe/get-fee-by-category-id', 'DOEController@getDOEFeeByCategoryId');
    Route::get('doe/get-submitting-office', 'DOEController@getDOESubmittingOffice');
    Route::get('doe/get-categories', 'DOEController@getCategories');
    Route::get('doe/get-refresh-token', "DOEController@getRefreshToken");

    /*additional payment*/

    Route::post('doe/additional-payment','DOEController@additionalpayment');

});
Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('doe/list/{process_id}', 'ProcessPathController@processListById');

    /*     * ********************************End of Route group****************************** */
});
