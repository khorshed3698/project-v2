<?php

Route::group(['module' => 'WasaNewConnection', 'middleware' => ['api'], 'namespace' => 'App\Modules\WasaNewConnection\Controllers'], function() {

    Route::resource('WasaNewConnection', 'WasaNewConnectionController');

});
