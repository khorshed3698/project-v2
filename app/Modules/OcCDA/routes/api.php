<?php

Route::group(['module' => 'CdaForm', 'middleware' => ['api'], 'namespace' => 'App\Modules\CdaForm\Controllers'], function() {

    Route::resource('CdaForm', 'CdaFormController');

});
