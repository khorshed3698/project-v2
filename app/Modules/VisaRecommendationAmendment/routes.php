<?php

Route::group(['module' => 'VisaRecommendationAmendment', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\VisaRecommendationAmendment\Controllers'], function() {

    Route::get('visa-recommendation-amendment/add', 'VisaRecommendationAmendmentController@applicationForm');
    Route::post('visa-recommendation-amendment/store', 'VisaRecommendationAmendmentController@appStore');
    Route::get('visa-recommendation-amendment/edit/{id}/{openMode}', "VisaRecommendationAmendmentController@applicationEdit");
    //Route::get('visa-recommendation-amendment/view/{id}/{openMode}', "VisaRecommendationAmendmentController@applicationViewEdit");
    Route::get('visa-recommendation-amendment/view/{id}', "VisaRecommendationAmendmentController@applicationView");
    Route::get('visa-recommendation-amendment/preview', "VisaRecommendationAmendmentController@preview");
//    Route::get('visa-recommendation-amendment/app-pdf/{id}', 'VisaRecommendationAmendmentController@appFormPdf');
    Route::post('visa-recommendation-amendment/upload-document', "VisaRecommendationAmendmentController@uploadDocument");
    Route::post('visa-recommendation-amendment/payment', "VisaRecommendationAmendmentController@Payment");
    Route::get('visa-recommendation-amendment/afterPayment/{payment_id}', 'VisaRecommendationAmendmentController@afterPayment');
    Route::post('visa-recommendation-amendment/getDocList', 'VisaRecommendationAmendmentController@getDocList');
    Route::get('visa-recommendation-amendment/afterCounterPayment/{payment_id}', 'VisaRecommendationAmendmentController@afterCounterPayment');
});


Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {

    Route::get('visa-recommendation-amendment/list/{process_id}', 'ProcessPathController@processListById');
});

