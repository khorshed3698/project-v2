<?php

Route::group(array('module' => 'MeetingForm', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'],
    'namespace' => 'App\Modules\MeetingForm\Controllers'), function () {

    Route::get('meeting-form/add', 'MeetingFormController@applicationForm');
    Route::post('meeting-form/add', 'MeetingFormController@appStore');

    //Loan Locator view/edit and application download route
    Route::get('meeting-form/view/{id}', "MeetingFormController@applicationViewEdit");
    Route::get('meeting-form/view/{id}/{openMode}', "MeetingFormController@applicationViewEdit");
    Route::get('meeting-form/view/{id}/board-meeting', "MeetingFormController@applicationViewEdit");
    Route::get('space-allocation/application/{openMode}/{id}', 'SpaceAllocationController@applicationViewEdit');
    Route::get('space-allocation/download/{id}', 'SpaceAllocationController@applicationDownload');
    Route::get('space-allocation/certificate/{app_id}/{process_type_id}', 'SpaceAllocationController@certificateAndOther');
    Route::get('loan-locator/updateAD/{app_id}/{process_type_id}', 'LoanLocatorController@updateADInfo');
    Route::get('loan-locator/verify_history/{process_type_id}/{process_list_id}', 'LoanLocatorController@verifyProcessHistory');
    Route::get('space-allocation/desk_form', 'SpaceAllocationController@AdddeskForm');


    /*     *********************************End of Route group****************************** */

});



// Route group without XssProtection so that the data from Rich text editor in the process bar do not get pursed
Route::group(array('module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'), function () {
    Route::get('meeting-form/list/{process_id}', 'ProcessPathController@processListById');
    /*     * ********************************End of Route group****************************** */
});
