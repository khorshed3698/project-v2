<?php

Route::group(['module' => 'ERC', 'middleware' => ['api'], 'namespace' => 'App\Modules\ERC\Controllers'], function() {

    Route::resource('ERC', 'ERCController');

});
