<?php

Route::group(['module' => 'NewConnectionBPDB', 'middleware' => ['api'], 'namespace' => 'App\Modules\NewConnectionBPDB\Controllers'], function() {

    Route::resource('NewConnectionBPDB', 'NewConnectionBPDBController');

});
