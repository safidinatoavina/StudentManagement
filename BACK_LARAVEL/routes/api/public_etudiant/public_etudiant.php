<?php

use App\Http\Controllers\Public\EvenementController;
use App\Http\Controllers\EtudiantPublic\EtudiantPublicController;

Route::controller(EtudiantPublicController::class)->prefix('public')->group(function(){
    Route::post('/etudiant-filter','filter');
    Route::get('/annees','getAnneeList');
    Route::post('/historiques-etudiant/{etudiant}','getHistoriqueList');
    Route::post('/resultat-validation/{historique}','getResultExam');
});


Route::get('/evenement/active-evenement',[EvenementController::class,'getActiveEvenement']);
