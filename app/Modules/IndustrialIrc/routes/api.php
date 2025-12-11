<?php

Route::group(['module' => 'IndustrialIrc', 'middleware' => ['api'], 'namespace' => 'App\Modules\IndustrialIrc\Controllers'], function() {

    Route::resource('IndustrialIrc', 'IndustrialIrcController');

});
