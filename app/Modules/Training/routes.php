<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'Training', 'middleware' => ['auth', 'checkAdmin', 'XssProtection'], 'namespace' => 'App\Modules\Training\Controllers'], function () 
{
    Route::get('training/schedule/list', "TrScheduleController@index");
    Route::post('training/get-schedule-data', "TrScheduleController@getData");
    Route::get('training/create-schedule', "TrScheduleController@createSchedule");
    Route::get('training/edit-schedule/{id}', "TrScheduleController@editSchedule");
    Route::get('training/view-schedule-details/{id}', "TrScheduleController@scheduleDetails");
    
    Route::get('training/schedule-update/{id}', "TrScheduleController@scheduleUpdate");

    Route::get('training/category-list/get-image-and-category', "TrScheduleController@trainingCategoryGetImageByCategory");
    Route::get('training/category-list', "TrCategoryController@index");
    Route::post('training/get-category-data', "TrCategoryController@getData");
    Route::get('training/create-category', "TrCategoryController@createCategory");
    Route::post('training/store-category', "TrCategoryController@storeCategory");
    Route::get('training/edit-category/{id}', "TrCategoryController@editCategory");

    Route::get('training/course-list', "TrCourseController@index");
    Route::post('training/get-course-data', "TrCourseController@getData");
    Route::get('training/create-course', "TrCourseController@createCourse");


    Route::get('training/view-schedule/{id}', "TrScheduleController@trainingDetails");

    Route::get('training/schedule/check-batch-name', "TrScheduleController@checkBatchName");

    //Common Route
    Route::get('training/get-batch-by-course-id', "TrCommonController@getBatchByCourseId");
    Route::get('training/get-course-by-trScheduleMasterId', "TrCommonController@getSessionBytrScheduleMasterId");

    //Notification
    Route::get('training/notification/list', "TrNotificationController@index");
    Route::post('training/notification/get-data', "TrNotificationController@getData");
    Route::get('training/notification/add-notification', "TrNotificationController@createNotification");
    Route::post('training/notification/add-notification', "TrNotificationController@storeNotification");

    //Participants Marks
    Route::get('training/evaluation/list', "TrEvaluationController@index");
    Route::get('training/evaluation/create', "TrEvaluationController@evaluationCreate");
    Route::get('training/participants-marks', "TrEvaluationController@participantsMarks");
    Route::post('training/store-participant-marks-bulk', "TrEvaluationController@storeParticipantMarksBulk");
    Route::post('training/evaluation/get-data', "TrEvaluationController@getData");

    //Participants Attendance

    Route::get('training/attendance/create', "TrAttendanceController@attendanceCreate");
    Route::post('training/attendance-entry', "TrAttendanceController@attendanceEntry");
    Route::post('training/attendance-entry-all', "TrAttendanceController@attendanceEntryAll");
    Route::get('training/get-participants-by-scheduleSessionId', "TrAttendanceController@getParticipantsBytrSessionMasterId");


    Route::get('training/dashboard', "TrScheduleController@trainingDashboard");
    Route::get('training/upcoming-course', "TrScheduleController@upcomingCourse");
    Route::get('training/purchase-course', "TrScheduleController@purchaseCourse");
    Route::get('training/course-details/{id}', "TrParticipantController@courseDetails");
    Route::post('training/upload-document', "TrScheduleController@uploadDocument");

    //Certificate
    Route::get('training/get-certificate/{part_id}/{course_id}', "TrCertificateController@participantCertificate");
    Route::get('training/regenerate-certificate/{part_id}/{course_id}', "TrCertificateController@regenerateCertificate");

    // TrParticipantController
    Route::post('training/enroll-participants/{id}', "TrParticipantController@enrollParticipants");
    Route::get('training/afterPayment/{payment_id}', 'TrParticipantController@afterPayment');
    Route::post('training/participants-data/update', "TrParticipantController@updateParticipantsData");
    Route::get('training/check-session-participant', "TrParticipantController@checkSessionParticipant");
    Route::get('training/schedule/get-status-wise-user-list', "TrParticipantController@getstatusWiseTrainingUserData");
    Route::get('training/schedule/download-participants/{id}', "TrParticipantController@downloadParticipantsAll");
    Route::get('training/schedule-participant-info/{course_id}/{part_id}', "TrParticipantController@participantInfo");
    Route::get('/training/schedule/get-user-list', "TrParticipantController@getUserData");
    Route::get('training/schedule/participant-activates', "TrParticipantController@participantActivates");

});
Route::group(['module' => 'Training', 'middleware' => ['auth', 'checkAdmin'], 'namespace' => 'App\Modules\Training\Controllers'], function () 
{
    Route::post('training/store-schedule', "TrScheduleController@storeSchedule");
    Route::post('training/update-schedule/{id}', "TrScheduleController@storeSchedule");

    Route::post('training/store-course', "TrCourseController@storeCourse");
    Route::get('training/edit-course/{id}', "TrCourseController@editCourse");

});

Route::group(['module' => 'Training', 'middleware' => ['XssProtection'], 'namespace' => 'App\Modules\Training\Controllers'], function () 
{   
    Route::get('bida/training-list', 'TrScheduleController@training');
    Route::get('bida/training-details/{id}', "TrScheduleController@trainingDetailsNew");
    Route::get('bida/get-training-data', "TrScheduleController@getTrainingData");
    Route::get('bida/training/filter-data', "TrScheduleController@getTrainingFilterData");
});



Route::group(['module' => 'Training', 'middleware' => ['XssProtection'], 'namespace' => 'App\Modules\Training\Controllers'], function () 
{   
    Route::post('training/identity-verify', "TrainingSignupController@identityVerifyConfirm");

});









