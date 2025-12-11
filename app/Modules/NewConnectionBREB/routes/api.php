<?php

Route::group(['module' => 'NewConnectionBREB', 'middleware' => ['api'], 'namespace' => 'App\Modules\NewConnectionBREB\Controllers'], function () {

    Route::resource('NewConnectionBREB', 'NewConnectionBREBController');

});
