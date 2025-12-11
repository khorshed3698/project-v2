<?php

Route::group(array('module' => 'Reports', 'middleware' => ['auth','XssProtection'], 'namespace' => 'App\Modules\Reports\Controllers'), function() {

    Route::get('/reports', "ReportsController@index");

    Route::get('/reports/create', "ReportsController@create")->middleware('checkSysAdminAndMIS');
    Route::get('/reports/edit/{id}', "ReportsController@edit")->middleware('checkSysAdminAndMIS');

    Route::get('/reports/show/{id}', "ReportsController@show");
    Route::get('/reports/view/{id}', "ReportsController@view");

    Route::get('/reports/add-to-favourite/{id}', "ReportsController@addToFavourite");
    Route::get('/reports/getuserbytype', "ReportsController@getusers");
    Route::get('/reports/remove-from-favourite/{id}', "ReportsController@removeFavourite");

    Route::post('/reports/verify', "ReportsController@reportsVerify");
    Route::get('/reports/tables', "ReportsController@showTables");

    Route::post('/reports/show-report/{report_id}', "ReportsController@showReport");
    Route::get('/reports/show-report/{report_id}', "ReportsController@showReport");

    foreach (glob(__DIR__ . '/routes/*.php') as $route_file) {
        require_once $route_file;
    }

    Route::patch('/reports/store', "ReportsController@store");
    Route::patch('/reports/update/{id}', "ReportsController@update");
    
});

