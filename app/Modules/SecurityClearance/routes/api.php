<?php

Route::group(['module' => 'SecurityClearance', 'middleware' => ['api'], 'namespace' => 'App\Modules\SecurityClearance\Controllers'], function() {

    Route::resource('SecurityClearance', 'SecurityClearanceController');

});
