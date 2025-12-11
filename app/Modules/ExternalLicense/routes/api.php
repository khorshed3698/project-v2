<?php

Route::group(['module' => 'ExternalLicense', 'middleware' => ['api'], 'namespace' => 'App\Modules\ExternalLicense\Controllers'], function() {

    Route::resource('ExternalLicense', 'ExternalLicenseController');

});
