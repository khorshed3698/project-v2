<?php

Route::group(['module' => 'ProjectOfficeNew', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\ProjectOfficeNew\Controllers'], function() {

    Route::get('project-office-new/add', 'ProjectOfficeNewController@applicationForm');
    Route::post('project-office-new/upload-document', "ProjectOfficeNewController@uploadDocument");
    Route::post('project-office-new/store', 'ProjectOfficeNewController@appStore');
    Route::get('project-office-new/edit/{id}/{openMode}', 'ProjectOfficeNewController@applicationViewEdit');
    Route::get('project-office-new/view/{id}/{openMode}', 'ProjectOfficeNewController@applicationViewEdit');
    Route::get('project-office-new/view/{id}', 'ProjectOfficeNewController@applicationView');
    Route::get('project-office-new/preview', "ProjectOfficeNewController@preview");
    Route::get('project-office-new/app-pdf/{id}', 'ProjectOfficeNewController@appFormPdf');
    Route::post('project-office-new/getDocList', 'ProjectOfficeNewController@getDocList');
    Route::post('project-office-new/payment', "ProjectOfficeNewController@Payment");
    Route::get('project-office-new/afterPayment/{payment_id}', 'ProjectOfficeNewController@afterPayment');
    Route::get('project-office-new/afterCounterPayment/{payment_id}', 'ProjectOfficeNewController@afterCounterPayment');

    Route::post('project-office-new/conditionalApproveStore', 'ProjectOfficeNewController@conditionalApproveStore');

    Route::post('project-office-new/load-office-permission-data', 'ProjectOfficeNewController@loadOfficePermissionData');
});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'], 'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('project-office-new/list/{process_id}', 'ProcessPathController@processListById');
});
