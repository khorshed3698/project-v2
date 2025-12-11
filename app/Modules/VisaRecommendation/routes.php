<?php

Route::group(['module' => 'VisaRecommendation', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\VisaRecommendation\Controllers'], function() {

    Route::get('visa-recommendation/add', 'VisaRecommendationController@applicationForm');
    Route::get('visa-recommendation/edit/{id}/{openMode}', "VisaRecommendationController@applicationEdit");
    Route::get('visa-recommendation/view/{id}', "VisaRecommendationController@applicationView");
    Route::post('visa-recommendation/store', 'VisaRecommendationController@appStore');
    Route::get('visa-recommendation/get-embassy-by-country', 'VisaRecommendationController@getEmbassyByCountry');
    Route::get('/visa-recommendation/preview', "VisaRecommendationController@preview");
    Route::post('/visa-recommendation/getCategory/byService', "VisaRecommendationController@getServicewiseType");
    Route::post('visa-recommendation/upload-document', "VisaRecommendationController@uploadDocument");
    Route::get('visa-recommendation/app-pdf/{id}', 'VisaRecommendationController@appFormPdf');
    Route::post('/visa-recommendation/getDocList', "VisaRecommendationController@loadDocList");
    Route::post('/visa-recommendation/getTravelHistoryDocList', "VisaRecommendationController@loadDocList");
    Route::get('/visa-recommendation/certificate/{app_id}/{process_type_id}', 'VisaRecommendationController@vrCertificateAndOther');
    Route::get('visa-recommendation/afterPayment/{payment_id}', 'VisaRecommendationController@afterPayment');
    Route::get('visa-recommendation/afterCounterPayment/{payment_id}', 'VisaRecommendationController@afterCounterPayment');

});


Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('visa-recommendation/list/{process_id}', 'ProcessPathController@processListById');
});

