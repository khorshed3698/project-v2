<?php

Route::group(['module' => 'CertificateGeneration', 'middleware' => ['api'], 'namespace' => 'App\Modules\CertificateGeneration\Controllers'], function() {

    Route::resource('certificateGeneration', 'CertificateGenerationController');

});
