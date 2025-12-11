<?php

Route::group(/**
 *
 */ ['module' => 'CompanyRegSingleForm', 'middleware' => ['XssProtection','auth', 'checkAdmin'], 'namespace' => 'App\Modules\CompanyRegSingleForm\Controllers'], function() {
    Route::get('licence-applications/company-registration-sf/company_type', 'NewRegController@selectCompanyType');
    Route::get('licence-applications/company-registration-sf/add', 'NewRegController@selectCompanyType');
    Route::get('licence-applications/company-registration-sf/foreign-company-add', 'NewRegController@foreignCompanyAdd');
    Route::get('bangla_convert/{number}', 'GeneralinfoController@convert_to_bangla');

    Route::post('company-registration-sf/rjsc-particular-save', 'ParticularsController@rjscPartiularStore');
    Route::post('company-registration-sf/rjsc-witness-save', 'NewRegController@witnessStore');

    Route::get('company-registration-sf/company-registration-sf-page/notice-of-situation', 'NewRegController@noticeOfSituation');
    Route::get('company-registration-sf-page/companies-act', 'NewRegController@companiesAct');
    Route::get('company-registration-sf-page/companies-act-2', 'NewRegController@companiesAct2');
    Route::get('company-registration-sf-page/agreement-page', 'NewRegController@agreementPage');
    Route::get('company-registration-sf-page/particulars-page', 'NewRegController@particularsPage');

    Route::post('company-registration-sf-page/preview-data-section-12', 'NewRegController@getViewFor12Section');
    Route::get('company-registration-sf-page/memorandum-of-association', 'NewRegController@memorandumAssociation');
    Route::post('company-registration-sf/company-registration-sf-page/final-submit', 'NewRegController@submitandsavejson');
    Route::get('licence-applications/company-registration-sf/applicationstatus/{app_id}', 'NewRegController@checkstatus');
    Route::get('company-registration-sf/check-rjsc-application-status', 'NewRegController@applicationstatus');
    Route::get('company-registration-sf/check-rjsc-doc-status', 'NewRegController@checkdocstatus');
    Route::get('company-registration-sf/check-rjsc-payment-status', 'NewRegController@checkpaymentstatus');
    Route::post('company-registration-sf/company-registration-sf/payment', "NewRegController@nrPayment");
    Route::get('licence-applications/company-registration-sf/afterPayment/{payment_id}', 'NewRegController@afterPayment');
    Route::get('licence-applications/company-registration-sf/afterCounterPayment/{payment_id}', 'NewRegController@afterCounterPayment');

    Route::post('/company-registration-sf/store', 'GeneralinfoController@appStore');
    Route::get('/company-registration-sf/get-refresh-token', "NewRegController@getRefreshToken");
    Route::get('licence-applications/company-registration-sf/view/{id}/{openMode}', 'GeneralinfoController@applicationViewEdit');

    Route::post('company-registration-sf/subscriberStore', 'ListSubscriberController@appStore');
    Route::get('company-registration-sf/load-rjsc-subsectors', 'NewRegController@loadSubSector');


    Route::post('company-registration-sf/save-reg-form', 'NewRegistrationController@saveRegForm');
    Route::post('company-registration-sf/save-aoa-clause', 'AoaClauseController@saveAoaCloause');
    Route::put('company-registration-sf/update-aoa-clause', 'AoaClauseController@updateAoaCloause');
    Route::post('company-registration-sf/delete-aoa-clause', 'AoaClauseController@deleteAoaCloause');
    Route::get('company-registration-sf/article-show', "AoaClauseController@articleShow");

    Route::post('company-registration-sf/save-reg-objecive', 'ObjectiveController@saveobjective');
    Route::post('company-registration-sf/save-dec-upload-form', 'DeclarationUploadController@saveDecUpload');

    Route::get('company-registration-sf/new-reg-get-subscriber', 'ListSubscriberController@getData');
    Route::get('company-registration-sf/new-reg-delete-subscriber', 'ListSubscriberController@deleteData');

    Route::post('company-registration-sf/tin-store', 'ListSubscriberController@storeTin');
    Route::post('licence-applications/tin/tin-response', 'ListSubscriberController@tinResponse');

    /*submission number store and validation*/

    Route::post('company-registration-sf/store-submission-number', 'NewRegController@storesubmissionnumber');
    Route::post('company-registration-sf/submission-number-response', 'NewRegController@submissverifationResponse');

    Route::get('company-registration-sf/new-reg-delete-subscriber/next', 'ListSubscriberController@gotowitness');
    Route::get('company-registration-sf/new-reg-delete-subscriber/next/edit/{app_id}/{company_type}', 'ListSubscriberController@gotowitnessEdit');
    Route::get('company-registration-sf/new-reg-pdf/{app_id}', 'GeneralinfoController@appFormPdf');
    Route::get('company-registration-sf/new-reg-form-i-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_I_Pdf');
    Route::get('company-registration-sf/new-reg-form-vi-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_VI_Pdf');
    Route::get('company-registration-sf/new-reg-form-ix-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_IX_Pdf');
    Route::get('company-registration-sf/new-reg-form-x-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_X_Pdf');
    Route::get('company-registration-sf/new-reg-form-xi-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_XI_Pdf');
    Route::get('/company-registration-sf/company-registration-sf-form-xii-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_XII_Pdf');
    Route::get('company-registration-sf/new-reg-form-xiv-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_XIV_Pdf');

    Route::get('company-registration-sf/new-reg-form-moa-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_moa_Pdf');

    Route::get('company-registration-sf/new-reg-form-article-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_article_Pdf');

    Route::get('company-registration-sf/preview-list/{app_id}', 'GeneralinfoController@appFormPreview');
    Route::post('company-registration-sf/new-store/upload', 'GeneralinfoController@storeFiles');
    Route::get('company-registration-sf/upload', 'GeneralinfoController@appFormsUpload');
    Route::get('/company-registration-sf/get-pdf/check-all-pdf-files-up/{app_id}', 'GeneralinfoController@PDFUploadCheck');

    Route::get('getregistrationdata', 'NewRegController@getData');

    Route::get('company-registration-sf/getregistrationdata', 'NewRegController@getData');

    Route::post('list-subscriber/upload-document', "NameClearanceController@uploadDocument");

    /*single pdf*/
    Route::get('company-registration-sf/list-personel-pdf/{app_id}', 'GeneralinfoController@appFormPdf');

    /* by mithun */
    Route::get('/company-registration-sf/get-pdf/{form_id}/{app_id}', 'GeneralinfoController@appFormFind');
    Route::post('/company-registration-sf/check-position-is-director', 'ListSubscriberController@checkisdirector');

    /*rakib download pdf*/
    Route::get('company-registration-sf/downloadpdf/{crt_no}/{app_id}','NewRegController@downloadpdf');

    Route::get('/company-registration-sf/get-pdf/test/{form_id}/{app_id}', 'GeneralinfoController@appFormFindTest');
    Route::get('/company-registration-sf/get-pdf/check-all-pdf-files-up-test/{app_id}', 'GeneralinfoController@PDFUploadCheckTest');
    Route::post('company-registration-sf/upload-document', 'NewRegController@uploadDocument');

    /*test new registration json ASCI verification  */
    Route::get('/company-registration-sf/asciicheck', 'DeclarationUploadController@asciicheck');

    Route::get('company-registration-sf/individual','IndividualFileController@getIndividualFile');
    Route::post('company-registration-sf/store_individual_file','IndividualFileController@storeIndividualFile');

});

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('company-registration-sf/list/{process_id}', 'ProcessPathController@processListById');
});

Route::group(['module' => 'CompanyRegSingleForm', 'namespace' => 'App\Modules\CompanyRegSingleForm\Controllers'], function() {
    Route::get('company-registration-sf/total-aproved-application','NewRegController@aprovedApplication');
});

