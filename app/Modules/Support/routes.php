<?php

Route::group(array('module' => 'Support', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\Support\Controllers'), function() {

    // Dashboard bottom right help support
    Route::get('support/help/{segment}', "SupportController@help");

    // Dashboard more notice
    Route::get('support/view-notice/{id}', "SupportController@viewNotice");


    // The initial investigation of the support module the below routs are commented cause of the module is not working properly
    // after live update and closely monitoring the below route and all others component like controller, model, view, etc. will be removed


    ###support feedback
//    Route::get('support/feedback', "SupportController@feedback");
//    Route::get('support/create-feedback', "SupportController@createFeedback");
    ### Feedback details data
//    Route::get('support/get-feedback-details-data', "SupportController@getFeedbackDetailsData");
    ### Get feedback details data of assigned to a specific user (admin)
//    Route::post('support/get-uncategorized-feedback-data/{flag}', "SupportController@getUncategorizedFeedbackData");
    /* start route of NID support*/
//    Route::get('support/nid-list', "SupportController@nidList");
//    Route::post('support/get-nid-list', "SupportController@getNIDList");
//    Route::get('support/view-nid-details/{nid}/{dob}', "SupportController@getNidDetails");
//    Route::get('support/nid-edit/{nid}/{dob}', "SupportController@editNidSource");
//    Route::post('support/nid-edit-store/{nid}/{dob}', "SupportController@editNidStore");
//    Route::get('support/re_submit_nid_status/{nid?}/{dob?}', "SupportController@reSubmitNidStatus");
//    Route::get('support/search-nid', "SupportController@searchNid");
    /* Notice of NID support */
//    Route::resource('support', 'SupportController');

});	