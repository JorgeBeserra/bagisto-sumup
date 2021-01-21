<?php

if ( ! defined( 'SUMUP_CONTROLER')) {
    define('SUMUP_CONTROLER', 'Jorgebeserra\Sumup\Http\Controllers\SumupController@');
}

Route::group(['middleware' => ['web']], function () {
    Route::prefix('sumup')->group(function () {
        Route::get('/redirect', SUMUP_CONTROLER . 'redirect')->name('sumup.redirect');
        Route::post('/notify', SUMUP_CONTROLER . 'notify')->name('sumup.notify');
        Route::get('/success', SUMUP_CONTROLER . 'success')->name('sumup.success');
        Route::get('/cancel', SUMUP_CONTROLER . 'cancel')->name('sumup.cancel');
        Route::get('/callback', SUMUP_CONTROLER . 'callback')->name('sumup.callback');
    });
});
