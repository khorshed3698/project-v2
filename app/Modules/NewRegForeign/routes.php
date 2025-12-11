<?php

Route::group(/**
 *
 */ ['module' => 'NewRegForeign', 'middleware' => ['XssProtection','auth', 'checkAdmin'], 'namespace' => 'App\Modules\NewRegForeign\Controllers'], function() {
    Route::get('licence-applications/company-registration-foreign/company_type', 'NewRegControllerForeign@selectCompanyType');
    Route::get('licence-applications/company-registration-foreign/add', 'NewRegControllerForeign@selectCompanyType');
    Route::get('licence-applications/company-registration/foreign-company-add', 'NewRegController@foreignCompanyAdd');
    Route::get('bangla_convert/{number}', 'GeneralinfoController@convert_to_bangla');

//    Route::post('rjsc-particular-save', 'NewRegController@rjscPartiularStore');
    Route::post('new-reg-foreign/rjsc-particular-save', 'ParticularsForeignController@rjscPartiularStore');
    Route::post('new-reg-foreign/rjsc-witness-save', 'NewRegControllerForeign@witnessStore');
//    Route::get('rjsc-witness-edit/{app_id}', 'NewRegController@newRegWitnessEdit');
//    Route::put('rjsc-witness-update/{app_id}', 'NewRegController@newRegWitnessUpdate');
//    Route::get('rjsc-new-reg-edit/{id}', 'NewRegController@newRegWitnessEdit');
    Route::get('new-reg-foreign/new-reg-page/notice-of-situation', 'NewRegController@noticeOfSituation');
    Route::get('new-reg-page/companies-act', 'NewRegController@companiesAct');
    Route::get('new-reg-page/companies-act-2', 'NewRegController@companiesAct2');
    Route::get('new-reg-page/agreement-page', 'NewRegController@agreementPage');
    Route::get('new-reg-page/particulars-page', 'NewRegController@particularsPage');


    Route::post('new-reg-page/preview-data-section-12', 'NewRegController@getViewFor12Section');
    Route::get('new-reg-page/memorandum-of-association', 'NewRegController@memorandumAssociation');
    Route::post('new-reg-foreign/new-reg-page/final-submit', 'NewRegControllerForeign@submitandsavejson');
    Route::get('licence-applications/company-registration-foreign/applicationstatus/{app_id}', 'NewRegControllerForeign@checkstatus');
    Route::get('new-reg-foreign/check-rjsc-application-status', 'NewRegControllerForeign@applicationstatus');
    Route::get('new-reg-foreign/check-rjsc-doc-status', 'NewRegControllerForeign@checkdocstatus');
    Route::get('new-reg-foreign/check-rjsc-payment-status', 'NewRegControllerForeign@checkpaymentstatus');
    Route::post('new-reg-foreign/new-reg/payment', "NewRegControllerForeign@nrPayment");
    Route::get('licence-applications/company-registration-foreign/afterPayment/{payment_id}', 'NewRegControllerForeign@afterPayment');
    Route::get('licence-applications/company-registration-foreign/afterCounterPayment/{payment_id}', 'NewRegControllerForeign@afterCounterPayment');


    Route::post('/new-reg-foreign/store', 'GeneralinfoForeignController@appStore');
    Route::get('licence-applications/company-registration-foreign/view/{id}/{openMode}', 'GeneralinfoForeignController@applicationViewEdit');

    Route::post('new-reg-foreign/subscriberStore', 'ListSubscriberForeignController@appStore');
    Route::get('new-reg-foreign/load-rjsc-subsectors', 'NewRegController@loadSubSector');
//    Route::post('first-form', 'NewRegController@firstForm');

    Route::post('new-reg-foreign/save-reg-form', 'NewRegistrationForeignController@saveRegForm');
    Route::get('new-reg-foreign/save-reg-form', 'NewRegistrationForeignController@saveRegForm');
    Route::post('new-reg-foreign/save-aoa-clause', 'AoaClauseController@saveAoaCloause');
    Route::put('new-reg-foreign/update-aoa-clause', 'AoaClauseController@updateAoaCloause');
    Route::post('new-reg-foreign/delete-aoa-clause', 'AoaClauseController@deleteAoaCloause');
    Route::get('new-reg-foreign/article-show', "AoaClauseController@articleShow");

    Route::post('new-reg-foreign/save-reg-objecive', 'ObjectiveForeignController@saveobjective');
    Route::post('new-reg-foreign/save-dec-upload-form', 'DeclarationUploadForeignController@saveDecUpload');

    Route::get('new-reg-foreign/new-reg-get-subscriber', 'ListSubscriberForeignController@getData');
    Route::get('new-reg-foreign/new-reg-delete-subscriber', 'ListSubscriberForeignController@deleteData');
//    Route::get('check-tin-status', 'ListSubscriberController@checkTinStatus');
    Route::post('new-reg-foreign/tin-store', 'ListSubscriberForeignController@storeTin');
    Route::post('licence-applications/tin-foreign/tin-response', 'ListSubscriberForeignController@tinResponse');

    /*submission number store and validation*/

    Route::post('new-reg-foreign/store-submission-number', 'NewRegControllerForeign@storesubmissionnumber');
    Route::post('new-reg-foreign/submission-number-response', 'NewRegControllerForeign@submissverifationResponse');

    Route::get('new-reg-foreign/new-reg-delete-subscriber/next', 'ListSubscriberController@gotowitness');
    Route::get('new-reg-foreign/new-reg-delete-subscriber/next/edit/{app_id}/{company_type}', 'ListSubscriberForeignController@gotowitnessEdit');
    Route::get('new-reg-foreign/new-reg-foreign-pdf/{app_id}', 'GeneralinfoForeignController@appFormPdf');

    // Test form start
    Route::get('new-reg-foreign/test-data', 'GeneralinfoForeignController@testData');
    Route::post('new-reg-foreign/test-data/store', 'GeneralinfoForeignController@testDataStore');
    // Test form end

    // testing foreign pdf generation
    Route::get('/new-reg-foreign/form-xxxvi-pdf/{app_id}/{flag?}', 'GeneralinfoForeignController@genPdfXXXVI');
    Route::get('/new-reg-foreign/form-xxxviii-pdf/{app_id}/{flag?}', 'GeneralinfoForeignController@genPdfXXXVIII');
    Route::get('/new-reg-foreign/form-xxxix-pdf/{app_id}/{flag?}', 'GeneralinfoForeignController@genPdfXXXIX');
    Route::get('/new-reg-foreign/form-xxxvii-pdf/{app_id}/{flag?}', 'GeneralinfoForeignController@genPdfXXXVII');
    Route::get('/new-reg-foreign/form-xlii-pdf/{app_id}/{flag?}', 'GeneralinfoForeignController@genPdfXLII');

    Route::get('new-reg-foreign/new-reg-form-i-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_I_Pdf');
    Route::get('new-reg-foreign/new-reg-form-vi-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_VI_Pdf');
    Route::get('new-reg-foreign/new-reg-form-ix-pdf/{app_id}/{flag?}', 'GeneralinfoForeignController@appForm_IX_Pdf');
    Route::get('new-reg-foreign/new-reg-form-x-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_X_Pdf');
    Route::get('new-reg-foreign/new-reg-form-xi-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_XI_Pdf');
    Route::get('/new-reg-foreign/new-reg-form-xii-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_XII_Pdf');
    Route::get('new-reg-foreign/new-reg-form-xiv-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_XIV_Pdf');

    Route::get('new-reg-foreign/new-reg-form-moa-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_moa_Pdf');

    Route::get('new-reg-foreign/new-reg-form-article-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_article_Pdf');



    Route::get('new-reg-foreign/preview-list/{app_id}', 'GeneralinfoController@appFormPreview');
    Route::post('new-reg-foreign/new-store/upload', 'GeneralinfoForeignController@storeFiles');
    Route::get('new-reg-foreign/upload', 'GeneralinfoController@appFormsUpload');
    Route::get('/new-reg-foreign/get-pdf/check-all-pdf-files-up/{app_id}', 'GeneralinfoController@PDFUploadCheck');



    Route::get('getregistrationdata', 'NewRegControllerForeign@getData');


    Route::get('new-reg-foreign/getregistrationdata', 'NewRegController@getData');
    //Route::get('new-reg-foreign/getregistrationdata/{app_id}', 'NewRegController@getData2');

    Route::post('list-subscriber/upload-document', "NameClearanceController@uploadDocument");

    /*single pdf*/
    Route::get('new-reg-foreign/list-personel-pdf/{app_id}', 'GeneralinfoController@appFormPdf');

    /* by mithun */
    Route::get('/new-reg-foreign/get-pdf/{form_id}/{app_id}', 'GeneralinfoForeignController@appFormFind');
    Route::post('/new-reg-foreign/check-position-is-director', 'ListSubscriberForeignController@checkisdirector');

    /*rakib download pdf*/
    Route::get('new-reg-foreign/downloadpdf/{crt_no}/{app_id}','NewRegController@downloadpdf');


    Route::get('/new-reg-foreign/get-pdf/test/{form_id}/{app_id}', 'GeneralinfoController@appFormFindTest');
    Route::get('/new-reg-foreign/get-pdf/check-all-pdf-files-up-test/{app_id}', 'GeneralinfoController@PDFUploadCheckTest');
    Route::post('new-reg-foreign/upload-document', 'NewRegController@uploadDocument');


    /*test new registration json ASCI verification  */
    Route::get('/new-reg-foreign/asciicheck', 'DeclarationUploadController@asciicheck');

    Route::get('new-reg-foreign/individual','IndividualFileController@getIndividualFile');
    Route::post('new-reg-foreign/store_individual_file','IndividualFileController@storeIndividualFile');

    /*feedback system*/
    Route::get('new-reg-foreign/feedback/{app_id}','FeedbackController@feedbackform');
    Route::post('new-reg-foreign/feedback-store','FeedbackController@storefeedback');

    // API ROUTES
    Route::get('new-reg-foreign/get-registration-offices', 'GeneralinfoForeignController@getRegistrationOfficeList');
    Route::get('new-reg-foreign/get-entity-sub-type-list', 'GeneralinfoForeignController@getEntitySubTypeList');
    Route::get('new-reg-foreign/get-country-origin-list', 'GeneralinfoForeignController@getCountryOriginList');
    Route::get('new-reg-foreign/get-district-list', 'GeneralinfoForeignController@getDistrictList');
    Route::get('new-reg-foreign/get-district-list-by-office-id/{office_id}', 'GeneralinfoForeignController@getDistrictListByOfficeId');
    Route::get('new-reg-foreign/get-business-sector-list', 'GeneralinfoForeignController@getBusinessSectorList');
    Route::get('new-reg-foreign/get-business-sub-sector-list', 'GeneralinfoForeignController@getBusinessSubSectorList');
    Route::get('new-reg-foreign/get-business-sub-sector-by-sector-id/{sector_id}', 'GeneralinfoForeignController@getBusinessSubSectorBySectorId');
    Route::get('new-reg-foreign/get-constitution-instrument-list', 'GeneralinfoForeignController@getConstitutionInstrumentList');
    Route::post('new-reg-foreign/get-position-by-entity-type-id', 'GeneralinfoForeignController@getPositionByEntityTypeId');


});

//Route::post('save-dec-upload-form', 'DeclarationUploadController@saveDecUpload');

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('new-reg-foreign/list/{process_id}', 'ProcessPathController@processListById');
});




Route::group(['module' => 'NewReg', 'namespace' => 'App\Modules\NewReg\Controllers'], function() {
    Route::get('new-reg-foreign/total-aproved-application','NewRegController@aprovedApplication');
});

