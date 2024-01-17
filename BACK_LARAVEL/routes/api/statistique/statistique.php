<?php

use App\Http\Controllers\statistique\StatistiqueController;


Route::middleware(['auth:sanctum','admin'])->controller(StatistiqueController::class)
->prefix('statistique')->group(function(){

    Route::get('/ue-matieres','getUeMatieres');
    Route::get('/etudiant-has-note','getEtudiantHasNote');
    Route::get('/note-percent','getMatiereNoteStats')->middleware('admin');

});

