<?php

use App\Http\Controllers\Notification\NotificationController;


Route::middleware('auth:sanctum')->controller(NotificationController::class)->prefix('notifications')->group(function(){
    Route::get('/','index');
    Route::post('/read/{notification}','markAsRead');
    Route::post('/read-all','markAsReadAll');
    Route::delete('/delete/{notification}','delete');
});

