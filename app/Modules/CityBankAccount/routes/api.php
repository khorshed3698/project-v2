<?php

Route::group(['module' => 'SBaccount', 'middleware' => ['api'], 'namespace' => 'App\Modules\SBaccount\Controllers'], function() {

    Route::resource('SBaccount', 'SBaccountController');

});
