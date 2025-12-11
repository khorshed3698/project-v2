<?php

Route::group(['module' => 'DCCICos', 'middleware' => ['api'], 'namespace' => 'App\Modules\DCCICos\Controllers'], function () {

    Route::resource('DCCICos', 'DCCICosController');

});
