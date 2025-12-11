<?php

Route::group(['module' => 'BasicInformation', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'],
    'namespace' => 'App\Modules\BasicInformation\Controllers'], function () {

//    Route::get('basic-information/add', 'BasicInformationController@appForm');
//    Route::post('basic-information/add', 'BasicInformationController@appStore');
//    Route::get('basic-information/preview', 'BasicInformationController@preview');
//    Route::post('basic-information/upload-document', "BasicInformationController@uploadDocument");
//    Route::get('basic-information/view/{id}/{openMode}', "BasicInformationController@appFormEditView");
//    Route::get('basic-information/certificate/{app_id}/{process_type_id}', 'BasicInformationController@certificateAndOther');
//    Route::get('basic-information/app-pdf/{id}', 'BasicInformationController@appFormPdf');
//    Route::get('basic-information/get-district-by-division', 'BasicInformationController@getDistrictByDivision');

    Route::post('basic-information/load-sub-sector', 'BasicInformationController@loadSubSector');

    // Request shadow file
    // Route::post('basic-information/request-shadow-file', 'BasicInformationController@requestShadowFile');

    // Basic info for stack holder
    Route::get('basic-information/form-stakeholder/{type}', "BasicInformationController@BiFormStakeholder");
    Route::get('basic-information/form-stakeholder/{type}/{company_id}', "BasicInformationController@BiFormStakeholderView");
    Route::post('basic-information/form-stakeholder/add', "BasicInformationController@appStoreStakeholder");

    // Basic info for BIDA user
    Route::get('basic-information/form-bida/{type}', "BasicInformationController@BiFormBIDA");
    Route::get('basic-information/form-bida/{type}/{company_id}', "BasicInformationController@BiFormBIDAView");
    Route::post('basic-information/form-bida/add', "BasicInformationController@appStoreBIDA");

    // Department change
    Route::get('basic-information/change-dept/{app_id}/{company_id}', 'BasicInformationController@changeDeptModal');
    Route::post('basic-information/store-change-dept', 'BasicInformationController@storeChangeDept');

    //department more information modal
    Route::get('basic-information/dept-more-info', 'BasicInformationController@DeptMoreInfoModal');

    Route::post('basic-information/upload-auth-letter', 'BasicInformationController@uploadAuthLetter');

    //show all service list
    Route::get('basic-information/show-all-service/{company_id}', 'BasicInformationController@showAllService');
    Route::post('basic-information/get-all-service-list', 'BasicInformationController@getServiceList');

    //show all company list..
    Route::get('basic-information/show-all-company/{company_id}', 'BasicInformationController@showAllCompany');
    Route::post('basic-information/get-all-company-list', 'BasicInformationController@getCompanyList');

    // Change basic info
    // Route::get('basic-information/change-basic-info/{app_id}/{company_id}', "BasicInformationController@changeBasicInfoModal");
    // Route::post('basic-information/store-change-basic-info', "BasicInformationController@storeChangeBasicInfo");

});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
//    Route::get('basic-information/list/{process_id}', 'ProcessPathController@processListById');
});