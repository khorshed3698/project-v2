<?php

Route::group(['module' => 'DOE', 'middleware' => ['api'], 'namespace' => 'App\Modules\DOE\Controllers'], function() {

    Route::resource('DOE', 'DOEController');

});
