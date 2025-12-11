<?php

Route::group(['module' => 'SingleLicence', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'],
    'namespace' => 'App\Modules\SingleLicence\Controllers'], function() {

//    Route::get('single-licence/add', 'SingleLicenceController@singleLicenceApplication');
    Route::get('single-licence/add', 'SingleLicenceController@applicationForm');
    Route::post('single-licence/add', 'SingleLicenceController@appStore');
    Route::get('single-licence/view/{id}/{openMode}', "SingleLicenceController@applicationViewEdit");
    Route::get('single-licence/app-home/{app_id}', 'SingleLicenceController@appHome');
    Route::get('single-licence/app-home-edit/{mode}/{app_id}', 'SingleLicenceController@appHomeEdit');
    Route::post('single-licence/attachment', "SingleLicenceController@attachment");

    //*********** Name Clearance
    Route::get('single-licence/name-clearance/add', "SingleLicenceController@ncAppForm");
    Route::post('single-licence/name-clearance/add', "SingleLicenceController@ncAppStore");
    Route::post('single-licence/name-clearance/upload-document', "SingleLicenceController@uploadDocument");
    Route::get('single-licence/name-clearance/view/{id}/{openMode}', "SingleLicenceController@appFormEditView");

    //*********** Bank Account
    Route::post('single-licence/bank-account/add', "SingleLicenceController@baAppStore");
    Route::post('single-licence/bank-account/branches', "SingleLicenceController@getBankBranch");

    // company registration
    Route::post('single-licence/company-registration/add', 'SingleLicenceController@crAppStore');

    // ********** E-tin
    Route::post('single-licence/e-tin/add', "SingleLicenceController@etinAppStore");

    // trade licence
    Route::post('single-licence/trade-licence/add', 'SingleLicenceController@tlAppStore');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('single-licence/list/{process_id}', 'ProcessPathController@processListById');
});
