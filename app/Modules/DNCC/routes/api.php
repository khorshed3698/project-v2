<?php

Route::group(['module' => 'DNCC', 'middleware' => ['api'], 'namespace' => 'App\Modules\DNCC\Controllers'], function() {

    Route::resource('DNCC', 'DNCCController');

});
