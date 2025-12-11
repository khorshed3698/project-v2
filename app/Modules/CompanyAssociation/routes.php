<?php

Route::group(['module' => 'CompanyAssociation', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\CompanyAssociation\Controllers'], function () {

    Route::get('company-association/list', 'CompanyAssociationController@getList');
    Route::post('company-association/get-list', 'CompanyAssociationController@getCompanyAssociationList');
    Route::get('company-association/create', 'CompanyAssociationController@appForm');
    Route::post('company-association/store', 'CompanyAssociationController@appStore');
    Route::get('company-association/open/{id}', 'CompanyAssociationController@appOpen');
    Route::post('company-association/update', 'CompanyAssociationController@appUpdate');
    Route::get('company-association/status/{request_id}/{status_id}', 'CompanyAssociationController@status_update');
    Route::get('company-association/change-auth-letter/{request_id}', 'CompanyAssociationController@loadLetterChangeModal');
    Route::post('company-association/change-auth-letter', 'CompanyAssociationController@saveChangeLetter');
    Route::post('company-association/upload-document', 'CompanyAssociationController@uploadDocument');

    Route::get('company-association/switch-company', 'CompanyAssociationController@changeOragizationForm');
//    Route::post('company-association/changeOrganization', 'CompanyAssociationController@changeOragization');
});


Route::group(['module' => 'CompanyAssociation', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\CompanyAssociation\Controllers'], function () {
// For user company update when first login
    Route::post('company-association/update-working-company', 'CompanyAssociationController@updateWorkingCompany');
});