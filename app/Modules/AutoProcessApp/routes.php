<?php

Route::group(array('module' => 'AutoProcessApp','middleware' => ['auth','XssProtection'], 'namespace' => 'App\Modules\AutoProcessApp\Controllers'), function() {

    Route::get('auto-process-list/{id}', 'AutoProcessAppController@applist');

    Route::get('auto-process/get-list/{status?}/{desk?}',[
        'as' => 'autoProcess.getList',
        'uses' => 'AutoProcessAppController@getList'
    ]);


    Route::resource('AutoProcessApp', 'AutoProcessAppController');
});	