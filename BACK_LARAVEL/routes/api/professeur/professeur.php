<?php

use App\Http\Controllers\Admin\ProfesseurController;

Route::middleware('auth:sanctum')->controller(ProfesseurController::class)->prefix('professeur')->group(function(){
    Route::get('/matieres','getMatieres');
    Route::get('/matieres-tp','getTP');
});


//getMatieres
