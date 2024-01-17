<?php

use App\Http\Controllers\Message\MessageController;


Route::middleware('auth:sanctum')->controller(MessageController::class)->group(function(){
    Route::get('/messages', 'index');
    Route::post('/store/messages', 'store');
});


