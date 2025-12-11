<?php

Route::group(['module' => 'LicenceApplication', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'],
    'namespace' => 'App\Modules\LicenceApplication\Controllers'], function() {

    //******************* bida registration
    Route::get('licence-application/app-home', 'LicenceApplicationController@appHome');
    Route::get('licence-application/licence-list', 'LicenceApplicationController@licenceList');
    Route::get('licence-applications/individual-licence', 'LicenceApplicationController@individualLicenceNEw')->name('individualLicence');
    Route::get('licence-applications/individual-licence-new', 'LicenceApplicationController@individualLicenceNEw')->name('individualLicenceNew');
  //  Route::get('licence-application/pdf', 'LicenceApplicationController@appFormPdf');
    Route::get('licence-application/get-hscodes', 'LicenceApplicationController@appFormPdf');
    //Route::get('licence-application/view/{id}/{openMode}', "LicenceApplicationController@appLicenceForm");
    Route::get('licence-application/add', 'LicenceApplicationController@appLicenceForm');
    Route::post('licence-application/add', 'LicenceApplicationController@appLicenceFormStore');
    Route::post('licence-application/upload-document', 'LicenceApplicationController@uploadDocument');
    Route::get('licence-application/view/{id}/{openMode}', "LicenceApplicationController@appFormEditView");
    Route::get('licence-application/preview', "LicenceApplicationController@preview");


    //******************* BANK ACCOUNT
    Route::get('licence-applications/bank-account/add', "BankAccountController@appForm");
    Route::post('licence-application/bank-account/add', "BankAccountController@appStore");
    Route::post('licence-application/bank-account/branches', "BankAccountController@getBankBranch");
    Route::post('licence-applications/bank-account/upload-document', "BankAccountController@uploadDocument");
    Route::get('licence-applications/bank-account/view/{id}/{openMode}', "BankAccountController@appFormEditView");
    Route::get('licence-applications/bank-account/afterPayment/{payment_id}', 'BankAccountController@afterPayment');
    Route::post('licence-application/bank-account/upload-document', 'BankAccountController@uploadDocument');
    Route::get('licence-applications/bank-account/view-pdf/{id}', 'BankAccountController@appFormPdf');
    Route::post('licence-applications/bank-account/payment', "BankAccountController@Payment");

    //*********** Name Clearance
    Route::get('licence-applications/name-clearance/add', "NameClearanceController@appForm");
    Route::get('licence-applications/name-clearance/company-type','NameClearanceController@getCompanyType');
    Route::post('licence-applications/name-clearance/add', "NameClearanceController@appStore");
    Route::post('licence-application/name-clearance/upload-document', "NameClearanceController@uploadDocument");
    Route::get('licence-applications/name-clearance/edit/{id}/{openMode}', "NameClearanceController@appFormEditView");
    Route::get('licence-applications/name-clearance/view/{id}/{openMode}', "NameClearanceController@appFormView");
    Route::get('licence-applications/name-clearance/afterPayment/{payment_id}', 'NameClearanceController@afterPayment');
    Route::get('licence-applications/name-clearance/afterCounterPayment/{payment_id}', 'NameClearanceController@afterCounterPayment');
    Route::get('licence-applications/name-clearance/view-pdf/{id}', 'NameClearanceController@appFormPdf');
    Route::get('licence-applications/name-clearance/submission-response/{id}', 'NameClearanceController@waitforresfonse');
    Route::post('/licence-applications/name-clearance/check-submission-verification', 'NameClearanceController@verificationResponse');

    Route::post('liceprocess/licence-applications/name-clearancence-applications/name-clearance/payment', "NameClearanceController@Payment");
    Route::get('licence-applications/rjsc-list-get', "NameClearanceController@rjscList");
    Route::get('licence-applications/company-list-get', "NameClearanceController@companyList");
    Route::get('licence-applications/designation-list-get/{id}', "NameClearanceController@designationList");
    Route::get('licence-applications/organization-list-get', "NameClearanceController@organizationList");
    Route::get('licence-applications/district-list-get', "NameClearanceController@districtList");


    //payment rjsc nc
    Route::post('licence-applications/name-clearance/payment', "NameClearanceController@ncPayment");

//    Route::post('licence-applications/name-clearance/payment', "NameClearanceController@Payment");
    Route::post('licence-applications/name-clearance/check-company','NameClearanceController@searchCompanyName');
    Route::post('licence-applications/name-clearance/rjsc-response','NameClearanceController@rjscResponse');
    Route::post('licence-applications/name-clearance/check-rjsc-status','NameClearanceController@getRjscStatus');
    Route::get('licence-applications/name-clearance/check-rjsc-status/{applicationId}/{paymentId}', "NameClearanceController@checkRjscStatus");
    Route::get('licence-applications/name-clearance/rjsc-district-by-office/{officeId}', "NameClearanceController@getDistrictByRjscOffice");

    // ********** E-tin
    Route::get('licence-applications/e-tin/add', "EtinController@appForm");
    Route::post('licence-application/e-tin/add', "EtinController@appStore");
    Route::get('licence-applications/e-tin/view/{id}/{openMode}', "EtinController@appFormEditView");
    Route::get('licence-applications/e-tin/view-pdf/{id}', 'EtinController@appFormPdf');
    Route::post('licence-applications/e-tin/payment', "EtinController@Payment");
    Route::post('licence-applications/e-tin/check-api-request-status', "EtinController@checkApiRequestStatus");
    Route::get('licence-applications/show-certificate/{app_id}/{certificate_id}', "EtinController@showCertificate");
    Route::get('licence-applications/e-tin/get-company-list', 'EtinController@getCompanyList');

    Route::get('licence-applications/e-tin/get-thana-by-district', 'EtinController@getThanaByDistrict');
    Route::get('licence-applications/e-tin/afterPayment/{payment_id}', 'EtinController@afterPayment');
    Route::get('licence-applications/e-tin/afterCounterPayment/{payment_id}', 'EtinController@afterCounterPayment');

//******************* bida registration
    Route::get('licence-application/preview', 'LicenceApplicationController@preview');
    Route::get('licence-application/certificate/{app_id}/{process_type_id}', 'LicenceApplicationController@certificateAndOther');
    Route::get('licence-application/view-pdf/{id}', 'LicenceApplicationController@appFormPdf');
    Route::get('licence-application/get-district-by-division', 'LicenceApplicationController@getDistrictByDivision');

    Route::get('licence-application/payment-confirmatspg/initiateion', 'LicenceApplicationController@paymentConfirmation');
    Route::post('licence-application/payment-success', 'LicenceApplicationController@paymentSuccess');
    Route::post('licence-application/payment-failed', 'LicenceApplicationController@paymentFailed');
    Route::post('licence-application/payment-cancelled', 'LicenceApplicationController@paymentCancelled');
    Route::get('licence-application/afterPayment/{payment_id}', 'LicenceApplicationController@afterPayment');

    Route::resource('basic', 'LicenceApplicationController');
    // Request shadow file
    Route::post('licence-application/request-shadow-file', 'LicenceApplicationController@requestShadowFile');
// download as pdf
    Route::get('licence-application/app-pdf/{id}', 'VisaRecommendationController@appFormPdf');

    // company registration
//    Route::get('licence-applications/company-registration/add', 'CompanyRegistrationController@createRegistration');
//    Route::post('licence-application/company-registration/add', 'CompanyRegistrationController@storeRegistration');
//    Route::get('licence-applications/company-registration/view/{id}/{openMode}', "CompanyRegistrationController@appFormEditView");
//    Route::post('licence-applications/company-registration/upload-document', "CompanyRegistrationController@uploadDocument");
//    Route::get('licence-applications/company-registration/afterPayment/{payment_id}', 'CompanyRegistrationController@afterPayment');
//    Route::get('licence-applications/company-registration/view-pdf/{id}', 'CompanyRegistrationController@appFormPdf');
//    Route::post('licence-applications/company-registration/payment', "CompanyRegistrationController@Payment");

    // trade licence
    Route::get('licence-applications/trade-licence/add', 'TradeLicenceController@appForm');
    Route::post('licence-applications/trade-licence/store', 'TradeLicenceController@appStore');
    Route::get('licence-applications/trade-licence/view/{id}/{openMode}', "TradeLicenceController@appFormEditView");
    Route::post('licence-applications/trade-licence/payment', "TradeLicenceController@Payment");
    //api
    Route::get('licence-applications/trade-licence/zone-area', 'TradeLicenceController@getZoneWardArea');
    Route::get('licence-applications/trade-licence/division-area', 'TradeLicenceController@getDivisionDistrictThana');
    Route::get('licence-applications/trade-licence/sub-category-list', 'TradeLicenceController@getSubCategory');

    Route::get('licence-applications/trade-licence/view-pdf/{id}', 'TradeLicenceController@appFormPdf');

    Route::post('licence-applications/trade-licence/upload-document', "TradeLicenceController@uploadDocument");
    Route::get('licence-applications/trade-licence/afterPayment/{payment_id}', 'TradeLicenceController@afterPayment');

    Route::get('licence-applications/trade-licence/get-fee', 'TradeLicenceController@getFees');

    Route::post('licence-applications/trade-licence/check-api-request-status', "TradeLicenceController@checkApiRequestStatus");

    Route::post('licence-applications/trade-licence/payment', 'TradeLicenceController@Payment');

});

Route::group(array('module' => 'ProcessPath'
, 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('licence-applications/{subModue}/list/{process_id}', 'ProcessPathController@processListById');
    Route::get('licence-application/list/{process_id}', 'ProcessPathController@processListById');
});


Route::group(['module' => 'LicenceApplication', 'middleware' => [],
    'namespace' => 'App\Modules\LicenceApplication\Controllers'], function() {
    Route::get('licence-applications/name-clearance/update-confirmation', "NameClearanceController@updateRjscFinalStatus");
    Route::get('licence-applications/name-clearance/expired-notification', "NcExpCronController@expirednotification");
});
