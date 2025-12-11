<?php

Route::group(['module' => 'ETINforeigner', 'middleware' => ['api'], 'namespace' => 'App\Modules\ETINforeigner\Controllers'], function() {

    Route::resource('eTINforeigner', 'ETINforeignerController');

});
