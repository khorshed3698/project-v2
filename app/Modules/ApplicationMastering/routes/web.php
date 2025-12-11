<?php

Route::group(['module' => 'ApplicationMastering', 'middleware' => ['XssProtection', 'web'], 'namespace' => 'App\Modules\ApplicationMastering\Controllers'], function() {

    Route::get('application-mastering', "ApplicationMasteringController@index");
});
