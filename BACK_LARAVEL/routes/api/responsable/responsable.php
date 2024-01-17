<?php

use App\Http\Controllers\responsable\ResponsableController;

Route::middleware(['auth:sanctum','responsable'])->controller(ResponsableController::class)
->prefix('responsable')->group(function(){

    Route::get('/ue-facult/{parcour?}','getUeFacult');
    Route::post('/set-ue-obli','setUeObli');

});
