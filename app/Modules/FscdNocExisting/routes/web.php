<?php

Route::group(['module' => 'FscdNocExisting', 'middleware' => ['auth', 'XssProtection'], 'namespace' => 'App\Modules\FscdNocExisting\Controllers'], function() {

    Route::resource('FscdNocExisting', 'FscdNocExistingController');
    Route::get('licence-applications/bfscd-noc-exiting/add', 'FscdNocExistingController@appForm');
    Route::post('noc-exiting/store', 'FscdNocExistingController@appStore');
    Route::get('licence-applications/bfscd-noc-exiting/edit/{id}/{openMode}', "FscdNocExistingController@applicationViewEdit");
    Route::get('licence-applications/bfscd-noc-exiting/view/{id}/{openMode}', "FscdNocExistingController@applicationView");

    Route::get('licence-applications/bfscd-noc-exiting/afterPayment/{payment_id}', 'FscdNocExistingController@afterPayment');
    Route::get('licence-applications/bfscd-noc-exiting/afterCounterPayment/{payment_id}', 'FscdNocExistingController@afterCounterPayment');


    Route::get('bfscd-noc-exiting/get-refresh-token', "FscdNocExistingController@getRefreshToken");


});

Route::group(array(
    'module' => 'ProcessPath', 'middleware' => ['auth', 'checkAdmin'],
    'namespace' => 'App\Modules\ProcessPath\Controllers'
), function () {
    Route::get('bfscd-noc-exiting/list/{process_id}', 'ProcessPathController@processListById');
});
