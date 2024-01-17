<?php

use App\Http\Controllers\Etudiant\EtudiantController;


Route::middleware('auth:sanctum')->controller(EtudiantController::class)->prefix('etudiant')->group(function(){
    Route::get('/tous','etudiants');
    Route::get('/{etudiant}','etudiant');
    Route::get('/jury/etudiant-parcour/{parcour}','etudiantParcour');
    Route::post('/create-historique/{etudiant_id}',"creationHistoriqueDetail")->middleware('admin');
    Route::post('/inscrit','store')->middleware('admin');
    Route::post('/update/{etudiant}','update')->middleware('admin');
    Route::get('/annee/{annee}','etudinatsAnnee');
    Route::get('/historiques/{etudiant}','etudiantHistoriques');
    Route::get('/get-by-parcour-and-anne/{parcour}/{anne?}','getByParcours');
    Route::delete('/delete/{personne}','delete')->middleware('admin');
    Route::delete('/delete-historique/{historique}','deleteHistorique')->middleware('admin');

});

