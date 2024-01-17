<?php

use App\Http\Controllers\verification\VerificationDataController;

Route::middleware(['auth:sanctum'])
->controller(VerificationDataController::class)->prefix('verification-data')->group(function(){
    Route::post('feth-ue-parcours','getUeParcour');
});

