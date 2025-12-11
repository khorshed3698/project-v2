<?php

Route::group(['module' => 'VATReg', 'middleware' => ['api'], 'namespace' => 'App\Modules\VATReg\Controllers'], function() {

    Route::resource('VATReg', 'VATRegController');

});
