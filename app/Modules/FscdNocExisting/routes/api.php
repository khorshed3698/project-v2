<?php

Route::group(['module' => 'FscdNocExisting', 'middleware' => ['api'], 'namespace' => 'App\Modules\FscdNocExisting\Controllers'], function() {

    Route::resource('FscdNocExisting', 'FscdNocExistingController');

});
