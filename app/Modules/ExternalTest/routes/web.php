<?php

Route::group(['module' => 'ExternalTest','prefix'=>'external-test', 'middleware' => ['XssProtection'], 'namespace' => 'App\Modules\ExternalTest\Controllers'], function() {

    Route::resource('ExternalTest', 'ExternalTestController');
    Route::get('/list', 'ExternalTestController@index');
    Route::post('/submission', 'ExternalTestController@store');
    Route::get('/show/{id}', 'ExternalTestController@show');
    Route::get('/application/{id}', 'ExternalTestController@show');
    Route::get('/status', 'ExternalTestController@status');

});
