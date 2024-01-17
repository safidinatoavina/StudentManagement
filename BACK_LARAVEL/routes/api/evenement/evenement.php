<?php

use App\Http\Controllers\Public\EvenementController;

Route::controller(EvenementController::class)->prefix('evenement')->group(function(){
    Route::get('/get-evenement','index');
    Route::get('/show-evenement/{evenement}','show');
    Route::post('/store-evenement','store');
    Route::post('/update-evenement/{evenement}','update');
    Route::post('/update-status-evenement/{evenement}','updateStatus');
    Route::delete('/delete-evenement/{evenement}','destroy');
});

