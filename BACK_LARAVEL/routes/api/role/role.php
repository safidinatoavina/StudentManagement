<?php

use App\Http\Controllers\Admin\RoleController;

Route::middleware('auth:sanctum')->controller(RoleController::class)->prefix('role')->group(function(){
    Route::get('/tous','index');
    Route::delete('/delete/{user}/{role}','deleteRoles')->middleware('admin');
});


//getMatieres
