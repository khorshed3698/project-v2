<?php

Route::group(['module' => 'FscdNocProposed', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\FscdNocProposed\Controllers'], function() {

    Route::resource('FscdNocProposed', 'FscdNocProposedController');
    Route::get('licence-applications/bfscd-noc-proposed/add', 'FscdNocProposedController@appForm');
    Route::post('noc-proposed/store', 'FscdNocProposedController@appStore');
    Route::get('licence-applications/bfscd-noc-proposed/edit/{id}/{openMode}', "FscdNocProposedController@applicationViewEdit");
    Route::get('licence-applications/bfscd-noc-proposed/view/{id}/{openMode}', "FscdNocProposedController@applicationView");

    Route::get('licence-applications/bfscd-noc-proposed/afterPayment/{payment_id}', 'FscdNocProposedController@afterPayment');
    Route::get('licence-applications/bfscd-noc-proposed/afterCounterPayment/{payment_id}', 'FscdNocProposedController@afterCounterPayment');


    Route::get('bfscd-noc-proposed/get-refresh-token', "FscdNocProposedController@getRefreshToken");


});

Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('bfscd-noc-proposed/list/{process_id}', 'ProcessPathController@processListById');
});
