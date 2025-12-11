<?php

Route::group(['module' => 'FscdNocProposed', 'middleware' => ['api'], 'namespace' => 'App\Modules\FscdNocProposed\Controllers'], function() {

    Route::resource('FscdNocProposed', 'FscdNocProposedController');

});
