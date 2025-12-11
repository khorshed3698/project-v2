<?php

Route::group(array('module' => 'Settings', 'middleware' => ['auth', 'checkAdmin'], 'namespace' => 'App\Modules\Settings\Controllers'), function () {

    //****** Area Info ****//
    Route::get('settings/area-list', "SettingsController@areaList");
    Route::get('settings/document', "SettingsController@document");
    Route::get('settings/create-area', "SettingsController@createArea");
    Route::get('settings/edit-area/{id}', "SettingsController@editArea");

    Route::post('settings/store-area', "SettingsController@storeArea");
    Route::patch('settings/update-area/{id}', "SettingsController@updateArea");

    Route::post('settings/get-area-data', "SettingsController@getAreaData");

    //****** Bank List ****//
    Route::get('settings/bank-list', "SettingsController@bank");
    Route::get('settings/create-bank', "SettingsController@createBank");
    Route::get('settings/edit-bank/{id}', "SettingsController@editBank");
    Route::get('settings/view-bank/{id}', "SettingsController@viewBank");
    Route::patch('settings/store-bank', "SettingsController@storeBank");
    Route::patch('settings/update-bank/{id}', "SettingsController@updateBank");


    //****** Regulatory Agencies List ****//
    Route::get('settings/regulatory-agency', "SettingsController@regulatoryAgency");
    Route::post('settings/get-regulatory-agency-data', "SettingsController@getRegulatoryAgencyData");
    Route::get('settings/create-regulatory-agency', "SettingsController@createRegulatoryAgency");
    Route::get('settings/edit-regulatory-agency/{id}', "SettingsController@editRegulatoryAgency");
    Route::post('settings/store-regulatory-agency', "SettingsController@storeRegulatoryAgency");
    Route::patch('settings/update-regulatory-agency/{id}', "SettingsController@updateRegulatoryAgency");


    //****** Regulatory Agency Details List ****//
    Route::get('settings/regulatory-agency-details', "SettingsController@regulatoryAgencyDetails");
    Route::post('settings/get-regulatory-agency-details-data', "SettingsController@getRegulatoryAgencyDetailsData");
    Route::get('settings/create-regulatory-agency-details', "SettingsController@createRegulatoryAgencyDetails");
    Route::post('settings/store-regulatory-agency-details', "SettingsController@storeRegulatoryAgencyDetails");
    Route::get('settings/edit-regulatory-agency-details/{id}', "SettingsController@editRegulatoryAgencyDetails");
    Route::post('settings/update-regulatory-agency-details/{id}', "SettingsController@updateRegulatoryAgencyDetails");

    //****** Whats New ****//
    Route::get('settings/whats-new', "SettingsController@whatsNew");
    Route::get('settings/create-whats_new', "SettingsController@whatsNewCreate");
    Route::post('settings/store-whats-new', "SettingsController@whatsNewStore");
    Route::get('settings/get-whats-new-details-data', "SettingsController@getWhatsNew");
    Route::get('settings/edit-whats-new/{id}', "SettingsController@editWhatsNew");
    Route::patch('settings/update-whats-new/{id}', "SettingsController@updateWhatsNew");

    //****** Dashboard Slider ****//
    Route::get('settings/dashboard-slider', "SettingsController@dashSlider");
    Route::get('settings/create-dashboard-slider', "SettingsController@dashSliderCreate");
    Route::post('settings/store-dashboard-slider', "SettingsController@dashSliderStore");
    Route::get('settings/get-dashboard-slider-details-data', "SettingsController@getDashSlider");
    Route::get('settings/edit-dashboard-slider/{id}', "SettingsController@editDashSlider");
    Route::patch('settings/update-dashboard-slider/{id}', "SettingsController@updateDashSlider");

    //****** Holiday ****//
    Route::get('settings/holiday', "SettingsController@holiday");
    Route::get('settings/create-holiday', "SettingsController@holidayCreate");
    Route::post('settings/store-holiday', "SettingsController@holidayStore");
    Route::post('settings/get-holiday-data', "SettingsController@getHoliday");
    Route::get('settings/edit-holiday/{id}', "SettingsController@editHoliday");
    Route::patch('settings/update-holiday/{id}', "SettingsController@updateHoliday");

    // ***** Home Page Slider ******/
    Route::get('settings/home-page-slider', "SettingsController@HomePageSlider");
    Route::get('settings/create-home-page-slider', "SettingsController@HomePageSliderCreate");
    Route::post('settings/store-home-page-slider', "SettingsController@homePageSliderStore");
    Route::get('settings/get-home-page-slider-details-data', "SettingsController@getHomePageSlider");
    Route::get('settings/edit-home-page-slider/{id}', "SettingsController@editHomePageSlider");
    Route::patch('settings/update-home-page-slider/{id}', "SettingsController@updateHomePageSlider");


    // ********* User Manual ******//
    Route::get('settings/user-manual', "SettingsController@Usermanual");
    Route::get('settings/create-user-manual', "SettingsController@UsermanualCreate");
    Route::post('settings/store-user-manual', "SettingsController@UsermanualStore");
    Route::get('settings/get-user-manual-details-data', "SettingsController@getUsermanual");
    Route::get('settings/edit-user-manual/{id}', "SettingsController@editUsermanual");
    Route::patch('settings/update-user-manual/{id}', "SettingsController@updateUsermanual");


    //****** Branch List ****//
    Route::post('settings/get-branch/list', 'SettingsController@getList');
    Route::get('settings/branch-list', "SettingsController@branch");
    Route::get('settings/create-branch', "SettingsController@createBranch");
    Route::get('settings/edit-branch/{id}', "SettingsController@editBranch");
    Route::get('settings/view-branch/{id}', "SettingsController@viewBranch");
    Route::post('settings/store-branch', "SettingsController@storeAndUpdateBranch");
    Route::post('settings/store-branch/{id}', "SettingsController@storeAndUpdateBranch");

    /* Company Information */
    Route::get('settings/company-info', 'SettingsController@companyInfo');
    Route::get('settings/create-company', 'SettingsController@createCompany');
    Route::post('settings/company-store', 'SettingsController@storeCompany');
    Route::get('settings/company-info-action/{id}', 'SettingsController@companyInfoAction');
    Route::get('settings/company-change-status/{id}/{status_id}', 'SettingsController@companyChangeStatus');
    Route::get('settings/approved-change-status/{company_id}', 'SettingsController@companyApprovedStatus');
    Route::get('settings/rejected-change-status/{company_id}', 'SettingsController@companyRejectedStatus');
    Route::post('settings/get-company-data', 'SettingsController@getCompanyData');

    //****** Rejected Draft Company ****//
    Route::get('settings/rejected-draft-company-list', 'SettingsController@rejectedDraftCompanyList');
    Route::post('settings/get-rejected-draft-company-list', 'SettingsController@getRejectedDraftCompanyList');
    Route::get('settings/rejected-draft-company-change-status/{id}', 'SettingsController@rejectedDraftCompanyReject');

    //****** Stakeholder  ****//
    Route::get('settings/stakeholder', "SettingsController@stakeholder");
    Route::post('settings/get-stakeholder-data', "SettingsController@getStakeholderData");
    Route::get('settings/create-stakeholder', "SettingsController@createStakeholder");
    Route::get('settings/edit-stakeholder/{id}', "SettingsController@editStakeholder");
    Route::post('settings/store-stakeholder', "SettingsController@storeStakeholder");
    Route::patch('settings/update-stakeholder/{id}', "SettingsController@updateStakeholder");
    Route::post('settings/getProcessByDept', "SettingsController@getProcessByDept");

    //****** Process Category  ****//
    Route::get('settings/process-category', "SettingsController@processCategory");
    Route::post('settings/get-process-category-data', "SettingsController@getProcessCategoryData");
    Route::get('settings/create-process-category', "SettingsController@createProcessCategory");
    Route::post('settings/store-process-category', "SettingsController@storeProcessCategory");
    Route::get('settings/edit-process-category/{id}', "SettingsController@editProcessCategory");
    Route::patch('settings/update-process-category/{id}', "SettingsController@updateProcessCategory");

    //****** Currency  ****//
    Route::get('settings/currency', "SettingsController@Currency");
    Route::get('settings/create-currency', "SettingsController@createCurrency");
    Route::get('settings/edit-currency/{id}', "SettingsController@editCurrency");

    Route::post('settings/store-currency', "SettingsController@storeCurrency");
    Route::patch('settings/update-currency/{id}', "SettingsController@updateCurrency");

    //****** Config List ****//
    Route::get('settings/configuration', "SettingsController@configuration");
    Route::get('settings/edit-config/{id}', "SettingsController@editConfiguration");
    Route::patch('settings/update-config/{id}', "SettingsController@updateConfig");

    //****** Document List ****// 
    Route::get('settings/document', "SettingsController@document");
    Route::post('settings/get-document-data', "SettingsController@getDocData");
    Route::get('settings/create-document', "SettingsController@createDocument");
    Route::post('settings/store-document', "SettingsController@storeDocument");
    Route::post('/settings/get-attachment-type', "SettingsController@getAttachmentType");
    Route::get('settings/edit-document/{id}', "SettingsController@editDocument");
    Route::patch('settings/update-document', "SettingsController@storeDocument");

    //****** Economic Zone List ****//
    Route::get('settings/park-info', "SettingsController@parks");
    Route::post('settings/get-eco-park-data', "SettingsController@getEcoParkData");
    Route::get('settings/create-park-info', "SettingsController@createPark");
    Route::post('settings/store-eco-zone', "SettingsController@storeEcoZone");
    Route::get('settings/edit-park-info/{id}', "SettingsController@editEcoZone");
    Route::patch('settings/update-park/{id}', "SettingsController@updatePark");

    //****** FAQ Category List ****//
    Route::get('settings/faq-cat', "SettingsController@faqCat");
    Route::get('settings/create-faq-cat', "SettingsController@createFaqCat");
    Route::get('settings/edit-faq-cat/{id}', "SettingsController@editFaqCat");

    //****** High Commission  ****//
    Route::get('settings/high-commission', "SettingsController@highCommission");
    Route::get('settings/create-high-commission', "SettingsController@createHighCommission");
    Route::get('settings/edit-high-commission/{id}', "SettingsController@editHighCommission");

    Route::post('settings/store-high-commission', "SettingsController@storeHighCommission");
    Route::patch('settings/update-high-commission/{id}', "SettingsController@updateHighCommission");

    Route::post('settings/get-high-commission-data', "SettingsController@getHighCommissionData");

    //****** HS Code  ****//
    Route::get('settings/hs-codes', "SettingsController@HsCodes");
    Route::get('settings/create-hs-code', "SettingsController@createHsCode");
    Route::get('settings/edit-hs-code/{id}', "SettingsController@editHsCode");

    Route::post('settings/store-hs-code', "SettingsController@storeHsCode");
    Route::patch('settings/update-hs-code/{id}', "SettingsController@updateHsCode");

    //****** Notice ****//
    Route::get('settings/notice', "SettingsController@Notice");
    Route::get('settings/create-notice', "SettingsController@createNotice");
    Route::get('settings/create-notice/board-meeting', "SettingsController@createNotice");
    Route::get('settings/edit-notice/{id}', "SettingsController@editNotice");
    Route::patch('settings/store-notice', "SettingsController@storeNotice");
    Route::patch('settings/update-notice/{id}', "SettingsController@updateNotice");

    Route::post('settings/get-notice-details-data', "SettingsController@getNoticeDetailsData");

    //****** Ports  ****//
    Route::get('settings/ports', "SettingsController@Ports");
    Route::get('settings/create-port', "SettingsController@createPort");
    Route::get('settings/edit-port/{id}', "SettingsController@editPort");

    Route::post('settings/store-port', "SettingsController@storePort");
    Route::patch('settings/update-port/{id}', "SettingsController@updatePort");

    //****** Industrial Category  ****//
    Route::get('settings/indus-cat', "SettingsController@IndusCat");
    Route::get('settings/create-indus-cat', "SettingsController@createIndusCat");
    Route::get('settings/edit-indus-cat/{id}', "SettingsController@editIndusCat");

    Route::post('settings/store-indus-cat', "SettingsController@storeIndusCat");
    Route::patch('settings/update-indus-cat/{id}', "SettingsController@updateIndusCat");

    //****** Notify List ****//
    Route::get('settings/notification', "SettingsController@notification");
    Route::get('settings/view-notify/{id}', "SettingsController@viewNotify");

    //****** Logo List ****//
    Route::get('settings/logo', "SettingsController@logo");
    Route::patch('settings/update-logo', "SettingsController@storeLogo");
    Route::get('settings/edit-logo', "SettingsController@editLogo");
//    Route::patch('settings/update-logo/{id}', "SettingsController@storeLogo");
//    Route::get('settings/edit-logo/{id}', "SettingsController@editLogo");

    //****** Security List ****//
    Route::get('settings/security', "SettingsController@security");
    Route::patch('settings/store-security', "SettingsController@storeSecurity");
    Route::get('settings/edit-security/{id}', "SettingsController@editSecurity");
    Route::post('settings/get-security-data', "SettingsController@getSecurityData");
    Route::patch('settings/update-security/{id}', "SettingsController@updateSecurity");

    //****** Stakeholder List ****//
    Route::get('settings/stakeholder', "SettingsController@stakeholder");
    Route::post('settings/get-details-data', "SettingsController@getDetailsData");
    Route::get('settings/create-stakeholder', "SettingsController@createStakeholder");
    Route::get('settings/edit-stakeholder/{id}', "SettingsController@editStakeholder");
    Route::patch('settings/store-stakeholder', "SettingsController@storeStakeholder");
    Route::patch('settings/update-stakeholder/{id}', "SettingsController@updateStakeholder");

    /*     * *******************Units*********************** */
    Route::get('settings/units', "SettingsController@Units");
    Route::get('settings/create-unit', "SettingsController@createUnit");
    Route::get('settings/edit-unit/{id}', "SettingsController@editUnit");

    Route::post('settings/store-unit', "SettingsController@storeUnit");
    Route::patch('settings/update-unit/{id}', "SettingsController@updateUnit");

    //************************************Soft Delete *******************************/
    Route::get('settings/delete/{model}/{id}', "SettingsController@softDelete");
    Route::get('settings/delete-new/{model}/{id}', "SettingsController@softDeleteNew");
    Route::get('settings/delete-is-archive/{model}/{id}', "SettingsController@softDeleteIsArchive");

    //****** User Desk  ****//
    Route::get('settings/user-desk', "SettingsController@userDesk");
    Route::get('settings/create-user-desk', "SettingsController@createUserDesk");
    Route::get('settings/edit-user-desk/{id}', "SettingsController@editUserDesk");

    Route::patch('settings/store-user-desk', "SettingsController@storeUserDesk");
    Route::patch('settings/update-user-desk/{id}', "SettingsController@updateUserDesk");

    Route::get('settings/get-user-desk-data', "SettingsController@getUserDeskData");

    /*     * *********User Types ***************** */
    Route::get('settings/user-type', "SettingsController@userType");
    Route::get('settings/edit-user-type/{id}', "SettingsController@editUserType");
    Route::patch('settings/update-user-type/{id}', "SettingsController@updateUserType");

    /*     * *********Service info ***************** */
    Route::get('settings/service-info', "SettingsController@serviceInfo");
    Route::get('settings/create-service-info-details', "SettingsController@createServiceInfoDetails");
    Route::get('settings/edit-service-info-details/{id}', "SettingsController@editServiceInfoDetails");
    Route::patch('settings/update-service-info-details/{id}', "SettingsController@updateServiceDetails");
    Route::post('settings/service-details-save', "SettingsController@serviceSave");

    Route::patch('settings/store-faq-cat', "SettingsController@storeFaqCat");
    Route::patch('settings/update-faq-cat/{id}', "SettingsController@updateFaqCat");

    Route::get('settings/get-faq-cat-details-data', "SettingsController@getFaqCatDetailsData");

    Route::resource('settings/', "SettingsController");


    /*     * *********features ***************** */
    Route::get('settings/features', "SettingsController@features");
    Route::get('settings/create-features', "SettingsController@featuresCreate");
    Route::post('settings/store-features', "SettingsController@featuresStore");
    Route::get('settings/get-features-details-data', "SettingsController@getfeatures");
    Route::get('settings/edit-features/{id}', "SettingsController@editfeatures");
    Route::patch('settings/update-features/{id}', "SettingsController@updatefeatures");


    Route::get('settings/edit-user-type/{id}', "SettingsController@editUserType");
    Route::patch('settings/update-user-type/{id}', "SettingsController@updateUserType");


    //{{--------------sector--------------}}

    Route::get('settings/sector/list', "SettingsController@sectorList");
    Route::get('settings/sector/create', "SettingsController@sectorCreate");
    Route::post('settings/sector/store', "SettingsController@sectorStore");
    Route::post('settings/sector/get-list', "SettingsController@getSectorList");
    Route::get('settings/sector/open/{id}', "SettingsController@sectorOpen");
    Route::get('settings/sector/edit/{id}', "SettingsController@sectorEdit");
    Route::post('settings/sector/update/{id}', "SettingsController@sectorStore");

    /*------sub-sector------*/
    Route::get('settings/sectors/create-sub-sector/{id}', "SettingsController@createSubSector");
    Route::get('settings/sub-sector/edit/{id}', "SettingsController@editSubSector");
    Route::post('settings/sector/store-sub-sector', "SettingsController@subSectorStore");
    Route::post('settings/sector/store-sub-sector/{id}', "SettingsController@subSectorStore");
    Route::post('settings/sector/get-sub-sector-list', "SettingsController@getSubSectorList");
    /*------sub-sector------*/

    /*------product------*/
    Route::get('settings/sub-sector/add-product/{id}', "SettingsController@addEditProduct");
    Route::post('settings/sector/store-product/{id}', "SettingsController@productStore");
    /*------product------*/

    /*-------------Airport-------------*/
    Route::get('settings/airport/list', "SettingsController@airportList");
    Route::get('settings/airport/create-airport', "SettingsController@airportCreate");
    Route::post('settings/airport/get-list', "SettingsController@getAirportList");
    Route::post('settings/airport/store', "SettingsController@airportStore");
    Route::get('settings/edit-airport/{id}', "SettingsController@airportEdit");
    Route::post('settings/airport/update/{id}', "SettingsController@airportStore");
    /*-------------end-------------*/

    //{{--------------sector--------------}}

    //****** Payment Configuration ****//
    Route::get('settings/payment-configuration', "SettingsController@paymentConfiguration");
    Route::get('settings/create-payment-configuration', "SettingsController@paymentConfigurationCreate");
    Route::post('settings/store-payment-configuration', "SettingsController@paymentConfigurationStore");
    Route::post('settings/get-payment-configuration-details-data', "SettingsController@getPaymentConfiguration");
    Route::get('settings/edit-payment-configuration/{id}', "SettingsController@editPaymentConfiguration");
    Route::patch('settings/update-payment-configuration/{id}', "SettingsController@updatePaymentConfiguration");

    Route::post('settings/get-payment-distribution-data', "SettingsController@getPaymentDistributionData");
    Route::get('settings/stakeholder-distribution/{id}', "SettingsController@stakeholderDistribution");
    Route::post('settings/stakeholder-distribution', "SettingsController@stakeholderDistributionStore");
    Route::get('settings/stakeholder-distribution-edit/{id}', "SettingsController@editStakeholderDistribution");
    Route::post('settings/stakeholder-distribution-update/{id}', "SettingsController@stakeholderDistributionStore");


    //****** Stakeholder Payment Configuration ****//
    Route::get('settings/stakeholder-payment-configuration', "SettingsController@stakeholderPaymentConfiguration");
    Route::post('settings/get-stakeholder-payment-configuration-details-data', "SettingsController@getStakeholderPaymentConfiguration");
    Route::post('settings/store-stakeholder-payment-configuration', "SettingsController@stakeholderPaymentConfigurationStore");
    Route::get('settings/create-stakeholder-payment-configuration', "SettingsController@stakeholderPaymentConfigurationCreate");
    Route::get('settings/edit-stakeholder-payment-configuration/{id}', "SettingsController@editStakeholderPaymentConfiguration");
    Route::patch('settings/update-stakeholder-payment-configuration/{id}', "SettingsController@updateStakeholderPaymentConfiguration");

    Route::get('settings/api-stakeholder-distribution/{id}', "SettingsController@apiStakeholderDistribution");
    Route::post('settings/get-stakeholder-payment-distribution-data', "SettingsController@getStakeholderPaymentDistributionData");
    Route::post('settings/api-stakeholder-distribution', "SettingsController@apiStakeholderDistributionStore");
    Route::get('settings/api-stakeholder-distribution-edit/{id}', "SettingsController@apiEditStakeholderDistribution");
    Route::post('settings/api-stakeholder-distribution-update', "SettingsController@apiStakeholderDistributionStore");

    //********Pdf_queue and Pdf_print_request_queue********//
    Route::get('settings/pdf-print-requests', "SettingsController@pdfPrintRequest");
    Route::post('settings/get-pdf-print-requests', "SettingsController@getPdfPrintRequest");
    Route::get('settings/pdf-print-request-search-list', "SettingsController@pdfPrintRequestSearchList");
    Route::get('settings/resend-pdf-print-requests/{id}', "SettingsController@resendPdfPrintRequest");
    Route::get('settings/edit-pdf-print-requests/{id}', "SettingsController@editPdfPrintRequest");
    Route::post('settings/update-pdf-print-requests', "SettingsController@updatePdfPrintRequest");
    Route::get('settings/pdf-print-request-verify/{pdf_id}/{certificate_name}', "SettingsController@verifyPdfPrintRequest");

    Route::get('settings/pdf-queue', "SettingsController@pdfQueue");
    Route::post('settings/pdf-print-request-update', "SettingsController@pdfPrintRequestUpdate");
    Route::post('settings/pdf-queue-update', "SettingsController@pdfQueueUpdate");

    //****** Payment Stakeholder  ****//
    Route::get('settings/payment-stakeholder', "SettingsController@paymentStakeholder");
    Route::post('settings/get-payment-stakeholder-data', "SettingsController@getPaymentStakeholderData");
    Route::get('settings/create-payment-stakeholder', "SettingsController@createPaymentStakeholder");
    Route::post('settings/store-payment-stakeholder', "SettingsController@storePaymentStakeholder");
    Route::get('settings/edit-payment-stakeholder/{id}', "SettingsController@editPaymentStakeholder");
    Route::patch('settings/update-payment-stakeholder/{id}', "SettingsController@updatePaymentStakeholder");

    //****** IPN  ****//
    Route::get('ipn', "SettingsController@ipnList");
    Route::post('ipn/list', "SettingsController@getIpnList");
    Route::get('ipn/ipn-history/{id}', "SettingsController@ipnHistory");

    //****** Email & SMS Queue  ****//
    Route::get('settings/email-sms-queue', 'SettingsController@emailSmsQueueList');
    Route::post('settings/email-sms-queue/list', 'SettingsController@getEmailSmsQueueList');
    Route::get('settings/resend-email-sms-queue/{id}/{type}', 'SettingsController@resendEmailSmsQueue');
    Route::get('settings/email-sms-queue/edit/{id}', 'SettingsController@editEmailSmsQueue');
    Route::patch('settings/update-email-sms-queue/{id}', "SettingsController@updateEmailSmsQueue");
    Route::get('settings/email-sms-search-list', "SettingsController@emailSmsSearchList");



    //****** Application Rollback  ****//
    Route::get('settings/app-rollback', 'SettingsController@applicationRollbackList');
    Route::post('settings/app-rollback/list', 'SettingsController@getApplicationList');
    Route::get('settings/app-rollback-search', 'SettingsController@applicationSearch');
    Route::post('settings/app-rollback-open', 'SettingsController@applicationRollbackOpen');
    Route::post('settings/app-rollback/update', 'SettingsController@applicationRollbackUpdate');
    Route::post('settings/get-user-by-desk', "SettingsController@getUserByDesk");
    Route::get('settings/app-rollback-view/{id}', 'SettingsController@viewApplicationRollback');

    //****** External Service List  ****//
    Route::get('settings/external-service-list', 'SettingsController@externalServiceList');
    Route::post('settings/get-external-service-list', 'SettingsController@getExternalServiceList');
    Route::get('settings/external-service-list/edit/{id}', 'SettingsController@externalServiceEdit');
    Route::post('settings/get-external-service-list/update', 'SettingsController@externalServiceUpdate');


    //****** Forcefully data update update ****//
    Route::get('settings/forcefully-data-update', "SettingsController@forcefullyDataUpdate");
    Route::post('settings/get-forcefully-data-update-data', "SettingsController@getForcefullyDataList");
    Route::get('settings/create-forcefully-data-update', "SettingsController@createForcefullyDataUpdate");
//    Route::get('settings/edit-forcefully-data-update/{id}', "SettingsController@editForcefullyDataUpdate");
    Route::post('settings/store-forcefully-data-update', "SettingsController@storeForcefullyDataUpdate");
    Route::post('settings/approve-forcefully-data-update', "SettingsController@approveForcefullyDataUpdate");
    Route::get('settings/forcefully-data-update-view/{id}', "SettingsController@singleForcefullyViewById");


    // Change basic info
    Route::get('settings/get-change-basic-info-list', "SettingsController@getChangeBasicInfoList");
    Route::post('settings/get-change-basic-info-list-data', "SettingsController@getChangeBasicInfoListData");
    Route::get('settings/change-basic-info/{company_id}', "SettingsController@changeBasicInfo");
    Route::post('settings/store-change-basic-info', "SettingsController@storeChangeBasicInfo");
    Route::get('settings/change-basic-info-view/{id}', "SettingsController@singleBasicInfoViewById");
    Route::post('settings/change-basic-info-data-update', "SettingsController@approveBasicInfoDataUpdate");


    //****** Maintenance Mode  ****//
    Route::get('settings/maintenance-mode', "SettingsController@maintenanceMode");
    Route::get('settings/maintenance-mode/get-users-list', "SettingsController@getMaintenanceUserList");
    Route::get('settings/maintenance-mode/remove-user/{user_id}', "SettingsController@removeUserFromMaintenance");
    Route::post('settings/maintenance-mode/store', "SettingsController@maintenanceModeStore");


    /*     * ***********************End of Group Route file***************************** */
});


// some route which are used in different module
Route::group(array('module' => 'Settings', 'middleware' => ['auth'], 'namespace' => 'App\Modules\Settings\Controllers'), function () {
    Route::post('settings/storeDe', "SettingsController@storeDe");
    Route::get('settings/edit-form-json', "SettingsController@editFormJson");
    Route::get('/settings/get-district-by-division-id', 'SettingsController@get_district_by_division_id');
    Route::get('settings/get-police-stations', 'SettingsController@getPoliceStations');
    Route::get('settings/get-district-user', 'SettingsController@getDistrictUser');
    Route::get('settings/form', 'SettingsController@form');

    // For Feedback //

    // Route::get('settings/feedback','FeedbackController@feedback');
    // Route::get('settings/fMsgShow','FeedbackController@fMsgShow');
});
