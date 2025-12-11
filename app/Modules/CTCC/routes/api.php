<?php

Route::group(['module' => 'CTCC', 'middleware' => ['api'], 'namespace' => 'App\Modules\CTCC\Controllers'], function() {

    Route::resource('CTCC', 'CTCCController');

});
