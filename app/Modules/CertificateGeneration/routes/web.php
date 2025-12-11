<?php

Route::group(['module' => 'CertificateGeneration', 'middleware' => ['XssProtection'],
    'namespace' => 'App\Modules\CertificateGeneration\Controllers'], function() {

    Route::get('certificate-generate', "CertificateGenerationController@generateCertificate");
});
