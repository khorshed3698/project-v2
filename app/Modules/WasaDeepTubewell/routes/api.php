<?php

Route::group(['module' => 'WasaDeepTubewell', 'middleware' => ['api'], 'namespace' => 'App\Modules\WasaDeepTubewell\Controllers'], function() {

    Route::resource('WasaNewConnection', 'WasaDeepTubewellController');

});
