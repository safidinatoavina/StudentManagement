<?php 

use App\Http\Controllers\Auth\AuthController;

Route::controller(AuthController::class)->group(function(){
    Route::post('/login','login');
    Route::get('/capchat','capchat');
    //Route::post('/register','register')->middleware('admin');
    Route::get('/auth','profile')->middleware('auth:sanctum');
    Route::post('/logout','logout')->middleware('auth:sanctum');

});


