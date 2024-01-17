<?php

use App\Http\Controllers\Admin\PDF\GeneratePdfController;

Route::middleware('auth:sanctum')->controller(GeneratePdfController::class)->prefix('pdf')->group(function(){
    Route::post('generate-list-etudiant-with-note','generateListeNoteOrEtudiant');
    Route::post('generate-list-for-professeur','generateListeForProfesseur');
});