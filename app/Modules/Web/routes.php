<?php

use Illuminate\Support\Facades\Route;

Route::group(array('module' => 'Web', 'middleware' => ['XssProtection'],  'namespace' => 'App\Modules\Web\Controllers'), function () {

//    Route::resource('Web', 'WebController');
    Route::get('web/get-report-object/{type}', 'WebController@loadDashboardObjectsChart');
    //    public training Schedule
    Route::get('training-public/get-training-public-schedule', "WebController@getTrainingPublicSchedule");
    Route::post('training-public/application-form', "WebController@applyForm");
    Route::post('apply', "WebController@applyPublicTraining");

    Route::get('web/page-object', 'WebController@loadPageObjects');
    Route::get('web/get-object', 'WebController@loadDashboardObjectsList');

    Route::get('web/chart-render', 'WebController@chartRender');
    Route::get('web/get-HomePage-dod-object', 'WebController@getHomePageDODObject');

    Route::get('training-resource-public/embedded/{id}', "WebController@publicTrainingVideo");

    Route::get('docs/{pdf_type}/{doc_id}', 'WebController@getDocs');
    // Route::get('director-machinery-docs/{app_id}/{process_type_id}', 'WebController@getDirMacDocs');

    Route::get('/2FA', 'WebController@twoStep');
    Route::patch('/2FA/check-two-step', 'WebController@checkTwoStep');

    Route::get('web/necessary-resources', 'WebController@necessaryResources');
    Route::post('web/get-necessary-resources', 'WebController@getNecessaryResources');

    Route::get('web/get-available-services', 'WebController@getAvailableService');
    Route::get('/web/get-available-service-details/{sub_service_id}', 'WebController@getAvailableServiceDetails');
    Route::get('/web/get-bbs-code', 'WebController@getBBSCode');
    Route::get('/web/get-business-class-list', 'WebController@getBusinessClassList');

    Route::get('web/get-agency-list', 'WebController@getAgencyList');
    Route::get('web/get-sub-agency-content/{sub_agency_id}', 'WebController@getSubAgencyContent');

    //is helpful article route
    Route::get('/web/is-helpful-article', 'WebController@isHelpFul');

    Route::post('/web/view-page-link-count', 'WebController@viewPageLinkCount');


    // new landing page routes start
    Route::get('/', 'HomeController@index')->name('web.home');
    Route::get('/login', 'HomeController@index');
//    Route::get('/login/{lang}', 'HomeController@index');

    /** contact start **/
    Route::get('settings/home-page/contact/next-step-html', 'ContactController@nextStepHtml')->name('contact.next_step_html');
    Route::post('settings/home-page/contact/store', 'ContactController@store')->name('contact.store');
    Route::get('settings/home-page/contact', 'ContactController@index')->name('contact.list')->middleware(['auth', 'checkAdmin']);
    Route::get('settings/home-page/contact/view/{id}', 'ContactController@view')->name('contact.view')->middleware(['auth', 'checkAdmin']);
    /** contact end **/

    /** inner page start **/
    Route::get('service-tracking', 'HomeController@serviceTracking')->name('service_tracking');
    Route::get('common-innerpage', 'HomeController@commonInnerPage')->name('common_innerpage');
    /** inner page end **/

    Route::get('articles/bida', 'HomeArticlesController@aboutBida')->name('web.aboutBida');
    Route::get('articles/one-stop-service', 'HomeArticlesController@aboutOneStopService')->name('web.aboutOneStopService');
    Route::get('articles/about-osspid', 'HomeArticlesController@aboutOsspid')->name('web.aboutOsspid');
    Route::get('articles/about-quick-service-portal', 'HomeArticlesController@aboutQuickServicePortal')->name('web.aboutQuickServicePortal');
    Route::get('articles/available-services', 'HomeArticlesController@availableOnlineServices')->name('web.availableOnlineServices');

    
    Route::get('articles/privacy-statement', 'HomeArticlesController@privacyStatement');
    Route::get('articles/investment-promotion-agency-bd', 'HomeArticlesController@investmentPromotionAgencyBd');
    Route::get('articles/certificate-issuing-agency-bd', 'HomeArticlesController@certificateIssuingAgencyBbd');
    Route::get('articles/document-and-downloads', 'HomeArticlesController@documentAndDownloads');
    Route::get('articles/utility-service-provider', 'HomeArticlesController@utilityServiceProvider');
    Route::get('articles/terms-of-services', 'HomeArticlesController@termsOfServices');
    Route::get('articles/business-sector', 'HomeArticlesController@businessSector');
    Route::get('articles/support', 'ArticlesController@support');

    Route::post('get-company-by-process-type', 'HomeController@getCompanyByProcessType')->name('getCompanyByProcessType');
    Route::post('search-service-info', 'HomeController@searchServiceInfo')->name('searchServiceInfo');

    Route::post('/bida-oss-landing/dataSet', 'HomeController@dynamicDataSets');
    Route::post('/bida-oss-landing/insightdb-token', 'HomeController@getInsightDBApiToken');
    Route::post('/data-sets', 'HomeController@fetchDataSets');
    Route::post('/bida_oss_public_notice', 'HomeController@getLatestNotice');

    // new landing page routes end
});


Route::group(array('module' => 'Web', 'middleware' => ['XssProtection'],  'namespace' => 'App\Modules\Web\Controllers'), function () {
    Route::get('/br-common-pool-data-update', 'OldDataUpdateController@brCommonPoolList');
});
