<?php

Route::group(/**
 *
 */ ['module' => 'NewReg', 'middleware' => ['XssProtection','auth', 'checkAdmin'], 'namespace' => 'App\Modules\NewReg\Controllers'], function() {
    Route::get('licence-applications/company-registration/company_type', 'NewRegController@selectCompanyType');
    Route::get('licence-applications/company-registration/add', 'NewRegController@selectCompanyType');
    Route::get('licence-applications/company-registration/foreign-company-add', 'NewRegController@foreignCompanyAdd');
    Route::get('bangla_convert/{number}', 'GeneralinfoController@convert_to_bangla');

//    Route::post('rjsc-particular-save', 'NewRegController@rjscPartiularStore');
    Route::post('new-reg/rjsc-particular-save', 'ParticularsController@rjscPartiularStore');
    Route::post('new-reg/rjsc-witness-save', 'NewRegController@witnessStore');
//    Route::get('rjsc-witness-edit/{app_id}', 'NewRegController@newRegWitnessEdit');
//    Route::put('rjsc-witness-update/{app_id}', 'NewRegController@newRegWitnessUpdate');
//    Route::get('rjsc-new-reg-edit/{id}', 'NewRegController@newRegWitnessEdit');
    Route::get('new-reg/new-reg-page/notice-of-situation', 'NewRegController@noticeOfSituation');
    Route::get('new-reg-page/companies-act', 'NewRegController@companiesAct');
    Route::get('new-reg-page/companies-act-2', 'NewRegController@companiesAct2');
    Route::get('new-reg-page/agreement-page', 'NewRegController@agreementPage');
    Route::get('new-reg-page/particulars-page', 'NewRegController@particularsPage');


    Route::post('new-reg-page/preview-data-section-12', 'NewRegController@getViewFor12Section');
    Route::get('new-reg-page/memorandum-of-association', 'NewRegController@memorandumAssociation');
    Route::post('new-reg/new-reg-page/final-submit', 'NewRegController@submitandsavejson');
    Route::get('licence-applications/company-registration/applicationstatus/{app_id}', 'NewRegController@checkstatus');
    Route::get('new-reg/check-rjsc-application-status', 'NewRegController@applicationstatus');
    Route::get('new-reg/check-rjsc-doc-status', 'NewRegController@checkdocstatus');
    Route::get('new-reg/check-rjsc-payment-status', 'NewRegController@checkpaymentstatus');
    Route::post('new-reg/new-reg/payment', "NewRegController@nrPayment");
    Route::get('licence-applications/company-registration/afterPayment/{payment_id}', 'NewRegController@afterPayment');
    Route::get('licence-applications/company-registration/afterCounterPayment/{payment_id}', 'NewRegController@afterCounterPayment');


    Route::post('/new-reg/store', 'GeneralinfoController@appStore');
    Route::get('licence-applications/company-registration/view/{id}/{openMode}', 'GeneralinfoController@applicationViewEdit');

    Route::post('new-reg/subscriberStore', 'ListSubscriberController@appStore');
    Route::get('new-reg/load-rjsc-subsectors', 'NewRegController@loadSubSector');
//    Route::post('first-form', 'NewRegController@firstForm');

    Route::post('new-reg/save-reg-form', 'NewRegistrationController@saveRegForm');
    Route::post('new-reg/save-aoa-clause', 'AoaClauseController@saveAoaCloause');
    Route::put('new-reg/update-aoa-clause', 'AoaClauseController@updateAoaCloause');
    Route::post('new-reg/delete-aoa-clause', 'AoaClauseController@deleteAoaCloause');
    Route::get('new-reg/article-show', "AoaClauseController@articleShow");

    Route::post('new-reg/save-reg-objecive', 'ObjectiveController@saveobjective');
    Route::post('new-reg/save-dec-upload-form', 'DeclarationUploadController@saveDecUpload');

    Route::get('new-reg/new-reg-get-subscriber', 'ListSubscriberController@getData');
    Route::get('new-reg/new-reg-delete-subscriber', 'ListSubscriberController@deleteData');
//    Route::get('check-tin-status', 'ListSubscriberController@checkTinStatus');
    Route::post('new-reg/tin-store', 'ListSubscriberController@storeTin');
    Route::post('licence-applications/tin/tin-response', 'ListSubscriberController@tinResponse');

    /*submission number store and validation*/

    Route::post('new-reg/store-submission-number', 'NewRegController@storesubmissionnumber');
    Route::post('new-reg/submission-number-response', 'NewRegController@submissverifationResponse');

    Route::get('new-reg/new-reg-delete-subscriber/next', 'ListSubscriberController@gotowitness');
    Route::get('new-reg/new-reg-delete-subscriber/next/edit/{app_id}/{company_type}', 'ListSubscriberController@gotowitnessEdit');
    Route::get('new-reg/new-reg-pdf/{app_id}', 'GeneralinfoController@appFormPdf');
    Route::get('new-reg/new-reg-form-i-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_I_Pdf');
    Route::get('new-reg/new-reg-form-vi-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_VI_Pdf');
    Route::get('new-reg/new-reg-form-ix-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_IX_Pdf');
    Route::get('new-reg/new-reg-form-x-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_X_Pdf');
    Route::get('new-reg/new-reg-form-xi-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_XI_Pdf');
    Route::get('/new-reg/new-reg-form-xii-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_XII_Pdf');
    Route::get('new-reg/new-reg-form-xiv-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_XIV_Pdf');

    Route::get('new-reg/new-reg-form-moa-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_moa_Pdf');

    Route::get('new-reg/new-reg-form-article-pdf/{app_id}/{flag?}', 'GeneralinfoController@appForm_article_Pdf');



    Route::get('new-reg/preview-list/{app_id}', 'GeneralinfoController@appFormPreview');
    Route::post('new-reg/new-store/upload', 'GeneralinfoController@storeFiles');
    Route::get('new-reg/upload', 'GeneralinfoController@appFormsUpload');
    Route::get('/new-reg/get-pdf/check-all-pdf-files-up/{app_id}', 'GeneralinfoController@PDFUploadCheck');



    Route::get('getregistrationdata', 'NewRegController@getData');


    Route::get('new-reg/getregistrationdata', 'NewRegController@getData');
    //Route::get('new-reg/getregistrationdata/{app_id}', 'NewRegController@getData2');

    Route::post('list-subscriber/upload-document', "NameClearanceController@uploadDocument");

    /*single pdf*/
    Route::get('new-reg/list-personel-pdf/{app_id}', 'GeneralinfoController@appFormPdf');

    /* by mithun */
    Route::get('/new-reg/get-pdf/{form_id}/{app_id}', 'GeneralinfoController@appFormFind');
    Route::post('/new-reg/check-position-is-director', 'ListSubscriberController@checkisdirector');

    /*rakib download pdf*/
    Route::get('new-reg/downloadpdf/{crt_no}/{app_id}','NewRegController@downloadpdf');


    Route::get('/new-reg/get-pdf/test/{form_id}/{app_id}', 'GeneralinfoController@appFormFindTest');
    Route::get('/new-reg/get-pdf/check-all-pdf-files-up-test/{app_id}', 'GeneralinfoController@PDFUploadCheckTest');
    Route::post('new-reg/upload-document', 'NewRegController@uploadDocument');


    /*test new registration json ASCI verification  */
    Route::get('/new-reg/asciicheck', 'DeclarationUploadController@asciicheck');

    Route::get('new-reg/individual','IndividualFileController@getIndividualFile');
    Route::post('new-reg/store_individual_file','IndividualFileController@storeIndividualFile');

});

//Route::post('save-dec-upload-form', 'DeclarationUploadController@saveDecUpload');

Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('new-reg/list/{process_id}', 'ProcessPathController@processListById');
});




Route::group(['module' => 'NewReg', 'namespace' => 'App\Modules\NewReg\Controllers'], function() {
    Route::get('new-reg/total-aproved-application','NewRegController@aprovedApplication');
});

