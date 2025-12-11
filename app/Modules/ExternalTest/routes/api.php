<?php

Route::group(['module' => 'ExternalTest', 'middleware' => ['api'], 'namespace' => 'App\Modules\ExternalTest\Controllers'], function() {

    Route::resource('ExternalTest', 'ExternalTestController');

});
