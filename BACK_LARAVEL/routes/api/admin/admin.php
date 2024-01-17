<?php 

use App\Http\Controllers\Admin\AdminController;

Route::middleware('auth:sanctum')->controller(AdminController::class)->prefix('admins')->group(function(){
    Route::get('/','admins');
    Route::post('/create','store')->middleware('admin');
    Route::post('/update/{user}','update');
    Route::delete('/delete/{user}','deleteUser')->middleware('admin');
});


