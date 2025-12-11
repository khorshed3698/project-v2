<?php

Route::group(array('module' => 'Dashboard', 'middleware' => ['auth'],'namespace' => 'App\Modules\Dashboard\Controllers'), function() {


//     Route::post('dashboard/store-feedback', "DashboardController@featuresFeedback");
     Route::post('dashboard/feedback/store', "DashboardController@applicationWiseFeedbackStorage");
     Route::post('dashboard/feedback/check-already-exist', "DashboardController@feedbackCheck");
    // Route::post('dashboard/steps-modal', "DashboardController@skipNextFeedback");
    // Route::get('/dashboard-featureShow', "DashboardController@featureShow");

    Route::get('/dashboard/new-application', 'DashboardController@applyNewApplication');

    Route::get('/notifications/show',"DashboardController@notifications");
    Route::get('/notification-all',"DashboardController@notificationAll");
    Route::get('/dashboard/feedback-lists',"DashboardController@feedbackList");

    Route::get('/single-notification/{id}',"DashboardController@notificationSingle");
    Route::get('/notifications/count',"DashboardController@notificationCount");
    Route::get('/dashboard/apply-service', 'DashboardController@applyNewService');
    Route::resource('dashboard', 'DashboardController');
    
});