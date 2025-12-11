<?php

Route::group(['module' => 'BccCDA', 'middleware' => ['api'], 'namespace' => 'App\Modules\BccCDA\Controllers'], function () {

    Route::resource('BccCDA', 'BccCdaController');

});
