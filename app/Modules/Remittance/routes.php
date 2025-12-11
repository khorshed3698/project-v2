<?php

Route::group(['module' => 'Remittance', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'],
    'namespace' => 'App\Modules\Remittance\Controllers'], function() {


    Route::get('remittance-new/add', 'RemittanceController@applicationForm');
    Route::post('remittance-new/store', 'RemittanceController@appStore');
    Route::get('remittance-new/edit/{id}/{openMode}', 'RemittanceController@applicationViewEdit');
    Route::get('remittance-new/view/{id}/{openMode}', 'RemittanceController@applicationViewEdit');
    Route::get('remittance-new/view/{id}', 'RemittanceController@applicationView');
    Route::post('remittance-new/upload-document', "RemittanceController@uploadDocument");
    Route::post('remittance-new/load-fiscal-year', "RemittanceController@LoadFiscalYear");
    Route::post('remittance-new/payment', "RemittanceController@Payment");
    Route::get('/remittance-new/preview', "RemittanceController@preview");
    Route::get('/remittance-new/app-pdf/{id}', 'RemittanceController@appFormPdf');
    Route::get('remittance-new/afterPayment/{payment_id}', 'RemittanceController@afterPayment');
    Route::get('remittance-new/afterCounterPayment/{payment_id}', 'RemittanceController@afterCounterPayment');
    Route::post('/remittance-new/getDocList', 'RemittanceController@getDocList');
//    Route::post('remittance-new/get-duration', 'RemittanceController@getDuration');

    Route::get('/remittance-new/get-branch-by-bank', 'RemittanceController@getBranckBybankId');
    Route::get('/remittance-new/new-reg', 'RemittanceController@getNewReg');

    Route::post('remittance-new/conditionalApproveStore', 'RemittanceController@conditionalApproveStore');


});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('remittance-new/list/{process_id}', 'ProcessPathController@processListById');
});